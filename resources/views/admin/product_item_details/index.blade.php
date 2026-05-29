@extends('layouts.admin')

@section('page_title', 'Detail Produk')
@section('breadcrumb', 'Barang / Detail')

@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(138, 118, 80, 0.05);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #DBCEA5;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 24px;
            background: linear-gradient(135deg, #8A7650 0%, #6E5034 100%);
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(138, 118, 80, 0.2);
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(138, 118, 80, 0.3);
            color: white;
        }

        th {
            background: #ECE7D1;
            color: #8A7650;
            padding: 15px;
            text-align: left;
            font-weight: 700;
            border-bottom: 2px solid #DBCEA5;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        tr:hover {
            background: #fcfbf9;
        }

        .variant-stock { background: #8E977D; color: white; padding: 6px 14px; border-radius: 20px; display: inline-flex; align-items: center; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.5px; }
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s; }
        
        .btn-delete { background: #f8d7da; color: #dc3545; }
        .btn-delete:hover { background: #dc3545; color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(220,53,69,0.2); }
        .empty-note { padding:40px; text-align:center; color:#999 }
    </style>

    <a href="{{ route('admin.barang.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Stock Barang
    </a>

    <div class="data-card" style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15); overflow: hidden; margin: 0 auto; border: 1px solid rgba(219, 206, 165, 0.5);">
        <div class="data-header" style="background: #8A7650; color: #ffffff; padding: 25px 35px; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="data-title" style="margin: 0; font-size: 1.4rem; font-weight: 700; color: #ffffff;"><i class="fas fa-boxes"></i> Detail Varian: {{ $barang->name }}</h3>
        </div>

        <div style="padding: 35px;">

        @if (session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif

        @if ($details->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nama Varian</th>
                        <th>Ukuran</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $d)
                        <tr>
                            <td><strong>{{ $d->name ?? '—' }}</strong></td>
                            <td><i class="fas fa-ruler-combined" style="color:#8A7650"></i> {{ $d->size ?? '-' }}</td>
                            <td><strong>Rp {{ number_format($d->price,0,',','.') }}</strong></td>
                            <td><span class="variant-stock"><i class="fas fa-cubes"></i> &nbsp;{{ $d->stock }} pcs</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:12px">{{ $details->links() }}</div>
        @else
            <div class="empty-note">Belum ada detail untuk produk ini.</div>
        @endif
        </div>
    </div>
@endsection
