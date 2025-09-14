<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create() { return view('products.create'); }

    public function store(Request $r) {
        $data = $r->validate([
            'name'  => 'required|string|max:255',
            'sku'   => 'required|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        Product::create($data);
        return redirect()->route('products.index')->with('ok','Product added');
    }

    public function edit(Product $product) { return view('products.edit', compact('product')); }

    public function update(Request $r, Product $product) {
        $data = $r->validate([
            'name'  => 'required|string|max:255',
            'sku'   => 'required|string|max:100|unique:products,sku,'.$product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        $product->update($data);
        return redirect()->route('products.index')->with('ok','Product updated');
    }

    public function destroy(Product $product) {
        $product->delete();
        return back()->with('ok','Product deleted');
    }
}
