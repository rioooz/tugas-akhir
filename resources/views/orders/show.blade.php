@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('extra_css')
    <style>
        .invoice-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0f2e9;
        }

        .invoice-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta-item {
            margin-bottom: 5px;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .invoice-meta-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .invoice-section {
            margin-bottom: 30px;
        }

        .invoice-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0f2e9;
        }

        .invoice-table th {
            background: white;
            font-weight: 600;
            color: var(--text-dark);
        }

        .invoice-table td {
            color: var(--text);
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        .invoice-summary {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #e0f2e9;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0f2e9;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            color: var(--text-light);
        }

        .summary-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-total {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #e0f2e9;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-processing {
            background: #cfe2ff;
            color: #084298;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-expired {
            background: #f8d7da;
            color: #721c24;
        }

        .status-cancelled {
            background: #e2e3e5;
            color: #41464b;
        }

        .invoice-actions {
            margin-top: 30px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--accent);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn:hover {
            background: var(--red);
        }
    </style>
@endsection

@section('content')
    <div class="invoice-container">
        <div class="invoice-header">
            <div>
                <h1 class="invoice-title">Nota Belanja</h1>
                <p style="color: var(--text-light); margin-top: 5px;">Mahesty Mebel</p>
            </div>
            <div class="invoice-meta">
                <div class="invoice-meta-item">
                    <span>ID Pesanan:</span> <span class="invoice-meta-value">#{{ $order->id }}</span>
                </div>
                <div class="invoice-meta-item">
                    <span>Tanggal:</span> <span
                        class="invoice-meta-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="invoice-meta-item">
                    <span>Status:</span>
                    <span class="status-badge status-{{ $order->payment_status->color() }}" id="statusBadge"
                        data-payment-status="{{ $order->payment_status->value }}">
                        @if ($order->payment_status->value == 'paid')
                            Lunas
                        @elseif($order->payment_status->value == 'pending')
                            Menunggu Pembayaran
                        @elseif($order->payment_status->value == 'failed')
                            Gagal
                        @elseif($order->payment_status->value == 'expired')
                            Kadaluarsa
                        @else
                            {{ $order->payment_status->label() }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="invoice-section">
            <h2 class="invoice-section-title">Informasi Pelanggan</h2>
            <div>
                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
            </div>
        </div>

        <div class="invoice-section">
            <h2 class="invoice-section-title">Detail Produk</h2>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->productItem->name }}</strong>
                            </td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="invoice-summary">
            <div class="summary-row">
                <span class="summary-label">Subtotal:</span>
                <span class="summary-value">Rp
                    {{ number_format($order->orderItems->sum(function ($item) {return $item->price * $item->quantity;}),0,',','.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Biaya Pengiriman:</span>
                <span class="summary-value">Rp
                    {{ number_format($order->total -$order->orderItems->sum(function ($item) {return $item->price * $item->quantity;}),0,',','.') }}</span>
            </div>
            <div class="summary-row summary-total">
                <span class="summary-label">Total Pembayaran:</span>
                <span class="summary-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="invoice-section">
            <h2 class="invoice-section-title">Informasi Pembayaran</h2>
            <div>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                @if ($order->transaction_id)
                    <p><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
                @endif
                <p><strong>Status Pembayaran:</strong>
                    <span class="status-badge status-{{ $order->payment_status->color() }}" id="paymentStatusBadge"
                        data-payment-status="{{ $order->payment_status->value }}">
                        @if ($order->payment_status->value == 'paid')
                            Lunas
                        @elseif($order->payment_status->value == 'pending')
                            Menunggu Pembayaran
                        @elseif($order->payment_status->value == 'failed')
                            Gagal
                        @elseif($order->payment_status->value == 'expired')
                            Kadaluarsa
                        @else
                            {{ $order->payment_status->label() }}
                        @endif
                    </span>
                </p>
            </div>
        </div>

        <div class="invoice-actions">
            @if ($order->payment_status->value == 'pending')
            @endif
            <a href="{{ route('orders.index') }}" class="btn" style="background: #0e8f2c;">Kembali ke Riwayat</a>
        </div>
    </div>

    <script>
        const orderId = {{ $order->id }};
        const checkPaymentUrl = `/api/orders/${orderId}/check-payment-status`;

        // Function untuk check status dan update halaman
        async function checkAndUpdateStatus() {
            try {
                const response = await fetch(checkPaymentUrl);
                const data = await response.json();

                console.log('Payment status check:', data);

                if (data.payment_status === 'paid') {
                    // Jika pembayaran sudah berhasil, reload halaman
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            } catch (err) {
                console.error('Check status error:', err);
            }
        }

        // Auto-refresh setiap 5 detik jika status masih pending
        @if ($order->payment_status->value == 'pending')
            let autoRefreshCount = 0;
            const autoRefreshInterval = setInterval(() => {
                checkAndUpdateStatus();
                autoRefreshCount++;

                // Stop auto-refresh setelah 12 kali (60 detik)
                if (autoRefreshCount >= 12) {
                    clearInterval(autoRefreshInterval);
                    console.log('Auto-refresh stopped after 60 seconds');
                }
            }, 5000);

            // Manual refresh button
            const refreshBtn = document.getElementById('refreshBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    checkAndUpdateStatus();
                });
            }
        @endif
    </script>
@endsection
