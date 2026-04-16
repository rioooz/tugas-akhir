<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Statistik dasar
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Status breakdown
        $ordersByStatus = [];
        foreach (OrderStatus::cases() as $status) {
            $ordersByStatus[$status->label()] = Order::where('status', $status)->count();
        }

        // Top 5 produk terlaris
        $topProducts = OrderItem::selectRaw('product_item_id, SUM(quantity) as total_qty, SUM(price * quantity) as total_revenue')
            ->groupBy('product_item_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->limit(5)
            ->with('productItem')
            ->get();

        // Revenue trend (30 hari terakhir)
        $revenueTrend = Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Product stock info
        $lowStockProducts = ProductItem::where('stock', '<', 10)->get();

        return view('admin.reports.index', compact(
            'totalOrders',
            'totalRevenue',
            'completedOrders',
            'pendingOrders',
            'ordersByStatus',
            'topProducts',
            'revenueTrend',
            'lowStockProducts'
        ));
    }
}

