@extends('layouts.admin')

@section('page_title', 'Barang Masuk')
@section('breadcrumb', 'Barang Masuk')

@section('content')
    <style>
        .card {
            border: 1px solid #DBCEA5;
            box-shadow: 0 2px 8px rgba(138, 118, 80, 0.1);
        }

        .btn-primary {
            background: #8A7650;
            border-color: #8A7650;
        }

        .btn-primary:hover {
            background: #736140;
            border-color: #736140;
        }

        .table-hover tbody tr:hover {
            background-color: #ECE7D1;
        }

        .badge-received {
            background: #DBCEA5;
            color: #8A7650;
        }

        .badge-verified {
            background: #8E977D;
            color: #fff;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(135deg, #DBCEA5 0%, #ECE7D1 100%);">
                        <h3 class="mb-0" style="color: #8A7650;">Daftar Barang Masuk</h3>
                        <a href="{{ route('admin.stock-in.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Barang Masuk
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($stockIns->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead style="background: #DBCEA5;">
                                        <tr>
                                            <th style="color: #8A7650;">No</th>
                                            <th style="color: #8A7650;">Barang</th>
                                            <th style="color: #8A7650;">Jumlah</th>
                                            <th style="color: #8A7650;">Status</th>
                                            <th style="color: #8A7650;">Pencatat</th>
                                            <th style="color: #8A7650;">Tanggal</th>
                                            <th style="color: #8A7650;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stockIns as $key => $stockIn)
                                            <tr>
                                                <td>{{ $stockIns->firstItem() + $key }}</td>
                                                <td>
                                                    <strong>{{ $stockIn->productItem->name }}</strong>
                                                    @if($stockIn->productItemDetail)
                                                        <br><small class="text-muted">Varian: {{ $stockIn->productItemDetail->name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge" style="background: #8E977D; color: white;">
                                                        {{ $stockIn->quantity }} {{ $stockIn->productItem->satuan }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($stockIn->status === 'received')
                                                        <span class="badge badge-received">Diterima</span>
                                                    @else
                                                        <span class="badge badge-verified">Diverifikasi</span>
                                                    @endif
                                                </td>
                                                <td>{{ $stockIn->user->name ?? 'Unknown' }}</td>
                                                <td>{{ $stockIn->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.stock-in.show', $stockIn->id) }}"
                                                            class="btn btn-sm btn-info" title="Lihat">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.stock-in.edit', $stockIn->id) }}"
                                                            class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.stock-in.destroy', $stockIn->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Hapus" onclick="return confirm('Yakin hapus?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $stockIns->links() }}
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-inbox"></i> Belum ada data barang masuk
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
