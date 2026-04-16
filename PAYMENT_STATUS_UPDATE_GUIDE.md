# Panduan Update Status Pembayaran Midtrans

## Masalah yang Diperbaiki

Sebelumnya, setelah pembayaran berhasil di Midtrans, status order masih menunjukkan "Menunggu Pembayaran" karena:

1. Notification callback mungkin terlewat atau delay
2. Tidak ada mekanisme untuk check status langsung ke Midtrans
3. User tidak bisa manual refresh status

## Solusi yang Diterapkan

### 1. **Auto-Refresh Status** (Halaman Invoice)

Halaman nota belanja (`/orders/{id}`) sekarang:

-   Automatically check status setiap 5 detik
-   Refresh halaman otomatis jika pembayaran terdeteksi sebagai berhasil
-   Berhenti auto-check setelah 60 detik (untuk menghemat resource)

**File yang diubah:**

-   `resources/views/orders/show.blade.php`

**JavaScript yang ditambahkan:**

```javascript
// Check status ke API setiap 5 detik
checkAndUpdateStatus(); // Memanggil API check status

// Jika status sudah 'paid', halaman akan reload otomatis
```

### 2. **Manual Refresh Button** (Halaman Invoice)

-   Tombol "Refresh Status" muncul jika pembayaran masih pending
-   User bisa klik tombol untuk manual check status
-   Color: Hijau (#0e8f2c) sesuai theme

**Kondisi:**

-   Hanya muncul jika `payment_status == 'pending'`
-   Hilang otomatis jika pembayaran berhasil

### 3. **API Endpoint Check Status** (Baru)

Endpoint baru untuk check status pembayaran langsung ke Midtrans:

**Route:**

```
GET /api/orders/{order}/check-payment-status
```

**Respons jika pembayaran berhasil (settlement/capture):**

```json
{
    "status": "success",
    "message": "Pembayaran terdeteksi dan status sudah diperbarui",
    "payment_status": "paid",
    "order_status": "processing"
}
```

**Respons jika pembayaran pending:**

```json
{
    "status": "pending",
    "message": "Pembayaran masih dalam proses",
    "payment_status": "pending",
    "transaction_status": "pending"
}
```

**Respons jika pembayaran kadaluarsa:**

```json
{
    "status": "error",
    "message": "Pembayaran sudah kadaluarsa",
    "payment_status": "expired"
}
```

**File yang ditambahkan:**

-   `app/Http/Controllers/MidtransController.php` - Method `checkPaymentStatus()`
-   `routes/api.php` - Route registrasi

### 4. **Alur Lengkap Update Status**

#### Cara 1: Via Notification Callback (Rekomendasi)

```
1. User melakukan pembayaran di Midtrans
2. Midtrans mengirim POST ke /midtrans/notification
3. MidtransController->notification() memproses
4. Order status terupdate ke 'paid'
5. Stock dikurangi otomatis
```

#### Cara 2: Via Manual Check (Fallback)

```
1. User membuka halaman invoice
2. Halaman auto-check setiap 5 detik ke /api/orders/{id}/check-payment-status
3. Jika pembayaran terdeteksi paid, order terupdate
4. Halaman auto-reload untuk tampilkan status baru
```

#### Cara 3: User Click Refresh Button

```
1. User membuka halaman invoice
2. User klik tombol "Refresh Status"
3. Aplikasi check ke /api/orders/{id}/check-payment-status
4. Jika pembayaran sudah paid, halaman reload
5. Status berubah ke "Lunas"
```

## Teknologi yang Digunakan

### Frontend

-   **Vanilla JavaScript** (Fetch API)
-   **Auto-refresh interval**: 5 detik
-   **Max attempts**: 12 kali (60 detik total)

### Backend

-   **Midtrans\Transaction::status()** - Query status dari Midtrans
-   **Order model** - Update status dan payment info
-   **Stock decrement logic** - Kurangi inventory otomatis

## Testing Procedure

### Test 1: Auto-Refresh Functionality

1. Buka halaman invoice (status = pending)
2. Monitor browser console (F12 > Console)
3. Harus ada log "Payment status check: {...}" setiap 5 detik
4. Lakukan pembayaran di Midtrans
5. Halaman harus auto-reload dalam 5-10 detik
6. Status berubah menjadi "Lunas"

### Test 2: Manual Refresh Button

1. Buka halaman invoice (status = pending)
2. Lakukan pembayaran di Midtrans
3. Klik tombol "Refresh Status"
4. Halaman harus reload
5. Status berubah menjadi "Lunas"

### Test 3: Stock Decrement

```sql
-- Sebelum pembayaran
SELECT id, stock FROM product_items WHERE id = 1;
-- Contoh: stock = 10

-- Lakukan pembayaran order dengan quantity = 2

-- Sesudah pembayaran
SELECT id, stock FROM product_items WHERE id = 1;
-- Harusnya: stock = 8
```

### Test 4: Monitor Logs

```bash
# Terminal 1: Tail logs
tail -f storage/logs/laravel.log | grep Midtrans

# Terminal 2: Trigger payment test
# Lakukan pembayaran di Midtrans

# Harusnya muncul log:
# - "Midtrans Raw Notification"
# - "Midtrans Parsed Notification"
# - "Midtrans Check Status"
# - "Payment success processed"
```

## Troubleshooting

### Masalah: Status tidak berubah setelah pembayaran

**Solusi:**

1. Check logs: `tail -f storage/logs/laravel.log`
2. Cari "Midtrans" entries - lihat error message
3. Pastikan `transaction_id` ada di database
4. Pastikan Midtrans callback URL sudah benar di dashboard

### Masalah: Auto-refresh tidak jalan

**Solusi:**

1. Buka browser DevTools (F12)
2. Lihat Console tab untuk error
3. Check Network tab untuk request ke `/api/orders/{id}/check-payment-status`
4. Pastikan API endpoint return JSON response yang benar

### Masalah: Stock tidak dikurangi setelah pembayaran

**Solusi:**

1. Check notification callback diterima
2. Cek logs untuk error di `handlePaymentSuccess()`
3. Pastikan ProductItem model punya `stock` column
4. Pastikan order items berelasi dengan ProductItem dengan benar

## File yang Dimodifikasi

### View Layer

-   `resources/views/orders/show.blade.php`
    -   Tambah status badges dengan `data-payment-status` attribute
    -   Tambah refresh button
    -   Tambah JavaScript untuk auto-check dan refresh

### Controller Layer

-   `app/Http/Controllers/MidtransController.php`
    -   Tambah method `checkPaymentStatus($orderId)`
    -   Query status dari Midtrans
    -   Auto-update order jika pembayaran terdeteksi paid

### Routing Layer

-   `routes/api.php`
    -   Tambah route: `GET /api/orders/{order}/check-payment-status`

## Performance Considerations

1. **Auto-refresh stops after 60 seconds** - Mencegah infinite loop
2. **5 second interval** - Balance antara responsivitas dan resource usage
3. **Idempotent logic** - Check `if ($order->payment_status != PaymentStatus::PAID->value)` untuk prevent duplicate processing
4. **Async API calls** - Tidak block UI saat check status

## Security Notes

1. API endpoint memerlukan authentication untuk user access order mereka
2. Validated order ownership di controller sebelum return data
3. Server-side validation dari Midtrans signature (di notification endpoint)
4. Transaction ID verified sebelum query Midtrans API

## Next Steps (Optional Enhancements)

1. **Email notification** - Kirim email saat pembayaran berhasil
2. **WhatsApp notification** - Notif WhatsApp ke customer
3. **Push notification** - Real-time browser push notification
4. **Invoice PDF** - Generate PDF invoice setelah pembayaran sukses
5. **Retry mechanism** - Jika pembayaran retry, handle duplicate transaction IDs
