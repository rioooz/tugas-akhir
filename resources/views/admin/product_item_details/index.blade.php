@extends('layouts.admin')

@section('page_title', 'Detail Produk')
@section('breadcrumb', 'Barang / Detail')

@section('content')
    <style>
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .variant-card {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .variant-meta { color: #555; font-size: 0.95rem; margin-bottom: 8px; }
        .variant-name { font-weight: 700; color: #222; margin-bottom: 6px; }
        .variant-price { color: #0e8f2c; font-weight: 800; font-size: 1.05rem; }
        .variant-stock { margin-top: 8px; font-size: 0.95rem; }

        .variant-actions { text-align: right; margin-top: 12px; }
        .action-btn { display: inline-block; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-weight:600; margin-left:6px }
        .btn-edit { background:#ffc107; color:#222 }
        .btn-delete { background:#dc3545; color:#fff }
        .btn-add { background:#28a745; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none }
        .empty-note { padding:40px; text-align:center; color:#999 }
    </style>

    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Detail untuk: {{ $barang->name }}</h3>
            <a href="{{ route('admin.barang.details.create', $barang->id) }}" class="btn-add">+ Tambah Detail</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif

        @if ($details->count() > 0)
            <div class="detail-grid">
                @foreach ($details as $d)
                    <div class="variant-card">
                        <div>
                            <div class="variant-name">{{ $d->name ?? '—' }}</div>
                            <div class="variant-meta">Ukuran: {{ $d->size ?? '-' }}</div>
                            <div class="variant-price">Rp {{ number_format($d->price,0,',','.') }}</div>
                            <div class="variant-stock">Stok: <strong>{{ $d->stock }}</strong> pcs</div>
                        </div>

                        <div class="variant-actions">
                            <a href="{{ route('admin.barang.details.edit', ['barang' => $barang->id, 'detail' => $d->id]) }}" class="action-btn btn-edit">Edit</a>
                            <form action="{{ route('admin.barang.details.destroy', ['barang' => $barang->id, 'detail' => $d->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus detail ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn btn-delete">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:12px">{{ $details->links() }}</div>
        @else
            <div class="empty-note">Belum ada detail untuk produk ini.</div>
        @endif
    </div>
@endsection
