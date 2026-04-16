@extends('layouts.admin')

@section('page_title', 'Profil Admin')
@section('breadcrumb', 'Profil')

@section('content')
    <style>
        body {
            background: #f0f8f4 !important;
        }

        .profile-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(14, 143, 44, 0.1);
            overflow: hidden;
            border: 1px solid #d4f1d4;
        }

        .profile-header {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border: 4px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 60px;
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-email {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .profile-body {
            padding: 30px;
            background: #fafcfb;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #d4f1d4;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #2e7d32;
        }

        .info-value {
            color: #558b2f;
        }

        .btn-edit {
            background: #66bb6a;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit:hover {
            background: #4caf50;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .data-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(14, 143, 44, 0.1);
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid #d4f1d4;
        }

        .data-header {
            padding: 20px;
            border-bottom: 1px solid #d4f1d4;
            background: linear-gradient(135deg, #f1f8f6 0%, #e8f5e9 100%);
        }

        .data-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2e7d32;
        }

        .data-body {
            padding: 20px;
            background: #fafcfb;
        }

        .notification-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #e8f5e9;
            align-items: flex-start;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: #c8e6c9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #2e7d32;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-time {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 5px;
        }

        .notification-text {
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 5px;
        }

        .notification-amount {
            font-size: 0.9rem;
            color: #558b2f;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #2e7d32;
            font-size: 0.85rem;
            text-transform: uppercase;
            background: #f1f8f6;
            border-bottom: 2px solid #c8e6c9;
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #e8f5e9;
        }

        .data-table tbody tr:hover {
            background: #f1f8f6;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #7cb342;
        }
    </style>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">👨‍💼</div>
                    <div class="profile-name">{{ $admin->name }}</div>
                    <div class="profile-email">{{ $admin->email }}</div>
                </div>
                <div class="profile-body">
                    <div class="info-row">
                        <span class="info-label">Role:</span>
                        <span class="info-value badge badge-success">{{ ucfirst($admin->role) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value badge badge-info">Aktif</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bergabung:</span>
                        <span class="info-value">{{ $admin->created_at->format('d M Y') }}</span>
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="#" class="btn-edit">Edit Profil</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Panel -->
        <div class="col-lg-8">
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">📬 Notifikasi Pesanan Terbaru</h3>
                </div>
                <div class="data-body">
                    @forelse ($notifications as $order)
                        <div class="notification-item">
                            <div class="notification-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                <div class="notification-text">Pesanan baru dari {{ $order->user->name }}</div>
                                <div class="notification-amount">Total: Rp {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-message">Tidak ada notifikasi baru</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Purchase History -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="data-card">
                <div class="data-header">
                    <h3 class="data-title">📊 Riwayat Transaksi Pelanggan Terbaru</h3>
                </div>
                <div class="data-body">
                    @if ($customers->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Pelanggan</th>
                                        <th>Email</th>
                                        <th>Total Pesanan</th>
                                        <th>Total Belanja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td><strong>{{ $customer->name }}</strong></td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->orders->count() }} pesanan</td>
                                            <td>Rp {{ number_format($customer->orders->sum('total'), 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-message">Belum ada riwayat transaksi pelanggan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
