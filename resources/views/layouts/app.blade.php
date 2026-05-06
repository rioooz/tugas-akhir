<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mahesty Mebel — @yield('title', 'Home')</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .nav-link {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #0e8f2c;
        }

        .btn {
            background: #0e8f2c;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #e43522;
        }

        .btn-outline {
            border: 1px solid #0e8f2c;
            color: #0e8f2c;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: #0e8f2c;
            color: white;
        }
    </style>
    @yield('extra_css')
</head>

<body>
    <header class="app-header">
        <img src="{{ asset('assets/riologo.png') }}" style="width:64px; height:auto" />
        <a class="brand" href="{{ route('home') }}" style="margin-left: -1rem">MAHESTY MEBEL</a>
        <div class="spacer"></div>
        @guest
            <a href="{{ route('login') }}" class="btn-outline">Login</a>
        @else
            <a class="nav-link" href="{{ route('dashboard') }}" style="margin-right:12px">Dashboard</a>
            <a class="nav-link" href="{{ route('riwayat') }}" style="margin-right:12px">Riwayat</a>
            <a class="nav-link" href="{{ route('profile') }}" style="margin-right:12px">Profil</a>
            <a class="nav-link" href="{{ route('cart.index') }}" style="margin-right:12px">Keranjang</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-outline">Logout</button>
            </form>
        @endguest
    </header>

    <main class="container">
        @if (session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeeba;">
                {{ session('warning') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer" style="margin-top: 60px; padding: 40px 20px; background: #2c3e50; color: white; text-align: left;">
        <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <div>
                <h3 style="color: #0e8f2c; margin-bottom: 15px;">MAHESTY MEBEL</h3>
                <p style="font-size: 0.9rem; line-height: 1.6; color: #bdc3c7;">
                    Penyedia mebel berkualitas tinggi dengan desain modern dan klasik dengan kayu jati pilihan terbagus.
                </p>
            </div>
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 2px solid #0e8f2c; display: inline-block; padding-bottom: 5px;">Kontak Kami</h4>
                <ul style="list-style: none; padding: 0; font-size: 0.9rem; color: #bdc3c7;">
                    <li style="margin-bottom: 10px;">📍 Jl. Raya Tajem, Sleman, DIY</li>
                    <li style="margin-bottom: 10px;">📞 08156580019</li>
                    <li style="margin-bottom: 10px;">✉️ mahestymebel@gmail.com</li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 2px solid #0e8f2c; display: inline-block; padding-bottom: 5px;">Jam Operasional</h4>
                <ul style="list-style: none; padding: 0; font-size: 0.9rem; color: #bdc3c7;">
                    <li style="margin-bottom: 10px;">Senin - Sabtu: 08.00 - 17.00</li>
                    <li style="margin-bottom: 10px;">Minggu: Tutup</li>
                </ul>
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #3e4f5f; font-size: 0.8rem; color: #7f8c8d;">
            <p>&copy; {{ date('Y') }} Mahesty Mebel </p>
        </div>
    </footer>

    @yield('scripts')
    @yield('extra_js')
</body>

</html>
