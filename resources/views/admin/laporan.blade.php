@extends('layouts.admin')

@section('page_title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
    <style>
        .stat-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 4px solid #007bff;
        }

        .stat-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-content h3 {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
        }

        .stat-content .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-top: 5px;
        }

        .report-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .report-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .report-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .report-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .stat-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .stat-list li {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-list li:last-child {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-primary {
            background: #e7f3ff;
            color: #004085;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="grid-2">
        <div class="stat-card" style="border-left-color: #007bff;">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3>Total Pesanan</h3>
                <div class="value">{{ $totalOrders }}</div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #28a745;">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Total Pendapatan</h3>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #17a2b8;">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <h3>Pesanan Selesai</h3>
                <div class="value">{{ $completedOrders }}</div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #ffc107;">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <h3>Pesanan Pending</h3>
                <div class="value">{{ $pendingOrders }}</div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="report-card">
            <div class="report-header">
                <h3 class="report-title">Status Pesanan</h3>
            </div>
            <div class="report-body">
                <ul class="stat-list">
                    @forelse($ordersByStatus as $status => $count)
                        <li>
                            <span><strong>{{ $status }}</strong></span>
                            <span class="badge badge-primary">{{ $count }} pesanan</span>
                        </li>
                    @empty
                        <li class="empty-message">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="report-card">
            <div class="report-header">
                <h3 class="report-title">Produk Stok Rendah (&lt; 10 pcs)</h3>
            </div>
            <div class="report-body">
                @if ($lowStockProducts->count() > 0)
                    <ul class="stat-list">
                        @foreach ($lowStockProducts as $product)
                            <li>
                                <span><strong>{{ $product->name }}</strong></span>
                                <span class="badge badge-danger">{{ $product->stock }} pcs</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-message">✓ Semua produk stok aman</div>
                @endif
            </div>
        </div>
    </div>

    <div class="report-card">
        <div class="report-header">
            <h3 class="report-title">Top 5 Produk Terlaris (30 Hari Terakhir)</h3>
        </div>
        <div class="report-body">
            @if ($topProducts->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Terjual</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topProducts as $item)
                            <tr>
                                <td><strong>{{ $item->productItem->name ?? 'Produk Dihapus' }}</strong></td>
                                <td>{{ $item->total_qty }} pcs</td>
                                <td>Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">Belum ada penjualan</div>
            @endif
        </div>
    </div>

    <div class="report-card">
        <div class="report-header">
            <h3 class="report-title">Tren Revenue (30 Hari Terakhir)</h3>
        </div>
        <div class="report-body">
            @if ($revenueTrend->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revenueTrend as $trend)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($trend->date)->format('d M Y') }}</strong></td>
                                <td>Rp {{ number_format($trend->revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">Belum ada transaksi</div>
            @endif
        </div>
    </div>
@endsection
