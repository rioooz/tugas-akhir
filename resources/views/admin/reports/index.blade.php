@extends('layouts.admin')

@section('page_title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
    <style>
        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.1);
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid rgba(219, 206, 165, 0.5);
            border-left: 5px solid #8A7650;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(138, 118, 80, 0.15);
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
            color: #8A7650;
            border: 1px solid rgba(219, 206, 165, 0.3);
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
            font-weight: 800;
            color: #8A7650;
            margin-top: 5px;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid rgba(219, 206, 165, 0.5);
        }

        .report-header {
            padding: 20px 25px;
            border-bottom: 2px solid #DBCEA5;
            background: #ECE7D1;
        }

        .report-title {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: #8A7650;
        }

        .report-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 700;
            color: #8A7650;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #fcfbf9;
            border-bottom: 2px solid #DBCEA5;
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
            background: #ECE7D1;
            color: #8A7650;
            border: 1px solid #DBCEA5;
        }

        .badge-success {
            background: #E8EEDF;
            color: #8E977D;
            border: 1px solid #C8D1BB;
        }

        .badge-warning {
            background: #FDF9E8;
            color: #B29124;
            border: 1px solid #EBE1B1;
        }

        .badge-danger {
            background: #fae3e5;
            color: #dc3545;
            border: 1px solid #f5c6cb;
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

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 25px 35px; border-radius: 12px; box-shadow: 0 5px 20px rgba(138,118,80,0.1); border: 1px solid rgba(219,206,165,0.5);">
        <div>
            <h2 style="margin: 0; color: #8A7650; font-weight: 800; font-size: 1.4rem;">Laporan Penjualan</h2>
        </div>
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-size: 0.9rem; font-weight: 700; color: #8A7650;">Dari:</label>
                <input type="date" id="start_date" style="padding: 10px 15px; border-radius: 8px; border: 1px solid #DBCEA5; font-size: 0.95rem; outline: none;">
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-size: 0.9rem; font-weight: 700; color: #8A7650;">Sampai:</label>
                <input type="date" id="end_date" style="padding: 10px 15px; border-radius: 8px; border: 1px solid #DBCEA5; font-size: 0.95rem; outline: none;">
            </div>
            <button onclick="downloadPDF()" class="btn" style="background: linear-gradient(135deg, #8A7650 0%, #6E5034 100%); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(138, 118, 80, 0.2); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                <span>📄</span> Download Laporan (PDF)
            </button>
        </div>
    </div>

    <script>
        function downloadPDF() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            
            let url = "{{ route('admin.reports.export') }}";
            const params = new URLSearchParams();
            
            if (start) params.append('start_date', start);
            if (end) params.append('end_date', end);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            window.location.href = url;
        }
    </script>

    <div class="grid-2">
        <div class="stat-card" style="border-left-color: #8A7650;">
            <div class="stat-icon" style="color: #8A7650;">📊</div>
            <div class="stat-content">
                <h3>Total Pesanan</h3>
                <div class="value">{{ $totalOrders }}</div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #8E977D;">
            <div class="stat-icon" style="color: #8E977D;">💰</div>
            <div class="stat-content">
                <h3>Total Pendapatan</h3>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #DBCEA5;">
            <div class="stat-icon" style="color: #B29124;">✓</div>
            <div class="stat-content">
                <h3>Pesanan Selesai</h3>
                <div class="value">{{ $completedOrders }}</div>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #6E5034;">
            <div class="stat-icon" style="color: #6E5034;">⏳</div>
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
            <h3 class="report-title">Tren Revenue (3 Bulan Terakhir)</h3>
        </div>
        <div class="report-body">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px;">
                <div>
                    <label for="monthFilter" style="font-weight:600; color:#444; margin-right:8px">Filter Bulan:</label>
                    <select id="monthFilter" style="padding:6px 10px; border-radius:6px; border:1px solid #ddd">
                        <option value="">Seluruh (3 bulan)</option>
                        @foreach($monthKeys as $idx => $mk)
                            <option value="{{ $mk }}">{{ $labels[$idx] }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="color:#666; font-size:0.95rem">Nilai dalam Rupiah (Rp)</div>
            </div>

            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const monthlyLabels = {!! json_encode($labels) !!};
            const monthlyData = {!! json_encode($revenues) !!};
            const monthKeys = {!! json_encode($monthKeys) !!};

            let revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: monthlyData,
                        borderColor: '#8A7650',
                        backgroundColor: 'rgba(138, 118, 80, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#8A7650'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + Number(context.parsed.y).toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            function setMonthlyView() {
                revenueChart.data.labels = monthlyLabels;
                revenueChart.data.datasets[0].data = monthlyData;
                revenueChart.data.datasets[0].label = 'Revenue (Rp)';
                revenueChart.update();
            }

            function setDailyView(labels, data, monthLabel) {
                revenueChart.data.labels = labels;
                revenueChart.data.datasets[0].data = data;
                revenueChart.data.datasets[0].label = 'Revenue - ' + monthLabel;
                revenueChart.update();
            }

            document.getElementById('monthFilter').addEventListener('change', function(e){
                const val = e.target.value;
                if (!val) {
                    setMonthlyView();
                    return;
                }

                // fetch daily revenue for selected month
                fetch('{{ route("admin.reports.revenueByMonth") }}?month=' + val)
                    .then(r => r.json())
                    .then(json => {
                        if (json.labels && json.data) {
                            setDailyView(json.labels, json.data, json.monthLabel || val);
                        }
                    }).catch(err => {
                        console.error(err);
                        alert('Gagal mengambil data untuk bulan tersebut.');
                    });
            });
        })();
    </script>
@endsection
