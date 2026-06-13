<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sumber extends Model
{
    protected $table = 'sumber';
    protected $primaryKey = 'id_sumber';
    public $timestamps = true;

    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi dengan pengeluaran
     */
    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'id_sumber', 'id_sumber');
    }
}
