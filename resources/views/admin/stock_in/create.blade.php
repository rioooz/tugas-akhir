@extends('layouts.admin')

@section('page_title', 'Tambah Barang Masuk')
@section('breadcrumb', 'Tambah Barang Masuk')

@section('content')
    <style>
        .card {
            border: 1px solid #DBCEA5;
            box-shadow: 0 2px 8px rgba(138, 118, 80, 0.1);
        }

        .form-label {
            color: #8A7650;
            font-weight: 600;
        }

        .btn-submit {
            background: #8A7650;
            border-color: #8A7650;
            color: white;
        }

        .btn-submit:hover {
            background: #736140;
            border-color: #736140;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header"
                        style="background: #8A7650; color: #ffffff; padding: 25px 35px; border-bottom: none;">
                        <h4 class="mb-0" style="font-weight: 700; font-size: 1.4rem;">Tambah Barang Masuk</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.stock-in.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="item_selection" class="form-label">Pilih Barang/Varian <span
                                        style="color: #e43522;">*</span></label>
                                <select name="item_selection" id="item_selection"
                                    class="form-control @error('item_selection') is-invalid @enderror" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($products as $product)
                                        @if($product->details && $product->details->count() > 0)
                                            <optgroup label="{{ $product->name }}">
                                                @foreach($product->details as $variant)
                                                    <option value="variant_{{ $variant->id }}"
                                                        {{ old('item_selection') == 'variant_'.$variant->id ? 'selected' : '' }}>
                                                        {{ $variant->name }} (Stok: {{ $variant->stock }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="product_{{ $product->id }}"
                                                {{ old('item_selection') == 'product_'.$product->id ? 'selected' : '' }}>
                                                {{ $product->name }} (Stok Umum: {{ $product->stock }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('item_selection')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="quantity" class="form-label">Jumlah Masuk <span
                                        style="color: #e43522;">*</span></label>
                                <input type="number" name="quantity" id="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" placeholder="Masukkan jumlah" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="reference" class="form-label">Nomor Referensi / PO</label>
                                <input type="text" name="reference" id="reference"
                                    class="form-control @error('reference') is-invalid @enderror"
                                    value="{{ old('reference') }}" placeholder="Contoh: PO-2025-001">
                                @error('reference')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4"
                                    placeholder="Masukkan catatan (opsional)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-save"></i> Simpan
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
