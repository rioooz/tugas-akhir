@extends('layouts.admin')

@section('page_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .stat-card.primary {
            border-left-color: #8A7650;
        }

        .stat-card.success {
            border-left-color: #8E977D;
        }

        .stat-card.warning {
            border-left-color: #DBCEA5;
        }

        .stat-card.danger {
            border-left-color: #736140;
        }

        .stat-icon {
            font-size: 2.2rem;
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fcfbf9;
            border-radius: 10px;
            border: 1px solid rgba(219, 206, 165, 0.4);
            opacity: 1;
        }

        .stat-content h3 {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #8A7650;
            margin-top: 10px;
        }

        .data-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .data-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #8A7650;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: #DBCEA5;
        }

        .data-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #8A7650;
            font-size: 0.85rem;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            color: #333;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .action-link {
            display: inline-block;
            padding: 8px 12px;
            background: #8A7650;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .action-link:hover {
            background: #736140;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .alert-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #ffc107;
        }

        .alert-title {
            font-size: 1rem;
            font-weight: 700;
            color: #856404;
            margin: 0 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .alert-items li {
            padding: 8px 0;
            color: #856404;
            border-bottom: 1px solid #ffeaa7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-items li:last-child {
            border-bottom: none;
        }

        .stock-badge {
            background: #e43522;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .alert-danger {
            background: #fcfbf9;
            border: 1px solid #DBCEA5;
            border-left-color: #e43522;
            box-shadow: 0 4px 15px rgba(228, 53, 34, 0.05);
        }

        .alert-danger .alert-title {
            color: #e43522;
        }

        .alert-danger .alert-items li {
            color: #444;
            border-bottom-color: #eee;
        }

        .alert-success {
            background: #fcfbf9;
            border: 1px solid #DBCEA5;
            border-left-color: #8E977D;
            box-shadow: 0 4px 15px rgba(142, 151, 125, 0.05);
        }

        .alert-success .alert-title {
            color: #8E977D;
        }

        .alert-success .alert-items li {
            color: #444;
            border-bottom-color: #eee;
        }

        .sales-badge {
            background: #8E977D;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .notification-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
    </style>

    <!-- Summary Cards -->
    <div class="dashboard-grid">
        <div class="stat-card primary">
            <div class="stat-content">
                <h3>Total Produk</h3>
                <div class="stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="stat-icon">📦</div>
        </div>

        <div class="stat-card success">
            <div class="stat-content">
                <h3>Total Pesanan</h3>
                <div class="stat-value">{{ $totalOrders }}</div>
            </div>
            <div class="stat-icon">🛒</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-content">
                <h3>Total Pelanggan</h3>
                <div class="stat-value">{{ $totalCustomers }}</div>
            </div>
            <div class="stat-icon">👥</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-content">
                <h3>Total Pendapatan</h3>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <!-- Notification Alerts -->
    <div class="notification-grid">
        <!-- Alert 1: Critical Stock (< 2) -->
        @if ($criticalStockProducts->count() > 0)
            <div class="alert-box alert-danger">
                <h3 class="alert-title">🚨 URGENT: Stok Kritis - Perlu Restok Segera!</h3>
                <ul class="alert-items">
                    @foreach ($criticalStockProducts as $product)
                        <li>
                            <span><strong>{{ $product->name }}</strong></span>
                            <span class="stock-badge">{{ $product->stock }} pcs</span>
                        </li>
                    @endforeach
                </ul>
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 0.9rem;">
                    <a href="{{ route('admin.stock-in.index') }}"
                        style="color: #e43522; font-weight: 600; text-decoration: none;">⚡ Restok Sekarang →</a>
                </p>
            </div>
        @endif

        <!-- Alert 2: Best Sellers -->
        @if ($bestsellers->count() > 0)
            <div class="alert-box alert-success">
                <h3 class="alert-title">🔥 Produk Laris: Perlu Diproses Segera</h3>
                <ul class="alert-items">
                    @foreach ($bestsellers as $item)
                        @if ($item->productItem)
                            <li>
                                <span><strong>{{ $item->productItem->name }}</strong></span>
                                <span class="sales-badge">{{ $item->total_qty }} terjual</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 0.9rem;">
                    <a href="{{ route('admin.orders.index') }}"
                        style="color: #8E977D; font-weight: 600; text-decoration: none;">📦 Lihat Pesanan Masuk →</a>
                </p>
            </div>
        @endif

        <!-- Unprocessed Orders Alert -->
        @if ($unprocessedOrders->count() > 0)
            <div class="alert-box" style="background: #fcfbf9; border: 1px solid #DBCEA5; border-left: 4px solid #8A7650; box-shadow: 0 4px 15px rgba(138, 118, 80, 0.05);">
                <h3 class="alert-title" style="color: #8A7650;">⏳ Transaksi Menunggu: {{ $unprocessedOrders->count() }} Pesanan Belum Diproses</h3>
                <ul class="alert-items">
                    @foreach ($unprocessedOrders as $order)
                        <li style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee;">
                            <span style="color: #444;"><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong> -
                                {{ $order->user->name ?? 'Pelanggan Tidak Diketahui' }}</span>
                            <span class="sales-badge"
                                style="background: #8A7650;">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 0.9rem;">
                    <a href="{{ route('admin.orders.index') }}"
                        style="color: #8A7650; font-weight: 600; text-decoration: none;">⚙️ Proses Transaksi →</a>
                </p>
            </div>
        @endif
    </div>

    <!-- Recent Orders -->
    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="action-link">Lihat Semua →</a>
        </div>

        @if ($recentOrders->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $order->user->name ?? 'Tidak diketahui' }}</td>
                            <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status->color() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="action-link">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">Belum ada pesanan</div>
        @endif
    </div>
@endsection
