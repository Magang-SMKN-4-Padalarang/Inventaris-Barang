<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kategori Barang - Inventaris Barang</title>
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
            --sukses: #0f7a4d;
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
            --sukses: #4fd69c;
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
        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            border-radius: 10px;
            padding: 11px 20px;
            min-height: 44px;
            font-weight: 700;
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

        .ikon-kotak {
            width: 44px; height: 44px; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 12px; font-size: 1.25rem;
            background: color-mix(in srgb, var(--biru) 14%, transparent);
            color: var(--biru);
        }
        .ikon-kecil { width: 38px; height: 38px; border-radius: 10px; font-size: 1rem; }

        .badge-jumlah { background: var(--hover); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); font-weight: 600; }

        .input-cari, .form-control {
            min-height: 44px;
            background-color: var(--kartu);
            border-color: var(--bs-border-color);
            color: var(--bs-body-color);
        }
        .input-cari:focus, .form-control:focus {
            background-color: var(--kartu);
            color: var(--bs-body-color);
            border-color: var(--biru);
            box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 20%, transparent);
        }
        .form-control { border-radius: 10px; }
        .input-group > .input-group-text.input-cari { border-radius: 10px 0 0 10px; color: var(--bs-secondary-color); }
        .input-group > .form-control.input-cari { border-radius: 0 10px 10px 0; }

        .tabel-kategori {
            margin-bottom: 0; vertical-align: middle;
            --bs-table-bg: transparent; --bs-table-color: var(--bs-body-color);
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-hover-bg: var(--hover); --bs-table-hover-color: var(--bs-body-color);
        }
        .tabel-kategori thead th { padding: 14px 16px; color: var(--bs-secondary-color); background: var(--hover); font-size: .85rem; font-weight: 700; white-space: nowrap; border-bottom: 1px solid var(--bs-border-color); }
        .tabel-kategori tbody td { padding: 16px; border-color: var(--bs-border-color); }

        .nomor { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 10px; background: var(--hover); font-weight: 700; font-size: .85rem; font-variant-numeric: tabular-nums; }
        .jumlah-barang { display: inline-flex; min-width: 38px; justify-content: center; padding: 6px 11px; border-radius: 8px; background: color-mix(in srgb, var(--biru) 13%, transparent); color: var(--biru); font-weight: 700; font-size: .85rem; font-variant-numeric: tabular-nums; }
        .tombol-aksi { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-height: 38px; padding: 7px 14px; font-size: .85rem; font-weight: 600; }

        .kosong { padding: 48px 20px !important; color: var(--bs-secondary-color); }
        .kosong i { display: block; font-size: 2.5rem; margin-bottom: 12px; opacity: .65; }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .modal-header, .modal-footer { border-color: var(--bs-border-color); }
        [data-bs-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        /* Popup sukses */
        .ikon-sukses {
            width: 72px; height: 72px; margin: 0 auto 16px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 2.25rem;
            color: var(--sukses);
            background: color-mix(in srgb, var(--sukses) 14%, transparent);
            animation: muncul .35s ease-out;
        }
        @keyframes muncul {
            from { transform: scale(.6); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }

        .pagination { gap: 5px; margin-bottom: 0; flex-wrap: wrap; }
        .page-link { min-width: 40px; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; color: var(--bs-body-color); background: var(--kartu); border-color: var(--bs-border-color); border-radius: 8px !important; }
        .page-item.active .page-link { color: #1a1405; background: var(--emas); border-color: var(--emas); }
        .page-item.disabled .page-link { color: var(--bs-secondary-color); background: var(--hover); }

        .alert { border-radius: 12px; }

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

            .tabel-kategori thead { display: none; }
            .tabel-kategori, .tabel-kategori tbody { display: block; width: 100%; }

            .tabel-kategori tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: auto 1fr;
                grid-template-areas: "no nama" "no jumlah" "aksi aksi";
                column-gap: 14px; row-gap: 8px;
                padding: 16px 4px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-kategori tbody tr:not(.baris-kosong):last-child { border-bottom: 0; }
            .tabel-kategori tbody tr:not(.baris-kosong) td { display: block; padding: 0; border: 0; width: auto !important; }

            .col-no { grid-area: no; align-self: start; }
            .col-nama { grid-area: nama; min-width: 0; }
            .col-jumlah { grid-area: jumlah; }
            .col-aksi { grid-area: aksi; text-align: left !important; margin-top: 6px; }
            .col-aksi > div { display: flex !important; width: 100%; }
            .col-aksi .tombol-aksi { flex: 1 1 0; min-height: 44px; }

            .tabel-kategori tbody tr.baris-kosong { display: table-row; }
            .tabel-kategori tbody tr.baris-kosong td { display: table-cell; }

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
            .tombol-aksi { padding: 7px 8px; font-size: .8rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .topbar { padding-top: .35rem !important; padding-bottom: .35rem !important; }
        }

        @media (min-width: 1400px) {
            .isi { max-width: 1200px !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            .btn-emas, .side-link { transition: none; }
            .btn-emas:hover { transform: none; }
            .ikon-sukses { animation: none; }
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

                    <a href="{{ route('dashboard') }}"
                       class="d-flex align-items-center gap-2 text-decoration-none text-reset">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                        <span class="brand-teks">InventarisBarang</span>
                    </a>

                    <div class="side-judul">Menu</div>

                    <nav class="nav flex-column gap-1" aria-label="Navigasi admin">
                        <a class="side-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a class="side-link active" href="{{ route('kategori.index') }}" aria-current="page">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                        <a class="side-link" href="{{ $linkBarang }}">
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

                    {{-- Judul halaman --}}
                    <section class="d-flex flex-wrap justify-content-between align-items-center gap-3 py-4 py-md-5">
                        <div style="min-width: 0;">
                            <h1>Kategori Barang</h1>
                            <p class="lead mt-3 mb-0">Kelola kategori dan lihat jumlah barang pada setiap kategori.</p>
                        </div>

                        <button type="button" class="btn btn-emas tombol-tambah"
                                data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
                        </button>
                    </section>

                    {{-- Notifikasi error / info (pesan sukses ditampilkan sebagai popup) --}}
                    @foreach (['error' => 'danger', 'info' => 'info'] as $key => $warna)
                        @if (session($key))
                            <div class="alert alert-{{ $warna }} alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                {{ session($key) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        @endif
                    @endforeach

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif

                    {{-- Daftar kategori --}}
                    <section class="papan mb-5" aria-labelledby="judul-daftar">

                        <div class="papan-kepala">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="ikon-kotak"><i class="bi bi-collection-fill"></i></div>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h2 id="judul-daftar" class="h5 fw-bold mb-1">Daftar Kategori</h2>
                                    <div class="small text-body-secondary">Data kategori barang inventaris</div>
                                </div>

                                <span class="badge rounded-pill px-3 py-2 badge-jumlah">
                                    {{ $kategori->total() }} kategori
                                </span>
                            </div>
                        </div>

                        <div class="papan-isi">

                            {{-- Pencarian --}}
                            <form method="GET" action="{{ route('kategori.index') }}" class="row g-2 align-items-center mb-4">
                                <div class="col-12 col-md-6 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-text input-cari"><i class="bi bi-search"></i></span>
                                        <input type="text" name="q" value="{{ request('q') }}"
                                               class="form-control input-cari"
                                               placeholder="Cari nama kategori..." aria-label="Cari kategori">
                                    </div>
                                </div>

                                <div class="col-12 col-md-auto">
                                    <div class="cari-tombol d-md-flex gap-2">
                                        <button type="submit" class="btn btn-emas">
                                            <i class="bi bi-search me-1"></i> Cari
                                        </button>

                                        @if (request('q'))
                                            <a href="{{ route('kategori.index') }}"
                                               class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            {{-- Tabel (jadi daftar kartu di HP) --}}
                            <div class="table-responsive">
                                <table class="table table-hover tabel-kategori">
                                    <thead>
                                        <tr>
                                            <th style="width: 75px;">No.</th>
                                            <th>Nama Kategori</th>
                                            <th style="width: 155px;">Jumlah Barang</th>
                                            <th style="width: 200px;" class="text-end">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($kategori as $item)
                                            <tr>
                                                <td class="col-no">
                                                    <span class="nomor">{{ $kategori->firstItem() + $loop->index }}</span>
                                                </td>

                                                <td class="col-nama">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="ikon-kotak ikon-kecil d-none d-md-inline-flex">
                                                            <i class="bi bi-box-seam"></i>
                                                        </div>
                                                        <span class="fw-semibold text-break">{{ $item->nama_kategori }}</span>
                                                    </div>
                                                </td>

                                                <td class="col-jumlah">
                                                    <span class="jumlah-barang">{{ $item->barang_count }}</span>
                                                    <span class="small text-body-secondary ms-1">barang</span>
                                                </td>

                                                <td class="col-aksi text-end">
                                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                        <button type="button"
                                                                class="btn btn-outline-secondary tombol-aksi btn-edit"
                                                                data-url="{{ route('kategori.update', $item->id_kategori) }}"
                                                                data-nama="{{ $item->nama_kategori }}">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </button>

                                                        <button type="button"
                                                                class="btn btn-outline-danger tombol-aksi btn-hapus"
                                                                data-url="{{ route('kategori.destroy', $item->id_kategori) }}"
                                                                data-nama="{{ $item->nama_kategori }}">
                                                            <i class="bi bi-trash3"></i> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="baris-kosong">
                                                <td colspan="4" class="text-center kosong">
                                                    <i class="bi bi-inbox"></i>
                                                    <div class="fw-bold mb-1">Belum ada data kategori</div>
                                                    <div class="small">
                                                        @if (request('q'))
                                                            Kategori dengan kata kunci tersebut tidak ditemukan.
                                                        @else
                                                            Klik tombol Tambah Kategori untuk menambahkan data baru.
                                                        @endif
                                                    </div>

                                                    @if (request('q'))
                                                        <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary btn-sm mt-3">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset pencarian
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($kategori->hasPages())
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                                    <small class="text-body-secondary info-halaman">
                                        Menampilkan
                                        {{ $kategori->firstItem() ?? 0 }}–{{ $kategori->lastItem() ?? 0 }}
                                        dari {{ $kategori->total() }} kategori
                                    </small>

                                    <div class="nav-halaman">
                                        {{ $kategori->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </section>

                </div>
            </main>

            <footer class="py-3 mt-auto px-3 px-md-5">
                <div class="isi mx-auto d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start" style="max-width: 1120px;">
                    <span>&copy;2026 smkn4padalarang | Lab PPLG PK</span>
                </div>
            </footer>

        </div>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="judulModalTambah" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('kategori.store') }}" class="modal-content">
                @csrf

                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="judulModalTambah">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Kategori
                        </h2>
                        <div class="small text-body-secondary mt-1">Tambahkan kategori barang baru.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <label for="namaKategori" class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="namaKategori" class="form-control"
                           maxlength="100" placeholder="Contoh: Elektronik" required>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emas">
                        <i class="bi bi-check-lg me-1"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="judulModalEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="formEdit" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="judulModalEdit">
                            <i class="bi bi-pencil-square me-2"></i> Edit Kategori
                        </h2>
                        <div class="small text-body-secondary mt-1">Ubah nama kategori barang.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <label for="editNama" class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="editNama" class="form-control" maxlength="100" required>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emas">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulModalHapus" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form method="POST" id="formHapus" class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-body text-center p-4">
                    <div class="ikon-kotak mx-auto mb-3"
                         style="width: 58px; height: 58px; color: var(--merah); background: color-mix(in srgb, var(--merah) 12%, transparent);">
                        <i class="bi bi-trash3"></i>
                    </div>

                    <h2 class="h5 fw-bold mb-2" id="judulModalHapus">Hapus Kategori?</h2>
                    <p class="text-body-secondary small mb-1">Apakah kamu yakin ingin menghapus kategori ini?</p>
                    <p class="fw-bold mb-4 text-break" id="hapusNama"></p>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger flex-fill" style="border-radius: 10px;">
                            <i class="bi bi-trash3 me-1"></i> Ya, Hapus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Popup sukses (tambah / edit / hapus) --}}
    @if (session('success'))
        <div class="modal fade" id="modalSukses" tabindex="-1" aria-labelledby="judulSukses" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="ikon-sukses"><i class="bi bi-check-lg"></i></div>
                        <h2 class="h5 fw-bold mb-2" id="judulSukses">Berhasil!</h2>
                        <p class="text-body-secondary mb-4" role="status">{{ session('success') }}</p>
                        <button type="button" class="btn btn-emas w-100" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal konfirmasi logout --}}
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

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-edit').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('formEdit').action = this.dataset.url;
                    document.getElementById('editNama').value = this.dataset.nama;
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEdit')).show();
                });
            });

            document.querySelectorAll('.btn-hapus').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('formHapus').action = this.dataset.url;
                    document.getElementById('hapusNama').textContent = this.dataset.nama;
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalHapus')).show();
                });
            });

            // Popup sukses: tampil otomatis, tutup sendiri setelah 3 detik
            const elSukses = document.getElementById('modalSukses');
            if (elSukses) {
                const popup = bootstrap.Modal.getOrCreateInstance(elSukses);
                popup.show();
                const timer = setTimeout(() => popup.hide(), 3000);
                elSukses.addEventListener('hidden.bs.modal', () => clearTimeout(timer), { once: true });
            }
        });
    </script>
</body>
</html>