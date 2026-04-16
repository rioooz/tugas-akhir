@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="auth-wrap">
  <div class="auth-title">Daftar Akun Baru</div>
  
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="error-list">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="auth-card">
    <form action="{{ route('register.post') }}" method="POST">
      @csrf
      
      <input type="text" class="input" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required />
      
      <input type="email" class="input" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required />
      
      <input type="password" class="input" name="password" placeholder="Password" required />
      
      <input type="password" class="input" name="password_confirmation" placeholder="Konfirmasi Password" required />
      
      <div style="display:flex; gap:12px; justify-content:center; margin-top:6px;">
        <button type="submit" class="btn">DAFTAR</button>
      </div>
    </form>
    <div class="muted small">Sudah punya akun?</div>
    <div style="display:flex; gap:10px; justify-content:center; margin-top:6px;">
      <a class="btn-outline" href="{{ route('login') }}">Login</a>
    </div>
  </div>
</section>

<style>
.alert {
  padding: 12px;
  margin-bottom: 20px;
  border-radius: 6px;
  border: 1px solid transparent;
}

.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}

.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
}

.error-list {
  margin: 0;
  padding-left: 20px;
}

.error-list li {
  color: #dc3545;
  font-size: 14px;
}
</style>
@endsection