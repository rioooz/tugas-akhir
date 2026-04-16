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
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        .order-status.paid {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .order-status.processing {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .order-status.pending {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }

        .order-status.failed {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .order-status.expired {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .order-status.cancelled {
            background: rgba(149, 165, 166, 0.15);
            color: #95a5a6;
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
        }

        .product-item .details {
            flex: 1;
        }

        .product-item .title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .product-item .price {
            color: var(--red);
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
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderItems = document.querySelectorAll('.order-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');

                    orderItems.forEach(item => {
                        if (filter === 'all') {
                            item.style.display = 'block';
                        } else {
                            const status = item.querySelector('.order-status');
                            if (status && status.classList.contains(filter)) {
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
            <div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">Semua</button>
                    <button class="filter-btn" data-filter="paid">Lunas</button>
                    <button class="filter-btn" data-filter="processing">Diproses</button>
                    <button class="filter-btn" data-filter="pending">Menunggu</button>
                    <button class="filter-btn" data-filter="failed">Gagal</button>
                </div>
                <a href="{{ route('home') }}" class="btn-small" style="margin-top: 10px; display: inline-block;">Belanja
                    Lagi</a>
            </div>
        </div>

        <div class="order-list">
            @forelse($orders as $order)
                <div class="order-item">
                    <div class="order-header">
                        <div class="order-id">Order #{{ $order->id }}</div>
                        <div class="order-date">{{ $order->created_at->format('d F Y') }}</div>
                        <div class="order-status {{ $order->payment_status->value }}">
                            @if ($order->payment_status->value == 'paid')
                                Lunas
                            @elseif($order->payment_status->value == 'pending')
                                Menunggu Pembayaran
                            @elseif($order->payment_status->value == 'processing')
                                Diproses
                            @elseif($order->payment_status->value == 'failed')
                                Gagal
                            @elseif($order->payment_status->value == 'expired')
                                Kadaluarsa
                            @else
                                {{ $order->payment_status->label() }}
                            @endif
                        </div>
                    </div>
                    <div class="order-products">
                        @foreach ($order->orderItems as $item)
                            <div class="product-item">
                                <div class="img">
                                    <img src="{{ $item->productItem->image ? asset($item->productItem->image) : 'https://via.placeholder.com/100x100' }}"
                                        alt="{{ $item->productItem->name }}" />
                                </div>
                                <div class="details">
                                    <div class="title">{{ $item->productItem->name }}</div>
                                    <div class="price">Rp {{ number_format($item->price, 0, ',', '.') }} x
                                        {{ $item->quantity }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="order-footer">
                        <div class="total">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        <a class="btn-small" href="{{ route('orders.show', $order->id) }}">Lihat Detail</a>
                    </div>
                </div>
            @empty
                <div class="empty-orders">
                    <p>Anda belum memiliki pesanan.</p>
                    <a href="{{ route('home') }}" class="btn-small" style="margin-top: 20px; display: inline-block;">Mulai
                        Belanja</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
