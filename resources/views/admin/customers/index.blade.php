@extends('layouts.admin')

@section('page_title', 'Daftar Pelanggan')
@section('breadcrumb', 'Pelanggan')

@section('content')
    <style>
        .data-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
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

        .search-box {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
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
            justify-content: center;
            padding: 20px;
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

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>

    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Daftar Pelanggan</h3>
            <input type="text" class="search-box" placeholder="Cari pelanggan...">
        </div>

        @if (session('success'))
            <div style="padding: 12px 20px; background: #d4edda; color: #155724; border-bottom: 1px solid #c3e6cb;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($customers->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Total Pesanan</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->name }}</strong></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ Str::limit($customer->address ?? '-', 30) }}</td>
                            <td>{{ $customer->orders->count() }} pesanan</td>
                            <td>{{ $customer->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}"
                                    class="action-btn btn-view">Lihat</a>
                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
                                    style="display: inline;" onsubmit="return confirm('Yakin hapus pelanggan ini?')">
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
                {{ $customers->links() }}
            </div>
        @else
            <div class="empty-message">Belum ada pelanggan</div>
        @endif
    </div>
@endsection
