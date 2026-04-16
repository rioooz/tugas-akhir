@extends('layouts.app')

@section('title', 'Pembayaran')

@section('extra_css')
    <style>
        .payment-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .payment-header {
            margin-bottom: 30px;
        }

        .payment-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .payment-header p {
            color: var(--text-light);
            font-size: 1rem;
        }

        .payment-info {
            background: var(--card);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .payment-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--muted);
        }

        .payment-info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .payment-info-label {
            font-weight: 600;
            color: var(--text-light);
        }

        .payment-info-value {
            font-weight: 700;
            color: var(--text-dark);
        }

        .snap-container {
            margin-top: 30px;
        }

        .loading {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid var(--muted);
            border-top-color: #0e8f2c;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="payment-container">
        <div class="payment-header">
            <h1>Pembayaran</h1>
            <p>Silakan selesaikan pembayaran Anda melalui Midtrans</p>
        </div>

        <div class="payment-info">
            <div class="payment-info-item">
                <span class="payment-info-label">Nomor Pesanan:</span>
                <span class="payment-info-value">#{{ $order->id }}</span>
            </div>
            <div class="payment-info-item">
                <span class="payment-info-label">Total Pembayaran:</span>
                <span class="payment-info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <div class="payment-info-item">
                <span class="payment-info-label">Metode Pembayaran:</span>
                <span class="payment-info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
        </div>

        <div class="snap-container">
            <div id="snap-container"></div>
            <div id="loading" class="loading" style="display: none;"></div>
            <button id="btn-pay" onclick="initSnapPayment()" class="btn" style="margin-top: 20px; display: none;">
                Buka Halaman Pembayaran
            </button>
            <div id="error-message" style="display: none; color: red; margin-top: 20px;"></div>
        </div>
    </div>
@endsection

@section('extra_js')
    @if (config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @endif

    <script>
        // Debug info
        console.log('Snap Token:', '{{ $snapToken }}');
        console.log('Client Key:', '{{ $clientKey }}');

        let snapLoaded = false;
        let retryCount = 0;
        const maxRetries = 10;

        // Check if Snap JS is loaded
        function checkSnapLoaded() {
            if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
                snapLoaded = true;
                console.log('Snap JS loaded successfully');
                document.getElementById('btn-pay').style.display = 'none';
                initSnapPayment();
            } else {
                retryCount++;
                if (retryCount < maxRetries) {
                    console.log('Waiting for Snap JS to load... (' + retryCount + '/' + maxRetries + ')');
                    setTimeout(checkSnapLoaded, 500);
                } else {
                    console.error('Snap JS failed to load after ' + maxRetries + ' attempts');
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('btn-pay').style.display = 'inline-block';
                    document.getElementById('error-message').style.display = 'block';
                    document.getElementById('error-message').innerHTML =
                        'Gagal memuat halaman pembayaran. Silakan klik tombol di bawah untuk membuka halaman pembayaran.';
                }
            }
        }

        // Wait for Snap JS to load
        function initSnapPayment() {
            if (typeof window.snap === 'undefined' || typeof window.snap.pay !== 'function') {
                console.error('Snap JS belum ter-load');
                document.getElementById('btn-pay').style.display = 'inline-block';
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').innerHTML =
                    'Gagal memuat halaman pembayaran. Silakan klik tombol di bawah untuk membuka halaman pembayaran.';
                return;
            }

            const snapToken = '{{ $snapToken }}';
            if (!snapToken || snapToken.trim() === '') {
                alert('Error: Snap token tidak tersedia. Silakan hubungi administrator.');
                console.error('Snap token kosong');
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').innerHTML = 'Error: Snap token tidak tersedia.';
                return;
            }

            // Hide button and show loading
            document.getElementById('btn-pay').style.display = 'none';
            document.getElementById('loading').style.display = 'block';
            document.getElementById('error-message').style.display = 'none';

            // Open Snap popup
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    document.getElementById('loading').style.display = 'none';
                    window.location.href = '{{ route('checkout.finish', $order->id) }}';
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    document.getElementById('loading').style.display = 'none';
                    window.location.href = '{{ route('checkout.unfinish', $order->id) }}';
                },
                onError: function(result) {
                    console.log('Payment Error:', result);
                    document.getElementById('loading').style.display = 'none';
                    alert('Terjadi kesalahan saat proses pembayaran. Silakan coba lagi.');
                    window.location.href = '{{ route('checkout.error', $order->id) }}';
                },
                onClose: function() {
                    console.log('Payment Popup Closed');
                    document.getElementById('loading').style.display = 'none';
                }
            });
        }

        // Initialize when page loads
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(checkSnapLoaded, 1000);
            });
        } else {
            setTimeout(checkSnapLoaded, 1000);
        }
    </script>
@endsection
