@extends('layouts.admin')

@section('page_title', 'Detail Pelanggan')
@section('breadcrumb', 'Detail Pelanggan')

@section('content')
    <style>
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
        }

        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1rem;
            color: #333;
        }

        .edit-form {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .orders-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .back-btn {
            margin-bottom: 20px;
            display: inline-block;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>

    <a href="{{ route('admin.customers.index') }}" class="back-btn" style="color: #007bff; text-decoration: none;">← Kembali ke
        Daftar Pelanggan</a>

    @if (session('success'))
        <div style="padding: 12px 20px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Pelanggan</h3>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama</div>
                        <div class="info-value">{{ $customer->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $customer->email }}</div>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Bergabung</div>
                        <div class="info-value">{{ $customer->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total Pesanan</div>
                        <div class="info-value">{{ $customer->orders->count() }} pesanan</div>
                    </div>
                </div>
            </div>

            <div class="edit-form">
                <h4 style="margin-top: 0; margin-bottom: 15px;">Edit Data</h4>
                <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nama *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}"
                            required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $customer->email) }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $customer->address ?? '') }}">
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($orders->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pesanan</h3>
            </div>
            <div class="card-body">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($order->status->value) }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($order->payment_status->value) }}">
                                        {{ $order->payment_status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 40px; color: #999;">
                Pelanggan ini belum memiliki pesanan
            </div>
        </div>
    @endif

    <div style="margin-top: 20px; padding: 20px; background: #fff3cd; border-radius: 4px; color: #856404;">
        <strong>⚠️ Zona Berbahaya:</strong>
        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display: inline;"
            onsubmit="return confirm('Yakin hapus pelanggan ini? Semua data akan terhapus!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" style="margin-top: 10px;">Hapus Pelanggan</button>
        </form>
    </div>
@endsection
