@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="card guest-card p-4 p-md-5">
    <h4 class="fw-bold text-center mb-1" style="color:#0d3b8c;">Lupa Password</h4>
    <p class="text-muted text-center small mb-4">
        Masukkan username Anda. Admin IT akan meninjau permintaan ini dan menghubungi Anda untuk mengatur ulang password.
    </p>

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

    <form method="POST" action="{{ route('password.request.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan (opsional)</label>
            <textarea name="catatan" rows="2" class="form-control" placeholder="Contoh: nomor HP yang bisa dihubungi">{{ old('catatan') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3">
            <i class="fa-solid fa-paper-plane me-2"></i>Kirim Permintaan
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small text-decoration-none">
            <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Login
        </a>
    </div>
</div>
@endsection