<?php
$replacements = [
    "'product_items'" => "'barang'",
    "'product_item_details'" => "'varian_barang'",
    "'orders'" => "'pesanan'",
    "'order_items'" => "'detail_pesanan'",
    "'stock_ins'" => "'barang_masuk'",
    '"product_items"' => '"barang"',
    '"product_item_details"' => '"varian_barang"',
    '"orders"' => '"pesanan"',
    '"order_items"' => '"detail_pesanan"',
    '"stock_ins"' => '"barang_masuk"',
];

$dir = __DIR__ . '/database/migrations';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "Updated migration: " . basename($file) . "\n";
    }
}
