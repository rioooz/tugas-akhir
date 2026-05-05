<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_item_id',
        'product_item_detail_id',
        'quantity',
        'price',
    ];

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke ProductItem
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function productItemDetail()
    {
        return $this->belongsTo(\App\Models\ProductItemDetail::class, 'product_item_detail_id');
    }
}
