<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProductItem;

$product = ProductItem::first();
if ($product) {
    $product->update(['stock' => 1]);
    echo "Produk '{$product->name}' stock diubah menjadi 1\n";
} else {
    echo "Tidak ada produk di database\n";
}
