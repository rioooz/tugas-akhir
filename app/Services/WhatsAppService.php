<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send low stock alert via WhatsApp (Fonnte)
     *
     * @param string $productName
     * @param string|null $variantName
     * @param int $remainingStock
     * @return bool
     */
    public static function sendLowStockAlert($productName, $variantName, $remainingStock)
    {
        $phone = env('WA_ADMIN_PHONE');
        $token = env('FONNTE_TOKEN');

        if (!$phone || !$token) {
            Log::warning('WhatsApp Alert failed: WA_ADMIN_PHONE or FONNTE_TOKEN not set in .env');
            return false;
        }

        $itemName = $productName;
        if ($variantName) {
            $itemName .= ' (Varian: ' . $variantName . ')';
        }

        $message = "⚠️ *Peringatan Stok Menipis* ⚠️\n\n";
        $message .= "Produk: *$itemName*\n";
        $message .= "Sisa Stok: *$remainingStock*\n\n";
        $message .= "Mohon segera lakukan pengecekan dan restock barang.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Optional
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp Low Stock Alert sent successfully to ' . $phone);
                return true;
            } else {
                Log::error('WhatsApp Alert failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Alert exception: ' . $e->getMessage());
            return false;
        }
    }
}
