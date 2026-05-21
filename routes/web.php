<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\DaftarBarangController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\StockInController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\CekRestok;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\HomeController;



    Route::get('/test-email', function () {
    Mail::raw('Halo Rio, ini email dari website mahesty mebel ', function ($message) {
        $message->to('emailtujuan@gmail.com') // GANTI EMAIL KAMU
                ->subject('Test Email Laravel');
    });

    return "Email berhasil dikirim!";
});

Route::get('/test-email', [TestEmailController::class, 'send']);

Route::get('/cek-restok', [CekRestok::class, 'cekRestok']);

Route::get('/midtrans/test', function () {
    return 'MIDTRANS OK';
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart & Checkout Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::match(['put', 'post'], '/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::match(['delete', 'post'], '/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');
Route::get('/checkout/finish/{orderId}', [CheckoutController::class, 'finish'])->name('checkout.finish');
Route::get('/checkout/unfinish/{orderId}', [CheckoutController::class, 'unfinish'])->name('checkout.unfinish');
Route::get('/checkout/error/{orderId}', [CheckoutController::class, 'error'])->name('checkout.error');
Route::get('/orders/{id}/check-status', [App\Http\Controllers\MidtransController::class, 'checkPaymentStatus'])->name('orders.check-status');

// Midtrans Notification (POST only, no CSRF)
Route::post('/midtrans/notification', [App\Http\Controllers\MidtransController::class, 'notification'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Orders Routes
Route::middleware('auth')->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Product Routes
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

// Home Route (accessible to all)
Route::get('/', [HomeController::class, 'index'])->name('home');

// User Routes
use App\Http\Controllers\User\DashboardController as UserDashboardController;

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat', [App\Http\Controllers\OrderController::class, 'index'])->name('riwayat');
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/wishlist', function () {
        return view('user.wishlist');
    })->name('wishlist');
});

// Admin Routes
Route::get('/admin', function () {
    return redirect()->route('admin.index');
})->name('admin')->middleware(['auth', 'admin']);

Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::resource('daftar-barang', DaftarBarangController::class);
    Route::resource('barang', AdminProductController::class)->except(['edit', 'update']);
    
    // Product item details (variants) nested under barang
    Route::prefix('barang/{barang}/details')->name('barang.details.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProductItemDetailController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ProductItemDetailController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ProductItemDetailController::class, 'store'])->name('store');
        Route::get('/{detail}/edit', [\App\Http\Controllers\Admin\ProductItemDetailController::class, 'edit'])->name('edit');
        Route::put('/{detail}', [\App\Http\Controllers\Admin\ProductItemDetailController::class, 'update'])->name('update');
        Route::delete('/{detail}', [\App\Http\Controllers\Admin\ProductItemDetailController::class, 'destroy'])->name('destroy');
    });
    
    // Orders Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::post('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });
    
    // Customers Routes
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminCustomerController::class, 'show'])->name('show');
        Route::put('/{id}', [AdminCustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCustomerController::class, 'destroy'])->name('destroy');
    });

    // ================= TRANSAKSI =================
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // Halaman utama transaksi (tabs penjualan & pembelian)
        Route::get('/', [App\Http\Controllers\Admin\TransactionController::class, 'index'])
            ->name('index');

        // Data transaksi penjualan
        Route::get('/penjualan', [App\Http\Controllers\Admin\TransactionController::class, 'sales'])
            ->name('penjualan');

        // Data transaksi pembelian bahan mentah
        Route::get('/pembelian', [App\Http\Controllers\Admin\TransactionController::class, 'purchases'])
            ->name('pembelian');

        // Detail transaksi (penjualan / pembelian)
        Route::get('/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])
            ->name('show');
    });
    Route::get('/laporan', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('/laporan/revenue-month', [App\Http\Controllers\Admin\ReportController::class, 'revenueByMonth'])->name('reports.revenueByMonth');
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    
    // Stock In Routes
    Route::resource('stock-in', StockInController::class);

});
