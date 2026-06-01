@extends('layouts.admin')

@section('page_title', 'Detail Pelanggan')
@section('breadcrumb', 'Detail Pelanggan')

@section('content')
    <style>
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15);
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid rgba(219, 206, 165, 0.5);
        }

        .card-header {
            padding: 20px 35px;
            background: #8A7650;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #DBCEA5;
        }

        .card-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
        }

        .card-body {
            padding: 30px 35px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .info-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #8A7650;
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
            border-top: 1px solid #DBCEA5;
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
            border: 1px solid #DBCEA5;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #8A7650;
            box-shadow: 0 0 0 3px rgba(138, 118, 80, 0.2);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary {
            background: #8A7650;
            color: white;
        }

        .btn-primary:hover {
            background: #6E5034;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(138, 118, 80, 0.2);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }

        .btn-secondary {
            background: #8E977D;
            color: white;
        }

        .btn-secondary:hover {
            background: #737c63;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(142, 151, 125, 0.2);
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 700;
            color: #8A7650;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #ECE7D1;
            border-bottom: 2px solid #DBCEA5;
        }

        .orders-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            color: #333;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .back-btn:hover {
            transform: translateX(-4px);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>

    <a href="{{ route('admin.customers.index') }}" class="back-btn" style="color: #8A7650; text-decoration: none;">← Kembali ke
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
                <h4 style="margin-top: 0; margin-bottom: 20px; color: #8A7650; font-weight: 700;">Edit Data</h4>
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

                    <div style="display: flex; gap: 10px; margin-top: 25px;">
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
            <div class="card-body" style="padding: 0;">
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

    <div style="margin-top: 20px; padding: 25px; background: #fdf8e2; border: 1px solid #DBCEA5; border-radius: 12px; color: #856404;">
        <strong style="font-size: 1.1rem;"><i class="fas fa-exclamation-triangle"></i> Zona Berbahaya</strong>
        <p style="margin: 8px 0 15px 0; color: #666; font-size: 0.95rem;">Setelah Anda menghapus pelanggan ini, data pelanggan beserta riwayat pesanannya tidak dapat dikembalikan.</p>
        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display: inline;"
            onsubmit="return confirm('Yakin hapus pelanggan ini? Semua data akan terhapus!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus Pelanggan</button>
        </form>
    </div>
@endsection
