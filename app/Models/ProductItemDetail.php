<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItemDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_item_id',
        'name',
        'price',
        'stock',
        'size',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(ProductItem::class);
    }
}
