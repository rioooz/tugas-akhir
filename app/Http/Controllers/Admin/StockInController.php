<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockIn;
use App\Models\ProductItem;
use App\Models\ProductItemDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockIns = StockIn::with(['productItem', 'productItemDetail', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.stock_in.index', compact('stockIns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = ProductItem::with('details')->get();
        return view('admin.stock_in.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_selection' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        if (str_starts_with($validated['item_selection'], 'variant_')) {
            $detailId = str_replace('variant_', '', $validated['item_selection']);
            $detail = ProductItemDetail::findOrFail($detailId);
            $productId = $detail->product_item_id;
            
            $detail->stock += $validated['quantity'];
            $detail->save();
        } else {
            $productId = str_replace('product_', '', $validated['item_selection']);
            $detailId = null;
            
            $product = ProductItem::findOrFail($productId);
            $product->stock += $validated['quantity'];
            $product->save();
        }

        StockIn::create([
            'product_item_id' => $productId,
            'product_item_detail_id' => $detailId,
            'quantity' => $validated['quantity'],
            'reference' => $validated['reference'],
            'notes' => $validated['notes'],
            'user_id' => auth()->id(),
            'status' => 'received',
        ]);

        return redirect()->route('admin.stock-in.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stockIn = StockIn::with(['productItem', 'productItemDetail', 'user'])->findOrFail($id);
        return view('admin.stock_in.show', compact('stockIn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stockIn = StockIn::findOrFail($id);
        $products = ProductItem::with('details')->get();
        return view('admin.stock_in.edit', compact('stockIn', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stockIn = StockIn::findOrFail($id);

        $validated = $request->validate([
            'item_selection' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldQuantity = $stockIn->quantity;
        $newQuantity = $validated['quantity'];

        // Revert old stock
        if ($stockIn->product_item_detail_id) {
            $oldDetail = ProductItemDetail::find($stockIn->product_item_detail_id);
            if ($oldDetail) {
                $oldDetail->stock -= $oldQuantity;
                $oldDetail->save();
            }
        } else {
            $oldProduct = ProductItem::find($stockIn->product_item_id);
            if ($oldProduct) {
                $oldProduct->stock -= $oldQuantity;
                $oldProduct->save();
            }
        }

        // Apply new stock
        if (str_starts_with($validated['item_selection'], 'variant_')) {
            $detailId = str_replace('variant_', '', $validated['item_selection']);
            $detail = ProductItemDetail::findOrFail($detailId);
            $productId = $detail->product_item_id;
            
            $detail->stock += $newQuantity;
            $detail->save();
        } else {
            $productId = str_replace('product_', '', $validated['item_selection']);
            $detailId = null;
            
            $product = ProductItem::findOrFail($productId);
            $product->stock += $newQuantity;
            $product->save();
        }

        $stockIn->update([
            'product_item_id' => $productId,
            'product_item_detail_id' => $detailId,
            'quantity' => $newQuantity,
            'reference' => $validated['reference'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.stock-in.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stockIn = StockIn::findOrFail($id);

        // Kurangi stok karena barang masuk dihapus
        if ($stockIn->product_item_detail_id) {
            $detail = ProductItemDetail::find($stockIn->product_item_detail_id);
            if ($detail) {
                $detail->stock -= $stockIn->quantity;
                $detail->save();
            }
        } else {
            $product = ProductItem::find($stockIn->product_item_id);
            if ($product) {
                $product->stock -= $stockIn->quantity;
                $product->save();
            }
        }

        $stockIn->delete();

        return redirect()->route('admin.stock-in.index');
    }
}
