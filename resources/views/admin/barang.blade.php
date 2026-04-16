@extends('layouts.admin')

@section('title', 'Barang')

@section('content')
<section class="admin-section">
  <h2 style="font-size:2rem; font-weight:700; margin-bottom:20px; color:var(--accent); text-align:center; letter-spacing:2px; text-shadow:1px 2px 8px #e2e8f0;">Menu Barang <span style="font-size:1.2rem; color:#718096; font-weight:400;">Gudang Mebel</span></h2>
  <div class="barang-menu" style="display: flex; gap: 24px; justify-content:center; margin-bottom: 32px;">
    <button class="btn" onclick="showBarangMasuk()" style="display:flex;align-items:center;gap:8px;box-shadow:0 2px 8px #cbd5e1;transition:transform .2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'"><span style="font-size:1.3rem;">📦</span> Barang Masuk <span style="background:#38b2ac;color:white;border-radius:12px;padding:2px 10px;font-size:0.9rem;margin-left:6px;">2</span></button>
    <button class="btn" onclick="showBarangKeluar()" style="display:flex;align-items:center;gap:8px;box-shadow:0 2px 8px #cbd5e1;transition:transform .2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'"><span style="font-size:1.3rem;">🚚</span> Barang Keluar <span style="background:#ed8936;color:white;border-radius:12px;padding:2px 10px;font-size:0.9rem;margin-left:6px;">2</span></button>
    <button class="btn" onclick="showStokBarang()" style="display:flex;align-items:center;gap:8px;box-shadow:0 2px 8px #cbd5e1;transition:transform .2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'"><span style="font-size:1.3rem;">📊</span> Stok Barang <span style="background:#4299e1;color:white;border-radius:12px;padding:2px 10px;font-size:0.9rem;margin-left:6px;">2</span></button>
  </div>
  <div id="barang-content" style="max-width:800px;margin:0 auto;">
    <div id="barang-masuk" style="display: block;">
      <div class="card" style="background:linear-gradient(90deg,#f7fafc 80%,#38b2ac10 100%);border-radius:16px;box-shadow:0 4px 16px rgba(56,178,172,0.09);padding:36px 28px;margin-bottom:28px;animation:fadeIn .7s;">
        <h3 style="font-size:1.5rem;color:#2d3748;margin-bottom:10px;display:flex;align-items:center;gap:8px;"><span>📦</span> Barang Masuk</h3>
        <p style="color:#4a5568;">Daftar barang yang baru masuk ke gudang. Tambahkan data barang masuk di sini.</p>
        <div style="margin-top:18px;text-align:right;"><button class="btn" style="background:var(--accent);color:white;box-shadow:0 2px 8px #38b2ac;">+ Tambah Barang Masuk</button></div>
      </div>
      <table style="width:100%;border-collapse:collapse;background:white;border-radius:12px;box-shadow:0 2px 8px #38b2ac30;overflow:hidden;">
        <thead style="background:var(--accent);color:white;">
          <tr>
            <th style="padding:12px;">Tanggal</th>
            <th style="padding:12px;">Nama Barang</th>
            <th style="padding:12px;">Jumlah</th>
            <th style="padding:12px;">Supplier</th>
          </tr>
        </thead>
        <tbody>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#e6fffa'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">2024-06-12</td>
            <td style="padding:12px;">Meja Kayu</td>
            <td style="padding:12px;">10</td>
            <td style="padding:12px;">PT. Kayu Jati</td>
          </tr>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#e6fffa'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">2024-06-10</td>
            <td style="padding:12px;">Kursi Rotan</td>
            <td style="padding:12px;">15</td>
            <td style="padding:12px;">CV. Rotan Indah</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="barang-keluar" style="display: none;">
      <div class="card" style="background:linear-gradient(90deg,#f7fafc 80%,#ed893610 100%);border-radius:16px;box-shadow:0 4px 16px rgba(237,137,54,0.09);padding:36px 28px;margin-bottom:28px;animation:fadeIn .7s;">
        <h3 style="font-size:1.5rem;color:#2d3748;margin-bottom:10px;display:flex;align-items:center;gap:8px;"><span>🚚</span> Barang Keluar</h3>
        <p style="color:#4a5568;">Daftar barang yang keluar dari gudang. Catat barang keluar di sini.</p>
        <div style="margin-top:18px;text-align:right;"><button class="btn" style="background:#ed8936;color:white;box-shadow:0 2px 8px #ed8936;">+ Catat Barang Keluar</button></div>
      </div>
      <table style="width:100%;border-collapse:collapse;background:white;border-radius:12px;box-shadow:0 2px 8px #ed893630;overflow:hidden;">
        <thead style="background:#ed8936;color:white;">
          <tr>
            <th style="padding:12px;">Tanggal</th>
            <th style="padding:12px;">Nama Barang</th>
            <th style="padding:12px;">Jumlah</th>
            <th style="padding:12px;">Tujuan</th>
          </tr>
        </thead>
        <tbody>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#fff5eb'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">2024-06-13</td>
            <td style="padding:12px;">Meja Kayu</td>
            <td style="padding:12px;">2</td>
            <td style="padding:12px;">Pelanggan A</td>
          </tr>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#fff5eb'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">2024-06-11</td>
            <td style="padding:12px;">Kursi Rotan</td>
            <td style="padding:12px;">5</td>
            <td style="padding:12px;">Pelanggan B</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="stok-barang" style="display: none;">
      <div class="card" style="background:linear-gradient(90deg,#f7fafc 80%,#4299e110 100%);border-radius:16px;box-shadow:0 4px 16px rgba(66,153,225,0.09);padding:36px 28px;margin-bottom:28px;animation:fadeIn .7s;">
        <h3 style="font-size:1.5rem;color:#2d3748;margin-bottom:10px;display:flex;align-items:center;gap:8px;"><span>📊</span> Stok Barang</h3>
        <p style="color:#4a5568;">Informasi stok barang saat ini di gudang.</p>
      </div>
      <table style="width:100%;border-collapse:collapse;background:white;border-radius:12px;box-shadow:0 2px 8px #4299e130;overflow:hidden;">
        <thead style="background:#4299e1;color:white;">
          <tr>
            <th style="padding:12px;">Nama Barang</th>
            <th style="padding:12px;">Stok Tersedia</th>
            <th style="padding:12px;">Satuan</th>
          </tr>
        </thead>
        <tbody>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#ebf8ff'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">Meja Kayu</td>
            <td style="padding:12px;">8</td>
            <td style="padding:12px;">Unit</td>
          </tr>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#ebf8ff'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">Kursi Rotan</td>
            <td style="padding:12px;">10</td>
            <td style="padding:12px;">Unit</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
<script>
function showBarangMasuk() {
  document.getElementById('barang-masuk').style.display = 'block';
  document.getElementById('barang-keluar').style.display = 'none';
  document.getElementById('stok-barang').style.display = 'none';
}
function showBarangKeluar() {
  document.getElementById('barang-masuk').style.display = 'none';
  document.getElementById('barang-keluar').style.display = 'block';
  document.getElementById('stok-barang').style.display = 'none';
}
function showStokBarang() {
  document.getElementById('barang-masuk').style.display = 'none';
  document.getElementById('barang-keluar').style.display = 'none';
  document.getElementById('stok-barang').style.display = 'block';
}
</script>
@endsectionight;"><button class="btn" style="background:#ed8936;color:white;box-shadow:0 2px 8px #ed8936;">+ Catat Barang Keluar</button></div>
      </div>
      <table style="width:100%;border-collapse:collapse;background:white;border-radius:12px;box-shadow:0 2px 8px #ed893630;overflow:hidden;">
        <thead style="background:#ed8936;color:white;">
          <tr>
            <th style="padding:12px;">Tanggal</th>
            <th style="padding:12px;">Nama Barang</th>
            <th style="padding:12px;">Jumlah</th>
            <th style="padding:12px;">Tujuan</th>
          </tr>
        </thead>
        <tbody>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#fff5eb'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">2024-06-13</td>
            <td style="padding:12px;">Meja Kayu</td>
            <td style="padding:12px;">2</td>
            <td style="padding:12px;">Pelanggan A</td>
          </tr>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#fff5eb'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">2024-06-11</td>
            <td style="padding:12px;">Kursi Rotan</td>
            <td style="padding:12px;">5</td>
            <td style="padding:12px;">Pelanggan B</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="stok-barang" style="display: none;">
      <div class="card" style="background:linear-gradient(90deg,#f7fafc 80%,#4299e110 100%);border-radius:16px;box-shadow:0 4px 16px rgba(66,153,225,0.09);padding:36px 28px;margin-bottom:28px;animation:fadeIn .7s;">
        <h3 style="font-size:1.5rem;color:#2d3748;margin-bottom:10px;display:flex;align-items:center;gap:8px;"><span>📊</span> Stok Barang</h3>
        <p style="color:#4a5568;">Informasi stok barang saat ini di gudang.</p>
      </div>
      <table style="width:100%;border-collapse:collapse;background:white;border-radius:12px;box-shadow:0 2px 8px #4299e130;overflow:hidden;">
        <thead style="background:#4299e1;color:white;">
          <tr>
            <th style="padding:12px;">Nama Barang</th>
            <th style="padding:12px;">Stok Tersedia</th>
            <th style="padding:12px;">Satuan</th>
          </tr>
        </thead>
        <tbody>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#ebf8ff'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">Meja Kayu</td>
            <td style="padding:12px;">8</td>
            <td style="padding:12px;">Unit</td>
          </tr>
          <tr style="transition:background .2s;" onmouseover="this.style.background='#ebf8ff'" onmouseout="this.style.background='white'">
            <td style="padding:12px;">Kursi Rotan</td>
            <td style="padding:12px;">10</td>
            <td style="padding:12px;">Unit</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
<script>
function showBarangMasuk() {
  document.getElementById('barang-masuk').style.display = 'block';
  document.getElementById('barang-keluar').style.display = 'none';
  document.getElementById('stok-barang').style.display = 'none';
}
function showBarangKeluar() {
  document.getElementById('barang-masuk').style.display = 'none';
  document.getElementById('barang-keluar').style.display = 'block';
  document.getElementById('stok-barang').style.display = 'none';
}
function showStokBarang() {
  document.getElementById('barang-masuk').style.display = 'none';
  document.getElementById('barang-keluar').style.display = 'none';
  document.getElementById('stok-barang').style.display = 'block';
}
</script>
@endsection
