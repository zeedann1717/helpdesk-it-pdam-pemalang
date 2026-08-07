@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="card guest-card p-4 p-md-5 bg-white">
    <div class="text-center mb-4">
        <div class="brand-logo"><i class="fa-solid fa-droplet"></i></div>
        <h6 class="text-primary text-uppercase fw-bold mb-1">PDAM Tirta Mulia Pemalang</h6>
        <h4 class="fw-bold">Login to your account</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
    </form>

    <p class="text-center text-muted mt-4 mb-0">
        Belum memiliki akun? <a href="{{ route('register') }}">Buat akun</a>
    </p>
</div>
@endsection
