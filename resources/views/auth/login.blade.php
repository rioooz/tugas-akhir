@extends('layouts.app')

@section('title', 'Login')

@section('extra_css')
<style>
  .auth-container {
    max-width: 450px;
    margin: 40px auto;
    padding: 30px;
    background: var(--card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
  }
  
  .auth-title {
    font-size: 1.8rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 25px;
    color: var(--accent);
  }
  
  .input-group {
    margin-bottom: 20px;
  }
  
  .input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
  }
  
  .input:focus {
    border-color: var(--accent);
    outline: none;
  }
  
  .btn-container {
    display: flex;
    justify-content: center;
    margin-top: 25px;
  }
  
  .admin-hint {
    text-align: center;
    margin: 20px 0;
    padding: 10px;
    background: var(--muted);
    border-radius: 6px;
    font-size: 0.9rem;
  }
  
  .register-link {
    text-align: center;
    margin-top: 20px;
  }

  .alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
  }

  .alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
  }

  .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
  }
</style>
@endsection

@section('content')
<div class="auth-container">
  <div class="auth-title">Login</div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif
  
  <form action="{{ route('login.post') }}" method="POST">
    @csrf
    <div class="input-group">
      <input class="input" name="username" placeholder="Username atau Email" required />
    </div>
    
    <div class="input-group">
      <input class="input" name="password" placeholder="Password" type="password" required />
    </div>
    
    <div class="btn-container">
      <button type="submit" class="btn">LOGIN</button>
    </div>
    
    <div class="admin-hint">
    </div>
    
    <div class="register-link">
      Belum punya akun? <a href="{{ route('register') }}">Register</a>
    </div>
  </form>
</div>
@endsection