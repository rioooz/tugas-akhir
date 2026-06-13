<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengeluaran extends Model
{
    protected $table = 'detail_pengeluaran';
    protected $primaryKey = 'id_pengeluaran_detail';
    public $timestamps = true;

    protected $fillable = [
        'id_pengeluaran',
        'nama_penerima',
        'Kehadiran',
        'Bon',
        'nama_bahan',
    ];

    /**
     * Relasi dengan Pengeluaran
     */
    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }
}
