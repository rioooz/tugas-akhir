@extends('layouts.admin')

@section('page_title', 'Detail Pesanan #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))
@section('breadcrumb', 'Detail Pesanan')

@section('content')
    <style>
        .detail-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-card {
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .detail-card h3 {
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
            color: #333;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            text-align: right;
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .items-table th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .total-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .total-row.final {
            font-size: 1.2rem;
            color: #007bff;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .detail-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <a href="{{ route('admin.orders.index') }}" class="back-link">← Kembali ke Daftar Pesanan</a>

    @if (session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="detail-container">
        <!-- Main Details -->
        <div>
            <!-- Order Info -->
            <div class="detail-card">
                <h3>Informasi Pesanan</h3>
                <div class="detail-row">
                    <span class="detail-label">ID Pesanan</span>
                    <span class="detail-value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal</span>
                    <span class="detail-value">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status Pembayaran</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $order->payment_status->color() }}">
                            {{ $order->payment_status->label() }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="detail-card">
                <h3>Informasi Pelanggan</h3>
                <div class="detail-row">
                    <span class="detail-label">Nama</span>
                    <span class="detail-value">{{ $order->user->name ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $order->user->email ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Alamat</span>
                    <span class="detail-value">{{ $order->user->address ?? '-' }}</span>
                </div>
            </div>

            <!-- Items -->
            <div class="detail-card">
                <h3>Item Pesanan</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->productItem->name ?? 'Produk tidak ditemukan' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td><strong>Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Status Update -->
            <div class="detail-card">
                <h3>Update Status</h3>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Status Pesanan</label>
                        <select name="status">
                            @foreach (\App\Enums\OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}" @if ($order->status === $status) selected @endif>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="action-btn btn-primary" style="width: 100%;">Simpan Status</button>
                </form>
            </div>

            <!-- Summary -->
            <div class="detail-card">
                <h3>Ringkasan Pesanan</h3>
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($order->orderItems->sum(fn($item) => $item->quantity * $item->price), 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Ongkir</span>
                        <span>Rp{{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Diskon</span>
                        <span>-Rp{{ number_format($order->discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row final">
                        <span>Total</span>
                        <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus pesanan ini?');" style="margin-top: 15px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn btn-danger" style="width: 100%;">Hapus Pesanan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
