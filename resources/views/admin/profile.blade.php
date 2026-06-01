@extends('layouts.admin')

@section('page_title', 'Profil Admin')
@section('breadcrumb', 'Profil')

@section('content')
    <style>
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15);
            overflow: hidden;
            border: 1px solid rgba(219, 206, 165, 0.5);
        }

        .profile-header {
            background: linear-gradient(135deg, #8A7650 0%, #6E5034 100%);
            padding: 35px 30px;
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
            background: white;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #DBCEA5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 700;
            color: #8A7650;
        }

        .info-value {
            color: #333;
        }

        .badge-custom-role {
            background-color: #8E977D !important;
            color: white !important;
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-custom-status {
            background-color: #8A7650 !important;
            color: white !important;
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-edit {
            background: #8E977D;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit:hover {
            background: #737c63;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(142, 151, 125, 0.2);
        }

        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15);
            overflow: hidden;
            border: 1px solid rgba(219, 206, 165, 0.5);
            margin-bottom: 25px;
        }

        .data-header {
            padding: 20px 30px;
            border-bottom: 1px solid #DBCEA5;
            background: #8A7650;
        }

        .data-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
        }

        .data-body {
            padding: 25px 30px;
            background: white;
        }

        .notification-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            align-items: flex-start;
        }

        .notification-item:first-child {
            padding-top: 0;
        }

        .notification-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: #ECE7D1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #8A7650;
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
            color: #8A7650;
            margin-bottom: 5px;
        }

        .notification-amount {
            font-size: 0.9rem;
            color: #333;
            font-weight: 700;
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

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #8E977D;
            font-weight: 600;
        }

        .form-control:focus {
            border-color: #8A7650 !important;
            box-shadow: 0 0 0 0.2rem rgba(138, 118, 80, 0.25) !important;
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
                        <span class="info-value badge-custom-role">{{ ucfirst($admin->role) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value badge-custom-status">Aktif</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bergabung:</span>
                        <span class="info-value">{{ $admin->created_at->format('d M Y') }}</span>
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <button type="button" class="btn-edit" data-toggle="modal" data-target="#editProfileModal">Edit Profil</button>
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

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 1px solid #DBCEA5; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background-color: #8A7650; color: white;">
                    <h5 class="modal-title" id="editProfileModalLabel" style="font-weight: 700;">✏️ Edit Profil Admin</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                        <span aria-hidden="true" style="color: white;">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="padding: 25px; background-color: #fcfbf9;">
                        <div class="form-group mb-3">
                            <label for="name" style="font-weight: 700; color: #8A7650;">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required
                                   style="border-radius: 6px; border: 1px solid #DBCEA5; background-color: white;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" style="font-weight: 700; color: #8A7650;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required
                                   style="border-radius: 6px; border: 1px solid #DBCEA5; background-color: white;">
                        </div>
                        <hr style="border-top: 1px solid #DBCEA5; margin: 20px 0;">
                        <div class="form-group mb-3">
                            <label for="password" style="font-weight: 700; color: #8A7650;">Password Baru (Opsional)</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                                   style="border-radius: 6px; border: 1px solid #DBCEA5; background-color: white;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation" style="font-weight: 700; color: #8A7650;">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                                   style="border-radius: 6px; border: 1px solid #DBCEA5; background-color: white;">
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #ECE7D1; border-top: 1px solid #DBCEA5; padding: 15px 25px;">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal" 
                                style="background-color: #8E977D; border: none; font-weight: 600; padding: 8px 20px; border-radius: 6px;">Batal</button>
                        <button type="submit" class="btn btn-primary" 
                                style="background-color: #8A7650; border: none; font-weight: 600; padding: 8px 20px; border-radius: 6px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
