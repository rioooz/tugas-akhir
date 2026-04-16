@extends('layouts.app')

@section('title', 'Checkout')

@section('extra_css')
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .checkout-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .checkout-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 40px;
            align-items: start;
        }

        .checkout-form,
        .order-summary {
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-section {
            margin-bottom: 35px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #0e8f2c;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: #0e8f2c;
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--muted);
            border-radius: 10px;
            outline: none;
            transition: all 0.3s;
            font-size: 1rem;
            background: #fafafa;
        }

        .form-group input:focus {
            border-color: #0e8f2c;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        }

        .order-summary {
            position: sticky;
            top: 20px;
        }

        .order-summary-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #0e8f2c;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-summary-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: #0e8f2c;
            border-radius: 2px;
        }

        .summary-items {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-item img {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .summary-item-details {
            flex-grow: 1;
        }

        .summary-item-details .name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
            font-size: 0.95rem;
        }

        .summary-item-details .quantity {
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .summary-item .price {
            font-weight: 600;
            color: #0e8f2c;
            font-size: 1rem;
        }

        .summary-calculation {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-row span:first-child {
            color: var(--text-light);
            font-weight: 500;
        }

        .summary-row span:last-child {
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-total {
            font-size: 1.3rem;
            font-weight: 700;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid rgba(0, 0, 0, 0.1);
            color: #0e8f2c;
        }

        .summary-total span:first-child {
            color: var(--text-dark);
        }

        .place-order-btn {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #0e8f2c 0%, #0a6b22 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 25px;
            box-shadow: 0 4px 15px rgba(14, 143, 44, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .place-order-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.1),
                    transparent);
            transition: left 0.5s;
        }

        .place-order-btn:hover {
            background: linear-gradient(135deg, #0a6b22 0%, #084b1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 143, 44, 0.4);
        }

        .place-order-btn:hover::before {
            left: 100%;
        }

        .place-order-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(14, 143, 44, 0.3);
        }

        /* Scrollbar styling */
        .summary-items::-webkit-scrollbar {
            width: 6px;
        }

        .summary-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .summary-items::-webkit-scrollbar-thumb {
            background: #0e8f2c;
            border-radius: 10px;
        }

        .summary-items::-webkit-scrollbar-thumb:hover {
            background: #0a6b22;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: relative;
                top: 0;
            }

            .checkout-header h1 {
                font-size: 2rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .checkout-form,
        .order-summary {
            animation: fadeIn 0.5s ease-out;
        }

        .form-group {
            animation: fadeIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }
    </style>
@endsection

@section('content')
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Lengkapi informasi pengiriman untuk melanjutkan</p>
        </div>

        <div class="checkout-content">
            <div class="checkout-form">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    <!-- Hidden input untuk payment method default (Midtrans) -->
                    <input type="hidden" name="payment_method" value="midtrans">

                    <div class="form-section">
                        <h2 class="form-section-title">Informasi Pengiriman</h2>
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Lengkap</label>
                            <input type="text" id="address" name="address" placeholder="Masukkan alamat lengkap"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="order-summary">
                <h2 class="order-summary-title">Ringkasan Pesanan</h2>

                <div class="summary-items">
                    @foreach ($cartItems as $item)
                        <div class="summary-item">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            <div class="summary-item-details">
                                <div class="name">{{ $item['name'] }}</div>
                                <div class="quantity">Qty: {{ $item['quantity'] }}</div>
                            </div>
                            <div class="price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-calculation">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Pengiriman</span>
                        <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" form="checkout-form" class="place-order-btn">
                    Lanjutkan Pembayaran
                </button>
            </div>
        </div>
    </div>
@endsection
