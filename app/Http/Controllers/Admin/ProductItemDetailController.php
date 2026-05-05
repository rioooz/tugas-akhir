<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductItem;
use App\Models\ProductItemDetail;

class ProductItemDetailController extends Controller
{
    public function index(ProductItem $barang)
    {
        $details = $barang->details()->orderBy('id','desc')->paginate(20);
        return view('admin.product_item_details.index', compact('barang','details'));
    }

    public function create(ProductItem $barang)
    {
        return view('admin.product_item_details.create', compact('barang'));
    }

    public function store(Request $request, ProductItem $barang)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $data['product_item_id'] = $barang->id;
        ProductItemDetail::create($data);

        return redirect()->route('admin.barang.details.index', $barang->id)->with('success', 'Detail produk berhasil ditambahkan.');
    }

    public function edit(ProductItem $barang, ProductItemDetail $detail)
    {
        return view('admin.product_item_details.edit', compact('barang','detail'));
    }

    public function update(Request $request, ProductItem $barang, ProductItemDetail $detail)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $detail->update($data);

        return redirect()->route('admin.barang.details.index', $barang->id)->with('success', 'Detail produk berhasil diperbarui.');
    }

    public function destroy(ProductItem $barang, ProductItemDetail $detail)
    {
        $detail->delete();
        return redirect()->route('admin.barang.details.index', $barang->id)->with('success', 'Detail produk berhasil dihapus.');
    }
}
