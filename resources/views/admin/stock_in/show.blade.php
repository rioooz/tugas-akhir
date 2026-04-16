@extends('layouts.admin')

@section('page_title', 'Detail Barang Masuk')
@section('breadcrumb', 'Detail Barang Masuk')

@section('content')
    <style>
        .card {
            border: 1px solid #c8e6c9;
            box-shadow: 0 2px 8px rgba(14, 143, 44, 0.1);
        }

        .detail-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #e8f5e9;
        }

        .detail-label {
            font-weight: 600;
            color: #2e7d32;
            min-width: 200px;
        }

        .detail-value {
            color: #333;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header"
                        style="background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%); color: #1b5e20;">
                        <h4 class="mb-0">Detail Barang Masuk</h4>
                    </div>
                    <div class="card-body">
                        <div class="detail-row">
                            <div class="detail-label">Barang</div>
                            <div class="detail-value">
                                <strong>{{ $stockIn->productItem->name }}</strong>
                                <br>
                                <small style="color: #999;">SKU: {{ $stockIn->productItem->kode_produk }}</small>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Jumlah Masuk</div>
                            <div class="detail-value">
                                <span class="badge"
                                    style="background: #66bb6a; color: white; font-size: 1em; padding: 8px 12px;">
                                    {{ $stockIn->quantity }} {{ $stockIn->productItem->satuan }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Stok Saat Ini</div>
                            <div class="detail-value">{{ $stockIn->productItem->stock }} {{ $stockIn->productItem->satuan }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Nomor Referensi</div>
                            <div class="detail-value">{{ $stockIn->reference ?? '-' }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                @if ($stockIn->status === 'received')
                                    <span class="badge" style="background: #c8e6c9; color: #2e7d32;">Diterima</span>
                                @else
                                    <span class="badge" style="background: #a5d6a7; color: #1b5e20;">Diverifikasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Dicatat oleh</div>
                            <div class="detail-value">{{ $stockIn->user->name ?? 'Unknown' }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Tanggal Masuk</div>
                            <div class="detail-value">{{ $stockIn->created_at->format('d F Y H:i:s') }}</div>
                        </div>

                        @if ($stockIn->notes)
                            <div class="detail-row">
                                <div class="detail-label">Catatan</div>
                                <div class="detail-value">
                                    <p>{{ $stockIn->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <div style="padding-top: 20px; border-top: 2px solid #c8e6c9;">
                            <a href="{{ route('admin.stock-in.edit', $stockIn->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.stock-in.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
