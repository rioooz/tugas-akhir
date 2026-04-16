<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menampilkan form registrasi
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses login pengguna
     */
    public function login(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        // Coba autentikasi dengan email atau username
        $credentials = $request->only('username', 'password');
        
        // Cek apakah input adalah email atau username
        $field = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        $loginAttempt = [
            $field => $credentials['username'],
            'password' => $credentials['password']
        ];

        if (Auth::attempt($loginAttempt)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role user
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.index');
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        return redirect()->route('login')
            ->with('error', 'Email/username atau password salah.')
            ->withInput();
    }

    /**
     * Memproses registrasi pengguna baru
     */
    public function register(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role untuk pelanggan
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    /**
     * Memproses logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil logout.');
    }
}