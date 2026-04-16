@extends('layouts.app')

@section('title', 'Payment Success')
@section('extra_css')
    <style>
        #success-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            padding: 20px;
        }

        .success-card {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #0e8f2c;
        }

        .success-card h2 {
            font-size: 2rem;
            color: #333;
            margin: 20px 0 10px;
        }

        .success-card p {
            color: #666;
            margin: 10px 0;
        }

        .success-actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            justify-content: center;
        }

        .btn {
            padding: 12px 30px;
            background: #0e8f2c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0a6b22;
        }

        .btn-outline {
            padding: 12px 30px;
            background: white;
            color: #0e8f2c;
            border: 2px solid #0e8f2c;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: #0e8f2c;
            color: white;
        }
    </style>
@endsection
<section id="success-section">
    <div class="success-card">
        <div class="success-icon">✓</div>
        <h2>Pembayaran Berhasil!</h2>
        <p>Pesanan Anda telah dikonfirmasi dan sedang diproses.</p>
        <p>Nomor Pesanan: <b>#ORD12345</b></p>
        <div class="success-actions">
            <a class="btn" href="{{ route('home') }}">Kembali ke Beranda</a>
            <a class="btn-outline" href="{{ route('riwayat') }}">Lihat Riwayat Pesanan</a>
        </div>
    </div>
</section>
@endsection
