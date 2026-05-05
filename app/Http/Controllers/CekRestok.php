<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItemDetail;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RestockNotification;

class CekRestok extends Controller
{


public function cekRestok()
{
    $items = ProductItemDetail::with('product')->get();

    foreach ($items as $item) {

        // hitung rata-rata penjualan 3 bulan terakhir per VARIAN
        $avg = OrderItem::where('product_item_id', $item->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->avg('quantity');

        $avg = $avg ?? 0;

        // LOGIC UTAMA
        if ($item->stock <= $avg && $item->stock > 0) {

            Notification::route('mail', 'emailkamu@gmail.com')
                ->notify(new RestockNotification($item));
        }
    }

    return "Cek restok varian selesai!";
}
}
