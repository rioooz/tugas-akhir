<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProductItem;

$count = ProductItem::where('stock', '<', 2)->count();
$products = ProductItem::where('stock', '<', 2)->get();

echo "Products with stock < 2: " . $count . "\n";
foreach ($products as $product) {
    echo "- {$product->name}: {$product->stock} pcs\n";
}
