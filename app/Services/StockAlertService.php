<?php

namespace App\Services;

use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\StockAlertMail;
use Illuminate\Support\Facades\Mail;

class StockAlertService
{
    /**
     * Get monthly sales totals for a product for the last N months (including current month).
     * Returns an associative array keyed by YYYY-MM with integer quantities.
     */
    public function getMonthlySales(int $productId, int $months = 3): array
    {
        $end = Carbon::now()->endOfMonth();
        $start = (clone $end)->subMonths($months - 1)->startOfMonth();

        $rows = DB::table('detail_pesanan')
            ->selectRaw("SUM(detail_pesanan.quantity) as qty, DATE_FORMAT(pesanan.created_at, '%Y-%m') as ym")
            ->join('pesanan', 'detail_pesanan.order_id', '=', 'pesanan.id')
            ->where('detail_pesanan.product_item_id', $productId)
            ->where('pesanan.payment_status', 'paid')
            ->whereBetween('pesanan.created_at', [$start->toDateTimeString(), $end->toDateTimeString()])
            ->groupBy('ym')
            ->get();

        // initialize months with zero
        $monthsData = [];
        $cursor = (clone $start);
        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $monthsData[$key] = 0;
            $cursor->addMonth();
        }

        foreach ($rows as $r) {
            $monthsData[$r->ym] = (int)$r->qty;
        }

        return $monthsData;
    }

    /**
     * Compute moving average (simple) for the last N months.
     */
    public function movingAverage(int $productId, int $months = 3): float
    {
        $monthly = $this->getMonthlySales($productId, $months);
        if (count($monthly) === 0) {
            return 0.0;
        }

        $sum = array_sum($monthly);
        return $sum / count($monthly);
    }

    /**
     * Check all products and return array of alerts where stock <= safety stock (moving average).
     */
    public function checkAllProducts(int $months = 3): array
    {
        $alerts = [];

        $products = ProductItem::all();
        foreach ($products as $p) {
            $avg = $this->movingAverage($p->id, $months);
            $safety = (int)ceil($avg);

            if ($p->stock <= $safety) {
                $alerts[] = [
                    'product' => $p,
                    'moving_average' => $avg,
                    'safety_stock' => $safety,
                ];
            }
        }

        return $alerts;
    }

    /**
     * Send alert emails to the admin(s).
     * Uses ADMIN_EMAIL environment variable (comma-separated allowed).
     */
    public function sendAlerts(array $alerts): void
    {
        if (empty($alerts)) {
            return;
        }

        $recipients = array_map('trim', explode(',', env('ADMIN_EMAIL', 'admin@example.com')));

        foreach ($recipients as $to) {
            foreach ($alerts as $alert) {
                Mail::to($to)->send(new StockAlertMail($alert['product'], $alert['moving_average'], $alert['safety_stock']));
            }
        }
    }
}
