@extends('layouts.app')

@section('title', 'Keranjang Belanja')


@section('extra_css')
    <style>
        /* Toastr customization */
        #toast-container > .toast-error { background-color: #e43522; }
        #toast-container > .toast-warning { background-color: #8A7650; }
        
        .cart-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .cart-header {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-dark);
            text-align: center;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto auto;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--muted);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-item-details {
            display: flex;
            flex-direction: column;
        }

        .cart-item-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-quantity button {
            background: var(--muted);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--text);
            transition: background 0.2s;
        }

        .cart-item-quantity button:hover {
            background: #8A7650;
            color: white;
        }

        .cart-item-quantity input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .cart-item-subtotal {
            font-weight: 600;
            font-size: 1.1rem;
            text-align: right;
        }

        .cart-item-remove button {
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .cart-item-remove button:hover {
            color: #e43522;
        }

        .cart-summary-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
            align-items: start;
        }

        .promo-code {
            padding: 20px;
            background: var(--card);
            border-radius: 8px;
        }

        .promo-code label {
            font-weight: 600;
            display: block;
            margin-bottom: 10px;
        }

        .promo-input-group {
            display: flex;
        }

        .promo-input-group input {
            flex-grow: 1;
            border: 1px solid var(--muted);
            padding: 10px;
            border-radius: 6px 0 0 6px;
            outline: none;
        }

        .promo-input-group button {
            padding: 10px 20px;
            border: none;
            background: var(--accent);
            color: white;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            font-weight: 500;
        }

        .cart-summary {
            padding-top: 20px;
            border-top: 1px solid var(--muted);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .summary-row span:first-child {
            color: var(--text-light);
        }

        .summary-row span:last-child {
            font-weight: 600;
        }

        .summary-total {
            font-weight: 700;
            font-size: 1.4rem;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--muted);
        }

        .summary-total span:first-child {
            color: var(--text-dark);
        }

        .checkout-btn-container {
            text-align: right;
            margin-top: 30px;
        }

        .checkout-btn-container .btn {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 8px;
            background: linear-gradient(45deg, #8A7650, #6E5034);
            box-shadow: 0 4px 15px rgba(138, 118, 80, 0.4);
            transition: all 0.3s ease;
        }

        .checkout-btn-container .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(138, 118, 80, 0.6);
        }

        .empty-cart {
            text-align: center;
            padding: white;
            border: 1px solid #DBCEA5;
            border-radius: 8px;
            color: #8A7650;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #ffffff;
            border: 1px solid #8A7650;
            border-radius: 8px;
            color: #8A7650;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #8A7650;
            color: white;
            border-color: #8A7650;
        }

        .btn-back svg {
            transition: transform 0.2s;
        }

        .btn-back:hover svg {
            transform: translateX(-3px);
        }
    </style>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('extra_js')
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        const cartItems = @json($cartItems);

        function updateQuantity(productId, newQuantity) {
            // Validasi quantity
            if (newQuantity < 1) {
                toastr.warning('Jumlah minimal adalah 1');
                return;
            }

            // Disable tombol saat proses update
            const quantityContainer = document.querySelector(`#quantity-${productId}`).closest('.cart-item-quantity');
            const buttons = quantityContainer.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);

            // Buat form data untuk method spoofing
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                '{{ csrf_token() }}');
            formData.append('quantity', newQuantity);

            // Kirim request ke server menggunakan POST dengan method spoofing
            fetch(`{{ route('cart.update', ':id') }}`.replace(':id', productId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update quantity input
                        document.getElementById(`quantity-${productId}`).value = newQuantity;

                        // Update subtotal item
                        document.getElementById(`subtotal-${productId}`).textContent = 'Rp ' + data.subtotal;

                        // Update subtotal cart
                        document.getElementById('cart-subtotal').textContent = 'Rp ' + data.total_raw.toLocaleString(
                            'id-ID');

                        // Update total cart (subtotal + shipping)
                        const newTotal = data.total_raw;
                        document.getElementById('cart-total').textContent = 'Rp ' + newTotal.toLocaleString('id-ID');

                        // Update cartItems array untuk sync data
                        const currentItem = cartItems.find(i => i.id === productId);
                        if (currentItem) {
                            currentItem.quantity = newQuantity;
                            // Update stock dari response jika ada
                            if (data.stock !== undefined) {
                                currentItem.stock = data.stock;
                            }
                        }

                        // Update tombol +/- berdasarkan stok menggunakan ID
                        const minusBtn = document.querySelector(`#btn-minus-${productId}`);
                        const plusBtn = document.querySelector(`#btn-plus-${productId}`);

                        // Update disabled state dan onclick untuk tombol minus
                        if (minusBtn) {
                            minusBtn.disabled = newQuantity <= 1;
                            minusBtn.setAttribute('onclick', `updateQuantity(${productId}, ${newQuantity - 1})`);
                        }

                        // Update disabled state dan onclick untuk tombol plus
                        if (plusBtn) {
                            // Gunakan stock dari response atau dari cartItems
                            const maxStock = data.stock !== undefined ? data.stock : (currentItem ? currentItem.stock :
                                999);
                            const canAddMore = newQuantity < maxStock;
                            plusBtn.disabled = !canAddMore;
                            // Update onclick dengan nilai baru yang sudah di-increment
                            plusBtn.setAttribute('onclick', `updateQuantity(${productId}, ${newQuantity + 1})`);
                            console.log('Updated plus button:', {
                                productId,
                                newQuantity,
                                maxStock,
                                canAddMore,
                                stock: data.stock
                            });
                        }
                    } else {
                        toastr.error(data.message || 'Terjadi kesalahan saat mengupdate quantity');
                        setTimeout(() => window.location.reload(), 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error(error.message || 'Terjadi kesalahan saat mengupdate quantity');
                })
                .finally(() => {
                    // Enable tombol kembali
                    buttons.forEach(btn => btn.disabled = false);
                });
        }
    </script>
@endsection

@section('content')
    <div class="cart-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('home') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
        <div class="cart-header">Keranjang Anda</div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        @if (count($cartItems) > 0)

            @foreach ($cartItems as $item)
                <div class="cart-item">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">{{ $item['name'] }}</div>
                        <div class="cart-item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                    </div>
                    <div class="cart-item-quantity">
                        <button type="button" id="btn-minus-{{ $item['id'] }}"
                            onclick="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] - 1 }})"
                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                        <input type="text" id="quantity-{{ $item['id'] }}" value="{{ $item['quantity'] }}" readonly>
                        <button type="button" id="btn-plus-{{ $item['id'] }}"
                            onclick="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] + 1 }})"
                            {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}>+</button>
                    </div>
                    <div class="cart-item-subtotal" id="subtotal-{{ $item['id'] }}">
                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                    </div>
                    <div class="cart-item-remove">
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST" style="display: inline;"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

              <div class="cart-summary-container">
                <div></div>
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
</div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="cart-total">Rp {{ number_format($total , 0, ',', '.') }}</span>
                    </div>
                    <div class="checkout-btn-container">
                        <a href="{{ route('checkout.index') }}" class="btn">Lanjutkan ke Checkout</a>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <p>Keranjang belanja Anda kosong.</p>
                <a href="{{ route('home') }}" class="btn" style="margin-top: 20px;">Mulai Belanja</a>
            </div>
        @endif
    </div>
@endsection
