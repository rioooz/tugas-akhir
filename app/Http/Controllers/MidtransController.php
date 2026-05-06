<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\ProductItemDetail;
use App\Services\WhatsAppService;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production') === true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    /**
     * Handle Midtrans notification/callback
     * Route ini harus diakses oleh Midtrans server (POST request)
     */
    public function notification(Request $request)
    {
        try {
            // Log raw notification for debugging
            \Log::debug('Midtrans Raw Notification', $request->all());
            
            // Create instance Midtrans notification
            $notification = new \Midtrans\Notification();

            // Log parsed notification
            \Log::debug('Midtrans Parsed Notification', [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status' => $notification->fraud_status ?? 'N/A',
                'transaction_id' => $notification->transaction_id,
            ]);

            // Get order ID dari notification
            $orderId = $notification->order_id;
            
            // Extract order ID dari format "ORDER-{id}-{timestamp}"
            if (strpos($orderId, 'ORDER-') === 0) {
                preg_match('/ORDER-(\d+)-/', $orderId, $matches);
                $orderId = isset($matches[1]) ? $matches[1] : null;
            }

            if (!$orderId) {
                \Log::error('Midtrans Notification: Invalid order ID format', ['order_id' => $notification->order_id]);
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID'], 400);
            }

            $order = Order::find($orderId);

            if (!$order) {
                \Log::error('Midtrans Notification: Order not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Get transaction status
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $transactionId = $notification->transaction_id;

            DB::beginTransaction();

            try {
                // Update payment status berdasarkan transaction status
                switch ($transactionStatus) {
                    case 'capture':
                        if ($fraudStatus == 'challenge') {
                            // Set payment status to pending
                            $order->update([
                                'payment_status' => PaymentStatus::PENDING->value,
                                'transaction_id' => $transactionId,
                            ]);
                            \Log::info('Payment challenged - status pending', ['order_id' => $order->id]);
                        } else if ($fraudStatus == 'accept') {
                            // Payment success, update order dan kurangi stok
                            $this->handlePaymentSuccess($order, $transactionId);
                            \Log::info('Payment success after fraud check', ['order_id' => $order->id]);
                        }
                        break;

                    case 'settlement':
                        // Payment success
                        $this->handlePaymentSuccess($order, $transactionId);
                        \Log::info('Payment settled', ['order_id' => $order->id]);
                        break;

                    case 'pending':
                        // Payment masih pending
                        $order->update([
                            'payment_status' => PaymentStatus::PENDING->value,
                            'transaction_id' => $transactionId,
                        ]);
                        \Log::info('Payment still pending', ['order_id' => $order->id]);
                        break;

                    case 'deny':
                    case 'expire':
                    case 'cancel':
                        // Payment failed/expired/cancelled
                        $this->handlePaymentFailed($order, $transactionStatus);
                        \Log::info('Payment failed', ['order_id' => $order->id, 'status' => $transactionStatus]);
                        break;
                }

                DB::commit();

                \Log::info('Midtrans Notification: Success', [
                    'order_id' => $order->id,
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                ]);

                return response()->json(['status' => 'success'], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Midtrans Notification: Database error', [
                    'order_id' => isset($order) ? $order->id : 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Midtrans Notification: Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check status dari Midtrans dan update order
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            // Gunakan transaction_id jika ada, jika tidak gunakan midtrans_order_id
            $idToCheck = $order->transaction_id ?: $order->midtrans_order_id;

            if (!$idToCheck) {
                \Log::warning('Midtrans Check Status: No identifier found', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'ID Transaksi tidak ditemukan. Silakan lakukan pembayaran terlebih dahulu.'], 404);
            }

            // Query status ke Midtrans
            $statusResponse = \Midtrans\Transaction::status($idToCheck);
            
            \Log::info('Midtrans Status Check', [
                'order_id' => $order->id,
                'transaction_id' => $order->transaction_id,
                'transaction_status' => $statusResponse->transaction_status ?? 'unknown',
                'fraud_status' => $statusResponse->fraud_status ?? 'N/A',
            ]);

            // Process sesuai status dari Midtrans
            if ($statusResponse->transaction_status == 'settlement' || $statusResponse->transaction_status == 'capture') {
                // Handle payment success
                if ($order->payment_status != PaymentStatus::PAID->value) {
                    $this->handlePaymentSuccess($order, $order->transaction_id);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pembayaran terdeteksi dan status sudah diperbarui',
                        'payment_status' => 'paid',
                        'order_status' => 'processing',
                    ]);
                } else {
                    return response()->json([
                        'status' => 'info',
                        'message' => 'Pembayaran sudah dikonfirmasi sebelumnya',
                        'payment_status' => 'paid',
                        'order_status' => $order->status,
                    ]);
                }
            } elseif ($statusResponse->transaction_status == 'expire') {
                $this->handlePaymentFailed($order, 'expire');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pembayaran sudah kadaluarsa',
                    'payment_status' => 'expired',
                ]);
            } elseif ($statusResponse->transaction_status == 'deny' || $statusResponse->transaction_status == 'failure') {
                $this->handlePaymentFailed($order, 'deny');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pembayaran ditolak',
                    'payment_status' => 'failed',
                ]);
            } else {
                $response = [
                    'status' => 'pending',
                    'message' => 'Pembayaran masih dalam proses',
                    'payment_status' => 'pending',
                    'transaction_status' => $statusResponse->transaction_status,
                ];
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json($response);
            }

            $type = $response['status'] == 'success' ? 'success' : ($response['status'] == 'info' ? 'info' : 'warning');
            return redirect()->back()->with($type, $response['message']);

        } catch (\Exception $e) {
            \Log::error('Midtrans Check Status Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengecek status: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment success
     */
    private function handlePaymentSuccess($order, $transactionId)
    {
        // Update order status hanya jika belum paid (untuk menghindari duplicate processing)
        if ($order->payment_status != PaymentStatus::PAID->value) {
            $order->update([
                'status' => OrderStatus::PROCESSING->value,
                'payment_status' => PaymentStatus::PAID->value,
                'transaction_id' => $transactionId,
            ]);

            // Kurangi stok produk (hanya jika belum dikurangi sebelumnya)
            foreach ($order->orderItems as $orderItem) {
                $productName = '';
                $variantName = null;
                $currentStock = 0;

                if ($orderItem->product_item_detail_id) {
                    $detail = ProductItemDetail::find($orderItem->product_item_detail_id);
                    if ($detail) {
                        $detail->decrement('stock', $orderItem->quantity);
                        $detail->refresh();
                        $currentStock = $detail->stock;
                        
                        $product = ProductItem::find($orderItem->product_item_id);
                        $productName = $product ? $product->name : 'Unknown Product';
                        $variantName = $detail->name;
                    }
                } else {
                    $product = ProductItem::find($orderItem->product_item_id);
                    if ($product) {
                        $product->decrement('stock', $orderItem->quantity);
                        $product->refresh();
                        $currentStock = $product->stock;
                        $productName = $product->name;
                    }
                }

                // Cek low stock alert (< 5)
                if ($currentStock < 5 && $productName != '') {
                    WhatsAppService::sendLowStockAlert($productName, $variantName, $currentStock);
                }
            }
            
            \Log::info('Payment success processed', ['order_id' => $order->id, 'transaction_id' => $transactionId]);
        }
    }

    /**
     * Handle payment failed/expired/cancelled
     */
    private function handlePaymentFailed($order, $status)
    {
        $paymentStatus = PaymentStatus::FAILED->value;
        if ($status == 'expire') {
            $paymentStatus = PaymentStatus::EXPIRED->value;
        }

        $order->update([
            'status' => OrderStatus::CANCELLED->value,
            'payment_status' => $paymentStatus,
        ]);
        
        \Log::info('Payment failed processed', ['order_id' => $order->id, 'status' => $status]);
    }
}
