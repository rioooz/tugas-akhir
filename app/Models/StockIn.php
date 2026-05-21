<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_item_id',
        'product_item_detail_id',
        'quantity',
        'reference',
        'notes',
        'status',
        'user_id',
    ];

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function productItemDetail()
    {
        return $this->belongsTo(ProductItemDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
