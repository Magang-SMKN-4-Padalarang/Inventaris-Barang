<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Barang - Inventaris Barang</title>
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
            --merah: #c2334d;
            --ada: #0f9d7a;
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
            --merah: #ff7a8e;
            --ada: #4fd1ac;
            --emas-teks: #f1b53d;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
            border-radius: 10px;
        }
        .btn-outline-danger {
            --bs-btn-color: var(--merah);
            --bs-btn-border-color: color-mix(in srgb, var(--merah) 45%, transparent);
            --bs-btn-hover-color: #fff; --bs-btn-hover-bg: var(--merah); --bs-btn-hover-border-color: var(--merah);
            --bs-btn-active-color: #fff; --bs-btn-active-bg: var(--merah); --bs-btn-active-border-color: var(--merah);
            border-radius: 10px;
        }
        .btn-outline-info { border-radius: 10px; }
        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            border-radius: 10px; padding: 11px 20px; min-height: 44px; font-weight: 700;
            transition: transform .2s, background .2s;
        }
        .btn-emas:hover { transform: translateY(-2px); }
        .btn-tema { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        .layout { display: flex; min-height: 100vh; }
        .sidebar { --bs-offcanvas-width: min(280px, 85vw); background: var(--kartu); }
        .sidebar-inner { width: 100%; min-height: 100%; display: flex; flex-direction: column; padding: 20px 16px; background: var(--kartu); }

        @media (min-width: 992px) {
            .sidebar { flex: 0 0 270px; position: sticky; top: 0; height: 100vh; border-right: 1px solid var(--bs-border-color); }
            .sidebar-inner { width: 270px; height: 100vh; overflow-y: auto; }
        }

        .logo { width: 56px; height: 56px; border-radius: 10px; object-fit: contain; flex-shrink: 0; }
        .brand-teks { font-weight: 800; font-size: 1.35rem; letter-spacing: -.01em; color: var(--bs-body-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .side-judul { font-size: .85rem; font-weight: 700; color: var(--bs-secondary-color); padding: 0 14px; margin: 24px 0 8px; }

        .side-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; min-height: 44px; border-radius: 10px;
            color: var(--bs-secondary-color); text-decoration: none; font-weight: 500;
            border: 1px solid transparent; transition: background .2s, color .2s;
        }
        .side-link i { font-size: 1.1rem; }
        .side-link:hover { color: var(--bs-body-color); background: var(--hover); }
        .side-link.active { color: var(--bs-body-color); background: var(--hover); border-color: var(--bs-border-color); box-shadow: inset 3px 0 0 var(--emas); }
        .sidebar-inner nav { margin-bottom: 24px; }

        .user-box { margin-top: auto; padding: 14px; border-radius: 14px; background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1a1405; background: var(--emas); flex-shrink: 0; }

        .topbar { position: sticky; top: 0; z-index: 1020; background: var(--nav-bg); backdrop-filter: blur(10px); border-bottom: 1px solid var(--bs-border-color); padding-top: max(.75rem, env(safe-area-inset-top)); }
        .topbar .btn-menu { min-width: 44px; min-height: 44px; }
        .content { flex: 1; min-width: 0; min-height: 100vh; display: flex; flex-direction: column; }

        h1 { font-size: clamp(1.75rem, 6vw, 3.2rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.08; overflow-wrap: break-word; }
        h2 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.015em; }
        .lead { max-width: 36rem; color: var(--bs-secondary-color); font-size: clamp(1rem, 2.2vw, 1.15rem); }

        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; overflow: hidden; }
        .papan-kepala { padding: 20px 24px; border-bottom: 1px solid var(--bs-border-color); }
        .papan-isi { padding: 24px; }

        .ikon-kotak { width: 44px; height: 44px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; background: color-mix(in srgb, var(--biru) 14%, transparent); color: var(--biru); }
        .badge-jumlah { background: var(--hover); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); font-weight: 600; }

        .input-cari, .form-control, .form-select { min-height: 44px; background-color: var(--kartu); border-color: var(--bs-border-color); color: var(--bs-body-color); border-radius: 10px; }
        .input-cari:focus, .form-control:focus, .form-select:focus { background-color: var(--kartu); color: var(--bs-body-color); border-color: var(--biru); box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 20%, transparent); }
        .input-group > .input-group-text.input-cari { border-radius: 10px 0 0 10px; color: var(--bs-secondary-color); }
        .input-group > .form-control.input-cari { border-radius: 0 10px 10px 0; }

        .tabel-data { margin-bottom: 0; vertical-align: middle; --bs-table-bg: transparent; --bs-table-color: var(--bs-body-color); --bs-table-border-color: var(--bs-border-color); --bs-table-hover-bg: var(--hover); --bs-table-hover-color: var(--bs-body-color); }
        .tabel-data thead th { padding: 14px 16px; color: var(--bs-secondary-color); background: var(--hover); font-size: .85rem; font-weight: 700; white-space: nowrap; border-bottom: 1px solid var(--bs-border-color); }
        .tabel-data tbody td { padding: 16px; border-color: var(--bs-border-color); }
        .nomor { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 10px; background: var(--hover); font-weight: 700; font-size: .85rem; font-variant-numeric: tabular-nums; }
        .kode { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: .85rem; padding: 4px 8px; border-radius: 8px; background: var(--hover); border: 1px solid var(--bs-border-color); }
        .tombol-aksi { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-height: 38px; padding: 7px 14px; font-size: .85rem; font-weight: 600; }

        .badge-status { font-weight: 600; border: 1px solid; padding: .45em .85em; }
        .b-hijau { color: var(--ada); background: color-mix(in srgb, var(--ada) 12%, transparent); border-color: color-mix(in srgb, var(--ada) 40%, transparent); }
        .b-emas { color: var(--emas-teks); background: color-mix(in srgb, var(--emas-teks) 12%, transparent); border-color: color-mix(in srgb, var(--emas-teks) 40%, transparent); }

        .col-pilih { width: 48px; }
        .cek-barang, #pilihSemua { width: 1.15rem; height: 1.15rem; cursor: pointer; }
        .bar-seleksi { padding: 10px 14px; border: 1px solid var(--bs-border-color); border-radius: 12px; background: var(--hover); }
        .tabel-data tbody tr.terpilih > td { background-color: color-mix(in srgb, var(--emas) 12%, transparent); }
        .kosong { padding: 48px 20px !important; color: var(--bs-secondary-color); }
        .kosong i { display: block; font-size: 2.5rem; margin-bottom: 12px; opacity: .65; }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .modal-header, .modal-footer { border-color: var(--bs-border-color); }
        [data-bs-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        .pagination { gap: 5px; margin-bottom: 0; flex-wrap: wrap; }
        .page-link { min-width: 40px; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; color: var(--bs-body-color); background: var(--kartu); border-color: var(--bs-border-color); border-radius: 8px !important; }
        .page-item.active .page-link { color: #1a1405; background: var(--emas); border-color: var(--emas); }
        .page-item.disabled .page-link { color: var(--bs-secondary-color); background: var(--hover); }
        .alert { border-radius: 12px; }

        .swal2-popup { border-radius: 16px !important; font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
        .swal2-styled.swal2-confirm { color: #1a1405 !important; font-weight: 700; border-radius: 10px !important; }
        .swal2-styled.swal2-confirm.swal-merah { color: #fff !important; }
        .swal2-styled.swal2-cancel { border-radius: 10px !important; }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
            .papan-kepala, .papan-isi { padding: 20px; }
        }

        @media (max-width: 767.98px) {
            .papan { border-radius: 14px; }
            .papan-kepala, .papan-isi { padding: 16px; }
            .topbar { padding-left: max(1rem, env(safe-area-inset-left)) !important; padding-right: max(1rem, env(safe-area-inset-right)) !important; }
            .tombol-tambah { width: 100%; }

            .tabel-data thead { display: none; }
            .tabel-data, .tabel-data tbody { display: block; width: 100%; }

            .tabel-data tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: 1fr auto;
                grid-template-areas: "nama status" "kode kode" "kategori kategori" "aksi aksi";
                column-gap: 12px; row-gap: 6px;
                padding: 16px 4px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-data tbody tr:not(.baris-kosong):last-child { border-bottom: 0; }
            .tabel-data tbody tr:not(.baris-kosong) td { display: block; padding: 0; border: 0; width: auto !important; }

            .col-no { display: none !important; }
            .col-pilih { grid-area: pilih; align-self: start; padding-top: 2px !important; }
            .tabel-data tbody tr.terpilih { background-color: color-mix(in srgb, var(--emas) 12%, transparent); border-radius: 10px; }
            .tabel-data tbody tr.terpilih > td { background-color: transparent; }
            .col-nama { grid-area: nama; font-weight: 700; min-width: 0; overflow-wrap: anywhere; }
            .col-status { grid-area: status; align-self: start; }
            .col-kode { grid-area: kode; }
            .col-kategori { grid-area: kategori; color: var(--bs-secondary-color); }
            .col-aksi { grid-area: aksi; text-align: left !important; margin-top: 8px; }
            .col-aksi > div { display: flex !important; width: 100%; }
            .col-aksi .tombol-aksi { flex: 1 1 0; min-height: 44px; padding-left: 6px; padding-right: 6px; }

            .tabel-data tbody tr.baris-kosong { display: table-row; }
            .tabel-data tbody tr.baris-kosong td { display: table-cell; }

            .cari-tombol { display: flex; gap: 8px; width: 100%; }
            .cari-tombol .btn { flex: 1 1 0; }
            .info-halaman { width: 100%; text-align: center; }
            .nav-halaman { width: 100%; display: flex; justify-content: center; }
            .modal-footer .btn { flex: 1 1 0; }
        }

        @media (max-width: 399.98px) {
            .logo { width: 44px; height: 44px; }
            .brand-teks { font-size: 1.1rem; }
            .papan { border-radius: 12px; }
            .papan-kepala, .papan-isi { padding: 14px; }
            .ikon-kotak { width: 38px; height: 38px; font-size: 1.05rem; }
            .col-aksi .tombol-aksi { font-size: .8rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .topbar { padding-top: .35rem !important; padding-bottom: .35rem !important; }
        }

        @media (min-width: 1400px) { .isi { max-width: 1200px !important; } }

        @media (prefers-reduced-motion: reduce) {
            .btn-emas, .side-link { transition: none; }
            .btn-emas:hover { transform: none; }
        }
    </style>
</head>

<body>
    @php
        $user       = auth()->user();
        $namaUser   = $user->name ?? ($user->username ?? 'Admin');
        $linkBarang = Route::has('barang.index') ? route('barang.index') : route('kategori.index');
    @endphp

    <div class="layout">

        <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu" aria-label="Menu admin">

            <div class="offcanvas-header justify-content-end pb-0 d-lg-none">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div class="sidebar-inner">

                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none text-reset">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                        <span class="brand-teks">InventarisBarang</span>
                    </a>

                    <div class="side-judul">Menu</div>

                    <nav class="nav flex-column gap-1" aria-label="Navigasi admin">
                        <a class="side-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a class="side-link" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                        <a class="side-link active" href="{{ $linkBarang }}" aria-current="page">
                            <i class="bi bi-box-seam"></i> Daftar Barang
                        </a>
                        <a class="side-link" href="{{ route('admin.riwayat') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                        <a class="side-link" href="{{ route('admin.log') }}">
                            <i class="bi bi-journal-text"></i> Log
                        </a>
                    </nav>

                    <div class="user-box">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar">{{ mb_strtoupper(mb_substr($namaUser, 0, 1)) }}</div>
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-truncate">{{ $namaUser }}</div>
                                <small class="text-body-secondary">Admin</small>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="form-logout">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </aside>

        <div class="content">

            <header class="topbar px-3 px-md-5 pb-3 d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3" style="min-width: 0;">
                    <button class="btn btn-outline-secondary btn-menu d-lg-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                            aria-controls="sidebarMenu" aria-label="Buka menu">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="overflow-hidden">
                        <div class="fw-semibold text-truncate">Panel Admin</div>
                        <div class="small text-body-secondary text-truncate">Manajemen inventaris barang</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="small text-body-secondary d-none d-sm-block">{{ now()->format('d/m/Y') }}</div>

                    <button type="button" id="btnTema" class="btn btn-outline-secondary btn-sm btn-tema"
                            aria-label="Ganti mode terang atau gelap">
                        <svg class="ikon-matahari" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                        <svg class="ikon-bulan" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                        <span id="labelTema" class="d-none d-sm-inline">Mode</span>
                    </button>
                </div>
            </header>

            <main class="flex-grow-1 px-3 px-md-5">
                <div class="isi mx-auto" style="max-width: 1120px;">

                    <section class="d-flex flex-wrap justify-content-between align-items-center gap-3 py-4 py-md-5">
                        <div style="min-width: 0;">
                            <h1>Data Barang</h1>
                            <p class="lead mt-3 mb-0">Lihat, tambah, dan atur barang beserta kategori dan statusnya.</p>
                        </div>

                        <button type="button" class="btn btn-emas tombol-tambah"
                                data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-lg me-2"></i> Tambah Barang
                        </button>
                    </section>

                    <section class="papan mb-5" aria-labelledby="judul-daftar">

                        <div class="papan-kepala">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="ikon-kotak"><i class="bi bi-box-seam-fill"></i></div>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h2 id="judul-daftar" class="h5 fw-bold mb-1">Daftar Barang</h2>
                                    <div class="small text-body-secondary">Data barang inventaris</div>
                                </div>

                                <span class="badge rounded-pill px-3 py-2 badge-jumlah">
                                    {{ $barang->total() }} barang
                                </span>
                            </div>
                        </div>

                        <div class="papan-isi">

                            <form method="GET" action="{{ route('barang.index') }}" class="row g-2 align-items-center mb-4">
                                <div class="col-12 col-md-5 col-lg-4">
                                    <div class="input-group">
                                        <span class="input-group-text input-cari"><i class="bi bi-search"></i></span>
                                        <input type="text" name="q" value="{{ request('q') }}" class="form-control input-cari"
                                               placeholder="Cari nama atau kode barang..." aria-label="Cari barang">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-3">
                                    <select name="kategori" class="form-select" aria-label="Filter kategori">
                                        <option value="">Semua kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id_kategori }}" @selected(request('kategori') == $k->id_kategori)>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-auto">
                                    <div class="cari-tombol d-md-flex gap-2">
                                        <button type="submit" class="btn btn-emas">
                                            <i class="bi bi-search me-1"></i> Cari
                                        </button>

                                        @if (request('q') || request('kategori'))
                                            <a href="{{ route('barang.index') }}"
                                               class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            @if ($barang->count())
                            
                                <div class="bar-seleksi d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <div class="form-check mb-0 d-flex align-items-center gap-2 ps-0">
                                        <input class="form-check-input m-0" type="checkbox" id="pilihSemua">
                                        <label class="form-check-label small" for="pilihSemua">Pilih semua di halaman ini</label>
                                    </div>

                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <small class="text-body-secondary"><span id="jumlahDipilih">0</span> dipilih</small>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBatalPilih" hidden>
                                            Batal pilih
                                        </button>
                                        <button type="button" class="btn btn-emas btn-sm" id="btnCetakTerpilih" disabled>
                                            <i class="bi bi-printer me-1"></i> Cetak terpilih
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover tabel-data">
                                    <thead>
                                        <tr>
                                            <th class="col-pilih"><span class="visually-hidden">Pilih</span></th>
                                            <th style="width: 75px;">No.</th>
                                            <th style="width: 150px;">Kode Barcode</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th style="width: 130px;">Status</th>
                                            <th style="width: 280px;" class="text-end">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($barang as $item)
                                            <tr>
                                                <td class="col-pilih">
                                                    <input type="checkbox" class="form-check-input cek-barang"
                                                           value="{{ $item->id_barang }}"
                                                           aria-label="Pilih {{ $item->nama_barang }}">
                                                </td>
                                                <td class="col-no"><span class="nomor">{{ $barang->firstItem() + $loop->index }}</span></td>
                                                <td class="col-kode"><span class="kode">{{ $item->kode_barcode }}</span></td>
                                                <td class="col-nama fw-semibold text-break">{{ $item->nama_barang }}</td>
                                                <td class="col-kategori">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                                <td class="col-status">
                                                    <span class="badge rounded-pill badge-status {{ $item->status_barang === 'Tersedia' ? 'b-hijau' : 'b-emas' }}">
                                                        {{ $item->status_barang }}
                                                    </span>
                                                </td>
                                                <td class="col-aksi text-end">
                                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">

                                                        <button type="button"
                                                                class="btn btn-outline-info tombol-aksi btn-cetak"
                                                                data-url="{{ route('barang.cetak', $item->id_barang) }}">
                                                            <i class="bi bi-upc-scan"></i> Cetak
                                                        </button>

                                                        <button type="button"
                                                                class="btn btn-outline-secondary tombol-aksi btn-edit"
                                                                data-url="{{ route('barang.update', $item->id_barang) }}"
                                                                data-nama="{{ $item->nama_barang }}"
                                                                data-kategori="{{ $item->id_kategori }}">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </button>

                                                        <button type="button"
                                                                class="btn btn-outline-danger tombol-aksi btn-hapus"
                                                                data-url="{{ route('barang.destroy', $item->id_barang) }}"
                                                                data-nama="{{ $item->nama_barang }}">
                                                            <i class="bi bi-trash3"></i> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="baris-kosong">
                                                <td colspan="6" class="text-center kosong">
                                                    <i class="bi bi-inbox"></i>
                                                    <div class="fw-bold mb-1">Belum ada data barang</div>
                                                    <div class="small">
                                                        @if (request('q') || request('kategori'))
                                                            Barang dengan filter tersebut tidak ditemukan.
                                                        @else
                                                            Klik tombol Tambah Barang untuk menambahkan data baru.
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($barang->hasPages())
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                                    <small class="text-body-secondary info-halaman">
                                        Menampilkan {{ $barang->firstItem() ?? 0 }}–{{ $barang->lastItem() ?? 0 }}
                                        dari {{ $barang->total() }} barang
                                    </small>

                                    <div class="nav-halaman">
                                        {{ $barang->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </section>

                </div>
            </main>

            <footer class="py-3 mt-auto px-3 px-md-5">
                <div class="isi mx-auto d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start" style="max-width: 1120px;">
                    <span>&copy; copyright 2026 smkn4padalarang | Lab PPLG PK</span>
                </div>
            </footer>

        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="judulModalTambah" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('barang.store') }}" id="formTambah" class="modal-content">
                @csrf

                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="judulModalTambah">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Barang
                        </h2>
                        <div class="small text-body-secondary mt-1">Tambahkan barang inventaris baru.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="tambahNama" class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" id="tambahNama" value="{{ old('nama_barang') }}"
                               class="form-control" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="tambahKategori" class="form-label fw-semibold">Kategori</label>
                        <select name="id_kategori" id="tambahKategori" class="form-select" required>
                            <option value="">-- Pilih kategori --</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}" @selected(old('id_kategori') == $k->id_kategori)>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <small class="text-body-secondary">
                        Kode barcode (mis. BRG-0001) dibuat otomatis dan status awal "Tersedia".
                    </small>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emas"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="judulModalEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="formEdit" class="modal-content">
                @csrf @method('PUT')

                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="judulModalEdit">
                            <i class="bi bi-pencil-square me-2"></i> Edit Barang
                        </h2>
                        <div class="small text-body-secondary mt-1">Ubah nama atau kategori barang.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="editNama" class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" id="editNama" class="form-control" maxlength="100" required>
                    </div>

                    <div class="mb-1">
                        <label for="editKategori" class="form-label fw-semibold">Kategori</label>
                        <select name="id_kategori" id="editKategori" class="form-select" required>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emas"><i class="bi bi-check-lg me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <form method="POST" id="formHapus" class="d-none">
        @csrf @method('DELETE')
    </form>

    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="judulLogout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="fs-1 mb-2" style="color: var(--emas);"><i class="bi bi-box-arrow-right"></i></div>
                    <h2 class="h5 fw-bold mb-2" id="judulLogout">Yakin akan logout?</h2>
                    <p class="text-body-secondary small mb-4">Kamu akan keluar dari panel admin dan kembali ke menu utama.</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-emas flex-fill" id="btnYaLogout">Ya, logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
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

        (function () {
            const modal = new bootstrap.Modal(document.getElementById('modalLogout'));
            let formLogout = null;

            document.querySelectorAll('.form-logout').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    formLogout = form;
                    modal.show();
                });
            });

            document.getElementById('btnYaLogout').addEventListener('click', () => {
                if (formLogout) formLogout.submit();
            });
        })();

        const temaPopup = () => {
            const css = getComputedStyle(document.documentElement);
            return {
                background: css.getPropertyValue('--kartu').trim(),
                color: css.getPropertyValue('--bs-body-color').trim(),
                confirmButtonColor: '#f1b53d',
            };
        };
        const popup = opsi => Swal.fire({ ...temaPopup(), ...opsi });

        @if (session('cetak_url'))
            popup({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                showCancelButton: true,
                confirmButtonText: 'Cetak barcode sekarang',
                cancelButtonText: 'Nanti saja',
            }).then(r => { if (r.isConfirmed) window.open(@json(session('cetak_url')), '_blank'); });
        @elseif (session('success'))
            popup({ icon: 'success', title: 'Berhasil', text: @json(session('success')) });
        @elseif (session('info'))
            popup({ icon: 'info', title: 'Info', text: @json(session('info')) });
        @elseif (session('error'))
            popup({ icon: 'error', title: 'Gagal', text: @json(session('error')) });
        @elseif ($errors->any())
            popup({ icon: 'error', title: 'Data tidak valid', text: @json($errors->first()) });
        @endif

        const formTambah = document.getElementById('formTambah');

        formTambah.addEventListener('submit', e => {
            e.preventDefault();
            popup({
                icon: 'question',
                title: 'Yakin ingin menambahkan?',
                text: document.getElementById('tambahNama').value,
                showCancelButton: true,
                confirmButtonText: 'Ya, tambah',
                cancelButtonText: 'Batal',
            }).then(r => { if (r.isConfirmed) formTambah.submit(); });
        });

        const formEdit = document.getElementById('formEdit');

        document.querySelectorAll('.btn-cetak').forEach(btn => {
            btn.addEventListener('click', () => window.open(btn.dataset.url, '_blank'));
        });

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                formEdit.action = btn.dataset.url;
                document.getElementById('editNama').value = btn.dataset.nama;
                document.getElementById('editKategori').value = btn.dataset.kategori;
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEdit')).show();
            });
        });

        formEdit.addEventListener('submit', e => {
            e.preventDefault();
            popup({
                icon: 'question',
                title: 'Yakin ingin mengupdate?',
                showCancelButton: true,
                confirmButtonText: 'Ya, update',
                cancelButtonText: 'Batal',
            }).then(r => { if (r.isConfirmed) formEdit.submit(); });
        });

        const formHapus = document.getElementById('formHapus');

        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', () => {
                popup({
                    icon: 'warning',
                    title: 'Yakin hapus barang?',
                    text: btn.dataset.nama,
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    customClass: { confirmButton: 'swal-merah' },
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Tidak',
                }).then(r => {
                    if (r.isConfirmed) {
                        formHapus.action = btn.dataset.url;
                        formHapus.submit();
                    }
                });
            });
        });

        (function () {
            const KUNCI = 'barcodeTerpilih';
            const MAKS = 100;
            const URL_CETAK = @json(route('barang.cetak-massal'));

            const semua = document.getElementById('pilihSemua');
            if (!semua) return;

            const cek = Array.from(document.querySelectorAll('.cek-barang'));
            const label = document.getElementById('jumlahDipilih');
            const btnCetak = document.getElementById('btnCetakTerpilih');
            const btnBatal = document.getElementById('btnBatalPilih');

            const baca = () => {
                try {
                    const data = JSON.parse(sessionStorage.getItem(KUNCI));
                    return new Set(Array.isArray(data) ? data.map(Number).filter(Number.isInteger) : []);
                } catch (e) { return new Set(); }
            };
            const simpan = () => {
                try { sessionStorage.setItem(KUNCI, JSON.stringify([...dipilih])); } catch (e) {}
            };
            const batas = () => popup({
                icon: 'info', title: 'Batas tercapai',
                text: `Maksimal ${MAKS} barang per sekali cetak.`,
            });

            const dipilih = baca();

            const render = () => {
                cek.forEach(c => {
                    const aktif = dipilih.has(Number(c.value));
                    c.checked = aktif;
                    c.closest('tr').classList.toggle('terpilih', aktif);
                });
                semua.checked = cek.length > 0 && cek.every(c => c.checked);
                semua.indeterminate = !semua.checked && cek.some(c => c.checked);
                label.textContent = dipilih.size;
                btnCetak.disabled = dipilih.size === 0;
                btnBatal.hidden = dipilih.size === 0;
            };

            document.querySelector('.tabel-data tbody').addEventListener('change', e => {
                const c = e.target.closest('.cek-barang');
                if (!c) return;

                const id = Number(c.value);
                if (c.checked && !dipilih.has(id) && dipilih.size >= MAKS) {
                    c.checked = false;
                    return batas();
                }
                c.checked ? dipilih.add(id) : dipilih.delete(id);
                simpan();
                render();
            });

            semua.addEventListener('change', () => {
                for (const c of cek) {
                    const id = Number(c.value);
                    if (!semua.checked) { dipilih.delete(id); continue; }
                    if (!dipilih.has(id) && dipilih.size >= MAKS) { batas(); break; }
                    dipilih.add(id);
                }
                simpan();
                render();
            });

            btnBatal.addEventListener('click', () => {
                dipilih.clear();
                simpan();
                render();
            });

            btnCetak.addEventListener('click', () => {
                if (!dipilih.size) return;
                const query = new URLSearchParams();
                dipilih.forEach(id => query.append('ids[]', id));
                window.open(`${URL_CETAK}?${query}`, '_blank');
            });

            render();
        })();
    </script>
</body>
</html>