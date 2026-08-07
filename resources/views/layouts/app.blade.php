<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Help Desk IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background: #0d3b8c;
            color: #fff;
        }
        .sidebar .brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        .sidebar a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            display: block;
            padding: .65rem 1.25rem;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,.1);
            color: #fff;
            border-left-color: #ffc107;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }
        .stat-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,.06);
        }
        @media (max-width: 767.98px) {
            .sidebar { min-height: auto; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex flex-column flex-md-row">
    <nav class="sidebar" style="width: 250px; flex-shrink: 0;">
        <div class="brand d-flex align-items-center gap-2">
            <i class="fa-solid fa-droplet fs-4"></i>
            <div>
                <div class="fw-bold">Help Desk IT</div>
                <small class="text-white-50">PDAM Tirta Mulia</small>
            </div>
        </div>
        <div class="py-2">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house me-2"></i> Home
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('lokasi.index') }}" class="{{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-location-dot me-2"></i> Lokasi
                </a>
                <a href="{{ route('divisi.index') }}" class="{{ request()->routeIs('divisi.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sitemap me-2"></i> Departemen
                </a>
                <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> User
                </a>
                <a href="{{ route('tiket.index') }}" class="{{ request()->routeIs('tiket.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket me-2"></i> Daftar Tiket
                </a>
                <a href="{{ route('tiket.waiting') }}" class="{{ request()->routeIs('tiket.waiting') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock me-2"></i> Tiket Waiting
                </a>
                <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines me-2"></i> Laporan
                </a>
            @else
                <a href="{{ route('tiket.create') }}" class="{{ request()->routeIs('tiket.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-plus me-2"></i> Buat Tiket
                </a>
                <a href="{{ route('tiket.my') }}" class="{{ request()->routeIs('tiket.my') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check me-2"></i> Tiket Saya
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-100 text-start border-0 bg-transparent" style="color: rgba(255,255,255,.85); padding: .65rem 1.25rem;">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="topbar d-flex align-items-center justify-content-between px-4 py-3">
            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">{{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->divisi?->nama_divisi ?? 'Pegawai') }}</small>
                </div>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
