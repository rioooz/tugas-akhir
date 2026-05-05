<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItem;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $cartKey => $item) {
            // cartKey can be numeric product id or detail_{id}
            if (str_starts_with($cartKey, 'detail_')) {
                $detailId = (int)str_replace('detail_', '', $cartKey);
                $detail = \App\Models\ProductItemDetail::find($detailId);
                if (!$detail) continue;
                $parent = $detail->product;
                if (!$parent) continue;
                $price = $item['price'] ?? $detail->price;
                $stock = $detail->stock;
                $name = $parent->name . ' - ' . $detail->name . ($detail->size ? ' (' . $detail->size . ')' : '');
                $image = $parent->image ? asset($parent->image) : 'https://via.placeholder.com/150';
                $cartItems[] = [
                    'id' => $cartKey,
                    'product_item_id' => $parent->id,
                    'detail_id' => $detail->id,
                    'name' => $name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'image' => $image,
                    'stock' => $stock,
                ];
                $total += $price * $item['quantity'];
            } else {
                $productId = $cartKey;
                $product = ProductItem::find($productId);
                if (!$product) continue;
                $price = $item['price'] ?? $product->price;
                $cartItems[] = [
                    'id' => $product->id,
                    'product_item_id' => $product->id,
                    'detail_id' => null,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image ? asset($product->image) : 'https://via.placeholder.com/150',
                    'stock' => $product->stock,
                ];
                $total += $price * $item['quantity'];
            }
        }


        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function add(Request $request, $productId)
    {
        $product = ProductItem::findOrFail($productId);
        $quantity = (int)$request->input('quantity', 1);

        $cart = session()->get('cart', []);

        // If variant selected
        $detailId = $request->input('detail_id');
        if ($detailId) {
            $detail = \App\Models\ProductItemDetail::findOrFail($detailId);
            // ensure detail belongs to product
            if ($detail->product_item_id != $product->id) {
                return redirect()->back()->with('error', 'Detail produk tidak valid untuk produk ini.');
            }

            $stock = $detail->stock;
            $price = $detail->price;
            $cartKey = 'detail_' . $detail->id;

            if ($stock < $quantity) {
                return redirect()->back()->with('error', 'Stok detail produk tidak mencukupi. Stok tersedia: ' . $stock);
            }

            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
                if ($newQuantity > $stock) {
                    return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $stock);
                }
                $cart[$cartKey]['quantity'] = $newQuantity;
            } else {
                $cart[$cartKey] = [
                    'quantity' => $quantity,
                    'product_item_id' => $product->id,
                    'detail_id' => $detail->id,
                    'price' => $price,
                ];
            }

            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Produk varian berhasil ditambahkan ke keranjang!');
        }

        // No detail selected: use product-level
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $product->stock);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'product_item_id' => $product->id,
                'detail_id' => null,
                'price' => $product->price,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity produk di keranjang.
     */
    public function update(Request $request, $productId)
    {
        try {
            $cartKey = $productId; // may be 'detail_123' or numeric
            $quantity = (int) $request->input('quantity');

            $cart = session()->get('cart', []);

            if (!isset($cart[$cartKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan di keranjang'
                ], 404);
            }

            $item = $cart[$cartKey];

            if (isset($item['detail_id']) && $item['detail_id']) {
                $detail = \App\Models\ProductItemDetail::findOrFail($item['detail_id']);
                $product = $detail->product;
                $stock = $detail->stock;
                $price = $item['price'] ?? $detail->price;
            } else {
                $product = ProductItem::findOrFail($item['product_item_id'] ?? $cartKey);
                $stock = $product->stock;
                $price = $item['price'] ?? $product->price;
            }

            // Validasi quantity
            if ($quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah minimal adalah 1'
                ], 400);
            }

            // Validasi stok
            if ($quantity > $stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $stock
                ], 400);
            }

            // Update quantity di cart
            $cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart', $cart);

            // Hitung subtotal untuk item ini
            $subtotal = $price * $quantity;

            // Hitung total keseluruhan
            $total = 0;
            foreach ($cart as $k => $it) {
                if (str_starts_with($k, 'detail_')) {
                    $dId = (int)str_replace('detail_', '', $k);
                    $d = \App\Models\ProductItemDetail::find($dId);
                    if ($d) {
                        $pprice = $it['price'] ?? $d->price;
                        $total += $pprice * $it['quantity'];
                    }
                } else {
                    $p = ProductItem::find($k);
                    if ($p) {
                        $pprice = $it['price'] ?? $p->price;
                        $total += $pprice * $it['quantity'];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total' => number_format($total, 0, ',', '.'),
                'total_raw' => $total,
                'quantity' => $quantity,
                'stock' => $stock,
            ]);

        } catch (\Exception $e) {
            \Log::error('Cart Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus produk dari keranjang.
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
        }

        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang!');
    }

    /**
     * Mengosongkan keranjang.
     */
    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
