<?php

namespace App\Http\Controllers;

use App\Models\ProductItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show product detail
     */
    public function show($id)
    {
        $product = ProductItem::findOrFail($id);
        
        // Get related products (same category or similar price range)
        $relatedProducts = ProductItem::where('id', '!=', $id)
            ->limit(4)
            ->get();
        
        return view('product.detail', compact('product', 'relatedProducts'));
    }
}
