@extends('layouts.admin')

@section('page_title', 'Daftar Pesanan')
@section('breadcrumb', 'Pesanan')

@section('content')
    <style>
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .filter-btn:hover {
            border-color: #007bff;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
        }

        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
            transition: all 0.3s;
        }

        .btn-view {
            background: #17a2b8;
            color: white;
        }

        .btn-view:hover {
            background: #138496;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .pagination {
            display: flex;
            gap: 5px;
            margin-top: 20px;
            justify-content: center;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
        }

        .pagination .active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>

    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Daftar Pesanan</h3>
        </div>

        @if (session('success'))
            <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="filters">
    <a href="{{ route('admin.orders.index') }}" 
       class="filter-btn @if (!request('status')) active @endif">
        Semua Pesanan
    </a>

    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
       class="filter-btn @if (request('status') == 'pending') active @endif">
        Pending
    </a>

    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
       class="filter-btn @if (request('status') == 'processing') active @endif">
        Diproses
    </a>

    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
       class="filter-btn @if (request('status') == 'completed') active @endif">
        Selesai
    </a>

    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
       class="filter-btn @if (request('status') == 'cancelled') active @endif">
        Dibatalkan
    </a>
</div>

        @if ($orders->count() > 0)
            <table>
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
                    @foreach ($orders as $order)
                        <tr>
                            <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                {{ $order->user->name ?? 'Tidak diketahui' }}
                                <br>
                                <small style="color: #999;">{{ $order->user->email ?? '' }}</small>
                            </td>
                            <td><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="status-badge status-{{ $order->status->color() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="action-btn btn-view">Lihat</a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                    style="display: inline;" onsubmit="return confirm('Yakin hapus pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
               {{ $orders->links() }}
          </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd;"></i>
                <p style="margin-top: 15px;">Belum ada pesanan</p>
            </div>
        @endif
    </div>
@endsection
