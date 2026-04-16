<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - MAHESTY MEBEL</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8f9fa; color: #333; line-height: 1.6; }
        #payment-section { max-width: 1000px; margin: 40px auto; padding: 20px; }
        #payment-section h2 { text-align: center; color: #2c3e50; margin-bottom: 30px; font-size: 2rem; }
        .payment-wrap { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .payment-details h3, .payment-summary h3 { color: #2c3e50; margin-bottom: 20px; font-size: 1.3rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; }
        .input { width: 100%; padding: 12px 15px; border: 2px solid #e1e8ed; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; }
        .input:focus { outline: none; border-color: #3498db; }
        textarea.input { min-height: 100px; resize: vertical; }
        .payment-summary { background: #f8f9fa; padding: 25px; border-radius: 10px; border: 1px solid #e1e8ed; }
        .summary-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e1e8ed; }
        .summary-row.total { border-bottom: none; font-weight: bold; font-size: 1.2rem; color: #2c3e50; padding-top: 15px; margin-top: 10px; border-top: 2px solid #e1e8ed; }
        .btn { display: block; width: 100%; background: #27ae60; color: white; padding: 15px; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: background 0.3s; margin-top: 20px; }
        .btn:hover { background: #219a52; }
        @media (max-width: 768px) { .payment-wrap { grid-template-columns: 1fr; gap: 20px; } #payment-section { margin: 20px; padding: 10px; } }
    </style>
</head>
<body>
<section id="payment-section">
<section id="payment-section">
  <h2>Pembayaran</h2>
  <div class="payment-wrap">
    <div class="payment-details">
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input class="input" placeholder="Nama Lengkap" />
      </div>
      <div class="form-group">
        <label>Alamat Pengiriman</label>
        <textarea class="input" placeholder="Alamat Lengkap"></textarea>
      </div>
      <div class="form-group">
        <label>Nomor Telepon</label>
        <input class="input" placeholder="Nomor Telepon" />
      </div>
      <div class="form-group">
        <label>Metode Pembayaran</label>
        <select class="input">
          <option>Transfer Bank</option>
          <option>COD (Bayar di Tempat)</option>
        </select>
      </div>
    </div>
    <div class="payment-summary">
      <h3>Ringkasan Pesanan</h3>
      <div class="summary-row">
        <div>Subtotal (2 item)</div>
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
      <a class="btn" href="#" onclick="processPayment()">Bayar Sekarang</a>
    </div>
  </div>
</section>

<script>
    function processPayment() {
        alert('Pembayaran berhasil diproses! (Simulasi tanpa login)');
        // Redirect ke halaman sukses atau beranda
        window.location.href = '/cart/success';
    }
</script>

</body>
</html>