@extends('layouts.admin')

@section('page_title', 'Edit Produk')
@section('breadcrumb', 'Edit Produk')

@section('content')
    <style>
        .form-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .form-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .form-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding: 20px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-text {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .image-preview {
            margin: 15px 0;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .preview-img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .delete-zone {
            padding: 20px;
            border: 1px solid #f8d7da;
            background: #f8f9fa;
            border-radius: 4px;
            margin-top: 20px;
        }

        .delete-zone h5 {
            margin-top: 0;
            color: #721c24;
            font-weight: 600;
        }

        .delete-zone p {
            margin: 10px 0;
            color: #666;
            font-size: 0.95rem;
        }
    </style>

    <a href="{{ route('admin.barang.index') }}" class="back-link">← Kembali ke Daftar Produk</a>

    <div class="form-card">
        <div class="form-header">
            <h3 class="form-title">Edit Produk: {{ $product->name }}</h3>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Perbaiki kesalahan berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.barang.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            <div class="form-body">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">Nama Produk *</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}"
                        required>
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">Harga (Rp) *</label>
                    <input type="number" name="price" id="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $product->price) }}" required min="0" step="1000">
                    @error('price')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock" class="form-label">Stok (pcs) *</label>
                    <input type="number" name="stock" id="stock"
                        class="form-control @error('stock') is-invalid @enderror"
                        value="{{ old('stock', $product->stock) }}" required min="0">
                    @error('stock')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Gambar Produk</label>

                    @if ($product->image)
                        <div class="image-preview">
                            <p style="margin: 0 0 10px 0; font-size: 0.85rem; color: #666;">Gambar saat ini:</p>
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="preview-img">
                        </div>
                    @endif

                    <input type="file" name="image" id="image"
                        class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <small style="color: #666; margin-top: 5px; display: block;">Format: JPG, PNG (Max: 2MB). Kosongkan jika
                        tidak ingin mengubah gambar</small>
                    @error('image')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>

        <!-- Delete Section -->
        <div class="delete-zone">
            <h5>Hapus Produk</h5>
            <p>Menghapus produk akan menghilangkan semua data yang terkait dengan produk ini. Tindakan ini tidak dapat
                dibatalkan.</p>
            <form action="{{ route('admin.barang.destroy', $product->id) }}" method="POST" style="display: inline;"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus Produk Ini</button>
            </form>
        </div>
    </div>
@endsection
