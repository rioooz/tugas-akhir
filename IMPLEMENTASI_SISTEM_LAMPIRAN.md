# Lampiran: Kode Lengkap (Implementasi Dashboard Pelanggan)

Berikut lampiran potongan kode lengkap yang menjadi inti implementasi fitur Dashboard Pelanggan.

---

## 1) Controller: `app/Http/Controllers/User/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Enums\OrderStatus;

class DashboardController extends Controller
{
    /**
     * Display the authenticated user's dashboard with simple stats.
     */
    public function index()
    {
        $user = Auth::user();

        // Total pesanan milik user
        $totalOrders = $user->orders()->count();

        // Pesanan yang sedang dalam status 'pending' (dapat disesuaikan)
        $dalamProses = $user->orders()->where('status', OrderStatus::PENDING)->count();

        // Pesanan dengan payment_status belum lunas (contoh)
        $belumBayar = $user->orders()->where('payment_status', OrderStatus::PENDING)->count();

        return view('user.dashboard', compact('totalOrders', 'dalamProses', 'belumBayar'));
    }
}
```

---

## 2) View: `resources/views/user/dashboard.blade.php`

```php
@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('extra_css')
<!-- CSS omitted for brevity (lihat file asli) -->
@endsection

@section('content')
    <section id="dashboard-section">
        <div class="dashboard-header">
            <h2>Dashboard Pelanggan</h2>
            <a href="{{ route('home') }}" class="btn">Kembali ke beranda</a>
        </div>

        <div class="welcome-card">
            <h3>Selamat datang, Pelanggan!</h3>
            <p>Kelola pesanan dan akun Anda dengan mudah melalui dashboard ini.</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-value">{{ $dalamProses ?? 0 }}</div>
                <div class="stat-label">Dalam Proses</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value">{{ $belumBayar ?? 0 }}</div>
                <div class="stat-label">Belum Bayar</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🚚</div>
                <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                <div class="stat-label">Riwayat Pesanan</div>
            </div>
        </div>
    </section>
@endsection
```

---

## 3) Route: `routes/web.php` (potongan)

```php
// User dashboard route (dijalankan dengan middleware 'auth' dan custom 'user')
use App\Http\Controllers\User\DashboardController as UserDashboardController;

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    // ... route lainnya untuk user
});
```

---

## 4) Model: `app/Models/Order.php` (potongan penting)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total', 'status', 'snap_token', 'payment_method',
        'payment_status', 'transaction_id', 'shipping_cost', 'discount',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}  
```

---

## 5) Model: `app/Models/User.php` (relasi orders)

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // ... atribut lainnya

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
```

---

## 6) Enum status: `app/Enums/OrderStatus.php`

```php
<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Diproses',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
```

---

Catatan:
- Lampiran ini berisi potongan inti yang relevan untuk laporan (`Implementasi Sistem`). Jika Anda menginginkan seluruh file tanpa pemangkasan (mis. CSS lengkap atau komentar lain), saya bisa menambahkan file-file lengkap sebagai lampiran terpisah.
