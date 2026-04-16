<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index()
    {
        $customers = User::where('role', 'user')->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show customer detail
     */
    public function show($id)
    {
        $customer = User::findOrFail($id);
        $orders = $customer->orders()->latest()->get();
        
        return view('admin.customers.show', compact('customer', 'orders'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'address' => 'nullable|string|max:500',
        ]);

        $customer = User::findOrFail($id);
        $customer->update($request->only(['name', 'email', 'address']));

        return redirect()->route('admin.customers.show', $id)
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}
