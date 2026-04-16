@extends('layouts.admin')

@section('page_title', 'Edit Barang Masuk')
@section('breadcrumb', 'Edit Barang Masuk')

@section('content')
    <style>
        .card {
            border: 1px solid #c8e6c9;
            box-shadow: 0 2px 8px rgba(14, 143, 44, 0.1);
        }

        .form-label {
            color: #2e7d32;
            font-weight: 600;
        }

        .btn-submit {
            background: #66bb6a;
            border-color: #66bb6a;
            color: white;
        }

        .btn-submit:hover {
            background: #4caf50;
            border-color: #4caf50;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header"
                        style="background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%); color: #1b5e20;">
                        <h4 class="mb-0">Edit Barang Masuk</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.stock-in.update', $stockIn->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label for="product_item_id" class="form-label">Pilih Barang <span
                                        style="color: #e43522;">*</span></label>
                                <select name="product_item_id" id="product_item_id"
                                    class="form-control @error('product_item_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $stockIn->product_item_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stok: {{ $product->stock }} {{ $product->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="quantity" class="form-label">Jumlah Masuk <span
                                        style="color: #e43522;">*</span></label>
                                <input type="number" name="quantity" id="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ $stockIn->quantity }}" placeholder="Masukkan jumlah" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="reference" class="form-label">Nomor Referensi / PO</label>
                                <input type="text" name="reference" id="reference"
                                    class="form-control @error('reference') is-invalid @enderror"
                                    value="{{ $stockIn->reference }}" placeholder="Contoh: PO-2025-001">
                                @error('reference')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4"
                                    placeholder="Masukkan catatan (opsional)">{{ $stockIn->notes }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Status</label>
                                <p style="color: #2e7d32; font-weight: 600;">
                                    @if ($stockIn->status === 'received')
                                        <span class="badge" style="background: #c8e6c9; color: #2e7d32;">Diterima</span>
                                    @else
                                        <span class="badge"
                                            style="background: #a5d6a7; color: #1b5e20;">Diverifikasi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Dicatat oleh</label>
                                <p>{{ $stockIn->user->name ?? 'Unknown' }}</p>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.stock-in.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
