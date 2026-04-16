@extends('layouts.admin')
@section('title','Transaksi')

@section('content')
<section style="padding:24px">

  <h2 style="font-size:2rem;font-weight:700;text-align:center;margin-bottom:20px">
    Manajemen Transaksi
  </h2>

  <!-- Tabs -->
  <div style="display:flex;gap:12px;justify-content:center;margin-bottom:16px">
    <button id="tab-sales"
      onclick="showTab('sales', this)"
      style="padding:10px 14px;border-radius:8px;border:1px solid #ddd;background:#2b6cb0;color:#fff;font-weight:600">
      🛒 Penjualan
    </button>

    <button id="tab-purchases"
      onclick="showTab('purchases', this)"
      style="padding:10px 14px;border-radius:8px;border:1px solid #ddd;background:#fff;font-weight:600">
      📦 Pembelian Bahan Mentah
    </button>
  </div>

  <!-- Filters -->
  <form method="GET"
    style="display:flex;gap:10px;justify-content:center;margin-bottom:16px">
    <input type="date" name="from" value="{{ request('from') }}"
      style="padding:8px;border:1px solid #ccc;border-radius:6px">
    <input type="date" name="to" value="{{ request('to') }}"
      style="padding:8px;border:1px solid #ccc;border-radius:6px">
    <select name="status"
      style="padding:8px;border:1px solid #ccc;border-radius:6px">
      <option value="">Semua Status</option>
      <option value="pending">Pending</option>
      <option value="completed">Selesai</option>
      <option value="canceled">Dibatalkan</option>
    </select>
    <button
      style="padding:8px 14px;border:none;border-radius:6px;background:#2b6cb0;color:white;font-weight:600">
      Filter
    </button>
  </form>

  <!-- ================= PENJUALAN ================= -->
  <div id="sales">
    <table style="width:100%;border-collapse:collapse;background:#fff">
      <thead>
        <tr style="background:#f1f5f9">
          <th style="padding:10px;border-bottom:1px solid #eee">Tanggal</th>
          <th style="padding:10px;border-bottom:1px solid #eee">No. Transaksi</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Pelanggan</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Item</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Jumlah</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Total</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Status</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sales as $trx)
        <tr>
          <td style="padding:10px">{{ $trx->date }}</td>
          <td style="padding:10px">{{ $trx->code }}</td>
          <td style="padding:10px">{{ $trx->partner }}</td>
          <td style="padding:10px">{{ $trx->items }}</td>
          <td style="padding:10px">{{ $trx->qty }}</td>
          <td style="padding:10px">Rp{{ number_format($trx->total) }}</td>
          <td style="padding:10px">
            <span style="
              padding:4px 8px;
              border-radius:6px;
              font-size:0.85rem;
              background:
              {{ $trx->status=='completed' ? '#d4edda' : ($trx->status=='pending' ? '#fff3cd' : '#f8d7da') }};
              color:
              {{ $trx->status=='completed' ? '#155724' : ($trx->status=='pending' ? '#856404' : '#721c24') }};
            ">
              {{ ucfirst($trx->status) }}
            </span>
          </td>
          <td style="padding:10px">
            <a href="{{ route('admin.transactions.show',$trx->id) }}"
              style="padding:6px 10px;background:#2b6cb0;color:white;border-radius:6px;text-decoration:none">
              Detail
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- ================= PEMBELIAN ================= -->
  <div id="purchases" style="display:none">
    <table style="width:100%;border-collapse:collapse;background:#fff">
      <thead>
        <tr style="background:#f1f5f9">
          <th style="padding:10px;border-bottom:1px solid #eee">Tanggal</th>
          <th style="padding:10px;border-bottom:1px solid #eee">No. Transaksi</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Supplier</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Bahan</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Jumlah</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Total</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Status</th>
          <th style="padding:10px;border-bottom:1px solid #eee">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($purchases as $trx)
        <tr>
          <td style="padding:10px">{{ $trx->date }}</td>
          <td style="padding:10px">{{ $trx->code }}</td>
          <td style="padding:10px">{{ $trx->partner }}</td>
          <td style="padding:10px">{{ $trx->items }}</td>
          <td style="padding:10px">{{ $trx->qty }}</td>
          <td style="padding:10px">Rp{{ number_format($trx->total) }}</td>
          <td style="padding:10px">
            <span style="
              padding:4px 8px;
              border-radius:6px;
              font-size:0.85rem;
              background:#fff3cd;
              color:#856404">
              {{ ucfirst($trx->status) }}
            </span>
          </td>
          <td style="padding:10px">
            <a href="{{ route('admin.transactions.show',$trx->id) }}"
              style="padding:6px 10px;background:#2b6cb0;color:white;border-radius:6px;text-decoration:none">
              Detail
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</section>

<script>
function showTab(tab, btn){
  document.getElementById('sales').style.display = 'none';
  document.getElementById('purchases').style.display = 'none';

  document.getElementById('tab-sales').style.background = '#fff';
  document.getElementById('tab-sales').style.color = '#000';
  document.getElementById('tab-purchases').style.background = '#fff';
  document.getElementById('tab-purchases').style.color = '#000';

  document.getElementById(tab).style.display = 'block';
  btn.style.background = '#2b6cb0';
  btn.style.color = '#fff';
}
</script>
@endsection
