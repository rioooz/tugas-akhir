@extends('layouts.admin')

@section('page_title', 'Daftar Pelanggan')
@section('breadcrumb', 'Pelanggan')

@section('content')
    <style>
        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15);
            overflow: hidden;
            border: 1px solid rgba(219, 206, 165, 0.5);
        }

        .data-header {
            padding: 25px 35px;
            background: #8A7650;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data-title {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
        }

        .search-box {
            padding: 10px 15px;
            border: 1px solid #DBCEA5;
            border-radius: 6px;
            width: 250px;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: #8A7650;
            box-shadow: 0 0 0 3px rgba(138, 118, 80, 0.2);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 700;
            color: #8A7650;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #ECE7D1;
            border-bottom: 2px solid #DBCEA5;
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .data-table tbody tr:hover {
            background: #fcfbf9;
        }

        .action-btn {
            display: inline-block;
            padding: 8px 14px;
            margin-right: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
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
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
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

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>

    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title"><i class="fas fa-users"></i> Daftar Pelanggan</h3>
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
