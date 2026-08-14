<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('contact', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");
        }

        if ($request->filled('province')) {
            $query->where('province', $request->get('province'));
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(CustomerStoreRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    public function show(Customer $customer)
    {
        $customer->load(['invoices' => fn($q) => $q->latest(), 'visits' => fn($q) => $q->latest()]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diupdate');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
