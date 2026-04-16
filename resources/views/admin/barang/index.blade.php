@extends('layouts.admin')

@section('page_title', 'Manajemen Barang')
@section('breadcrumb', 'Barang')

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

        .btn-add {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-add:hover {
            background: #218838;
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

        .product-img {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            object-fit: cover;
            border: 1px solid #ddd;
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

        .btn-edit {
            background: #ffc107;
            color: #333;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>

    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Daftar Produk</h3>
            <a href="{{ route('admin.barang.create') }}" class="btn-add">+ Tambah Produk</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($products->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-img">
                                @else
                                    <div
                                        style="width: 60px; height: 60px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.85rem;">
                                        Tanpa Gambar</div>
                                @endif
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }} pcs</td>
                            <td>
                                <a href="{{ route('admin.barang.edit', $product->id) }}"
                                    class="action-btn btn-edit">Edit</a>
                                <form action="{{ route('admin.barang.destroy', $product->id) }}" method="POST"
                                    style="display: inline;" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: #999;">
                Belum ada produk
            </div>
        @endif
    </div>
@endsection
