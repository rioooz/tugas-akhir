<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('product_item_id')->constrained('barang')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Harga saat checkout (untuk historical data)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
