@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<section id="pelanggan-section">
  <h2>Daftar Pelanggan</h2>
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Telepon</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Budi Santoso</td>
          <td>budi@example.com</td>
          <td>081234567890</td>
          <td>Jl. Merdeka No. 123, Jakarta</td>
          <td>
            <button class="btn-small">Edit</button>
            <button class="btn-small btn-danger">Hapus</button>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>Siti Rahayu</td>
          <td>siti@example.com</td>
          <td>087654321098</td>
          <td>Jl. Pahlawan No. 45, Bandung</td>
          <td>
            <button class="btn-small">Edit</button>
            <button class="btn-small btn-danger">Hapus</button>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>Ahmad Hidayat</td>
          <td>ahmad@example.com</td>
          <td>089876543210</td>
          <td>Jl. Sudirman No. 78, Surabaya</td>
          <td>
            <button class="btn-small">Edit</button>
            <button class="btn-small btn-danger">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>
@endsection