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
            gap: 10px;
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

    display: flex;
    flex-direction: column;
    height: 100%;
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
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

      .related-card .name {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.95rem;

    display: -webkit-box;
    -webkit-line-clamp: 2; /* max 2 baris */
    -webkit-box-orient: vertical;
    overflow: hidden;
}

      .related-card .price {
    color: #28a745;
    font-weight: 700;
    margin-top: auto;
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
            // quantity handling
            const qtyValue = document.getElementById('qty-value');
            const qtyMinus = document.getElementById('qty-minus');
            const qtyPlus = document.getElementById('qty-plus');
            const qtyInput = document.getElementById('quantity-input');
            let quantity = 1;
            let currentStock = {{ $product->stock }};

            function updateQtyDisplay() {
                qtyValue.textContent = quantity;
                qtyInput.value = quantity;
                qtyPlus.disabled = quantity >= currentStock;
                qtyMinus.disabled = quantity <= 1;
            }

            qtyMinus.addEventListener('click', function() {
                if (quantity > 1) {
                    quantity--;
                    updateQtyDisplay();
                }
            });

            qtyPlus.addEventListener('click', function() {
                if (quantity < currentStock) {
                    quantity++;
                    updateQtyDisplay();
                }
            });

            updateQtyDisplay();

                // variant buttons
                const variantButtons = document.querySelectorAll('.variant-btn');
                const detailInput = document.getElementById('detail-id-input');
                const stockInfo = document.getElementById('stock-info');
                const priceEl = document.querySelector('.price');
                let activeVariant = null;

                variantButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        variantButtons.forEach(b => b.style.boxShadow = 'none');
                        this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
                        const id = this.dataset.id;
                        const price = this.dataset.price;
                        const stock = parseInt(this.dataset.stock) || 0;
                        detailInput.value = id;
                        activeVariant = id;
                        currentStock = stock;
                        stockInfo.textContent = '📦 Stok varian: ' + currentStock + ' unit tersedia';
                        priceEl.textContent = 'Rp' + Number(price).toLocaleString('id-ID');
                        if (quantity > currentStock) {
                            quantity = currentStock > 0 ? currentStock : 1;
                        }
                        updateQtyDisplay();
                    });
                });
                // auto-select default variant: look for data-default="1" else pick first
                if (variantButtons.length > 0) {
                    const btnArray = Array.from(variantButtons);
                    const defaultBtn = btnArray.find(b => b.dataset.default === '1') || btnArray[0];
                    // small timeout to ensure UI ready
                    setTimeout(() => defaultBtn.click(), 50);
                }
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
                    <span id="stock-info">📦 Stok: {{ $product->stock }} unit tersedia</span>
                </div>

                <div class="description">
                    <p>{{ $product->description }}</p>
                </div>

                <div class="actions">
                    <div style="margin-bottom:12px">
                        @if ($product->details()->count() > 0)
                            <label style="display:block;margin-bottom:6px;font-weight:600">Pilih Varian</label>
                            <div id="variant-buttons" style="display:flex;gap:8px;flex-wrap:wrap;">
                                @foreach ($product->details as $d)
                                    <button type="button" class="variant-btn" data-id="{{ $d->id }}" data-price="{{ $d->price }}" data-stock="{{ $d->stock }}" style="padding:8px 10px;border-radius:6px;border:1px solid #ddd;background:#fff;cursor:pointer">
                                        {{ $d->name }}{{ $d->size ? ' (' . $d->size . ')' : '' }}<br>
                                        <small style="color:#28a745">Rp{{ number_format($d->price,0,',','.') }}</small>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="quantity" style="margin-bottom: 20px; margin-left: -120px;">
                        <button type="button" id="qty-minus">-</button>
                        <span id="qty-value">1</span>
                        <button type="button" id="qty-plus">+</button>
                    </div>
                </div>
                
                <div style="margin-top:18px">
                    <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1" id="quantity-input">
                        <input type="hidden" name="detail_id" value="" id="detail-id-input">
                        <button type="submit" class="btn btn-primary" id="add-to-cart-btn" style="width:100%;padding:14px;border-radius:8px">🛒 Tambah ke Keranjang</button>
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
