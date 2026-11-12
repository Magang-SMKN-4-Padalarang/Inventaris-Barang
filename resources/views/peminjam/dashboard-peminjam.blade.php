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
            --input: #f5f8fd;
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
            --input: rgba(255, 255, 255, .04);
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

        /* ===== Cek barang ===== */
        .form-control { min-height: 48px; background-color: var(--input); border-color: var(--bs-border-color); color: var(--bs-body-color); border-radius: 10px; }
        .form-control:focus { background-color: var(--input); color: var(--bs-body-color); border-color: var(--biru); box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 22%, transparent); }
        .input-group > .input-group-text { background-color: var(--input); border-color: var(--bs-border-color); color: var(--bs-secondary-color); border-radius: 10px 0 0 10px; }
        .input-group > .form-control { border-radius: 0 10px 10px 0; }
        .kode-cek { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .06em; color: var(--biru); }
        .kode-cek::placeholder { letter-spacing: 0; font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
        .btn-reset-cek { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }


        .btn-kamera { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap; }
        .panel-kamera { margin-top: 16px; }
        .panel-kamera[hidden] { display: none; }
        .kamera-wadah { position: relative; width: 100%; max-width: 420px; margin: 0 auto; aspect-ratio: 4 / 3; border-radius: 14px; overflow: hidden; background: #000; border: 1px solid var(--bs-border-color); }
        .kamera-wadah video { width: 100%; height: 100%; object-fit: cover; display: block; }
        .kamera-bingkai { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; }
        .kamera-bingkai span { width: 82%; height: 38%; border: 2px solid var(--emas); border-radius: 10px; box-shadow: 0 0 0 9999px rgba(0, 0, 0, .35); }
        .kamera-info { text-align: center; font-size: .9rem; color: var(--bs-secondary-color); margin-top: 10px; min-height: 1.4em; }
        .kamera-info.salah { color: var(--telat); }
        .kamera-aksi { display: flex; justify-content: center; gap: 8px; margin-top: 10px; }

        .hasil-cek { margin-top: 20px; }
        .hasil-cek:empty { display: none; }
        .pesan-cek { display: flex; align-items: center; gap: 10px; padding: 14px 16px; border-radius: 12px; font-size: .95rem; border: 1px solid var(--bs-border-color); background: var(--hover); color: var(--bs-secondary-color); }
        .pesan-cek.salah { color: var(--telat); border-color: color-mix(in srgb, var(--telat) 40%, transparent); background: color-mix(in srgb, var(--telat) 10%, transparent); }

        .detail-barang { display: flex; gap: 18px; align-items: flex-start; }
        .foto-thumb {
            display: inline-flex; align-items: center; justify-content: center;
            width: 112px; height: 112px; overflow: hidden; flex-shrink: 0;
            border-radius: 14px; border: 1px solid var(--bs-border-color);
            background: var(--hover); color: var(--bs-secondary-color); font-size: 2rem;
        }
        .foto-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .foto-thumb-kosong { border-style: dashed; }
        .detail-isi { min-width: 0; flex: 1; }
        .detail-nama { font-size: clamp(1.2rem, 3.5vw, 1.6rem); font-weight: 800; letter-spacing: -.015em; line-height: 1.15; overflow-wrap: anywhere; }
        .detail-kode { display: inline-block; margin-top: 6px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: .85rem; padding: 3px 9px; border-radius: 8px; background: var(--hover); border: 1px solid var(--bs-border-color); word-break: break-all; }
        .badge-status { font-weight: 600; border: 1px solid; padding: .45em .85em; }
        .b-hijau { color: var(--ada); background: color-mix(in srgb, var(--ada) 12%, transparent); border-color: color-mix(in srgb, var(--ada) 40%, transparent); }
        .b-emas { color: var(--emas-teks); background: color-mix(in srgb, var(--emas-teks) 12%, transparent); border-color: color-mix(in srgb, var(--emas-teks) 40%, transparent); }
        .b-merah { color: var(--telat); background: color-mix(in srgb, var(--telat) 12%, transparent); border-color: color-mix(in srgb, var(--telat) 40%, transparent); }

        .lokasi {
            display: flex; align-items: center; gap: 16px; margin-top: 18px; padding: 16px 18px;
            border-radius: 14px; border: 1.5px solid rgba(241, 181, 61, .55); background: rgba(241, 181, 61, .1);
        }
        .lokasi > i { font-size: 1.9rem; line-height: 1; color: var(--emas-teks); flex-shrink: 0; }
        .lokasi-judul { font-size: .8rem; font-weight: 700; letter-spacing: .02em; color: var(--emas-teks); }
        .lokasi-isi { display: flex; flex-wrap: wrap; gap: 6px 22px; margin-top: 2px; }
        .lokasi-isi div { font-size: 1.15rem; font-weight: 800; letter-spacing: -.01em; }
        .lokasi-isi small { display: block; font-size: .72rem; font-weight: 500; letter-spacing: 0; color: var(--bs-secondary-color); }
        .lokasi-kosong .lokasi-isi div { font-size: 1rem; font-weight: 600; color: var(--bs-secondary-color); }

        .rincian { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin: 16px 0 0; }
        .rincian > div { padding: 12px 14px; border-radius: 12px; border: 1px solid var(--bs-border-color); background: var(--hover); min-width: 0; }
        .rincian dt { font-size: .78rem; font-weight: 500; color: var(--bs-secondary-color); }
        .rincian dd { margin: 2px 0 0; font-weight: 700; overflow-wrap: anywhere; }
        .rincian .telat { color: var(--telat); }

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

            .form-control { font-size: 1rem; }
            .detail-barang { flex-direction: column; align-items: stretch; }
            .foto-thumb { width: 100%; height: 190px; }
        }

        @media (max-width: 575.98px) {
            .rincian { grid-template-columns: 1fr; }
            .lokasi { padding: 14px; gap: 12px; }
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/daftar-barang') }}">Daftar Barang</a></li>
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

        {{-- ================= CEK BARANG: scan barcode untuk melihat detail dan lokasi ================= --}}
        <section class="papan mb-4" aria-labelledby="judul-cek">
            <div class="papan-kepala">
                <div class="d-flex align-items-center gap-3">
                    <div class="ikon-kotak"><i class="bi bi-upc-scan"></i></div>
                    <div style="min-width: 0;">
                        <h2 id="judul-cek" class="h5 fw-bold mb-1">Cek barang</h2>
                        <div class="small text-secondary">Scan barcode untuk melihat detail dan lokasi penyimpanan barang.</div>
                    </div>
                </div>
            </div>

            <div class="papan-isi">
                <form id="form-cek" class="row g-2" autocomplete="off" novalidate>
                    <div class="col">
                        <label for="kode_cek" class="visually-hidden">Kode barang</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-upc-scan" aria-hidden="true"></i></span>
                            <input type="text" id="kode_cek" class="form-control kode-cek"
                                   placeholder="Scan barcode atau ketik kode barang" autocomplete="off" autocapitalize="characters" spellcheck="false">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="btn-kamera" class="btn btn-primary btn-kamera" aria-controls="panel-kamera" aria-expanded="false">
                            <i class="bi bi-camera-fill" aria-hidden="true"></i> <span class="d-none d-sm-inline">Scan kamera</span>
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="btn-reset-cek" class="btn btn-outline-secondary btn-reset-cek" hidden>
                            <i class="bi bi-x-lg" aria-hidden="true"></i> <span class="d-none d-sm-inline">Bersihkan</span>
                        </button>
                    </div>
                </form>

                <div id="panel-kamera" class="panel-kamera" hidden>
                    <div class="kamera-wadah" id="kamera-wadah">
                        <video id="kamera-video" playsinline muted autoplay></video>
                        <div class="kamera-bingkai" aria-hidden="true"><span></span></div>
                    </div>
                    <div id="kamera-info" class="kamera-info" role="status" aria-live="polite"></div>
                    <div class="kamera-aksi">
                        <button type="button" id="btn-ganti-kamera" class="btn btn-outline-secondary btn-sm" hidden>
                            <i class="bi bi-arrow-repeat" aria-hidden="true"></i> Ganti kamera
                        </button>
                        <button type="button" id="btn-tutup-kamera" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg" aria-hidden="true"></i> Tutup kamera
                        </button>
                    </div>
                </div>

                <div id="hasil-cek" class="hasil-cek" aria-live="polite"></div>
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
                <a href="{{ url('/peminjam/daftar-barang') }}" class="list-group-item list-group-item-action">
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
        // ===== Tema =====
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

        // ===== Cek barang =====
        (function () {
            const URL_CEK   = @json(url('/peminjam/cek-barang'));
            const form      = document.getElementById('form-cek');
            const input     = document.getElementById('kode_cek');
            const hasil     = document.getElementById('hasil-cek');
            const btnReset  = document.getElementById('btn-reset-cek');

            let timer = null;
            let idCek = 0;

            const el = (tag, kelas, teks) => {
                const e = document.createElement(tag);
                if (kelas) e.className = kelas;
                if (teks !== undefined && teks !== null) e.textContent = teks;
                return e;
            };

            const pesan = (teks, salah) => {
                const kotak = el('div', 'pesan-cek' + (salah ? ' salah' : ''));
                const ikon = el('i', 'bi ' + (salah ? 'bi-exclamation-circle' : 'bi-hourglass-split'));
                ikon.setAttribute('aria-hidden', 'true');
                kotak.append(ikon, el('span', null, teks));
                hasil.replaceChildren(kotak);
            };

            const buatFoto = (url, nama) => {
                const box = el('span', 'foto-thumb');
                const kosong = ket => {
                    box.classList.add('foto-thumb-kosong');
                    box.title = ket;
                    box.setAttribute('role', 'img');
                    box.setAttribute('aria-label', ket);
                    box.replaceChildren();
                    const i = el('i', 'bi bi-image');
                    i.setAttribute('aria-hidden', 'true');
                    box.append(i);
                };
                if (url) {
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Foto ' + nama;
                    img.addEventListener('error', () => kosong('Foto tidak dapat dimuat'));
                    box.append(img);
                } else {
                    kosong('Belum ada foto');
                }
                return box;
            };

            const blokLokasi = d => {
                const ada = d.ruang || d.lemari;
                const kotak = el('div', 'lokasi' + (ada ? '' : ' lokasi-kosong'));
                const ikon = el('i', 'bi bi-geo-alt-fill');
                ikon.setAttribute('aria-hidden', 'true');

                const isi = el('div');
                isi.append(el('div', 'lokasi-judul', 'LOKASI BARANG'));

                const baris = el('div', 'lokasi-isi');
                if (ada) {
                    if (d.ruang) {
                        const r = el('div', null, d.ruang);
                        r.append(el('small', null, 'Ruang'));
                        baris.append(r);
                    }
                    if (d.lemari) {
                        const l = el('div', null, d.lemari);
                        l.append(el('small', null, 'Lemari'));
                        baris.append(l);
                    }
                } else {
                    baris.append(el('div', null, 'Lokasi belum tercatat'));
                }
                isi.append(baris);
                kotak.append(ikon, isi);
                return kotak;
            };

            const itemRincian = (judul, nilai, kelas) => {
                const w = el('div');
                w.append(el('dt', null, judul), el('dd', kelas || '', nilai));
                return w;
            };

            const tampilkan = d => {
                const tersedia = d.status === 'Tersedia';

                const isi = el('div', 'detail-isi');
                isi.append(el('div', 'detail-nama', d.nama), el('span', 'detail-kode', d.kode));

                const status = el('div', 'mt-3');
                const badge = el('span', 'badge rounded-pill badge-status ' + (tersedia ? 'b-hijau' : 'b-emas'), d.status);
                status.append(badge);
                if (!tersedia && d.terlambat) {
                    status.append(' ', el('span', 'badge rounded-pill badge-status b-merah', 'Terlambat'));
                }
                isi.append(status);

                const atas = el('div', 'detail-barang');
                atas.append(buatFoto(d.foto, d.nama), isi);

                const rincian = el('dl', 'rincian');
                rincian.append(itemRincian('Kategori', d.kategori || '-'));
                rincian.append(itemRincian('Jenis barang', d.nama));
                if (!tersedia) {
                    rincian.append(itemRincian('Batas pengembalian', d.jatuh_tempo || '-', d.terlambat ? 'telat' : ''));
                }

                hasil.replaceChildren(atas, blokLokasi(d), rincian);
            };

            const cek = async () => {
                const kode = input.value.trim();
                const id = ++idCek;
                btnReset.hidden = !kode;

                if (!kode) { hasil.replaceChildren(); return; }
                pesan('Memeriksa barang...', false);

                try {
                    const respon = await fetch(`${URL_CEK}/${encodeURIComponent(kode)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await respon.json();
                    if (id !== idCek) return;

                    if (data.ok) tampilkan(data);
                    else pesan(data.pesan || 'Barang tidak ditemukan.', true);
                } catch (e) {
                    if (id !== idCek) return;
                    pesan('Gagal memeriksa barang. Periksa koneksi lalu coba lagi.', true);
                }
            };

            input.addEventListener('input', () => {
                btnReset.hidden = !input.value.trim();
                clearTimeout(timer);
                timer = setTimeout(cek, 400);
            });

            form.addEventListener('submit', e => {
                e.preventDefault();
                clearTimeout(timer);
                cek();
            });

            const panelKamera = document.getElementById('panel-kamera');
            const video       = document.getElementById('kamera-video');
            const infoKamera  = document.getElementById('kamera-info');
            const btnKamera   = document.getElementById('btn-kamera');
            const btnTutup    = document.getElementById('btn-tutup-kamera');
            const btnGanti    = document.getElementById('btn-ganti-kamera');
            const URL_FALLBACK = @json(asset('vendor/html5-qrcode/html5-qrcode.min.js'));

            let stream = null, loop = null, detektor = null, fallback = null, kameraAktif = false;
            let daftarKamera = [], idxKamera = 0;

            const info = (teks, salah) => {
                infoKamera.textContent = teks;
                infoKamera.classList.toggle('salah', !!salah);
            };

            const muatSkrip = src => new Promise((ok, gagal) => {
                const s = document.createElement('script');
                s.src = src; s.onload = ok; s.onerror = () => gagal(new Error('skrip'));
                document.head.append(s);
            });

            const hentikanKamera = () => {
                kameraAktif = false;
                clearTimeout(loop);
                if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
                if (fallback) { try { fallback.stop(); } catch (e) {} fallback = null; }
                video.srcObject = null;
                panelKamera.hidden = true;
                btnKamera.setAttribute('aria-expanded', 'false');
            };

            const kodeTerbaca = kode => {
                if (!kode) return;
                if (navigator.vibrate) { try { navigator.vibrate(80); } catch (e) {} }
                hentikanKamera();
                input.value = kode.trim();
                clearTimeout(timer);
                cek();
                hasil.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            };

            const pesanError = e => {
                const n = e && e.name;
                if (n === 'NotAllowedError' || n === 'SecurityError') return 'Izin kamera ditolak. Aktifkan izin kamera untuk situs ini di pengaturan browser, lalu coba lagi.';
                if (n === 'NotFoundError' || n === 'OverconstrainedError') return 'Kamera tidak ditemukan di perangkat ini.';
                if (n === 'NotReadableError') return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.';
                return 'Kamera tidak dapat dibuka. Ketik kode barang secara manual.';
            };

            const mulaiNative = async () => {
                const formatDidukung = await BarcodeDetector.getSupportedFormats();
                const formats = ['code_128', 'code_39', 'ean_13', 'ean_8', 'qr_code'].filter(f => formatDidukung.includes(f));
                if (!formats.length) throw new Error('format');
                detektor = new BarcodeDetector({ formats });

                const pindai = async () => {
                    if (!kameraAktif) return;
                    try {
                        if (video.readyState >= 2) {
                            const hasilScan = await detektor.detect(video);
                            if (hasilScan.length) { kodeTerbaca(hasilScan[0].rawValue); return; }
                        }
                    } catch (e) { /* frame gagal dibaca, coba lagi */ }
                    loop = setTimeout(pindai, 150);
                };
                pindai();
            };

            const mulaiFallback = async () => {
                if (!window.Html5Qrcode) await muatSkrip(URL_FALLBACK);
                const id = 'kamera-fallback';
                let kotak = document.getElementById(id);
                if (!kotak) {
                    kotak = document.createElement('div');
                    kotak.id = id;
                    document.getElementById('kamera-wadah').append(kotak);
                }
                video.hidden = true;
                fallback = new Html5Qrcode(id, {
                    formatsToSupport: [Html5QrcodeSupportedFormats.CODE_128, Html5QrcodeSupportedFormats.CODE_39, Html5QrcodeSupportedFormats.EAN_13, Html5QrcodeSupportedFormats.QR_CODE],
                    verbose: false
                });
                await fallback.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 280, height: 120 } },
                    teks => kodeTerbaca(teks),
                    () => {}
                );
            };

            const bukaStream = async () => {
                if (stream) stream.getTracks().forEach(t => t.stop());
                const constraints = {
                    audio: false,
                    video: daftarKamera.length
                        ? { deviceId: { exact: daftarKamera[idxKamera].deviceId }, width: { ideal: 1280 }, height: { ideal: 720 } }
                        : { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
                };
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.hidden = false;
                video.srcObject = stream;
                await video.play();
            };

            const mulaiKamera = async () => {
                if (kameraAktif) return;
                if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    panelKamera.hidden = false;
                    info('Kamera hanya bisa dipakai lewat alamat HTTPS. Ketik kode barang secara manual.', true);
                    return;
                }

                panelKamera.hidden = false;
                btnKamera.setAttribute('aria-expanded', 'true');
                kameraAktif = true;
                info('Membuka kamera...');

                try {
                    if ('BarcodeDetector' in window) {
                        try {
                            await bukaStream();
                            await mulaiNative();
                        } catch (e) {
                            if (e && e.name && e.name !== 'Error') throw e;  
                            if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
                            await mulaiFallback();
                        }
                    } else {
                        await mulaiFallback();
                    }

                    try {
                        const perangkat = await navigator.mediaDevices.enumerateDevices();
                        daftarKamera = perangkat.filter(d => d.kind === 'videoinput');
                        if (stream) {
                            const aktif = stream.getVideoTracks()[0].getSettings().deviceId;
                            const i = daftarKamera.findIndex(d => d.deviceId === aktif);
                            idxKamera = i >= 0 ? i : 0;
                        }
                        btnGanti.hidden = !stream || daftarKamera.length < 2;
                    } catch (e) {}

                    info('Arahkan kamera ke barcode barang.');
                } catch (e) {
                    hentikanKamera();
                    panelKamera.hidden = false;
                    info(e && e.message === 'skrip'
                        ? 'Browser ini belum mendukung scan kamera. Ketik kode barang secara manual.'
                        : pesanError(e), true);
                }
            };

            btnKamera.addEventListener('click', () => { kameraAktif ? hentikanKamera() : mulaiKamera(); });
            btnTutup.addEventListener('click', hentikanKamera);
            btnGanti.addEventListener('click', async () => {
                if (daftarKamera.length < 2 || !stream) return;
                idxKamera = (idxKamera + 1) % daftarKamera.length;
                try { await bukaStream(); } catch (e) { info(pesanError(e), true); }
            });

            document.addEventListener('visibilitychange', () => { if (document.hidden) hentikanKamera(); });
            window.addEventListener('pagehide', hentikanKamera);

            btnReset.addEventListener('click', () => {
                clearTimeout(timer);
                idCek++;
                input.value = '';
                btnReset.hidden = true;
                hasil.replaceChildren();
                input.focus();
            });
        })();
    </script>
</body>
</html>