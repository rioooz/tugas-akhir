<?php
$models = [
    'ProductItem.php' => 'barang',
    'ProductItemDetail.php' => 'varian_barang',
    'Order.php' => 'pesanan',
    'OrderItem.php' => 'detail_pesanan',
    'StockIn.php' => 'barang_masuk'
];

foreach ($models as $modelFile => $tableName) {
    $path = __DIR__ . '/app/Models/' . $modelFile;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (strpos($content, 'protected $table') === false) {
            $content = str_replace('use HasFactory;', "use HasFactory;\n\n    protected \$table = '$tableName';", $content);
            file_put_contents($path, $content);
            echo "Updated Model: $modelFile\n";
        }
    }
}
