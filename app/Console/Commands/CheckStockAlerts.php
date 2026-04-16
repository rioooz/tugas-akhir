<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StockAlertService;

class CheckStockAlerts extends Command
{
    protected $signature = 'stock:check {--months=3}';

    protected $description = 'Check product stocks and send email alerts when stock <= moving average (safety stock)';

    public function handle()
    {
        $months = (int)$this->option('months');

        $service = new StockAlertService();
        $alerts = $service->checkAllProducts($months);

        if (empty($alerts)) {
            $this->info('No stock alerts.');
            return 0;
        }

        $service->sendAlerts($alerts);

        $this->info('Sent ' . count($alerts) . ' stock alert(s).');
        return 0;
    }
}
