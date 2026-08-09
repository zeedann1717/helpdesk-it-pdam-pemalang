@extends('layouts.guest')

@section('title', 'Selamat Datang')

@push('styles')
<style>
    .welcome-card { padding: 2.4rem 2rem; }
    .feature-row { display: flex; gap: 10px; justify-content: center; margin: 22px 0 26px; flex-wrap: wrap; }
    .feature-chip {
        display: flex; align-items: center; gap: 8px;
        background: #f1f6ff; color: #0d3b8c; font-weight: 600; font-size: .82rem;
        padding: 8px 14px; border-radius: 999px;
    }
    .feature-chip i { color: #17b3e0; }
    .action-btn {
        border-radius: 14px; padding: 14px; font-weight: 600;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .action-btn:hover { transform: translateY(-2px); }
    .btn-primary.action-btn {
        background: linear-gradient(135deg, #0d3b8c, #1657c2);
        border: none; box-shadow: 0 10px 22px rgba(13,59,140,.35);
    }
    .btn-outline-primary.action-btn { border: 2px solid #0d3b8c; color: #0d3b8c; }
    .action-desc { font-size: .78rem; color: #6b7280; margin-top: 8px; }
</style>
@endpush

@section('content')
<div class="card guest-card welcome-card text-center">
    <h4 class="fw-bold mb-1" style="color: var(--pdam-blue, #0d3b8c);">Selamat Datang 👋</h4>
    <p class="text-muted mb-0" style="font-size:.92rem;">
        Sampaikan kendala perangkat IT kantor Anda dan pantau progresnya secara real-time
        langsung dari sistem ini.
    </p>

    <div class="feature-row">
        <div class="feature-chip"><i class="fa-solid fa-bolt"></i> Respon Cepat</div>
        <div class="feature-chip"><i class="fa-solid fa-comments"></i> Live Chat</div>
        <div class="feature-chip"><i class="fa-solid fa-shield-halved"></i> Terpantau &amp; Aman</div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6">
            <a href="{{ route('login') }}" class="btn btn-primary action-btn w-100">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Login
            </a>
            <p class="action-desc mb-0">Sudah punya akun? Masuk untuk membuat atau memantau tiket.</p>
        </div>
        <div class="col-12 col-sm-6">
            <a href="{{ route('register') }}" class="btn btn-outline-primary action-btn w-100">
                <i class="fa-solid fa-user-plus me-2"></i>Register
            </a>
            <p class="action-desc mb-0">Belum punya akun? Daftar terlebih dahulu di sini.</p>
        </div>
    </div>
</div>
@endsection