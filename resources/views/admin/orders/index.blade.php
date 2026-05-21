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
            padding: 8px 18px;
            border: 1px solid #DBCEA5;
            background: white;
            color: #8A7650;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }

        .filter-btn.active {
            background: #8A7650;
            color: white;
            border-color: #8A7650;
            box-shadow: 0 4px 10px rgba(138, 118, 80, 0.2);
        }

        .filter-btn:hover:not(.active) {
            border-color: #8A7650;
            background: #ECE7D1;
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
            box-shadow: 0 2px 10px rgba(138, 118, 80, 0.05);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #DBCEA5;
        }

        th {
            background: #ECE7D1;
            color: #8A7650;
            padding: 15px;
            text-align: left;
            font-weight: 700;
            border-bottom: 2px solid #DBCEA5;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        tr:hover {
            background: #fcfbf9;
        }

        .action-btn {
            padding: 6px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
            transition: all 0.3s;
        }

        .btn-view {
            background: #8A7650;
            color: white;
        }

        .btn-view:hover {
            background: #6E5034;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(138, 118, 80, 0.2);
        }

        .btn-delete {
            background: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
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
            border: 1px solid #DBCEA5;
            border-radius: 4px;
            text-decoration: none;
            color: #8A7650;
            font-weight: 600;
            background: white;
        }

        .pagination .active {
            background: #8A7650;
            color: white;
            border-color: #8A7650;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>

    <div class="data-card" style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15); overflow: hidden; border: 1px solid rgba(219, 206, 165, 0.5);">
        <div class="data-header" style="background: #8A7650; color: #ffffff; padding: 25px 35px; border-bottom: none; display: flex; align-items: center; justify-content: space-between;">
            <h3 class="data-title" style="margin: 0; font-size: 1.4rem; font-weight: 700; color: #ffffff;">
                <i class="fas fa-shopping-basket"></i> Daftar Pesanan
            </h3>
        </div>

        <div style="padding: 35px;">

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
                <i class="fas fa-inbox" style="font-size: 3rem; color: #DBCEA5;"></i>
                <p style="margin-top: 15px; color: #8A7650; font-weight: 600;">Belum ada pesanan</p>
            </div>
        @endif
        </div>
    </div>
@endsection
