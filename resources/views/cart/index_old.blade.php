@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<section id="cart-section">
  <h2>Keranjang Belanja</h2>
  <div class="cart-list">
    <div class="cart-item">
      <div class="img"><img src="https://images.unsplash.com/photo-1595428774229-6dee1ff0c899?w=300&h=300&fit=crop" alt="Meja Rias Kayu Jati" /></div>
      <div class="details">
        <div class="title">Meja Rias Kayu Jati</div>
        <div class="price">Rp800.000</div>
      </div>
      <div class="actions">
        <div class="quantity">
          <button>-</button>
          <span>1</span>
          <button>+</button>
        </div>
        <button class="btn-outline">Hapus</button>
      </div>
    </div>
    <div class="cart-item">
      <div class="img"><img src="https://images.unsplash.com/photo-1520034475321-cbe63696469a?w=300&h=300&fit=crop" alt="Lemari Pakaian 2 Pintu" /></div>
      <div class="details">
        <div class="title">Lemari Pakaian 2 Pintu</div>
        <div class="price">Rp1.500.000</div>
      </div>
      <div class="actions">
        <div class="quantity">
          <button>-</button>
          <span>1</span>
          <button>+</button>
        </div>
        <button class="btn-outline">Hapus</button>
      </div>
    </div>
  </div>
  <div class="cart-summary">
    <div class="summary-row">
      <div>Subtotal</div>
      <div>Rp2.300.000</div>
    </div>
    <div class="summary-row">
      <div>Ongkir</div>
      <div>Rp50.000</div>
    </div>
    <div class="summary-row total">
      <div>Total</div>
      <div>Rp2.350.000</div>
    </div>
    <a class="btn" href="{{ route('payment') }}">Checkout</a>
  </div>
</section>
@endsection