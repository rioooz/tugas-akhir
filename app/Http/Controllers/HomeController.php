<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItem;

class HomeController extends Controller
{
    public function index()
    {
        $products = ProductItem::all();
        return view('index', compact('products'));
    }
}
