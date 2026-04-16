<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display list of all orders
     */
 public function index(Request $request)
{
    $query = Order::with('user', 'orderItems.productItem')
        ->latest();

    // FILTER STATUS
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(15)->withQueryString();

    return view('admin.orders.index', compact('orders'));
}
    /**
     * Display single order detail
     */
    public function show($id)
    {
        $order = Order::with('user', 'orderItems.productItem')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus');
    }
}
