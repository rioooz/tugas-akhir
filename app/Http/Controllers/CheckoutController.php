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

        foreach ($cart as $productId => $item) {
            $product = ProductItem::find($productId);
            if ($product) {
                $itemTotal = $product->price * $item['quantity'];
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image ? asset($product->image) : 'https://via.placeholder.com/100x100',
                    'subtotal' => $itemTotal,
                ];
                $subtotal += $itemTotal;
            }
        }

        $shipping = 1000; // Biaya pengiriman
        $total = $subtotal + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
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

        // Validasi stok sebelum checkout
        foreach ($cart as $productId => $item) {
            $product = ProductItem::find($productId);
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
            }
            
            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', 'Stok produk "' . $product->name . '" tidak mencukupi. Stok tersedia: ' . $product->stock);
            }
        }

        DB::beginTransaction();
        try {
            // Hitung total
            $subtotal = 0;
            $itemDetails = [];
            
            foreach ($cart as $productId => $item) {
                $product = ProductItem::find($productId);
                if ($product) {
                    $itemTotal = $product->price * $item['quantity'];
                    $subtotal += $itemTotal;
                    
                    $itemDetails[] = [
                        'id' => (string) $product->id,
                        'price' => (int) $product->price,
                        'quantity' => (int) $item['quantity'],
                        'name' => $product->name,
                    ];
                }
            }
            
            $shipping = 1000;
            $total = $subtotal + $shipping;

            // Tambahkan shipping sebagai item detail
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shipping,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];

            // Buat order dengan status pending (belum kurangi stok)
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => OrderStatus::PENDING->value,
                'payment_status' => PaymentStatus::PENDING->value,
                'payment_method' => $request->payment_method,
            ]);

            // Buat order items (belum kurangi stok, akan dikurangi setelah payment success)
            foreach ($cart as $productId => $item) {
                $product = ProductItem::find($productId);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_item_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
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
