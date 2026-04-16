<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ProductItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

Paginator::useBootstrap();


        // Share notification data to all views
        View::composer('*', function($view) {
            $criticalStockCount = ProductItem::where('stock', '<', 2)->count();
            $criticalStockProducts = ProductItem::where('stock', '<', 2)->orderBy('stock')->get();
            
            \Log::debug('Critical Stock Count: ' . $criticalStockCount);
            \Log::debug('Critical Products: ' . json_encode($criticalStockProducts->pluck('name')->toArray()));
            
            $bestsellersCount = OrderItem::selectRaw('product_item_id, SUM(quantity) as total_qty')
                ->whereHas('order', function($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(7));
                })
                ->groupBy('product_item_id')
                ->orderByRaw('SUM(quantity) DESC')
                ->limit(5)
                ->count();
            
            $unprocessedOrdersCount = Order::whereIn('status', ['pending', 'processing'])->count();

            $view->with([
                'sidebarCriticalStockCount' => $criticalStockCount,
                'sidebarCriticalStockProducts' => $criticalStockProducts,
                'sidebarBestsellersCount' => $bestsellersCount,
                'sidebarUnprocessedCount' => $unprocessedOrdersCount,
            ]);
        });
    }
}

