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
        Schema::create('detail_pengeluaran', function (Blueprint $table) {
            $table->id('id_pengeluaran_detail');
            $table->foreignId('id_pengeluaran')->constrained('pengeluaran', 'id_pengeluaran');
            $table->string('nama_penerima', 255);
            $table->integer('Kehadiran');
            $table->enum('Bon', ['Y', 'T']);
            $table->string('nama_bahan', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengeluaran');
    }
};
