@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('extra_css')
    <style>
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #666;
        }

        .breadcrumb a {
            color: #0e8f2c;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0f2e9;
            padding-bottom: 15px;
        }

        .page-header h2 {
            font-size: 1.8rem;
            color: #0e8f2c;
            margin: 0;
        }

        .order-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .order-item {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .order-item:hover {
            transform: translateY(-5px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .order-id {
            font-weight: 700;
            color: #0e8f2c;
        }

        .order-date {
            color: #666;
            font-size: 0.9rem;
        }

        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .order-status.success {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .order-status.pending {
            background: rgba(243, 156, 18, 0.15);
            color: #f31212ff;
        }

        .order-status.canceled {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .order-products {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.05);
        }

        .product-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .product-item .img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .product-item .img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-item:hover .img img {
            transform: scale(1.1);
        }

        .product-item .details {
            flex: 1;
        }

        .product-item .title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .product-item .price {
            color: #e43522;
            font-size: 0.9rem;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.02);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .total {
            font-weight: 700;
            font-size: 1.1rem;
            color: #0e8f2c;
        }

        .btn-small {
            background: #0e8f2c;
            color: white;
            padding: 6px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-small:hover {
            background: #0a6b22;
        }

        .empty-orders {
            text-align: center;
            padding: 40px 0;
            color: #666;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
        }

        .filter-buttons {
            display: flex;
            gap: 5px;
        }

        .filter-btn {
            background: #f5f5f5;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: #e0e0e0;
        }

        .filter-btn.active {
            background: #0e8f2c;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .order-date {
                font-size: 0.8rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .action-buttons {
                width: 100%;
                align-items: flex-start;
            }


            .filter-buttons {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 5px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderItems = document.querySelectorAll('.order-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');

                    // Show/hide order items based on filter
                    orderItems.forEach(item => {
                        if (filter === 'all') {
                            item.style.display = 'block';
                        } else {
                            const status = item.querySelector('.order-status');
                            if (status.classList.contains(filter)) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
@endsection

@section('content')
    <section id="riwayat-section">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> &gt; <span>Riwayat Pesanan</span>
        </div>

        <div class="page-header">
            <h2>Riwayat Pesanan</h2>
            <div class="action-buttons">
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">Semua</button>
                    <button class="filter-btn" data-filter="success">Selesai</button>
                    <button class="filter-btn" data-filter="pending">Dalam Pengiriman</button>
                    <button class="filter-btn" data-filter="canceled">Dibatalkan</button>
                </div>
                <a href="{{ route('home') }}" class="btn-small">Belanja Lagi</a>
            </div>
        </div>

        <div class="order-list">
            <div class="order-item">
                <div class="order-header">
                    <div class="order-id">Order #ORD12345</div>
                    <div class="order-date">12 oktober 2025</div>
                    <div class="order-status success">Selesai</div>
                </div>
                <div class="order-products">
                    <div class="product-item">
                        <div class="img"><img src="{{ asset('assets/img/product1.jpg') }}" alt="Meja Rias Kayu Jati"
                                onerror="this.src='https://via.placeholder.com/100x100?text=Meja+Rias'" /></div>
                        <div class="details">
                            <div class="title">Meja Rias Kayu Jati</div>
                            <div class="price">Rp800.000 x 1</div>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="img"><img src="{{ asset('assets/img/product2.jpg') }}" alt="Lemari Pakaian 2 Pintu"
                                onerror="this.src='https://via.placeholder.com/100x100?text=Lemari+Pakaian'" /></div>
                        <div class="details">
                            <div class="title">Lemari Pakaian 2 Pintu</div>
                            <div class="price">Rp1.500.000 x 1</div>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="total">Total: Rp2.350.000</div>
                    <a class="btn-small" href="#">Lihat Detail</a>
                </div>
            </div>

            <div class="order-item">
                <div class="order-header">
                    <div class="order-id">Order #ORD12344</div>
                    <div class="order-date">5 November 2025</div>
                    <div class="order-status pending">Dalam Pengiriman</div>
                </div>
                <div class="order-products">
                    <div class="product-item">
                        <div class="img"><img src="{{ asset('assets/img/product3.jpg') }}" alt="Kursi Tamu Minimalis"
                                onerror="this.src='https://via.placeholder.com/100x100?text=Kursi+Tamu'" /></div>
                        <div class="details">
                            <div class="title">Kursi Tamu Minimalis</div>
                            <div class="price">Rp500.000 x 2</div>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="total">Total: Rp1.050.000</div>
                    <a class="btn-small" href="#">Lihat Detail</a>
                </div>
            </div>

            <div class="order-item">
                <div class="order-header">
                    <div class="order-id">Order #ORD12343</div>
                    <div class="order-date">28 Oktober 2025</div>
                    <div class="order-status canceled">Dibatalkan</div>
                </div>
                <div class="order-products">
                    <div class="product-item">
                        <div class="img"><img src="{{ asset('assets/img/product4.jpg') }}" alt="Buffet TV Modern"
                                onerror="this.src='https://via.placeholder.com/100x100?text=Buffet+TV'" /></div>
                        <div class="details">
                            <div class="title">Buffet TV Modern</div>
                            <div class="price">Rp1.200.000 x 1</div>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="total">Total: Rp1.200.000</div>
                    <a class="btn-small" href="#">Lihat Detail</a>
                </div>
            </div>
        </div>
    </section>
@endsection
