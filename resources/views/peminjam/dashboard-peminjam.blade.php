<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Dashboard Peminjam - Inventaris Barang</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <script>
        (function () {
            var tema = null;
            try { tema = localStorage.getItem('tema'); } catch (e) {}
            if (!tema) tema = matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', tema);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
    
        [data-bs-theme="light"] {
            --bs-body-bg: #eef3fb;
            --bs-body-color: #0b2447;
            --bs-secondary-color: #4a6087;
            --bs-border-color: rgba(11, 36, 71, .16);
            --kartu: #ffffff;
            --nav-bg: rgba(238, 243, 251, .92);
            --hover: rgba(11, 36, 71, .06);
            --biru: #1d4fa8;
            --ada: #0f9d7a;
            --pinjam: #d99a14;
            --telat: #d6336c;
            --emas-teks: #b97d00;
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --nav-bg: rgba(10, 25, 48, .92);
            --hover: rgba(173, 198, 245, .1);
            --biru: #6ea8ff;
            --ada: #4fd1ac;
            --pinjam: #f1b53d;
            --telat: #f0708f;
            --emas-teks: #f1b53d;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        .wadah {
            max-width: 1120px;
            padding-left: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-left));
            padding-right: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-right));
        }

        .navbar { background: var(--nav-bg); backdrop-filter: blur(10px); border-bottom: 1px solid var(--bs-border-color); padding-top: .75rem; padding-bottom: .75rem; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; letter-spacing: -.01em; color: var(--bs-body-color); min-width: 0; }
        .navbar-brand span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .logo { width: 56px; height: 56px; border-radius: 10px; object-fit: contain; flex-shrink: 0; }
        .navbar-toggler { min-width: 44px; min-height: 44px; border-color: var(--bs-border-color); }
        .navbar .nav-link { color: var(--bs-secondary-color); font-weight: 500; border-bottom: 2px solid transparent; padding-left: 0; padding-right: 0; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: var(--bs-body-color); border-color: var(--emas); }

        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
            border-radius: 10px;
        }
        .btn-tema, .btn-kembali { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        h1 { font-size: clamp(1.75rem, 6vw, 3.2rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.08; overflow-wrap: break-word; }
        h2 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.015em; }
        .lead { max-width: 36rem; color: var(--bs-secondary-color); font-size: clamp(1rem, 2.2vw, 1.15rem); }

        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; overflow: hidden; }
        .papan-kepala { padding: 20px 24px; border-bottom: 1px solid var(--bs-border-color); }
        .papan-isi { padding: 24px; }
        .ikon-kotak { width: 44px; height: 44px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; background: color-mix(in srgb, var(--biru) 14%, transparent); color: var(--biru); }
        .badge-jumlah { background: var(--hover); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); font-weight: 600; }

        .kondisi { list-style: none; margin: 0; padding: 0; }
        .kondisi li { display: grid; grid-template-columns: 6.5rem 1fr 2.5rem; align-items: center; gap: 14px; padding: .7rem 0; border-bottom: 1px solid var(--bs-border-color); }
        .kondisi li:last-child { border-bottom: 0; }
        .kondisi .nama { font-weight: 500; }
        .kondisi .jumlah { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; }
        .jalur { height: 6px; border-radius: 3px; background: var(--bs-border-color); overflow: hidden; }
        .jalur > span { display: block; height: 100%; border-radius: 3px; background: var(--c); }
        .k-ada { --c: var(--ada); }
        .k-pinjam { --c: var(--pinjam); }
        .k-telat { --c: var(--telat); }
        .peringatan { color: var(--telat); font-weight: 500; }

        .papan-kecil .ikon-kecil { width: 36px; height: 36px; font-size: 1.05rem; border-radius: 10px; }
        .papan-kecil .badge-jumlah { font-size: .75rem; padding: .4em .75em; }
        .papan-kecil .kondisi li { padding: .5rem 0; grid-template-columns: 5.5rem 1fr 2rem; }
        .papan-kecil .kondisi { padding-top: .25rem; padding-bottom: .25rem; }

        .layanan { display: block; padding: 1.75rem; border-radius: 16px; background: var(--emas); color: #1a1405; text-decoration: none; transition: transform .2s, background .2s; }
        .layanan:hover { background: #f7c55c; color: #1a1405; transform: translateY(-3px); }
        .layanan h3 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.01em; }
        .layanan .bi { font-size: 1.6rem; display: block; margin-bottom: .6rem; }
        .layanan-kembali { background: var(--kartu); border: 1px solid var(--bs-border-color); color: var(--bs-body-color); }
        .layanan-kembali .bi { color: var(--biru); }
        .layanan-kembali p { color: var(--bs-secondary-color); }
        .layanan-kembali:hover { background: var(--hover); border-color: var(--bs-secondary-color); color: var(--bs-body-color); }

        .daftar .list-group-item { background: transparent; color: var(--bs-body-color); display: flex; align-items: center; gap: 14px; padding: 1rem 24px; min-height: 64px; border-color: var(--bs-border-color); }
        .daftar .list-group-item:hover { background: var(--hover); }
        .daftar .teks { min-width: 0; }
        .daftar .teks span { display: block; color: var(--bs-secondary-color); font-size: .9rem; }
        .daftar .panah { margin-left: auto; color: var(--bs-secondary-color); }

        .aturan { border: 1.5px solid rgba(241, 181, 61, .45); background: rgba(241, 181, 61, .1); color: var(--bs-secondary-color); border-radius: 12px; }
        .aturan strong { color: var(--emas-teks); }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }

        @media (max-width: 991.98px) {
            .papan-kepala, .papan-isi { padding: 20px; }
            .daftar .list-group-item { padding-left: 20px; padding-right: 20px; }
        }

        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover, .navbar .nav-link.active { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            .papan { border-radius: 14px; }
            .papan-kepala, .papan-isi { padding: 16px; }
            .daftar .list-group-item { padding-left: 16px; padding-right: 16px; }
            .kondisi li { grid-template-columns: 5.5rem 1fr 2rem; gap: 10px; }
            .layanan { padding: 1.5rem; }
            .btn-kembali { width: 100%; }
            .aturan { padding: 1rem !important; }
            footer .wadah { text-align: center; }
        }

        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
            .papan { border-radius: 12px; }
            .papan-kepala, .papan-isi { padding: 14px; }
            .ikon-kotak { width: 38px; height: 38px; font-size: 1.05rem; }
            .kondisi li { grid-template-columns: 4.75rem 1fr 1.75rem; gap: 8px; }
            .layanan { padding: 1.25rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .navbar { padding-top: .35rem; padding-bottom: .35rem; }
            .logo { width: 38px; height: 38px; }
        }

        @media (min-width: 1400px) { .wadah { max-width: 1200px; } }

        @media (prefers-reduced-motion: reduce) {
            .layanan { transition: none; }
            .layanan:hover { transform: none; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @php
        $total    = (int) ($totalBarang ?? 0);
        $ada      = (int) ($barangTersedia ?? 0);
        $pinjam   = (int) ($sedangDipinjam ?? 0);
        $dasar    = max($total, 1);
        $lebar    = fn ($n) => round(min($n / $dasar * 100, 100), 2);
    @endphp

    <nav class="navbar navbar-expand-md sticky-top" aria-label="Menu utama">
        <div class="container wadah">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/peminjam') }}">
                <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                <span>InventarisBarang</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-md-center gap-md-4 py-3 py-md-0">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/peminjam') }}" aria-current="page">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/katalog') }}">Daftar Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/riwayat') }}">Riwayat</a></li>
                    <li class="nav-item mt-2 mt-md-0">
                        <button type="button" id="btnTema" class="btn btn-outline-secondary btn-sm btn-tema" aria-label="Ganti mode terang atau gelap">
                            <svg class="ikon-matahari" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                            <svg class="ikon-bulan" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                            <span id="labelTema">Mode</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container wadah flex-grow-1">
        <div class="pt-4">
            <a class="btn btn-outline-secondary btn-sm btn-kembali" href="{{ url('/') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke menu
            </a>
        </div>

        <section class="row g-4 g-lg-5 align-items-center py-4 py-md-5">
            <div class="col-12 col-lg-6">
                <h1>Selamat datang di layanan peminjaman.</h1>
                <p class="lead mt-3 mb-0">Pinjam dan kembalikan barang sekolah di satu tempat, tanpa perlu login.</p>
            </div>

            <div class="col-12 col-lg-6">
                <div class="papan papan-kecil" aria-labelledby="judul-kondisi">
                    <div class="d-flex align-items-center gap-3 px-3 pt-3 pb-2">
                        <div class="ikon-kotak ikon-kecil"><i class="bi bi-box-seam"></i></div>
                        <h2 id="judul-kondisi" class="h6 fw-bold mb-0 flex-grow-1">Kondisi barang saat ini</h2>
                        <span class="badge rounded-pill badge-jumlah">{{ $total }} barang</span>
                    </div>

                    <ul class="kondisi px-3">
                        <li class="k-ada">
                            <span class="nama">Tersedia</span>
                            <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($ada) }}%"></span></span>
                            <span class="jumlah">{{ $ada }}</span>
                        </li>
                        <li class="k-pinjam">
                            <span class="nama">Dipinjam</span>
                            <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($pinjam) }}%"></span></span>
                            <span class="jumlah">{{ $pinjam }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section aria-labelledby="judul-layanan" class="mb-4">
            <h2 id="judul-layanan" class="mb-3">Layanan</h2>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <a class="layanan h-100" href="{{ url('/peminjam/pinjam') }}">
                        <i class="bi bi-upc-scan"></i>
                        <h3 class="mb-2">Peminjaman</h3>
                        <p class="mb-0">Scan barcode barang, isi data, lalu dapatkan struk.</p>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a class="layanan layanan-kembali h-100" href="{{ url('/peminjam/kembali') }}">
                        <i class="bi bi-qr-code-scan"></i>
                        <h3 class="mb-2">Pengembalian</h3>
                        <p class="mb-0">Scan QR pada struk untuk mengembalikan barang.</p>
                    </a>
                </div>
            </div>
        </section>

        <section class="papan mb-4" aria-label="Pintasan">
            <div class="list-group list-group-flush daftar">
                <a href="{{ url('/peminjam/katalog') }}" class="list-group-item list-group-item-action">
                    <div class="ikon-kotak"><i class="bi bi-box-seam"></i></div>
                    <div class="teks">
                        <strong>Daftar barang</strong>
                        <span>Cek barang yang tersedia dan yang sedang dipinjam.</span>
                    </div>
                    <i class="bi bi-chevron-right panah"></i>
                </a>
                <a href="{{ url('/peminjam/riwayat') }}" class="list-group-item list-group-item-action">
                    <div class="ikon-kotak"><i class="bi bi-clock-history"></i></div>
                    <div class="teks">
                        <strong>Riwayat peminjaman dan pengembalian</strong>
                        <span>Pantau transaksi yang berjalan dan yang sudah selesai.</span>
                    </div>
                    <i class="bi bi-chevron-right panah"></i>
                </a>
            </div>
        </section>

        <div class="aturan px-4 py-3 mb-5 small">
            <strong>Ketentuan keterlambatan:</strong>
            batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.
        </div>
    </main>

    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            const html = document.documentElement;
            const label = document.getElementById('labelTema');
            const tampilkanLabel = () => {
                label.textContent = html.getAttribute('data-bs-theme') === 'dark' ? 'Mode terang' : 'Mode gelap';
            };
            tampilkanLabel();
            document.getElementById('btnTema').addEventListener('click', () => {
                const baru = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', baru);
                try { localStorage.setItem('tema', baru); } catch (e) {}
                tampilkanLabel();
            });
        })();
    </script>
</body>
</html>