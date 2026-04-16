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

        foreach ($cart as $productId => $item) {
            $product = ProductItem::find($productId);
            if ($product) {
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image ? asset($product->image) : 'https://via.placeholder.com/150',
                    'stock' => $product->stock,
                ];
                $total += $product->price * $item['quantity'];
            }
        }

        $shipping = 1000; // Biaya pengiriman

        return view('cart.index', compact('cartItems', 'total', 'shipping'));
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function add(Request $request, $productId)
    {
        $product = ProductItem::findOrFail($productId);
        $quantity = $request->input('quantity', 1);

        // Validasi stok
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        $cart = session()->get('cart', []);

        // Jika produk sudah ada di cart, tambahkan quantity
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            
            // Validasi stok total
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $product->stock);
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
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
            $product = ProductItem::findOrFail($productId);
            $quantity = (int) $request->input('quantity');

            // Validasi quantity
            if ($quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah minimal adalah 1'
                ], 400);
            }

            // Validasi stok
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $product->stock
                ], 400);
            }

            $cart = session()->get('cart', []);

            if (!isset($cart[$productId])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan di keranjang'
                ], 404);
            }

            // Update quantity di cart
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);

            // Hitung subtotal untuk item ini
            $subtotal = $product->price * $quantity;

            // Hitung total keseluruhan
            $total = 0;
            foreach ($cart as $id => $item) {
                $prod = ProductItem::find($id);
                if ($prod) {
                    $total += $prod->price * $item['quantity'];
                }
            }

            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total' => number_format($total, 0, ',', '.'),
                'total_raw' => $total,
                'quantity' => $quantity,
                'stock' => $product->stock,
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
