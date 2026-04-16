<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockIn;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockIns = StockIn::with(['productItem', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.stock_in.index', compact('stockIns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = ProductItem::all();
        return view('admin.stock_in.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_item_id' => 'required|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'received';

        $stockIn = StockIn::create($validated);

        // Update product stock
        $product = ProductItem::find($validated['product_item_id']);
        $product->stock += $validated['quantity'];
        $product->save();

        Session::flash('success', 'Barang masuk berhasil dicatat!');
        return redirect()->route('admin.stock-in.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stockIn = StockIn::with(['productItem', 'user'])->findOrFail($id);
        return view('admin.stock_in.show', compact('stockIn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stockIn = StockIn::findOrFail($id);
        $products = ProductItem::all();
        return view('admin.stock_in.edit', compact('stockIn', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stockIn = StockIn::findOrFail($id);

        $validated = $request->validate([
            'product_item_id' => 'required|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Adjust stock jika product berubah atau quantity berubah
        $oldQuantity = $stockIn->quantity;
        $newQuantity = $validated['quantity'];
        $quantityDiff = $newQuantity - $oldQuantity;

        if ($stockIn->product_item_id !== $validated['product_item_id']) {
            // Product berubah, kurangi stok lama, tambah stok baru
            $oldProduct = ProductItem::find($stockIn->product_item_id);
            $oldProduct->stock -= $oldQuantity;
            $oldProduct->save();

            $newProduct = ProductItem::find($validated['product_item_id']);
            $newProduct->stock += $newQuantity;
            $newProduct->save();
        } else {
            // Product sama, hanya update quantity difference
            $product = ProductItem::find($validated['product_item_id']);
            $product->stock += $quantityDiff;
            $product->save();
        }

        $stockIn->update($validated);

        Session::flash('success', 'Barang masuk berhasil diupdate!');
        return redirect()->route('admin.stock-in.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stockIn = StockIn::findOrFail($id);

        // Kurangi stok karena barang masuk dihapus
        $product = ProductItem::find($stockIn->product_item_id);
        $product->stock -= $stockIn->quantity;
        $product->save();

        $stockIn->delete();

        Session::flash('success', 'Barang masuk berhasil dihapus!');
        return redirect()->route('admin.stock-in.index');
    }
}
