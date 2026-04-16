@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profil Admin</h1>

    <div class="row">
        <div class="col-lg-4">
            <!-- Admin Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Admin</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Role:</strong> {{ auth()->user()->role }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-success">Aktif</span></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Notifications Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notifikasi Terbaru</h6>
                </div>
                <div class="card-body">
                    <p>Belum ada notifikasi.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer List Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pelanggan & Riwayat Transaksi</h6>
        </div>
        <div class="card-body">
            <p>Data pelanggan akan ditampilkan di sini.</p>
        </div>
    </div>
</div>
@endsection
