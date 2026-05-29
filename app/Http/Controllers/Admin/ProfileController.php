<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan halaman profil admin dengan notifikasi dan daftar pelanggan.
     */
    public function index()
    {
        // Mengambil 5 pesanan terbaru sebagai notifikasi
        $notifications = Order::with('user')->latest()->take(5)->get();

        // Mengambil daftar pelanggan yang pernah melakukan pemesanan
        $customers = User::whereHas('orders')->with(['orders.items.productItem'])->latest()->get();

        return view('admin.profile', [
            'admin' => Auth::user(),
            'notifications' => $notifications,
            'customers' => $customers,
        ]);
    }

    /**
     * Memperbarui profil admin.
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.profile.index')->with('success', 'Profil admin berhasil diperbarui.');
    }
}
