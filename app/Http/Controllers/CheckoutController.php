<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production') === true;
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        // Log configuration for debugging
        if (!Config::$serverKey) {
            \Log::error('Midtrans Server Key not configured');
        }
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $cartKey => $item) {
            // support variant keys like 'detail_{id}'
            if (is_string($cartKey) && str_starts_with($cartKey, 'detail_')) {
                $detailId = (int) str_replace('detail_', '', $cartKey);
                $detail = \App\Models\ProductItemDetail::find($detailId);
                if (!$detail) continue;
                $parent = $detail->productItem;
                $price = $item['price'] ?? $detail->price;
                $itemTotal = $price * $item['quantity'];
                $name =  $detail->name . ($detail->size ? ' (' . $detail->size . ')' : '');

                $cartItems[] = [
                    'key' => $cartKey,
                    'id' => $parent->id,
                    'detail_id' => $detail->id,
                    'name' => $name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'image' => $parent->image ? asset($parent->image) : 'https://via.placeholder.com/100x100',
                    'subtotal' => $itemTotal,
                ];
                $subtotal += $itemTotal;
            } else {
                $productId = $cartKey;
                $product = ProductItem::find($productId);
                if (!$product) continue;
                $price = $item['price'] ?? $product->price;
                $itemTotal = $price * $item['quantity'];

                $cartItems[] = [
                    'key' => $cartKey,
                    'id' => $product->id,
                    'detail_id' => null,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image ? asset($product->image) : 'https://via.placeholder.com/100x100',
                    'subtotal' => $itemTotal,
                ];
                $subtotal += $itemTotal;
            }
        }

        $total = $subtotal; // shipping can be added later

        return view('checkout.index', compact('cartItems', 'subtotal', 'total'));
    }

    /**
     * Memproses data checkout dan membuat transaksi dengan Midtrans Snap.
     */
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi stok sebelum checkout (support variant items)
        foreach ($cart as $cartKey => $item) {
            if (is_string($cartKey) && str_starts_with($cartKey, 'detail_')) {
                $detailId = (int) str_replace('detail_', '', $cartKey);
                $detail = \App\Models\ProductItemDetail::find($detailId);
                if (!$detail) {
                    return redirect()->route('cart.index')->with('error', 'Detail produk tidak ditemukan.');
                }
                if ($detail->stock < $item['quantity']) {
                    return redirect()->route('cart.index')->with('error', 'Stok varian "' . $detail->name . '" tidak mencukupi. Stok tersedia: ' . $detail->stock);
                }
            } else {
                $product = ProductItem::find($cartKey);
                if (!$product) {
                    return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
                }
                if ($product->stock < $item['quantity']) {
                    return redirect()->route('cart.index')->with('error', 'Stok produk "' . $product->name . '" tidak mencukupi. Stok tersedia: ' . $product->stock);
                }
            }
        }

        DB::beginTransaction();
        try {
            // Hitung total dan buat daftar item untuk Midtrans
            $subtotal = 0;
            $itemDetails = [];

            foreach ($cart as $cartKey => $item) {
                if (is_string($cartKey) && str_starts_with($cartKey, 'detail_')) {
                    $detailId = (int) str_replace('detail_', '', $cartKey);
                    $detail = \App\Models\ProductItemDetail::find($detailId);
                    if (!$detail) continue;
                    $parent = $detail->productItem;
                    $price = (int) ($item['price'] ?? $detail->price);
                    $qty = (int) $item['quantity'];
                    $subtotal += $price * $qty;

                    $itemDetails[] = [
                        'id' => 'D' . $detail->id,
                        'price' => $price,
                        'quantity' => $qty,
                        'name' => $parent->name . ' - ' . $detail->name,
                    ];
                } else {
                    $product = ProductItem::find($cartKey);
                    if (!$product) continue;
                    $price = (int) ($item['price'] ?? $product->price);
                    $qty = (int) $item['quantity'];
                    $subtotal += $price * $qty;

                    $itemDetails[] = [
                        'id' => (string) $product->id,
                        'price' => $price,
                        'quantity' => $qty,
                        'name' => $product->name,
                    ];
                }
            }

            $total = $subtotal;

            // Tambahkan shipping (kosong untuk saat ini)
            $shipping = 0;
            // Buat order dengan status pending (belum kurangi stok)
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => OrderStatus::PENDING->value,
                'payment_status' => PaymentStatus::PENDING->value,
                'payment_method' => $request->payment_method,
            ]);

            // Buat order items (simpan product_item_detail_id jika ada)
            foreach ($cart as $cartKey => $item) {
                if (is_string($cartKey) && str_starts_with($cartKey, 'detail_')) {
                    $detailId = (int) str_replace('detail_', '', $cartKey);
                    $detail = \App\Models\ProductItemDetail::find($detailId);
                    if (!$detail) continue;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_item_id' => $detail->product_item_id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? $detail->price,
                        'product_item_detail_id' => $detail->id,
                    ]);
                } else {
                    $product = ProductItem::find($cartKey);
                    if (!$product) continue;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_item_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? $product->price,
                    ]);
                }
            }

            // Prepare Midtrans Snap parameters
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . time(),
                    'gross_amount' => (int) $total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->name,
                    'phone' => $request->phone,
                    'billing_address' => [
                        'address' => $request->address,
                    ],
                ],
                'callbacks' => [
                    'finish' => route('checkout.finish', $order->id),
                    'unfinish' => route('checkout.unfinish', $order->id),
                    'error' => route('checkout.error', $order->id),
                ],
            ];

            // Generate Snap token
            try {
                $snapToken = Snap::getSnapToken($params);
                
                if (!$snapToken) {
                    throw new \Exception('Gagal generate Snap token. Pastikan MIDTRANS_SERVER_KEY sudah di-set di .env');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
                return redirect()->route('checkout.index')
                    ->with('error', 'Gagal memproses pembayaran. Error: ' . $e->getMessage() . '. Pastikan MIDTRANS_SERVER_KEY sudah di-set di .env');
            }

            // Update order dengan snap token
            $order->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            // Validasi client key
            $clientKey = config('midtrans.client_key');
            if (!$clientKey) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Konfigurasi Midtrans belum lengkap. Pastikan MIDTRANS_CLIENT_KEY sudah di-set di .env');
            }

            // Kirim snap token ke view
            return view('checkout.payment', [
                'order' => $order,
                'snapToken' => $snapToken,
                'clientKey' => $clientKey,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Handle finish callback dari Midtrans
     */
    public function finish($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        return redirect()->route('orders.show', $order->id)->with('success', 'Pembayaran sedang diproses. Kami akan mengirimkan konfirmasi setelah pembayaran berhasil.');
    }

    /**
     * Handle unfinish callback dari Midtrans
     */
    public function unfinish($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        return redirect()->route('orders.show', $order->id)->with('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
    }

    /**
     * Handle error callback dari Midtrans
     */
    public function error($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        return redirect()->route('orders.show', $order->id)->with('error', 'Terjadi kesalahan saat proses pembayaran. Silakan coba lagi.');
    }
}
