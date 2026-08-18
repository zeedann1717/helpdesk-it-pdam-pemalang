<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logopdam.jpg') }}">
    <title>@yield('title', 'Selamat Datang') - Help Desk IT PDAM Tirta Mulia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pdam-blue: #0d3b8c;
            --pdam-blue-dark: #082a66;
            --pdam-cyan: #17b3e0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(circle at 15% 20%, rgba(23,179,224,.35), transparent 40%),
                        radial-gradient(circle at 85% 80%, rgba(23,179,224,.25), transparent 45%),
                        linear-gradient(135deg, var(--pdam-blue-dark), var(--pdam-blue) 55%, #0f4fa8);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 24px;
            position: relative;
            overflow-x: hidden;
        }
        body::before, body::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        body::before { width: 420px; height: 420px; top: -140px; left: -140px; }
        body::after { width: 300px; height: 300px; bottom: -100px; right: -80px; }

        .guest-wrapper { position: relative; z-index: 1; width: 100%; max-width: 460px; }

        .brand-top { text-align: center; margin-bottom: 22px; color: #fff; }
        .brand-top .logo-box {
            width: 78px; height: 78px; margin: 0 auto 12px;
            background: #fff; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,.25);
            padding: 10px;
        }
        .brand-top .logo-box img { width: 100%; height: 100%; object-fit: contain; }
        .brand-top .instansi { letter-spacing: .5px; font-weight: 600; opacity: .9; font-size: .85rem; text-transform: uppercase; }
        .brand-top h1 { font-weight: 800; font-size: 1.55rem; margin: 4px 0 0; }

        .guest-card {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 25px 60px rgba(4,20,54,.35);
            border: none;
        }

        .badge-uptime {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.12); color: #fff;
            border: 1px solid rgba(255,255,255,.25);
            padding: 5px 14px; border-radius: 999px; font-size: .78rem;
            margin-top: 14px;
        }
        .badge-uptime i { color: #4ade80; }

        footer.guest-footer {
            text-align: center; color: rgba(255,255,255,.65);
            font-size: .78rem; margin-top: 22px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="guest-wrapper">
        <div class="brand-top">
            <div class="logo-box">
                {{-- Ganti file public/images/logopdam.jpg dengan logo asli PDAM Tirta Mulia Pemalang --}}
                <img src="{{ asset('images/logopdam.jpg') }}"
                     alt="Logo PDAM Tirta Mulia Pemalang"
                     onerror="this.onerror=null;this.replaceWith(Object.assign(document.createElement('i'),{className:'fa-solid fa-droplet fa-2x',style:'color:#0d3b8c'}));">
            </div>
            <div class="instansi">PDAM Tirta Mulia Pemalang</div>
            <h1>Help Desk IT</h1>
            <span class="badge-uptime"><i class="fa-solid fa-circle fa-xs"></i> Layanan pengaduan IT internal — online</span>
        </div>

        @yield('content')

        <footer class="guest-footer">
            &copy; {{ date('Y') }} PDAM Tirta Mulia Pemalang &middot; Bagian IT / Help Desk
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>