<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return view('admin.transaksi.index');
    }

    public function sales()
    {
        // Logika untuk menampilkan data penjualan
    }

    public function purchases()
    {
        // Logika untuk menampilkan data pembelian
    }

    public function show($id)
    {
        // Logika untuk menampilkan detail transaksi
    }
}
