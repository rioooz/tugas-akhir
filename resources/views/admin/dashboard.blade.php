@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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
    background: var(--accent);
    color: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(230, 126, 34, 0.2);
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
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
  }
  
  .stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
    color:var(accent);
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
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    color:var(accent);
  }
  
  .menu-card p {
    color: #666;
    margin-bottom: 20px;
    flex-grow: 1;
  }
  
  .menu-card .btn {
    background:var(accent);
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    text-align: center;
    transition: background 0.3s;
    font-weight: 500;
    margin-top: auto;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .menu-card .btn:hover {
    background: #d35400;
  }
  
  .btn-icon {
    margin-left: 8px;
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
    <a href="{{ route('home') }}" class="btn">Kembali ke beranda</a>
  </div>
  
  <div class="welcome-card">
    <h3>Selamat datang, {{ Auth::user()->name ?? 'Pelanggan' }}!</h3>
    <p>Kelola pesanan dan akun Anda dengan mudah melalui dashboard ini.</p>
  </div>
  
  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon">📦</div>
      <div class="stat-value">3</div>
      <div class="stat-label">Total Pesanan</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">🚚</div>
      <div class="stat-value">1</div>
      <div class="stat-label">Dalam Pengiriman</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">❤️</div>
      <div class="stat-value">5</div>
      <div class="stat-label">Wishlist</div>
    </div>
  </div>
  
  <div class="dashboard-menu">
    <div class="menu-card">
      <h3><span class="menu-icon">📋</span> Pesanan Saya</h3>
      <p>Lihat status pesanan dan riwayat pembelian Anda</p>
      <a class="btn" href="{{ route('riwayat') }}">Lihat Pesanan <span class="btn-icon">→</span></a>
    </div>
    <div class="menu-card">
      <h3><span class="menu-icon">👤</span> Profil Saya</h3>
      <p>Kelola informasi akun dan alamat pengiriman</p>
      <a class="btn" href="{{ route('profile') }}">Edit Profil <span class="btn-icon">→</span></a>
    </div>
    <div class="menu-card">
      <h3><span class="menu-icon">❤️</span> Wishlist</h3>
      <p>Produk yang Anda simpan untuk dibeli nanti</p>
      <a class="btn" href="{{ route('wishlist') }}">Lihat Wishlist <span class="btn-icon">→</span></a>
    </div>
  </div>
</section>
@endsection