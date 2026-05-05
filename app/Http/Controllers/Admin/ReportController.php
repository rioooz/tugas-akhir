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

        // Revenue trend (3 bulan terakhir, per bulan)
        $labels = [];
        $revenues = [];
        $monthKeys = [];
        for ($i = 2; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $start = $m->copy()->startOfMonth();
            $end = $m->copy()->endOfMonth();
            $label = $m->format('M Y');
            $sum = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total');

            $labels[] = $label;
            $monthKeys[] = $m->format('Y-m');
            $revenues[] = (int) $sum;
        }

        // Product stock info
        $lowStockProducts = ProductItem::where('stock', '<', 10)->get();

        return view('admin.reports.index', compact(
            'totalOrders',
            'totalRevenue',
            'completedOrders',
            'pendingOrders',
            'ordersByStatus',
            'topProducts',
            'labels',
            'revenues',
            'monthKeys',
            'lowStockProducts'
        ));
    }

    public function revenueByMonth(Request $request)
    {
        $month = $request->query('month'); // expected YYYY-MM
        if (! $month || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            return response()->json(['error' => 'Invalid month'], 400);
        }

        try {
            $m = Carbon::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid month format'], 400);
        }

        $start = $m->copy()->startOfMonth();
        $end = $m->copy()->endOfMonth();

        $days = [];
        $values = [];
        $period = new \DatePeriod(new \DateTime($start->toDateString()), new \DateInterval('P1D'), (new \DateTime($end->toDateString()))->modify('+1 day'));
        foreach ($period as $dt) {
            $days[] = $dt->format('d M');
            $dayStart = Carbon::parse($dt->format('Y-m-d'))->startOfDay();
            $dayEnd = Carbon::parse($dt->format('Y-m-d'))->endOfDay();
            $sum = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('total');
            $values[] = (int) $sum;
        }

        return response()->json(['labels' => $days, 'data' => $values, 'monthLabel' => $m->format('F Y')]);
    }
}

