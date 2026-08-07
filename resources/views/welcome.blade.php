@extends('layouts.guest')

@section('title', 'Selamat Datang')

@section('content')
<div class="card guest-card text-center p-4 p-md-5 bg-white">
    <div class="brand-logo"><i class="fa-solid fa-droplet"></i></div>
    <h6 class="text-primary text-uppercase fw-bold mb-1">PDAM Tirta Mulia Pemalang</h6>
    <h2 class="fw-bold mb-4">WELCOME TO HELPDESK IT</h2>

    <div class="row g-3">
        <div class="col-12 col-sm-6">
            <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3 rounded-3">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Login
            </a>
            <p class="small text-muted mt-2 mb-0">Jika Anda sudah memiliki akun, silahkan klik tombol login di bawah ini.</p>
        </div>
        <div class="col-12 col-sm-6">
            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 py-3 rounded-3">
                <i class="fa-solid fa-user-plus me-2"></i>Register
            </a>
            <p class="small text-muted mt-2 mb-0">Jika Anda belum memiliki akun, silahkan klik tombol register di bawah ini.</p>
        </div>
    </div>
</div>
@endsection
