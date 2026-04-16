﻿@extends('layouts.admin')

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
            border-left-color: #007bff;
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.warning {
            border-left-color: #ffc107;
        }

        .stat-card.danger {
            border-left-color: #dc3545;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.15;
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
            color: #333;
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
            color: #333;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: #f8f9fa;
        }

        .data-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #666;
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
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .action-link:hover {
            background: #0056b3;
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
            background: #ff6b6b;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-left-color: #dc3545;
        }

        .alert-danger .alert-title {
            color: #721c24;
        }

        .alert-danger .alert-items li {
            color: #721c24;
            border-bottom-color: #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-left-color: #28a745;
        }

        .alert-success .alert-title {
            color: #155724;
        }

        .alert-success .alert-items li {
            color: #155724;
            border-bottom-color: #c3e6cb;
        }

        .sales-badge {
            background: #28a745;
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
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 0.9rem; color: #721c24;">
                    <a href="{{ route('admin.barang.index') }}"
                        style="color: #721c24; font-weight: 600; text-decoration: none;">⚡ Restok Sekarang →</a>
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
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 0.9rem; color: #155724;">
                    <a href="{{ route('admin.orders.index') }}"
                        style="color: #155724; font-weight: 600; text-decoration: none;">📦 Lihat Pesanan Masuk →</a>
                </p>
            </div>
        @endif

        <!-- Unprocessed Orders Alert -->
        @if ($unprocessedOrders->count() > 0)
            <div class="alert-box alert-info" style="border-left: 4px solid #0dcaf0; background: #f0f9ff;">
                <h3 class="alert-title" style="color: #0c63e4;">⏳ Transaksi Menunggu: {{ $unprocessedOrders->count() }}
                    Pesanan Belum Diproses</h3>
                <ul class="alert-items">
                    @foreach ($unprocessedOrders as $order)
                        <li style="display: flex; justify-content: space-between; align-items: center;">
                            <span><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong> -
                                {{ $order->user->name ?? 'Pelanggan Tidak Diketahui' }}</span>
                            <span class="sales-badge"
                                style="background: #0dcaf0;">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 0.9rem; color: #0c63e4;">
                    <a href="{{ route('admin.orders.index') }}"
                        style="color: #0c63e4; font-weight: 600; text-decoration: none;">⚙️ Proses Transaksi →</a>
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

    <script>
        // Show toast notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if ($criticalStockProducts->count() > 0)
                // Critical Stock Alert
                let criticalProducts = [
                    @foreach ($criticalStockProducts as $product)
                        '{{ $product->name }} ({{ $product->stock }} pcs)',
                    @endforeach
                ];

                let criticalMessage = '<strong>🚨 STOK KRITIS - PERLU RESTOK SEGERA!</strong><br>';
                criticalMessage += '<ul style="margin-top: 10px; margin-bottom: 5px; padding-left: 20px;">';
                criticalProducts.forEach(function(product) {
                    criticalMessage += '<li style="margin: 5px 0;">' + product + '</li>';
                });
                criticalMessage += '</ul>';
                criticalMessage +=
                    '<a href="{{ route('admin.barang.index') }}" style="color: white; font-weight: bold; text-decoration: underline;">⚡ Restok Sekarang →</a>';

                toastr.error(criticalMessage, '', {
                    timeOut: 0,
                    extendedTimeOut: 0,
                    closeButton: true,
                    allowHtml: true
                });
            @endif

            @if ($bestsellers->count() > 0)
                // Best Sellers Alert
                let sellerProducts = [
                    @foreach ($bestsellers as $item)
                        @if ($item->productItem)
                            '{{ $item->productItem->name }} ({{ $item->total_qty }} terjual)',
                        @endif
                    @endforeach
                ];

                if (sellerProducts.length > 0) {
                    let sellerMessage = '<strong>🔥 PRODUK LARIS - PERLU DIPROSES SEGERA!</strong><br>';
                    sellerMessage += '<ul style="margin-top: 10px; margin-bottom: 5px; padding-left: 20px;">';
                    sellerProducts.forEach(function(product) {
                        sellerMessage += '<li style="margin: 5px 0;">' + product + '</li>';
                    });
                    sellerMessage += '</ul>';
                    sellerMessage +=
                        '<a href="{{ route('admin.orders.index') }}" style="color: white; font-weight: bold; text-decoration: underline;">📦 Lihat Pesanan →</a>';

                    toastr.warning(sellerMessage, '', {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        allowHtml: true
                    });
                }
            @endif

            @if ($unprocessedOrders->count() > 0)
                // Unprocessed Orders Alert
                let unprocessedOrders = [
                    @foreach ($unprocessedOrders as $order)
                        {
                            id: '{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}',
                            customer: '{{ $order->user->name ?? 'Pelanggan Tidak Diketahui' }}',
                            total: '{{ number_format($order->total, 0, ',', '.') }}'
                        },
                    @endforeach
                ];

                if (unprocessedOrders.length > 0) {
                    let unprocessedMessage = '<strong>⏳ ' + unprocessedOrders.length +
                        ' TRANSAKSI MENUNGGU DIPROSES!</strong><br>';
                    unprocessedMessage +=
                        '<ul style="margin-top: 10px; margin-bottom: 5px; padding-left: 20px; max-height: 200px; overflow-y: auto;">';
                    unprocessedOrders.forEach(function(order) {
                        unprocessedMessage += '<li style="margin: 5px 0;"><strong>#' + order.id +
                            '</strong> - ' + order.customer + ' (Rp' + order.total + ')</li>';
                    });
                    unprocessedMessage += '</ul>';
                    unprocessedMessage +=
                        '<a href="{{ route('admin.orders.index') }}" style="color: white; font-weight: bold; text-decoration: underline;">⚙️ Proses Sekarang →</a>';

                    toastr.info(unprocessedMessage, '', {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        allowHtml: true
                    });
                }
            @endif
        });
    </script>
@endsection
