<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $productsCol = Product::orderBy('name')->get(['id','name','sku','price']);

        $productList = $productsCol->map(function ($p) {
            return ['id'=>$p->id,'name'=>$p->name,'sku'=>$p->sku,'price'=>$p->price];
        })->values();

        $nextNumber = 'INV-'.str_pad(Invoice::count()+1, 5, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('customers','productList','nextNumber'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'customer_id'         => 'required|exists:customers,id',
            'invoice_number'      => 'required|string|max:100|unique:invoices,invoice_number',
            'invoice_date'        => 'required|date',
            'discount'            => 'nullable|numeric|min:0',
            'tax'                 => 'nullable|numeric|min:0',
            'status'              => 'nullable|in:unpaid,paid,partial',
            'items'               => 'required|array|min:1',
            'items.*.product_id'  => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $discount = (float)($data['discount'] ?? 0);
        $tax      = (float)($data['tax'] ?? 0);

        // Compute line totals & subtotal
        $subtotal = 0;
        foreach ($data['items'] as &$it) {
            $it['line_total'] = (float)$it['quantity'] * (float)$it['unit_price'];
            $subtotal += $it['line_total'];
        }
        $total = max(0, $subtotal - $discount + $tax);

        // All-or-nothing: create invoice + items + stock adjustments in a single transaction
        $invoice = DB::transaction(function () use ($data, $discount, $tax, $subtotal, $total) {

            // If you prefer to deduct only when status becomes 'paid', you can skip stock here
            // and handle it in updateStatus(). This version deducts immediately on create.

            // Pre-check stock for all items with a product_id
            foreach ($data['items'] as $it) {
                if (!empty($it['product_id'])) {
                    $product = Product::lockForUpdate()->find($it['product_id']);
                    if (!$product) {
                        throw ValidationException::withMessages([
                            'items' => ['Selected product no longer exists.']
                        ]);
                    }
                    if ($product->stock < (int)$it['quantity']) {
                        throw ValidationException::withMessages([
                            'items' => ["Not enough stock for {$product->name}. Available: {$product->stock}, needed: {$it['quantity']}."]
                        ]);
                    }
                }
            }

            // Create invoice
            $invoice = Invoice::create([
                'customer_id'   => $data['customer_id'],
                'invoice_number'=> $data['invoice_number'],
                'invoice_date'  => $data['invoice_date'],
                'discount'      => $discount,
                'tax'           => $tax,
                'subtotal'      => $subtotal,
                'total'         => $total,
                'status'        => $data['status'] ?? 'unpaid',
            ]);

            // Create items + deduct stock
            foreach ($data['items'] as $it) {
                $invoice->items()->create([
                    'product_id' => $it['product_id'] ?? null,
                    'description'=> $it['description'],
                    'quantity'   => (int)$it['quantity'],
                    'unit_price' => $it['unit_price'],
                    'line_total' => $it['line_total'],
                ]);

                if (!empty($it['product_id'])) {
                    $product = Product::lockForUpdate()->find($it['product_id']);
                    // We already pre-validated, so just decrement
                    $product->decrement('stock', (int)$it['quantity']);
                }
            }

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('ok','Invoice created');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer','items.product']);
        return view('invoices.show', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['customer','items.product']);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number.'.pdf');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:unpaid,paid,partial',
        ]);

        $new = $request->status;
        $old = $invoice->status;

        // Adjust stock only when toggling to/from "paid" to keep consistency
        DB::transaction(function () use ($invoice, $old, $new) {
            $invoice->load('items');

            if ($old !== 'paid' && $new === 'paid') {
                // Deduct stock now
                foreach ($invoice->items as $it) {
                    if ($it->product_id) {
                        $product = Product::lockForUpdate()->find($it->product_id);
                        if ($product->stock < $it->quantity) {
                            throw ValidationException::withMessages([
                                'status' => ["Not enough stock to mark as paid for {$product->name}. Available: {$product->stock}, needed: {$it->quantity}."]
                            ]);
                        }
                        $product->decrement('stock', (int)$it->quantity);
                    }
                }
            } elseif ($old === 'paid' && $new !== 'paid') {
                // Restock if moving away from paid
                foreach ($invoice->items as $it) {
                    if ($it->product_id) {
                        $product = Product::lockForUpdate()->find($it->product_id);
                        $product->increment('stock', (int)$it->quantity);
                    }
                }
            }

            $invoice->update(['status' => $new]);
        });

        return back()->with('ok', 'Invoice status updated');
    }
}
