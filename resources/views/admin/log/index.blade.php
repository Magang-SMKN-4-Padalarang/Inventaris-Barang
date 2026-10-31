<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Log - Inventaris Barang</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    {{-- Terapkan tema tersimpan sebelum render agar tidak berkedip --}}
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
    <link href="	{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
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

       
        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
            border-radius: 10px;
        }
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

        .tab-log { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .tab-link {
            display: inline-flex; align-items: center; gap: 10px;
            min-height: 44px; padding: 10px 18px; border-radius: 10px;
            color: var(--bs-secondary-color); text-decoration: none; font-weight: 600;
            background: var(--kartu); border: 1px solid var(--bs-border-color);
            transition: background .2s, color .2s;
        }
        .tab-link:hover { color: var(--bs-body-color); background: var(--hover); }
        .tab-link.active { color: var(--bs-body-color); background: var(--hover); box-shadow: inset 0 -3px 0 var(--emas); }

        .form-control, .form-select { min-height: 44px; background-color: var(--kartu); border-color: var(--bs-border-color); color: var(--bs-body-color); border-radius: 10px; }
        .form-control:focus, .form-select:focus { background-color: var(--kartu); color: var(--bs-body-color); border-color: var(--biru); box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 20%, transparent); }
        [data-bs-theme="dark"] input[type="date"] { color-scheme: dark; }
        .form-label { font-size: .8rem; font-weight: 600; color: var(--bs-secondary-color); margin-bottom: .35rem; }

        .tabel-data { margin-bottom: 0; vertical-align: middle; --bs-table-bg: transparent; --bs-table-color: var(--bs-body-color); --bs-table-border-color: var(--bs-border-color); --bs-table-hover-bg: var(--hover); --bs-table-hover-color: var(--bs-body-color); }
        .tabel-data thead th { padding: 14px 16px; color: var(--bs-secondary-color); background: var(--hover); font-size: .85rem; font-weight: 700; white-space: nowrap; border-bottom: 1px solid var(--bs-border-color); }
        .tabel-data tbody td { padding: 16px; border-color: var(--bs-border-color); }
        .waktu { font-variant-numeric: tabular-nums; white-space: nowrap; }
        .kode { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: .85rem; padding: 4px 8px; border-radius: 8px; background: var(--hover); border: 1px solid var(--bs-border-color); }

        .badge-status { font-weight: 600; border: 1px solid; padding: .45em .85em; }
        .b-hijau { color: var(--ada); background: color-mix(in srgb, var(--ada) 12%, transparent); border-color: color-mix(in srgb, var(--ada) 40%, transparent); }
        .b-emas { color: var(--emas-teks); background: color-mix(in srgb, var(--emas-teks) 12%, transparent); border-color: color-mix(in srgb, var(--emas-teks) 40%, transparent); }
        .b-merah { color: var(--telat); background: color-mix(in srgb, var(--telat) 12%, transparent); border-color: color-mix(in srgb, var(--telat) 40%, transparent); }
        .b-biru { color: var(--biru); background: color-mix(in srgb, var(--biru) 12%, transparent); border-color: color-mix(in srgb, var(--biru) 40%, transparent); }
        .b-abu { color: var(--bs-secondary-color); background: var(--hover); border-color: var(--bs-border-color); }

        .kosong { padding: 48px 20px !important; color: var(--bs-secondary-color); }
        .kosong i { display: block; font-size: 2.5rem; margin-bottom: 12px; opacity: .65; }

        .pagination { gap: 5px; margin-bottom: 0; flex-wrap: wrap; }
        .page-link { min-width: 40px; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; color: var(--bs-body-color); background: var(--kartu); border-color: var(--bs-border-color); border-radius: 8px !important; }
        .page-item.active .page-link { color: #1a1405; background: var(--emas); border-color: var(--emas); }
        .page-item.disabled .page-link { color: var(--bs-secondary-color); background: var(--hover); }
        .alert { border-radius: 12px; }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
            .papan-kepala, .papan-isi { padding: 20px; }
        }

        @media screen and (max-width: 767.98px) {
            .papan { border-radius: 14px; }
            .papan-kepala, .papan-isi { padding: 16px; }
            .topbar { padding-left: max(1rem, env(safe-area-inset-left)) !important; padding-right: max(1rem, env(safe-area-inset-right)) !important; }

            .tab-link { flex: 1 1 0; justify-content: center; padding: 10px 12px; }

            .tabel-data thead { display: none; }
            .tabel-data, .tabel-data tbody { display: block; width: 100%; }

            .tabel-data tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: 1fr auto;
                column-gap: 12px; row-gap: 4px;
                padding: 16px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-data tbody tr:not(.baris-kosong):last-child { border-bottom: 0; }
            .tabel-data tbody tr:not(.baris-kosong) td { display: block; padding: 0; border: 0; }

            .tabel-aktivitas tbody tr:not(.baris-kosong) { grid-template-areas: "waktu aksi" "aktor aktor" "deskripsi deskripsi" "ip ip"; }
            .tabel-keamanan tbody tr:not(.baris-kosong) { grid-template-areas: "waktu hasil" "user user" "ip ip"; }

            .col-waktu { grid-area: waktu; font-size: .9rem; color: var(--bs-secondary-color); align-self: center; }
            .col-aksi { grid-area: aksi; align-self: start; }
            .col-hasil { grid-area: hasil; align-self: start; text-align: right; }
            .col-aktor { grid-area: aktor; margin-top: 8px; }
            .col-user { grid-area: user; margin-top: 8px; font-weight: 700; min-width: 0; overflow-wrap: anywhere; }
            .col-deskripsi { grid-area: deskripsi; margin-top: 4px; overflow-wrap: anywhere; }
            .col-ip { grid-area: ip; margin-top: 8px; }
            .col-ip::before { content: "IP: "; color: var(--bs-secondary-color); font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

            .tabel-data tbody tr.baris-kosong { display: table-row; }
            .tabel-data tbody tr.baris-kosong td { display: table-cell; }
        }

        @media (max-width: 399.98px) {
            .logo { width: 44px; height: 44px; }
            .brand-teks { font-size: 1.1rem; }
            .papan { border-radius: 12px; }
            .papan-kepala, .papan-isi { padding: 14px; }
            .ikon-kotak { width: 38px; height: 38px; font-size: 1.05rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .topbar { padding-top: .35rem !important; padding-bottom: .35rem !important; }
        }

        @media (min-width: 1400px) { .isi { max-width: 1200px !important; } }

        @media (prefers-reduced-motion: reduce) {
            .btn-emas, .side-link, .tab-link { transition: none; }
            .btn-emas:hover { transform: none; }
        }
    </style>
</head>

<body>
    @php
        $user       = auth()->user();
        $namaUser   = $user->name ?? ($user->username ?? 'Admin');
        $linkBarang = Route::has('barang.index') ? route('barang.index') : route('kategori.index');

        $adaFilter  = collect($filter)->filter()->isNotEmpty();
        $urlTab     = route('admin.log', ['tab' => $tab]);

        // Warna badge untuk setiap aksi / aktor / hasil
        $kelasAksi  = [
            'tambah'        => 'b-hijau',
            'ubah'          => 'b-emas',
            'hapus'         => 'b-merah',
            'pinjam'        => 'b-emas',
            'kembali'       => 'b-hijau',
            'ekspor'        => 'b-biru',
            'cetak_barcode' => 'b-biru',
        ];
        $kelasAktor = ['admin' => 'b-biru', 'peminjam' => 'b-emas', 'sistem' => 'b-abu'];
        $kelasHasil = ['login_berhasil' => 'b-hijau', 'login_gagal' => 'b-merah', 'logout' => 'b-abu'];
        $labelHasil = ['login_berhasil' => 'Berhasil', 'login_gagal' => 'Gagal', 'logout' => 'Logout'];
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
                        <a class="side-link" href="{{ $linkBarang }}">
                            <i class="bi bi-box-seam"></i> Daftar Barang
                        </a>
                        <a class="side-link" href="{{ route('admin.riwayat') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                        <a class="side-link active" href="{{ route('admin.log') }}" aria-current="page">
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
                    <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 py-4 py-md-5">
                        <div style="min-width: 0;">
                            <h1>Log</h1>
                            <p class="lead mt-3 mb-0">Pantau aktivitas pengguna dan riwayat login ke sistem.</p>
                        </div>
                    </section>

                    {{-- Pesan filter tidak valid --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $pesan)
                                <div>{{ $pesan }}</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tab --}}
                    <nav class="tab-log" aria-label="Jenis log">
                        <a href="{{ route('admin.log', ['tab' => 'aktivitas']) }}"
                           class="tab-link {{ $tab === 'aktivitas' ? 'active' : '' }}"
                           @if ($tab === 'aktivitas') aria-current="page" @endif>
                            <i class="bi bi-activity"></i> Aktivitas
                        </a>
                        <a href="{{ route('admin.log', ['tab' => 'keamanan']) }}"
                           class="tab-link {{ $tab === 'keamanan' ? 'active' : '' }}"
                           @if ($tab === 'keamanan') aria-current="page" @endif>
                            <i class="bi bi-shield-lock"></i> Keamanan (Login)
                        </a>
                    </nav>

                    <section class="papan mb-5" aria-labelledby="judul-daftar">

                        <div class="papan-kepala">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="ikon-kotak">
                                    <i class="bi {{ $tab === 'aktivitas' ? 'bi-activity' : 'bi-shield-lock' }}"></i>
                                </div>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h2 id="judul-daftar" class="h5 fw-bold mb-1">
                                        {{ $tab === 'aktivitas' ? 'Log Aktivitas' : 'Log Keamanan' }}
                                    </h2>
                                    <div class="small text-body-secondary">
                                        {{ $tab === 'aktivitas'
                                            ? 'Perubahan data, peminjaman, pengembalian, dan pencetakan'
                                            : 'Percobaan login dan logout admin' }}
                                    </div>
                                </div>

                                <span class="badge rounded-pill px-3 py-2 badge-jumlah">
                                    {{ $data->total() }} log
                                </span>
                            </div>
                        </div>

                        <div class="papan-isi">

                            {{-- Filter --}}
                            <form method="GET" action="{{ route('admin.log') }}" class="row g-2 align-items-end mb-4">
                                <input type="hidden" name="tab" value="{{ $tab }}">

                                <div class="col-6 col-md-3 col-lg-2">
                                    <label for="fDari" class="form-label">Dari tanggal</label>
                                    <input type="date" id="fDari" name="dari" value="{{ $filter['dari'] ?? '' }}" class="form-control">
                                </div>

                                <div class="col-6 col-md-3 col-lg-2">
                                    <label for="fSampai" class="form-label">Sampai tanggal</label>
                                    <input type="date" id="fSampai" name="sampai" value="{{ $filter['sampai'] ?? '' }}" class="form-control">
                                </div>

                                @if ($tab === 'aktivitas')
                                    <div class="col-6 col-md-3 col-lg-2">
                                        <label for="fAksi" class="form-label">Aksi</label>
                                        <select id="fAksi" name="aksi" class="form-select">
                                            <option value="">Semua aksi</option>
                                            @foreach ($opsiAksi as $nilai => $label)
                                                <option value="{{ $nilai }}" @selected(($filter['aksi'] ?? '') === $nilai)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-3 col-lg-2">
                                        <label for="fAktor" class="form-label">Aktor</label>
                                        <select id="fAktor" name="aktor" class="form-select">
                                            <option value="">Semua aktor</option>
                                            @foreach ($opsiAktor as $nilai => $label)
                                                <option value="{{ $nilai }}" @selected(($filter['aktor'] ?? '') === $nilai)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="fHasil" class="form-label">Hasil</label>
                                        <select id="fHasil" name="hasil" class="form-select">
                                            <option value="">Semua hasil</option>
                                            @foreach ($opsiHasil as $nilai => $label)
                                                <option value="{{ $nilai }}" @selected(($filter['hasil'] ?? '') === $nilai)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-12 col-lg-auto">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-emas flex-fill">
                                            <i class="bi bi-funnel me-1"></i> Terapkan
                                        </button>

                                        @if ($adaFilter)
                                            <a href="{{ $urlTab }}"
                                               class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center flex-fill"
                                               style="min-height: 44px;">
                                                Reset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">

                                @if ($tab === 'aktivitas')
                                    <table class="table table-hover tabel-data tabel-aktivitas">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Aktor</th>
                                                <th>Aksi</th>
                                                <th>Deskripsi</th>
                                                <th>IP</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($data as $item)
                                                <tr>
                                                    <td class="col-waktu waktu">{{ $item->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                                                    <td class="col-aktor">
                                                        <span class="badge rounded-pill badge-status {{ $kelasAktor[$item->aktor] ?? 'b-abu' }}">
                                                            {{ $opsiAktor[$item->aktor] ?? $item->aktor }}
                                                        </span>
                                                        @if ($item->aktor === 'admin' && $item->admin)
                                                            <div class="small text-body-secondary mt-1">{{ $item->admin->username }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="col-aksi">
                                                        <span class="badge rounded-pill badge-status {{ $kelasAksi[$item->aksi] ?? 'b-abu' }}">
                                                            {{ $opsiAksi[$item->aksi] ?? $item->aksi }}
                                                        </span>
                                                    </td>
                                                    <td class="col-deskripsi">{{ $item->deskripsi }}</td>
                                                    <td class="col-ip"><span class="kode">{{ $item->ip_address ?? '-' }}</span></td>
                                                </tr>
                                            @empty
                                                <tr class="baris-kosong">
                                                    <td colspan="5" class="text-center kosong">
                                                        <i class="bi bi-inbox"></i>
                                                        <div class="fw-bold mb-1">
                                                            {{ $adaFilter ? 'Tidak ada log yang cocok' : 'Belum ada log aktivitas' }}
                                                        </div>
                                                        <div class="small">
                                                            {{ $adaFilter ? 'Coba ubah atau reset filter.' : 'Aktivitas akan muncul di sini setelah ada perubahan data atau peminjaman.' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table table-hover tabel-data tabel-keamanan">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Username Diketik</th>
                                                <th>Hasil</th>
                                                <th>IP</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($data as $item)
                                                <tr>
                                                    <td class="col-waktu waktu">{{ $item->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                                                    <td class="col-user fw-semibold">{{ $item->username_input }}</td>
                                                    <td class="col-hasil">
                                                        <span class="badge rounded-pill badge-status {{ $kelasHasil[$item->aksi] ?? 'b-abu' }}">
                                                            {{ $labelHasil[$item->aksi] ?? $item->aksi }}
                                                        </span>
                                                        @if ($item->keterangan && $item->aksi === 'login_gagal')
                                                            <div class="small text-body-secondary mt-1">{{ $item->keterangan }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="col-ip">
                                                        <span class="kode" @if ($item->user_agent) title="{{ $item->user_agent }}" @endif>{{ $item->ip_address ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="baris-kosong">
                                                    <td colspan="4" class="text-center kosong">
                                                        <i class="bi bi-inbox"></i>
                                                        <div class="fw-bold mb-1">
                                                            {{ $adaFilter ? 'Tidak ada log yang cocok' : 'Belum ada log login' }}
                                                        </div>
                                                        <div class="small">
                                                            {{ $adaFilter ? 'Coba ubah atau reset filter.' : 'Percobaan login dan logout akan muncul di sini.' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @endif

                            </div>

                            @if ($data->hasPages())
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                                    <small class="text-body-secondary">
                                        Menampilkan {{ $data->firstItem() ?? 0 }}–{{ $data->lastItem() ?? 0 }}
                                        dari {{ $data->total() }} log
                                    </small>

                                    <div>
                                        {{ $data->links('pagination::bootstrap-5') }}
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
    </script>
</body>
</html>