<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan user
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.productItem')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan / invoice
     */
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['orderItems.productItem', 'user'])
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
