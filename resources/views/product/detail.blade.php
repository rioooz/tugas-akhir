@extends('layouts.app')

@section('title', $product->name . ' - Detail Produk')

@section('extra_css')
    <style>
        .product-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            margin: 30px 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: 1px solid #f0f0f0;
        }

        .product-image {
            flex: 1;
            min-width: 300px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: var(--radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .product-image img:hover {
            transform: scale(1.02);
        }

        .product-info {
            flex: 1;
            min-width: 300px;
        }

        .product-info h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #0e8f2c;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 20px;
        }

        .description {
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .description p {
            margin-bottom: 10px;
        }

        .stock-info {
            padding: 12px;
            background: #e8f5e9;
            border-left: 4px solid #28a745;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #2e7d32;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .quantity {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }

        .quantity button {
            background: var(--muted);
            border: none;
            padding: 10px 15px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .quantity button:hover {
            background: #e5e5e5;
        }

        .quantity span {
            padding: 0 15px;
            font-weight: 600;
        }

        .btn {
            padding: 12px 25px;
            font-size: 1rem;
        }

        .btn-primary {
            background: #0e8f2c;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #0a6b22;
        }

        .related-section {
            margin-top: 60px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
        }

        .related-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .related-card:hover {
            transform: translateY(-5px);
        }

        .related-card .img {
            height: 150px;
            overflow: hidden;
        }

        .related-card .img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-card .info {
            padding: 12px;
        }

        .related-card .name {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .related-card .price {
            color: #28a745;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-detail {
                flex-direction: column;
                padding: 20px;
            }

            .product-image,
            .product-info {
                width: 100%;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .quantity {
                justify-content: center;
                margin-bottom: 15px;
            }
        }
    </style>
@endsection

@section('extra_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantitySpan = document.querySelector('.quantity span');
            const minusBtn = document.querySelector('.quantity button:first-child');
            const plusBtn = document.querySelector('.quantity button:last-child');

            let quantity = 1;

            minusBtn.addEventListener('click', function() {
                if (quantity > 1) {
                    quantity--;
                    quantitySpan.textContent = quantity;
                    document.getElementById('quantity-input').value = quantity;
                }
            });

            plusBtn.addEventListener('click', function() {
                quantity++;
                quantitySpan.textContent = quantity;
                document.getElementById('quantity-input').value = quantity;
            });
        });
    </script>
@endsection

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('home') }}" style="color: #007bff; text-decoration: none;">← Kembali</a>
    </div>

    <section id="detail-section">
        <div class="product-detail">
            <div class="product-image">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" />
            </div>
            <div class="product-info">
                <h2>{{ $product->name }}</h2>
                <div class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>

                <div class="stock-info">
                    📦 Stok: {{ $product->stock }} unit tersedia
                </div>

                <div class="description">
                    <p>{{ $product->description }}</p>
                </div>

                <div class="actions">
                    <div class="quantity">
                        <button type="button">-</button>
                        <span>1</span>
                        <button type="button">+</button>
                    </div>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1" id="quantity-input">
                        <button type="submit" class="btn btn-primary">🛒 Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->count() > 0)
        <section class="related-section">
            <h3 style="margin-bottom: 30px; font-size: 1.5rem;">Produk Lainnya</h3>
            <div class="related-grid">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('product.detail', $related->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="related-card">
                            <div class="img">
                                <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" />
                            </div>
                            <div class="info">
                                <div class="name">{{ $related->name }}</div>
                                <div class="price">Rp{{ number_format($related->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
