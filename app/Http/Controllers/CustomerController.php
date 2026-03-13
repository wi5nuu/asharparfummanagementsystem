<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(10);
        $activeCustomers = Customer::where('is_active', true)->count();
        $wholesaleCustomers = Customer::where('type', 'wholesale')->count();
        $averageSpent = 0;
        return view('customers.index', compact('customers', 'activeCustomers', 'wholesaleCustomers', 'averageSpent'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(\App\Http\Requests\StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        // Set default is_active jika tidak ada
        $validated['is_active'] = $validated['is_active'] ?? true;

        $customer = Customer::create($validated);

        if (request()->expectsJson()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(\App\Http\Requests\StoreCustomerRequest $request, Customer $customer)
    {
        $validated = $request->validated();

        // Set is_active dari form
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;

        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
