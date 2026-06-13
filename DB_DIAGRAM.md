# Diagram Database - Sistem Manajemen Mebel

## ER Diagram (Mermaid Syntax)

```mermaid
erDiagram
    PENGGUNA ||--o{ PESANAN : membuat
    PENGGUNA ||--o{ BARANG_MASUK : mencatat
    PENGGUNA ||--o{ PENGELUARAN : mencatat
    
    PESANAN ||--|{ DETAIL_PESANAN : memiliki
    BARANG ||--o{ DETAIL_PESANAN : terdapat_dalam
    BARANG ||--o{ VARIAN_BARANG : memiliki
    BARANG ||--o{ BARANG_MASUK : diterima
    
    VARIAN_BARANG ||--o{ DETAIL_PESANAN : dipilih
    VARIAN_BARANG ||--o{ BARANG_MASUK : diterima
    
    PENGELUARAN ||--|{ DETAIL_PENGELUARAN : memiliki

    PENGGUNA {
        int id PK "ID Pengguna"
        string name "Nama"
        string email "Email"
        string address "Alamat"
        string password "Password"
        string role "Role (admin, user)"
        timestamp email_verified_at "Email Terverifikasi"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    PESANAN {
        int id PK "ID Pesanan"
        int user_id FK "ID Pengguna"
        decimal total "Total Harga"
        string status "Status (pending, processing, completed, cancelled)"
        string snap_token "Snap Token Midtrans"
        string midtrans_order_id "Order ID Midtrans"
        string payment_method "Metode Pembayaran"
        string payment_status "Status Pembayaran"
        string transaction_id "ID Transaksi"
        decimal shipping_cost "Biaya Pengiriman"
        decimal discount "Diskon"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    DETAIL_PESANAN {
        int id PK "ID Detail Pesanan"
        int order_id FK "ID Pesanan"
        int product_item_id FK "ID Barang"
        int product_item_detail_id FK "ID Varian Barang"
        int quantity "Jumlah"
        decimal price "Harga"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    BARANG {
        int id PK "ID Barang"
        string name "Nama Barang"
        text description "Deskripsi"
        decimal price "Harga"
        int stock "Stok"
        string image "Gambar"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    VARIAN_BARANG {
        int id PK "ID Varian"
        int product_item_id FK "ID Barang"
        string name "Nama Varian"
        decimal price "Harga Varian"
        int stock "Stok Varian"
        string size "Ukuran"
        text description "Deskripsi"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    BARANG_MASUK {
        int id PK "ID Barang Masuk"
        int product_item_id FK "ID Barang"
        int product_item_detail_id FK "ID Varian Barang"
        int quantity "Jumlah"
        string reference "Nomor Referensi/PO"
        text notes "Catatan"
        string status "Status (received, verified)"
        int user_id FK "ID Admin"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    PENGELUARAN {
        int id_pengeluaran PK "ID Pengeluaran"
        date tanggal_pengeluaran "Tanggal Pengeluaran"
        int Jumlah "Jumlah"
        int id_admin FK "ID Admin"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }

    DETAIL_PENGELUARAN {
        int id_pengeluaran_detail PK "ID Detail Pengeluaran"
        int id_pengeluaran FK "ID Pengeluaran"
        string nama_penerima "Nama Penerima"
        int Kehadiran "Kehadiran"
        enum Bon "Bon (Y/T)"
        string nama_bahan "Nama Bahan"
        timestamp created_at "Dibuat"
        timestamp updated_at "Diperbarui"
    }
```

## Penjelasan Relasi

### Tabel Utama:

1. **PENGGUNA** (users)
   - Penyimpanan data pengguna sistem
   - Memiliki role (admin/user)
   - Relasi dengan pesanan, barang masuk, dan pengeluaran

2. **PESANAN** (orders)
   - Menyimpan data pesanan dari pengguna
   - Terhubung ke detail pesanan (item-item dalam pesanan)
   - Memiliki status pembayaran (pending, processing, completed, cancelled)
   - Terintegrasi dengan Midtrans untuk pembayaran

3. **DETAIL_PESANAN** (order items)
   - Setiap baris adalah 1 item dalam pesanan
   - Terhubung ke barang dan varian barang
   - Menyimpan harga saat pembelian (historical)

4. **BARANG** (products)
   - Master data produk/barang
   - Memiliki berbagai varian (ukuran, tipe)
   - Menyimpan stok utama

5. **VARIAN_BARANG** (product variants)
   - Detail varian dari setiap barang (size, tipe)
   - Setiap varian memiliki stok sendiri
   - Bisa memiliki harga berbeda

6. **BARANG_MASUK** (stock in)
   - Mencatat semua barang yang masuk ke gudang
   - Terhubung ke barang dan variannya
   - Dicatat oleh admin (user)

7. **PENGELUARAN** (expenses)
   - Mencatat pengeluaran kas/biaya operasional
   - Dicatat oleh admin
   - Dapat memiliki multiple detail pengeluaran

8. **DETAIL_PENGELUARAN** (expense details)
   - Detail item dalam satu pengeluaran
   - Menyimpan informasi penerima, kehadiran, bon

## Cardinality (Relasi):

- **One-to-Many (1:N)**:
  - PENGGUNA → PESANAN
  - PESANAN → DETAIL_PESANAN
  - BARANG → VARIAN_BARANG
  - BARANG → BARANG_MASUK
  - PENGELUARAN → DETAIL_PENGELUARAN

- **Many-to-Many (N:N)** via junction table:
  - PESANAN ↔ BARANG (via DETAIL_PESANAN)

## Constraints:

- Foreign keys dengan CASCADE delete untuk menjaga integritas data
- Timestamps (created_at, updated_at) untuk audit trail
- Status enums untuk kontrol nilai yang diperbolehkan
- Price menggunakan DECIMAL untuk akurasi financial data
