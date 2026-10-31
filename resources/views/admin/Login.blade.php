<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <title>Login - Inventaris Barang</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <script>
        (function () {
            let tema = null;
            try {
                tema = localStorage.getItem('tema');
            } catch (e) {}

            if (!tema) {
                tema = matchMedia('(prefers-color-scheme: light)').matches
                    ? 'light'
                    : 'dark';
            }

            document.documentElement.setAttribute('data-bs-theme', tema);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">

    <link href="	{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --emas: #f1b53d;
        }

        [data-bs-theme="light"] {
            --bs-body-bg: #eef3fb;
            --bs-body-color: #0b2447;
            --bs-secondary-color: #4a6087;
            --bs-border-color: rgba(11, 36, 71, .16);
            --kartu: #ffffff;
            --nav-bg: rgba(238, 243, 251, .94);
            --hover: rgba(11, 36, 71, .06);
            --bayangan: rgba(11, 36, 71, .14);
            --biru: #1d4fa8;
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --nav-bg: rgba(10, 25, 48, .94);
            --hover: rgba(173, 198, 245, .1);
            --bayangan: rgba(0, 0, 0, .5);
            --biru: #6ea8ff;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
            transition: background-color .2s, color .2s;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #6ea8ff;
            outline-offset: 3px;
        }

        .navbar-login {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: var(--nav-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bs-border-color);
            padding-top: env(safe-area-inset-top);
        }

        .navbar-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-login {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--bs-body-color);
            min-width: 0;
        }

        .logo {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -.01em;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-caption {
            display: block;
            color: var(--bs-secondary-color);
            font-size: .72rem;
            font-weight: 500;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-login > span {
            min-width: 0;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .nav-home {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 40px;
            color: var(--bs-secondary-color);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 600;
            padding: 10px 13px;
            border-radius: 10px;
            transition: background .2s, color .2s;
        }

        .nav-home:hover {
            background: var(--hover);
            color: var(--bs-body-color);
        }

        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color);
            --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color);
            --bs-btn-hover-bg: var(--hover);
            --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color);
            --bs-btn-active-bg: var(--hover);
            --bs-btn-active-border-color: var(--bs-secondary-color);
        }

        .btn-tema {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            border-radius: 10px;
            padding: 8px 12px;
        }

        [data-bs-theme="dark"] .ikon-bulan,
        [data-bs-theme="light"] .ikon-matahari {
            display: none;
        }

        /* AREA LOGIN */

        .login-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 18px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            padding: 34px;
            border-radius: 20px;
            background: var(--kartu);
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 20px 50px var(--bayangan);
        }

        .judul {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -.025em;
            line-height: 1.2;
            color: var(--bs-body-color);
        }

        .form-label {
            font-size: .875rem;
            font-weight: 600;
            color: var(--bs-secondary-color);
        }

        .form-control {
            padding: .75rem .9rem;
            border-radius: 10px;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-border-color);
            font-size: 1rem;
        }

        .form-control::placeholder {
            color: var(--bs-secondary-color);
            opacity: .75;
        }

        .form-control:focus {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border-color: var(--biru);
            box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 25%, transparent);
        }

        .btn-emas {
            --bs-btn-color: #1a1405;
            --bs-btn-bg: var(--emas);
            --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405;
            --bs-btn-hover-bg: #f7c55c;
            --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405;
            --bs-btn-active-bg: #f7c55c;
            --bs-btn-active-border-color: #f7c55c;

            font-weight: 800;
            padding: 12px 24px;
            border-radius: 10px;
            transition: transform .2s, background .2s;
        }

        .btn-emas:hover {
            transform: translateY(-2px);
        }

        .login-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #1a1405;
            background: var(--emas);
            flex-shrink: 0;
        }

        .login-description {
            color: var(--bs-secondary-color);
            font-size: .9rem;
            line-height: 1.6;
        }

        .alert-login {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border-radius: 10px;
            font-size: .9rem;
            padding: .75rem 1rem;
            animation: goyang .35s ease-in-out;
        }
        .alert-login i { font-size: 1.1rem; line-height: 1.4; flex-shrink: 0; }
        .alert-login .btn-close { margin-left: auto; padding: .9rem .75rem; }

        @keyframes goyang {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        footer {
            border-top: 1px solid var(--bs-border-color);
            color: var(--bs-secondary-color);
            font-size: .9rem;
            padding-bottom: max(1rem, env(safe-area-inset-bottom));
        }

        footer a {
            color: var(--bs-secondary-color);
            display: inline-block;
            padding: .25rem 0;
        }

        footer a:hover {
            color: var(--bs-body-color);
        }

        .wadah {
            max-width: 1120px;
            padding-left: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-left));
            padding-right: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-right));
        }

        @media (max-width: 767.98px) {
            .brand-text { font-size: 1.15rem; }
            .logo { width: 46px; height: 46px; }
        }

        @media (max-width: 575.98px) {
            .navbar-inner {
                min-height: 68px;
            }

            .brand-text {
                font-size: 1.05rem;
            }

            .brand-caption {
                font-size: .68rem;
            }

            .logo {
                width: 40px;
                height: 40px;
            }

            .nav-home {
                padding: 9px;
                font-size: .82rem;
            }

            .nav-home .nav-home-text {
                display: none;
            }

            .btn-tema {
                padding: 8px 10px;
            }

            .login-area {
                padding: 30px 15px;
                align-items: flex-start;
            }

            .login-card {
                padding: 25px 21px;
                border-radius: 17px;
            }
        }

        @media (max-width: 399.98px) {
            .brand-caption { display: none; }
            .login-card { padding: 22px 18px; }
            .judul { font-size: 1.4rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .navbar-inner { min-height: 56px; }
            .login-area { padding-top: 20px; padding-bottom: 20px; align-items: flex-start; }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            .alert-login {
                animation: none;
            }

            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar-login" aria-label="Menu utama">
        <div class="container wadah">
            <div class="navbar-inner">

                <a href="{{ route('menu') }}" class="brand-login">
                    <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                    <span>
                        <span class="brand-text d-block">InventarisBarang</span>
                        <span class="brand-caption">Sistem Informasi Inventaris</span>
                    </span>
                </a>

                <div class="navbar-actions">
                    <a href="{{ route('menu') }}" class="nav-home">
                        <i class="bi bi-house-door"></i>
                        <span class="nav-home-text">Dashboard</span>
                    </a>

                    <button
                        type="button"
                        id="btnTema"
                        class="btn btn-outline-secondary btn-sm btn-tema"
                        aria-label="Ganti mode terang atau gelap"
                    >
                        <svg class="ikon-matahari" width="16" height="16"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
                        </svg>

                        <svg class="ikon-bulan" width="16" height="16"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
                        </svg>

                        <span id="labelTema" class="d-none d-sm-inline">Mode terang</span>
                    </button>
                </div>

            </div>
        </div>
    </nav>

    <main class="login-area">
        <div class="login-card">

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="login-icon">
                    <i class="bi bi-person-lock"></i>
                </div>

                <div>
                    <h1 class="judul mb-1">Login Admin</h1>
                    <div class="small text-body-secondary">
                        Selamat datang kembali
                    </div>
                </div>
            </div>

            <p class="login-description mb-4">
                Silakan masuk menggunakan username dan password admin untuk
                mengelola inventaris barang.
            </p>

            @if (session('login_error') || session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible alert-login" role="alert">
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <div>
                        @if (session('login_error'))
                            {{ session('login_error') }}
                        @elseif (session('error'))
                            {{ session('error') }}
                        @else
                            {{ $errors->first() }}
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>

                    <input
                        type="text"
                        class="form-control @if ($errors->has('username') || session('login_error') || session('error')) is-invalid @endif"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>

                    <input
                        type="password"
                        class="form-control @if ($errors->has('password') || session('login_error') || session('error')) is-invalid @endif"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-emas w-100 mt-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Masuk sebagai Admin
                </button>
            </form>

            <div class="text-center mt-4 small text-body-secondary">
                <i class="bi bi-shield-lock me-1"></i>
                Halaman khusus administrator
            </div>

        </div>
    </main>

    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy;2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            const html = document.documentElement;
            const tombol = document.getElementById('btnTema');
            const label = document.getElementById('labelTema');

            function tampilkanTema() {
                const gelap = html.getAttribute('data-bs-theme') === 'dark';

                label.textContent = gelap ? 'Mode terang' : 'Mode gelap';
                tombol.setAttribute(
                    'aria-label',
                    gelap ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'
                );
            }

            tampilkanTema();

            tombol.addEventListener('click', function () {
                const temaBaru =
                    html.getAttribute('data-bs-theme') === 'dark'
                        ? 'light'
                        : 'dark';

                html.setAttribute('data-bs-theme', temaBaru);

                try {
                    localStorage.setItem('tema', temaBaru);
                } catch (e) {}

                tampilkanTema();
            });
        })();
    </script>

</body>
</html>