@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('extra_css')
    <style>
        #dashboard-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .dashboard-header h2 {
            font-size: 1.8rem;
            color: #333;
            margin: 0;
        }

        .welcome-card {
            background: linear-gradient(135deg, #0e8f2c 0%, #0a6b22 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(14, 143, 44, 0.2);
        }

        .welcome-card h3 {
            margin-top: 0;
            font-size: 1.4rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #0e8f2c;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin: 5px 0;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .dashboard-menu {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .menu-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
        }

        .menu-card:hover {
            transform: translateY(-5px);
        }

        .menu-card h3 {
            color: #333;
            margin-top: 0;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
        }

        .menu-icon {
            margin-right: 10px;
            color: #0e8f2c;
        }

        .menu-card p {
            color: #666;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .menu-card .btn {
            background: #0e8f2c;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            transition: background 0.3s;
            font-weight: 500;
            margin-top: auto;
        }

        .menu-card .btn:hover {
            background: #0a6b22;
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <section id="dashboard-section">
        <div class="dashboard-header">
            <h2>Dashboard Pelanggan</h2>
            <a href="{{ route('home') }}" class="btn"
                style="background: #0e8f2c; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Kembali
                ke beranda</a>
        </div>

        <div class="welcome-card">
            <h3>Selamat datang, Pelanggan!</h3>
            <p>Kelola pesanan dan akun Anda dengan mudah melalui dashboard ini.</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-value">{{ $dalamProses ?? 0 }}</div>
                <div class="stat-label">Dalam Proses</div>
            </div>
                <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value">{{ $belumBayar ?? 0 }}</div>
                <div class="stat-label">Belum Bayar</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🚚</div>
                <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                <div class="stat-label">Riwayat Pesanan</div>
            </div>
        </div>

    </section>
@endsection
