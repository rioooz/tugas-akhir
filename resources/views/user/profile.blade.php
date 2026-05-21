@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('extra_css')
    <style>
        .profile-page {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .profile-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #8A7650;
            margin-bottom: 5px;
        }

        .profile-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .profile-card {
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(138, 118, 80, 0.08);
            border: 1px solid rgba(219, 206, 165, 0.5);
            transition: transform 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(138, 118, 80, 0.15);
        }

        .profile-card h4 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #8A7650;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ECE7D1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #DBCEA5;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            transition: all 0.3s ease;
            background-color: #fcfbf9;
        }

        .form-control:focus {
            outline: none;
            border-color: #8A7650;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(138, 118, 80, 0.15);
        }

        .btn-submit {
            background: linear-gradient(135deg, #8A7650 0%, #6E5034 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(138, 118, 80, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(138, 118, 80, 0.5);
            background: linear-gradient(135deg, #7A6640 0%, #5E4024 100%);
        }
    </style>
@endsection

@section('content')
    <div class="profile-page">
        <div class="profile-header">
            <h2>Profil Anda</h2>
            <p>Kelola informasi akun, alamat, dan ubah password Anda.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin-top: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-top: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="profile-grid">
                <div class="profile-card">
                    <h4>Informasi Akun</h4>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" class="form-control" rows="4">{{ auth()->user()->address }}</textarea>
                    </div>
                </div>

                <div class="profile-card">
                    <h4>Ubah Password</h4>
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="form-control">
                    </div>
                </div>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
