<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Konfigurasi Reverb dibaca dari .env lewat config/broadcasting.php --}}
    <meta name="reverb-key" content="{{ config('broadcasting.connections.reverb.key') }}">
    <meta name="reverb-host" content="{{ config('broadcasting.connections.reverb.options.host') }}">
    <meta name="reverb-port" content="{{ config('broadcasting.connections.reverb.options.port') }}">
    <meta name="reverb-scheme" content="{{ config('broadcasting.connections.reverb.options.scheme', 'http') }}">
    <meta name="auth-user-id" content="{{ auth()->id() }}">
    <meta name="auth-is-admin" content="{{ auth()->user()->isAdmin() ? '1' : '0' }}">
    {{-- TAMBAHAN BARU: nama route saat ini, dipakai untuk auto-refresh saat tiket baru masuk --}}
    <meta name="route-name" content="{{ request()->route()->getName() }}">
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
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        /* ==== Notifikasi bell + badge ==== */
        .notif-bell {
            position: relative;
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: #f1f5f9;
            color: #0d3b8c;
            cursor: pointer;
            border: none;
        }
        .notif-dot {
            position: absolute; top: 6px; right: 7px;
            width: 9px; height: 9px; border-radius: 50%;
            background: #ef4444; display: none;
            box-shadow: 0 0 0 2px #fff;
        }
        .nav-badge {
            background: #ef4444; color: #fff; font-size: .68rem;
            border-radius: 999px; padding: 1px 7px; display: none;
        }

        /* ==== Toast pop-up chat ala Instagram DM ==== */
        #chatToastStack {
            position: fixed; top: 18px; right: 18px; z-index: 2000;
            display: flex; flex-direction: column; gap: 10px;
            width: 320px; max-width: 90vw;
        }
        .chat-toast {
            background: #fff; border-radius: 14px;
            box-shadow: 0 14px 34px rgba(0,0,0,.18);
            padding: 12px 14px; display: flex; gap: 10px;
            cursor: pointer; animation: chatToastIn .25s ease;
            border-left: 4px solid #0d3b8c;
        }
        @keyframes chatToastIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chat-toast .avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: #0d3b8c; color: #fff; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .chat-toast .body { flex: 1; min-width: 0; }
        .chat-toast .title { font-weight: 600; font-size: .85rem; color: #111827; }
        .chat-toast .msg { font-size: .8rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .chat-toast .close-x { color: #9ca3af; font-size: .75rem; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex flex-column flex-md-row">
    <nav class="sidebar" style="width: 250px; flex-shrink: 0;">
        <div class="brand d-flex align-items-center gap-2">
            <img src="{{ asset('images/logopdam.jpg') }}" alt="Logo PDAM" style="width:28px;height:28px;object-fit:contain;">
            <div>
                <div class="fw-bold">Help Desk IT</div>
                <small class="text-white-50">PDAM Tirta Mulia</small>
            </div>
        </div>
        <div class="py-2">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span><i class="fa-solid fa-house me-2"></i> Home</span>
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('lokasi.index') }}" class="{{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-location-dot me-2"></i> Lokasi</span>
                </a>
                <a href="{{ route('divisi.index') }}" class="{{ request()->routeIs('divisi.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-sitemap me-2"></i> Departemen</span>
                </a>
                <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-users me-2"></i> User</span>
                </a>
                <a href="{{ route('tiket.index') }}" class="{{ request()->routeIs('tiket.index') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-ticket me-2"></i> Daftar Tiket</span>
                    <span class="nav-badge" id="badgeTiketMasuk">0</span>
                </a>
                <a href="{{ route('tiket.waiting') }}" class="{{ request()->routeIs('tiket.waiting') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-clock me-2"></i> Tiket Waiting</span>
                </a>
                <a href="{{ route('perangkat.index') }}" class="{{ request()->routeIs('perangkat.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-hard-drive me-2"></i> Data Perangkat</span>
                </a>
                <a href="{{ route('pemeriksaan.index') }}" class="{{ request()->routeIs('pemeriksaan.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-clipboard-check me-2"></i> Pemeriksaan Berkala</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-file-lines me-2"></i> Laporan</span>
                </a>

                {{-- TAMBAHAN BARU: Permintaan Reset Password (admin) --}}
                <a href="{{ route('passwordRequests.index') }}" class="{{ request()->routeIs('passwordRequests.*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-key me-2"></i> Reset Password Requests</span>
                    <span class="nav-badge" id="badgeResetRequest">0</span>
                </a>
            @else
                <a href="{{ route('tiket.create') }}" class="{{ request()->routeIs('tiket.create') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-plus me-2"></i> Buat Tiket</span>
                </a>
                <a href="{{ route('tiket.my') }}" class="{{ request()->routeIs('tiket.my') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-list-check me-2"></i> Tiket Saya</span>
                    <span class="nav-badge" id="badgeTiketMasuk">0</span>
                </a>
            @endif

            {{-- TAMBAHAN BARU: Profile Saya (semua role) --}}
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span><i class="fa-solid fa-user-gear me-2"></i> Profile Saya</span>
            </a>

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
            <div class="d-flex align-items-center gap-3">
                <button class="notif-bell" id="notifBell" title="Notifikasi pesan">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-dot" id="notifDot"></span>
                </button>
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

{{-- Kontainer toast pop-up notifikasi chat, ala notifikasi komentar Instagram --}}
<div id="chatToastStack"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
(function () {
    const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content;
    const csrfToken = meta('csrf-token');
    const userId = meta('auth-user-id');
    const isAdmin = meta('auth-is-admin') === '1';
    const reverbScheme = meta('reverb-scheme') || 'http';

    // Penanda global: baru true kalau instance Echo BENERAN berhasil dibikin.
    // Dipakai di halaman lain (mis. tiket/show.blade.php) supaya gak crash
    // manggil window.Echo.private() padahal Reverb belum aktif.
    window.echoReady = false;

    if (!window.Pusher || !window.Echo || !meta('reverb-key')) {
        console.warn('Reverb belum dikonfigurasi (cek .env REVERB_APP_KEY dkk).');
        return;
    }

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: meta('reverb-key'),
        wsHost: meta('reverb-host'),
        wsPort: meta('reverb-port'),
        wssPort: meta('reverb-port'),
        forceTLS: reverbScheme === 'https',
        enabledTransports: ['ws', 'wss'],
        auth: { headers: { 'X-CSRF-TOKEN': csrfToken } },
    });
    window.echoReady = true;

    let unread = 0;
    const dot = document.getElementById('notifDot');
    const navBadge = document.getElementById('badgeTiketMasuk');

    function updateBadges() {
        dot.style.display = unread > 0 ? 'block' : 'none';
        if (navBadge) {
            navBadge.style.display = unread > 0 ? 'inline-block' : 'none';
            navBadge.textContent = unread > 99 ? '99+' : unread;
        }
    }

    function showChatToast(payload) {
        unread += 1;
        updateBadges();

        const stack = document.getElementById('chatToastStack');
        const el = document.createElement('div');
        el.className = 'chat-toast';
        el.innerHTML = `
            <div class="avatar">${payload.sender_name.substring(0, 1).toUpperCase()}</div>
            <div class="body">
                <div class="title">${payload.sender_name} &middot; ${payload.kode_tiket}</div>
                <div class="msg">${payload.message}</div>
            </div>
            <div class="close-x"><i class="fa-solid fa-xmark"></i></div>
        `;
        el.addEventListener('click', () => {
            window.location.href = `/tiket/${payload.tiket_id}`;
        });
        stack.appendChild(el);

        setTimeout(() => {
            el.style.transition = 'opacity .3s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 300);
        }, 6000);
    }

    // Dengarkan channel notifikasi sesuai role
    if (isAdmin) {
        window.Echo.private('admins').listen('.new-message', (e) => showChatToast(e));

        // ==== TAMBAHAN BARU: notifikasi tiket baru masuk (khusus admin) ====
        const routeName = meta('route-name');
        const refreshableRoutes = ['dashboard', 'tiket.index', 'tiket.waiting'];

        window.Echo.private('admins').listen('.new-ticket', (e) => {
            unread += 1;
            updateBadges();

            const stack = document.getElementById('chatToastStack');
            const el = document.createElement('div');
            el.className = 'chat-toast';
            el.style.borderLeftColor = '#16a34a';
            el.innerHTML = `
                <div class="avatar" style="background:#16a34a;"><i class="fa-solid fa-ticket"></i></div>
                <div class="body">
                    <div class="title">Tiket Baru &middot; ${e.kode_tiket}</div>
                    <div class="msg">${e.user_name} (${e.divisi ?? '-'}): ${e.keluhan}</div>
                </div>
            `;
            el.addEventListener('click', () => { window.location.href = `/tiket/${e.id}`; });
            stack.appendChild(el);
            setTimeout(() => el.remove(), 6000);

            // Kalau admin sedang di halaman Dashboard / Daftar Tiket / Tiket Waiting,
            // muat ulang otomatis biar data langsung update tanpa perlu refresh manual.
            if (refreshableRoutes.includes(routeName)) {
                setTimeout(() => window.location.reload(), 1500);
            }
        });
        // ==== AKHIR TAMBAHAN new-ticket ====

        // ==== TAMBAHAN BARU: notifikasi permintaan reset password (khusus admin) ====
        window.Echo.private('admins').listen('.password-reset-requested', (e) => {
            unread += 1;
            updateBadges();

            const badgeReq = document.getElementById('badgeResetRequest');
            if (badgeReq) {
                badgeReq.style.display = 'inline-block';
                badgeReq.textContent = (parseInt(badgeReq.textContent) || 0) + 1;
            }

            const stack = document.getElementById('chatToastStack');
            const el = document.createElement('div');
            el.className = 'chat-toast';
            el.style.borderLeftColor = '#f59e0b';
            el.innerHTML = `
                <div class="avatar" style="background:#f59e0b;"><i class="fa-solid fa-key"></i></div>
                <div class="body">
                    <div class="title">Permintaan Reset Password</div>
                    <div class="msg">${e.name} (${e.username})</div>
                </div>
            `;
            el.addEventListener('click', () => { window.location.href = "{{ route('passwordRequests.index') }}"; });
            stack.appendChild(el);
            setTimeout(() => el.remove(), 6000);
        });
        // ==== AKHIR TAMBAHAN password-reset-requested ====
    } else if (userId) {
        window.Echo.private(`App.Models.User.${userId}`).listen('.new-message', (e) => showChatToast(e));
    }

    document.getElementById('notifBell')?.addEventListener('click', () => {
        unread = 0;
        updateBadges();
    });
})();
</script>
@stack('scripts')
</body>
</html>
PHPEOF