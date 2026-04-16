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
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_item_id')->constrained('product_items')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('reference')->nullable()->comment('Nomor referensi/PO');
            $table->text('notes')->nullable();
            $table->string('status')->default('received')->comment('received, verified');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Admin yang mencatat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};
