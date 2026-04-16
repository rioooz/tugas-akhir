<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data ringkasan.
     */
    public function index()
    {
        // Mengambil data ringkasan dari database
        $totalProducts = ProductItem::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');

        // Mengambil 5 pesanan terbaru
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Notifikasi 1: Produk dengan stok sangat rendah (< 2)
        $criticalStockProducts = ProductItem::where('stock', '<', 2)->orderBy('stock')->get();

        // Notifikasi 2: Produk bestseller (paling sering terjual dalam 7 hari terakhir)
        $bestsellers = OrderItem::selectRaw('product_item_id, SUM(quantity) as total_qty')
            ->with('productItem')
            ->whereHas('order', function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            })
            ->groupBy('product_item_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->limit(5)
            ->get();

        // Notifikasi 3: Transaksi yang belum diproses (pending/processing)
        $unprocessedOrders = Order::with('user')
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->limit(10)
            ->get();

        // Mengirim data ke view
        return view('admin.index', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders,
            'criticalStockProducts' => $criticalStockProducts,
            'bestsellers' => $bestsellers,
            'unprocessedOrders' => $unprocessedOrders,
        ]);
    }
}

