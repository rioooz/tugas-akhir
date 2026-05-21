@extends('layouts.admin')

@section('page_title', 'Tambah Barang Baru')
@section('breadcrumb', 'Tambah Barang')

@section('content')
    <style>
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.15);
            overflow: hidden;
            max-width: 750px;
            margin: 0 auto;
            border: 1px solid rgba(219, 206, 165, 0.5);
        }

        .form-header {
            padding: 25px 35px;
            background: #8A7650;
            border-bottom: none;
        }

        .form-title {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-body {
            padding: 35px 35px 15px 35px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #6E5034;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #ECE7D1;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            background-color: #fcfbf9;
            transition: all 0.3s;
            color: #333;
        }

        .form-control:focus {
            outline: none;
            border-color: #8A7650;
            box-shadow: 0 0 0 4px rgba(138, 118, 80, 0.15);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding: 25px 35px;
            border-top: 1px solid rgba(219, 206, 165, 0.4);
            background: #faf9f6;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #8A7650;
            color: white;
        }

        .btn-primary:hover {
            background: #736140;
        }

        .btn-secondary {
            background: #8E977D;
            color: white;
        }

        .btn-secondary:hover {
            background: #727964;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: #8A7650;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 18px;
            background: transparent;
            border: 2px solid #8A7650;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .back-link:hover {
            background: #8A7650;
            color: #ffffff;
            transform: translateX(-5px);
        }
    </style>

    <a href="{{ route('admin.daftar-barang.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Barang
    </a>

    <div class="form-card">
        <div class="form-header">
            <h3 class="form-title"><i class="fas fa-plus"></i> Tambah Barang Baru</h3>
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

        <form action="{{ route('admin.daftar-barang.store') }}" method="POST" enctype="multipart/form-data">
            <div class="form-body">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Produk *</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">Harga (Rp) *</label>
                    <input type="number" name="price" id="price"
                        class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required
                        min="0" step="1000">
                    @error('price')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>



                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" id="image"
                        class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <small style="color: #666; margin-top: 5px; display: block;">Format: JPG, PNG (Max: 2MB)</small>
                    @error('image')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.daftar-barang.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Produk</button>
            </div>
        </form>
    </div>
@endsection
