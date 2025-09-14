<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create() { return view('customers.create'); }

    public function store(Request $r) {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'email'=> 'nullable|email|unique:customers,email',
            'phone'=> 'nullable|string|max:50',
            'address'=>'nullable|string|max:255',
        ]);
        Customer::create($data);
        return redirect()->route('customers.index')->with('ok','Customer added');
    }

    public function edit(Customer $customer) { return view('customers.edit', compact('customer')); }

    public function update(Request $r, Customer $customer) {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'email'=> 'nullable|email|unique:customers,email,'.$customer->id,
            'phone'=> 'nullable|string|max:50',
            'address'=>'nullable|string|max:255',
        ]);
        $customer->update($data);
        return redirect()->route('customers.index')->with('ok','Customer updated');
    }

    public function destroy(Customer $customer) {
        $customer->delete();
        return back()->with('ok','Customer deleted');
    }
}