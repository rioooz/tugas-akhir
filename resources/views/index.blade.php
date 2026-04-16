@extends('layouts.app')

@section('title', 'Home')

@section('extra_css')
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 20px;
            text-align: center;
            margin-bottom: 40px;
            border-radius: var(--radius);
        }

        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card .img {
            height: 200px;
            overflow: hidden;
        }

        .card .img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .card:hover .img img {
            transform: scale(1.05);
        }

        .card .title {
            padding: 15px 15px 5px;
            font-weight: 600;
        }

        .card .price {
            padding: 5px 15px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success"
            style="margin-bottom: 20px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error"
            style="margin-bottom: 20px; padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="hero-section">
        <h1>Mahesty Mebel</h1>
        <p>Temukan koleksi furniture berkualitas tinggi untuk melengkapi rumah impian Anda</p>
        <a href="#products" class="btn">Lihat Produk</a>
    </div>

    <section id="products">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:30px;">
            <h2>Produk Terbaru</h2>
            <div class="muted">Pilih dan tambahkan ke keranjang</div>
        </div>
        <div class="grid">
            @foreach ($products as $product)
                <div class="card">
                    <div class="img"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" /></div>
                    <div class="title">{{ $product->name }}</div>
                    <div class="price">
                        <div>Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST"
                                style="display: inline; flex: 1; min-width: 120px;">
                                @csrf
                                <button type="submit" class="btn"
                                    style="width: 100%; border: none; cursor: pointer; padding: 8px 12px; font-size: 0.9rem;">Tambah
                                    Keranjang</button>
                            </form>
                            <a class="btn" href="{{ route('product.detail', $product->id) }}"
                                style="flex: 1; min-width: 80px; background: #007bff; color: white; text-align: center; padding: 8px 12px; font-size: 0.9rem; text-decoration: none; border-radius: 4px; display: flex; align-items: center; justify-content: center;">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
