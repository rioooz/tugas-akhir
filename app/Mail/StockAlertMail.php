<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $movingAverage;
    public $safetyStock;

    public function __construct($product, $movingAverage, $safetyStock)
    {
        $this->product = $product;
        $this->movingAverage = $movingAverage;
        $this->safetyStock = $safetyStock;
    }

    public function build()
    {
        $subject = 'Stock Alert: ' . ($this->product->name ?? 'Produk #'.$this->product->id);

        $html = '<p>Perhatian, stok barang perlu dicek / direstock.</p>';
        $html .= '<ul>';
        $html .= '<li>Produk: ' . ($this->product->name ?? '') . '</li>';
        $html .= '<li>Stok saat ini: ' . ($this->product->stock ?? 0) . '</li>';
        $html .= '<li>Rata-rata penjualan (3 bulan): ' . number_format($this->movingAverage, 2) . '</li>';
        $html .= '<li>Safety stock (threshold): ' . $this->safetyStock . '</li>';
        $html .= '</ul>';
        $html .= '<p>Silakan lakukan restock jika diperlukan.</p>';

        return $this->subject($subject)->html($html);
    }
}
