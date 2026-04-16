<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductItem;

class ProductItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductItem::create([
            'name' => 'Kursi Kayu Jati Klasik',
            'description' => 'Kursi elegan yang terbuat dari kayu jati asli dengan ukiran tangan.',
            'price' => 1250000,
            'stock' => 15,
            'image' => 'images/1766068341.jpg',
        ]);

        ProductItem::create([
            'name' => 'Meja Makan Minimalis Modern',
            'description' => 'Meja makan untuk 4 orang dengan desain minimalis dan bahan berkualitas tinggi.',
            'price' => 3500000,
            'stock' => 8,
            'image' => 'images/1766105322.png',
        ]);

        ProductItem::create([
            'name' => 'Lemari Pakaian 3 Pintu',
            'description' => 'Lemari pakaian luas dengan cermin dan banyak ruang penyimpanan.',
            'price' => 4800000,
            'stock' => 5,
            'image' => 'images/1766068341.jpg',
        ]);
    }
}
