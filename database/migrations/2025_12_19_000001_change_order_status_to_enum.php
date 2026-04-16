<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL 5.7+ and PostgreSQL
        Schema::table('orders', function (Blueprint $table) {
            // Change status column to enum
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                ->default('pending')
                ->change();
            
            // Change payment_status column to enum
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to string
            $table->string('status')->default('pending')->change();
            $table->string('payment_status')->nullable()->change();
        });
    }
};
