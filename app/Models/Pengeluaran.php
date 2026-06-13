<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    public $timestamps = true;

    protected $fillable = [
        'tanggal_pengeluaran',
        'Jumlah',
        'id_admin',
        'id_sumber',
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
    ];

    /**
     * Relasi dengan User (admin)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }

    /**
     * Relasi dengan Sumber
     */
    public function sumber(): BelongsTo
    {
        return $this->belongsTo(Sumber::class, 'id_sumber', 'id_sumber');
    }

    /**
     * Relasi dengan Detail Pengeluaran
     */
    public function detailPengeluaran(): HasMany
    {
        return $this->hasMany(DetailPengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }
}
