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
        Schema::table('pesanan', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('pesanan', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('pesanan', 'total')) {
                $table->decimal('total', 15, 2)->default(0)->after('user_id');
            }
            if (!Schema::hasColumn('pesanan', 'status')) {
                $table->string('status')->default('pending')->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pesanan', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('pesanan', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
