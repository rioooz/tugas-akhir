5 Implementasi Sistem
=====================

5.1 Implementasi Fitur
----------------------

5.1.2 Implementasi dari salah satu fitur sistem — Dashboard Pelanggan

- Tujuan: Menampilkan ringkasan pesanan untuk pengguna yang sedang login (total pesanan, pesanan dalam proses, pesanan selesai/bayar).
- Lokasi implementasi (referensi file):
  - Controller: [app/Http/Controllers/User/DashboardController.php](app/Http/Controllers/User/DashboardController.php#L1-L120)
  - View: [resources/views/user/dashboard.blade.php](resources/views/user/dashboard.blade.php#L1-L200)
  - Route: [routes/web.php](routes/web.php#L70-L76)
  - Model Order: [app/Models/Order.php](app/Models/Order.php#L1-L160)
  - Model User: [app/Models/User.php](app/Models/User.php#L1-L200)
  - Enum status: [app/Enums/OrderStatus.php](app/Enums/OrderStatus.php#L1-L80)

Deskripsi singkat alur (MVC):

- Route: permintaan GET ke `'/dashboard'` (middleware `auth`) diarahkan ke `User\DashboardController@index`.
- Controller: `index()` mengambil user saat ini (`Auth::user()`), melakukan query Eloquent pada relasi `orders()` untuk menghitung statistik, lalu mengembalikan view `user.dashboard` dengan data.
- Model: `Order` merepresentasikan tabel pesanan; relasi `User->orders()` digunakan untuk query berdasar user.
- View: `user.dashboard` menerima variabel seperti `$totalOrders`, `$dalamProses`, `$belumBayar` dan menampilkannya menggunakan Blade.

Potongan kode inti (ringkas):

- Route (contoh):

  - Di `routes/web.php`:

    - Route yang memetakan dashboard:
      - `Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');`

- Controller (inti):

  - Method `index()` pada `User\DashboardController`:

    - Mengambil user saat ini: `Auth::user()`
    - Menghitung total pesanan milik user: `$totalOrders = $user->orders()->count();`
    - Menghitung pesanan dalam proses: `$dalamProses = $user->orders()->where('status', OrderStatus::PENDING)->count();`
    - Menghitung pesanan belum bayar (contoh field `payment_status`): `$belumBayar = $user->orders()->where('payment_status', OrderStatus::PENDING)->count();`
    - Mengembalikan view: `return view('user.dashboard', compact('totalOrders','dalamProses','belumBayar'));`

  - File controller lengkap: lihat [app/Http/Controllers/User/DashboardController.php](app/Http/Controllers/User/DashboardController.php#L1-L120)

- View (inti):

  - Di `resources/views/user/dashboard.blade.php` variabel diterima dan dicetak:

    - Contoh menampilkan nilai: `<div class="stat-value">{{ $dalamProses ?? 0 }}</div>`
    - Struktur kartu statistik dibuat dengan HTML + CSS pada Blade.

Penjelasan implementasi dan alasan desain singkat:

- Penggunaan Eloquent pada relasi `User->orders()` membuat query terfilter per pengguna, aman dan ringkas.
- Mengembalikan hanya tiga angka ringkasan membuat tampilan ringan dan cepat dimuat.
- Enum `OrderStatus` digunakan agar status ditulis secara konsisten (`OrderStatus::PENDING`, `OrderStatus::PROCESSING`, dll.). Lihat [app/Enums/OrderStatus.php](app/Enums/OrderStatus.php#L1-L80).

Syntax MVC singkat (alur contoh):

1) Route (routing)

   - `Route::get('/dashboard', [User\DashboardController::class, 'index'])->middleware(['auth']);`

2) Controller (logic)

   - `class DashboardController { public function index() { $user = Auth::user(); $total = $user->orders()->count(); return view('user.dashboard', compact('total')); } }`

3) Model (data)

   - `class User extends Authenticatable { public function orders() { return $this->hasMany(Order::class); } }`

4) View (presentation)

   - Blade: `{{ $total ?? 0 }}`

Lampiran / Catatan teknis singkat:

- Jika membutuhkan potongan kode lengkap (baris program panjang) saya simpan sebagai lampiran terpisah atau masukkan nomor baris yang relevan dari file-file sumber yang sudah disebutkan di atas.
- Nama file laporan ini: `IMPLEMENTASI_SISTEM.md` (root project).

— Selesai —
