<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Daftar Barang - Inventaris Barang</title>
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
            --aksen: #1d4fa8;
            --emas-teks: #a56d00;
            --sukses: #0f7a4d;
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
            --aksen: #7fb0ff;
            --emas-teks: #f1b53d;
            --sukses: #4fd69c;
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
        .navbar .nav-link:hover, .navbar .nav-link.aktif { color: var(--bs-body-color); border-color: var(--emas); }
        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
        }
        .btn-tema, .btn-kembali { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            font-weight: 700;
        }

        h1 { font-size: clamp(1.75rem, 6vw, 2.6rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.1; overflow-wrap: break-word; }

        .filter-bar .form-control,
        .filter-bar .form-select,
        .filter-bar .input-group-text { background-color: var(--input); border-color: var(--bs-border-color); min-height: 44px; }
        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus { border-color: var(--aksen); box-shadow: 0 0 0 .2rem rgba(110, 168, 255, .25); }
        .filter-bar .form-select option { background-color: var(--kartu); color: var(--bs-body-color); }
        .filter-bar .btn { min-height: 44px; }

        .table-card { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; overflow: hidden; }
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--bs-body-color);
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-hover-bg: var(--hover);
            --bs-table-hover-color: var(--bs-body-color);
        }
        .table thead th {
            color: var(--bs-secondary-color);
            font-size: .85rem;
            font-weight: 700;
            padding: 16px;
            border-bottom: 2px solid var(--bs-border-color);
            white-space: nowrap;
        }
        .table tbody td { padding: 14px 16px; }
        .table tbody tr[hidden] { display: none !important; }

        /* ===== Foto barang ===== */
        .foto-thumb {
            display: inline-flex; align-items: center; justify-content: center;
            width: 72px; height: 72px; padding: 0; overflow: hidden; flex-shrink: 0; vertical-align: middle;
            border-radius: 12px; border: 1px solid var(--bs-border-color);
            background: var(--hover); color: var(--bs-secondary-color); font-size: 1.6rem;
        }
        .foto-thumb-md { width: 64px; height: 64px; font-size: 1.35rem; }
        button.foto-thumb { cursor: zoom-in; transition: border-color .2s; }
        button.foto-thumb:hover { border-color: var(--aksen); }
        .foto-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .foto-thumb-kosong { border-style: dashed; }
        .foto-besar { max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 12px; }

        .nomor { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 10px; background: var(--hover); font-weight: 700; font-size: .85rem; font-variant-numeric: tabular-nums; }
        .total-unit { font-size: 1.4rem; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; letter-spacing: -.02em; }
        .total-unit small { margin-left: 4px; font-size: .8rem; font-weight: 500; letter-spacing: 0; color: var(--bs-secondary-color); }
        .stok-chip { font-size: .78rem; padding: .35em .7em; }

        /* ===== Kolom Merk/Type ===== */
        .merk-wrap { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 6px; }
        .merk-chip {
            display: inline-flex; align-items: center; gap: 4px;
            padding: .15em .65em; font-size: .78rem; font-weight: 600; line-height: 1.5;
            border-radius: 999px; color: var(--bs-secondary-color);
            background: var(--hover); border: 1px solid var(--bs-border-color);
            overflow-wrap: anywhere;
        }
        .merk-kosong { color: var(--bs-secondary-color); opacity: .7; }

        .badge-status { font-weight: 700; border: 1px solid; }
        .badge-tersedia { background: rgba(79, 214, 156, .12); border-color: rgba(79, 214, 156, .4); color: var(--sukses); }
        .badge-dipinjam { background: rgba(241, 181, 61, .12); border-color: rgba(241, 181, 61, .45); color: var(--emas-teks); }

        /* ===== Detail ===== */
        .btn-detail { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-height: 38px; padding: 7px 14px; font-size: .85rem; font-weight: 600; border-radius: 10px; }
        .badge-jumlah { background: var(--hover); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); font-weight: 600; }
        .detail-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .detail-meta .badge { font-size: .78rem; padding: .4em .75em; }
        .kode { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: .85rem; padding: 4px 8px; border-radius: 8px; background: var(--hover); border: 1px solid var(--bs-border-color); }
        .tabel-unit { margin-bottom: 0; }
        .tabel-unit thead th { background: var(--hover); }
        .kosong-unit { padding: 40px 20px !important; color: var(--bs-secondary-color); text-align: center; }
        .kosong-unit i { display: block; font-size: 2.2rem; margin-bottom: 10px; opacity: .65; }
        .input-cari { min-height: 44px; background-color: var(--kartu); border-color: var(--bs-border-color); color: var(--bs-body-color); }
        .input-cari:focus { background-color: var(--kartu); color: var(--bs-body-color); border-color: var(--aksen); box-shadow: 0 0 0 .2rem rgba(110, 168, 255, .25); }
        .input-group > .input-group-text.input-cari { color: var(--bs-secondary-color); }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .modal-header, .modal-footer { border-color: var(--bs-border-color); }
        [data-bs-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        .pagination {
            --bs-pagination-bg: transparent;
            --bs-pagination-color: var(--aksen);
            --bs-pagination-border-color: var(--bs-border-color);
            --bs-pagination-hover-bg: var(--hover);
            --bs-pagination-hover-color: var(--bs-body-color);
            --bs-pagination-hover-border-color: var(--bs-border-color);
            --bs-pagination-focus-bg: var(--hover);
            --bs-pagination-active-bg: var(--emas);
            --bs-pagination-active-border-color: var(--emas);
            --bs-pagination-active-color: #1a1405;
            --bs-pagination-disabled-bg: transparent;
            --bs-pagination-disabled-color: var(--bs-secondary-color);
            --bs-pagination-disabled-border-color: var(--bs-border-color);
            flex-wrap: wrap;
            margin-bottom: 0;
        }
        .page-link { min-width: 40px; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        @media (max-width: 991.98px) {
            .table thead th { padding: 12px; }
            .table tbody td { padding: 12px; }
        }

        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover, .navbar .nav-link.aktif { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            .btn-kembali { width: 100%; }

            .filter-bar .form-control, .filter-bar .form-select { font-size: 1rem; }

            .table-card { border-radius: 12px; }
            .tabel-barang, .tabel-barang tbody, .tabel-barang tr, .tabel-barang td { display: block; width: 100%; }
            .tabel-barang thead { display: none; }
            .tabel-barang tr { padding: .9rem 1rem; border-bottom: 1px solid var(--bs-border-color); }
            .tabel-barang tr:last-child { border-bottom: 0; }
            .tabel-barang td {
                display: flex; justify-content: space-between; align-items: center; gap: 1rem;
                padding: .3rem 0; border: 0; text-align: right; box-shadow: none;
            }
            .tabel-barang td::before {
                content: attr(data-label);
                flex-shrink: 0; text-align: left;
                color: var(--bs-secondary-color); font-weight: 500; font-size: .9rem;
            }
            .tabel-barang td[data-label="No"] { display: none; }
            .tabel-barang td[data-label="Foto"] { justify-content: flex-start; padding-bottom: .5rem; }
            .tabel-barang td[data-label="Foto"]::before { content: none; }
            .tabel-barang td[data-label="Jenis Barang"] { font-weight: 700; }
            .tabel-barang td[data-label="Merk/Type"] .merk-wrap { justify-content: flex-end; }
            .tabel-barang td[data-label="Ketersediaan"] { flex-wrap: wrap; }
            .tabel-barang td[data-label="Aksi"] { padding-top: .6rem; }
            .tabel-barang td[data-label="Aksi"]::before { content: none; }
            .tabel-barang td[data-label="Aksi"] .btn-detail { width: 100%; min-height: 44px; }
            .tabel-barang td.kosong { display: block; text-align: center; }
            .tabel-barang td.kosong::before { content: none; }

            .info-bawah { align-items: stretch !important; text-align: center; }
            .info-bawah .pagination { justify-content: center; }

            .modal-footer .btn { flex: 1 1 0; }
        }

        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
            .tabel-barang tr { padding: .8rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .navbar { padding-top: .35rem; padding-bottom: .35rem; }
            .logo { width: 38px; height: 38px; }
            main { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @php
        // Foto per jenis (dipakai bersama semua unit satu jenis)
        $fotoJenis = \Illuminate\Support\Facades\DB::table('jenis_barang')
            ->whereNotNull('foto_barang')
            ->pluck('foto_barang', 'id_jenis_barang');

        $namaKategori = collect($kategori)->pluck('nama_kategori', 'id_kategori');

        // Kelompokkan SEMUA unit per jenis barang (satu baris per jenis, seperti admin)
        $semuaUnit = \Illuminate\Support\Facades\DB::table('barang')->get();

        $grup = [];
        foreach ($semuaUnit->groupBy(fn ($u) => $u->id_jenis_barang ?: 'x-' . mb_strtolower($u->nama_barang)) as $kunci => $isi) {
            $pertama = $isi->first();
            $jid = $pertama->id_jenis_barang ?: null;

            $units = $isi->map(fn ($u) => [
                'kode'      => $u->kode_barcode ?? $u->kode_barang ?? $u->kode ?? '-',
                'nama'      => $u->nama_barang,
                'merk_tipe' => trim((string) ($u->merk_tipe ?? '')) ?: null,
                'status'    => $u->status_barang ?? '-',
            ])->sortBy('kode', SORT_NATURAL)->values()->all();

            // Semua merk/type yang berbeda dalam satu jenis (tanpa duplikat, urutan kemunculan)
            $merkList = array_values(array_unique(array_filter(array_column($units, 'merk_tipe'))));

            $grup[$kunci] = [
                'id'        => (string) $kunci,
                'nama'      => $pertama->nama_barang,
                'merk_list' => $merkList,
                'merk_tipe' => implode(', ', $merkList),
                'kat_id'    => (string) $pertama->id_kategori,
                'kat_nama'  => $namaKategori[$pertama->id_kategori] ?? '-',
                'foto'      => $jid && isset($fotoJenis[$jid]) ? asset($fotoJenis[$jid]) : null,
                'unit'      => count($units),
                'tersedia'  => count(array_filter($units, fn ($x) => $x['status'] === 'Tersedia')),
                'dipinjam'  => count(array_filter($units, fn ($x) => $x['status'] !== 'Tersedia')),
                'units'     => $units,
            ];
        }
        $grup = collect($grup)->sortBy('nama', SORT_NATURAL | SORT_FLAG_CASE)->all();
        $totalUnit = array_sum(array_column($grup, 'unit'));
    @endphp

    <nav class="navbar navbar-expand-md sticky-top" aria-label="Menu utama">
        <div class="container wadah">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/peminjam') }}">
                <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                <span>InventarisBarang</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar" aria-controls="menuNavbar" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuNavbar">
                <ul class="navbar-nav ms-auto align-items-md-center gap-md-4 py-3 py-md-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link aktif" href="{{ url('/peminjam/daftar-barang') }}" aria-current="page">Daftar Barang</a></li>
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

    <main class="container wadah flex-grow-1 py-4 py-md-5">

        <a class="btn btn-outline-secondary btn-sm btn-kembali mb-4" href="{{ url('/peminjam') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
            Kembali ke dashboard
        </a>

        <h1 class="mb-2">Daftar Barang</h1>
        <p class="text-secondary mb-4" style="max-width: 560px;">
            Cek barang yang tersedia dan yang sedang dipinjam.
        </p>

        {{-- Pencarian dan filter disaring langsung di browser --}}
        <form method="GET" action="{{ route('peminjam.daftar-barang') }}" id="formCari" class="filter-bar row g-2 mb-4">
            <div class="col-12 col-md">
                <div class="input-group">
                    <span class="input-group-text text-secondary"><i class="bi bi-search"></i></span>
                    <input type="search" name="cari" value="{{ request('cari') }}"
                           class="form-control" placeholder="Cari jenis, merk, atau kode barang..." maxlength="100"
                           aria-label="Cari jenis, merk, atau kode barang" autocomplete="off">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <select name="kategori" class="form-select" aria-label="Filter kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id_kategori }}" @selected((string) request('kategori') === (string) $k->id_kategori)>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-emas flex-grow-1">Cari</button>
                <button type="button" id="btnResetCari" class="btn btn-outline-secondary d-inline-flex align-items-center" hidden>Reset</button>
            </div>
        </form>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tabel-barang" id="tabelJenis">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th style="width: 100px;">Foto</th>
                            <th>Jenis Barang</th>
                            <th style="width: 170px;">Merk/Type</th>
                            <th>Kategori</th>
                            <th style="width: 120px;">Total Unit</th>
                            <th style="width: 200px;">Ketersediaan</th>
                            <th style="width: 130px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grup as $g)
                            <tr class="baris-jenis"
                                data-id="{{ $g['id'] }}"
                                data-kategori="{{ $g['kat_id'] }}"
                                data-cari="{{ mb_strtolower($g['nama'] . ' ' . implode(' ', $g['merk_list']) . ' ' . implode(' ', array_column($g['units'], 'kode'))) }}">
                                <td data-label="No"><span class="nomor">{{ $loop->iteration }}</span></td>

                                <td data-label="Foto">
                                    @if ($g['foto'])
                                        <button type="button" class="foto-thumb btn-lihat-foto"
                                                data-foto="{{ $g['foto'] }}"
                                                data-judul="{{ $g['nama'] }}"
                                                aria-label="Lihat foto {{ $g['nama'] }}">
                                            <img src="{{ $g['foto'] }}" alt="" width="72" height="72" loading="lazy">
                                        </button>
                                    @else
                                        <span class="foto-thumb foto-thumb-kosong" title="Belum ada foto" role="img" aria-label="Belum ada foto">
                                            <i class="bi bi-image" aria-hidden="true"></i>
                                        </span>
                                    @endif
                                </td>

                                <td data-label="Jenis Barang" class="fw-semibold text-break">{{ $g['nama'] }}</td>

                                <td data-label="Merk/Type">
                                    @if ($g['merk_list'])
                                        <div class="merk-wrap">
                                            @foreach ($g['merk_list'] as $merk)
                                                <span class="merk-chip"><i class="bi bi-cpu" aria-hidden="true"></i>{{ $merk }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="merk-kosong">-</span>
                                    @endif
                                </td>

                                <td data-label="Kategori">{{ $g['kat_nama'] }}</td>

                                <td data-label="Total Unit">
                                    <div class="total-unit">{{ $g['unit'] }}<small>unit</small></div>
                                </td>

                                <td data-label="Ketersediaan">
                                    {{-- Badge selalu bertumpuk ke bawah (tersedia di atas, dipinjam di bawahnya) --}}
                                    <div class="d-flex flex-column align-items-end align-items-md-start gap-1">
                                        <span class="badge rounded-pill badge-status stok-chip badge-tersedia">{{ $g['tersedia'] }} tersedia</span>
                                        @if ($g['dipinjam'])
                                            <span class="badge rounded-pill badge-status stok-chip badge-dipinjam">{{ $g['dipinjam'] }} dipinjam</span>
                                        @endif
                                    </div>
                                </td>

                                <td data-label="Aksi" class="text-end">
                                    <button type="button" class="btn btn-outline-info btn-detail" data-id="{{ $g['id'] }}">
                                        <i class="bi bi-list-ul" aria-hidden="true"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        <tr id="kosongJenis" hidden>
                            <td colspan="8" class="kosong text-center text-secondary py-5">
                                Tidak ada barang yang cocok dengan pencarian.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="info-bawah d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
            <p class="text-secondary small mb-0" id="infoHalaman" aria-live="polite">Total {{ count($grup) }} jenis, {{ $totalUnit }} unit.</p>
            <nav aria-label="Halaman daftar barang">
                <ul class="pagination" id="paginasi"></ul>
            </nav>
        </div>

    </main>

    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    {{-- ================= MODAL DETAIL (hanya lihat) ================= --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="judulDetail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">

                <div class="modal-header align-items-start">
                    <div class="d-flex align-items-center gap-3" style="min-width: 0;">
                        <div id="detFoto" class="flex-shrink-0"></div>
                        <div style="min-width: 0;">
                            <h2 class="modal-title fs-5 fw-bold text-break" id="judulDetail">Detail jenis barang</h2>
                            <div class="detail-meta">
                                <span class="badge rounded-pill badge-jumlah"><i class="bi bi-tags me-1" aria-hidden="true"></i><span id="detKategori"></span></span>
                                <span class="badge rounded-pill badge-jumlah" id="detMerkWrap" hidden><i class="bi bi-cpu me-1" aria-hidden="true"></i><span id="detMerk"></span></span>
                                <span class="badge rounded-pill badge-jumlah" id="detTotal"></span>
                                <span class="badge rounded-pill badge-status badge-tersedia" id="detTersedia"></span>
                                <span class="badge rounded-pill badge-status badge-dipinjam" id="detDipinjam"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="input-group">
                                <span class="input-group-text input-cari"><i class="bi bi-search"></i></span>
                                <input type="text" id="detCari" class="form-control input-cari"
                                       placeholder="Cari kode barcode atau merk unit..." aria-label="Cari unit" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle tabel-unit">
                            <thead>
                                <tr>
                                    <th style="width: 180px;">Kode Barcode</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 190px;">Merk/Type</th>
                                    <th style="width: 140px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="detBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal lihat foto ukuran besar --}}
    <div class="modal fade" id="modalLihatFoto" tabindex="-1" aria-labelledby="judulLihatFoto" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-6 fw-bold me-3" id="judulLihatFoto">Foto barang</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-3 text-center">
                    <img id="fotoBesar" class="foto-besar" alt="">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const html = document.documentElement;
        const labelTema = document.getElementById('labelTema');
        const tampilkanLabel = () => {
            labelTema.textContent = html.getAttribute('data-bs-theme') === 'dark' ? 'Mode terang' : 'Mode gelap';
        };
        tampilkanLabel();
        document.getElementById('btnTema').addEventListener('click', () => {
            const baru = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', baru);
            try { localStorage.setItem('tema', baru); } catch (e) {}
            tampilkanLabel();
        });

        (function () {
            const byId = id => document.getElementById(id);
            const DAFTAR = @json(array_values($grup));
            const GRUP = Object.fromEntries(DAFTAR.map(g => [String(g.id), g]));
            const TOTAL_UNIT = {{ $totalUnit }};

            /* ---------- Lihat foto besar ---------- */
            const modalLihat = new bootstrap.Modal(byId('modalLihatFoto'));
            document.addEventListener('click', e => {
                const btn = e.target.closest('.btn-lihat-foto');
                if (!btn) return;
                byId('fotoBesar').src = btn.dataset.foto;
                byId('fotoBesar').alt = btn.dataset.judul;
                byId('judulLihatFoto').textContent = btn.dataset.judul;
                modalLihat.show();
            });

            /* ---------- Modal Detail ---------- */
            const modalDetail = new bootstrap.Modal(byId('modalDetail'));
            const detBody = byId('detBody');

            const kosongUnit = document.createElement('tr');
            kosongUnit.hidden = true;
            kosongUnit.innerHTML = '<td colspan="4" class="kosong-unit"><i class="bi bi-search"></i>'
                + '<div class="fw-bold mb-1">Unit tidak ditemukan</div><div class="small">Coba kode barcode atau merk yang lain.</div></td>';

            const thumbDetail = g => {
                const s = document.createElement('span');
                if (g.foto) {
                    s.className = 'foto-thumb foto-thumb-md';
                    const im = document.createElement('img');
                    im.src = g.foto;
                    im.alt = g.nama;
                    s.append(im);
                    return s;
                }
                s.className = 'foto-thumb foto-thumb-md foto-thumb-kosong';
                s.title = 'Belum ada foto';
                s.setAttribute('role', 'img');
                s.setAttribute('aria-label', 'Belum ada foto');
                s.innerHTML = '<i class="bi bi-image" aria-hidden="true"></i>';
                return s;
            };

            // g = jenis induk. Merk jenis hanya dipakai sebagai cadangan bila jenis itu punya SATU merk saja.
            const barisUnit = (u, g) => {
                const merk = u.merk_tipe || (g.merk_list.length === 1 ? g.merk_list[0] : '');
                const tr = document.createElement('tr');
                tr.dataset.cari = (u.kode + ' ' + u.nama + ' ' + merk).toLowerCase();
                tr.innerHTML = '<td><span class="kode"></span></td>'
                    + '<td class="fw-semibold text-break"></td>'
                    + '<td class="kolom-merk"></td>'
                    + '<td><span class="badge rounded-pill badge-status"></span></td>';
                tr.querySelector('.kode').textContent = u.kode;
                tr.children[1].textContent = u.nama;

                const sel = tr.querySelector('.kolom-merk');
                if (merk) {
                    const chip = document.createElement('span');
                    chip.className = 'merk-chip';
                    chip.innerHTML = '<i class="bi bi-cpu" aria-hidden="true"></i>';
                    chip.append(document.createTextNode(merk));
                    sel.append(chip);
                } else {
                    sel.innerHTML = '<span class="merk-kosong">-</span>';
                }

                const b = tr.querySelector('.badge-status');
                b.textContent = u.status;
                b.classList.add(u.status === 'Tersedia' ? 'badge-tersedia' : 'badge-dipinjam');
                return tr;
            };

            const bukaDetail = id => {
                const g = GRUP[id];
                if (!g) return;
                byId('judulDetail').textContent = g.nama;
                byId('detFoto').replaceChildren(thumbDetail(g));
                byId('detKategori').textContent = g.kat_nama;
                byId('detMerk').textContent = g.merk_tipe || '';
                byId('detMerkWrap').hidden = !g.merk_tipe;
                byId('detTotal').textContent = g.unit + ' unit';
                byId('detTersedia').textContent = g.tersedia + ' tersedia';
                byId('detDipinjam').textContent = g.dipinjam + ' dipinjam';
                byId('detDipinjam').hidden = !g.dipinjam;
                byId('detCari').value = '';
                detBody.replaceChildren(...g.units.map(u => barisUnit(u, g)), kosongUnit);
                kosongUnit.hidden = g.units.length > 0;
                modalDetail.show();
            };

            byId('detCari').addEventListener('input', e => {
                const q = e.target.value.trim().toLowerCase();
                let tampak = 0;
                detBody.querySelectorAll('tr[data-cari]').forEach(tr => {
                    tr.hidden = !!q && !tr.dataset.cari.includes(q);
                    if (!tr.hidden) tampak++;
                });
                kosongUnit.hidden = tampak > 0;
            });

            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', () => bukaDetail(btn.dataset.id));
            });

            /* ---------- Pencarian, filter, dan pagination daftar jenis (di browser) ---------- */
            const formCari = byId('formCari');
            const inCari = formCari.querySelector('[name="cari"]');
            const selKat = formCari.querySelector('[name="kategori"]');
            const btnReset = byId('btnResetCari');
            const barisJenis = [...document.querySelectorAll('#tabelJenis tbody tr.baris-jenis')];
            const kosongJenis = byId('kosongJenis');
            const paginasi = byId('paginasi');
            const infoHalaman = byId('infoHalaman');

            const PER_HALAMAN = 10;
            let halaman = 1;
            let cocok = [];

            const butirHalaman = (aktif, maks) => {
                const set = new Set([1, maks, aktif - 1, aktif, aktif + 1]);
                const arr = [...set].filter(n => n >= 1 && n <= maks).sort((a, b) => a - b);
                const hasil = [];
                arr.forEach((n, i) => {
                    if (i && n - arr[i - 1] > 1) hasil.push('...');
                    hasil.push(n);
                });
                return hasil;
            };

            const itemHal = (isi, { page = null, aktif = false, mati = false, label = '' } = {}) => {
                const li = document.createElement('li');
                li.className = 'page-item' + (aktif ? ' active' : '') + (mati ? ' disabled' : '');
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'page-link';
                b.innerHTML = isi;
                if (label) b.setAttribute('aria-label', label);
                if (aktif) b.setAttribute('aria-current', 'page');
                if (mati) b.disabled = true;
                if (page) b.dataset.page = page;
                li.append(b);
                return li;
            };

            const renderHalaman = () => {
                const total = cocok.length;
                const maks = Math.max(1, Math.ceil(total / PER_HALAMAN));
                halaman = Math.min(Math.max(1, halaman), maks);
                const mulai = (halaman - 1) * PER_HALAMAN;

                barisJenis.forEach(tr => { tr.hidden = true; });
                cocok.slice(mulai, mulai + PER_HALAMAN).forEach((tr, i) => {
                    tr.hidden = false;
                    tr.querySelector('.nomor').textContent = mulai + i + 1;
                });

                kosongJenis.hidden = total > 0;
                infoHalaman.textContent = total
                    ? `Menampilkan ${mulai + 1}-${Math.min(mulai + PER_HALAMAN, total)} dari ${total} jenis (${TOTAL_UNIT} unit seluruhnya)`
                    : '';

                paginasi.replaceChildren();
                if (maks <= 1) return;
                paginasi.append(itemHal('<i class="bi bi-chevron-left"></i>', { page: halaman - 1, mati: halaman === 1, label: 'Sebelumnya' }));
                butirHalaman(halaman, maks).forEach(n => {
                    paginasi.append(n === '...'
                        ? itemHal('&hellip;', { mati: true })
                        : itemHal(n, { page: n, aktif: n === halaman }));
                });
                paginasi.append(itemHal('<i class="bi bi-chevron-right"></i>', { page: halaman + 1, mati: halaman === maks, label: 'Berikutnya' }));
            };

            paginasi.addEventListener('click', e => {
                const b = e.target.closest('button[data-page]');
                if (!b) return;
                halaman = Number(b.dataset.page);
                renderHalaman();
                byId('tabelJenis').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            const saring = () => {
                const q = inCari.value.trim().toLowerCase();
                const k = selKat.value;
                cocok = barisJenis.filter(tr =>
                    (!q || tr.dataset.cari.includes(q)) && (!k || tr.dataset.kategori === k));
                halaman = 1;
                renderHalaman();
                btnReset.hidden = !(q || k);
            };

            formCari.addEventListener('submit', e => { e.preventDefault(); saring(); });
            inCari.addEventListener('input', saring);
            selKat.addEventListener('change', saring);
            btnReset.addEventListener('click', () => {
                inCari.value = '';
                selKat.value = '';
                saring();
            });
            saring();

            /* ---------- Foto gagal dimuat -> tampilkan placeholder ---------- */
            document.addEventListener('error', e => {
                const img = e.target;
                if (!(img instanceof HTMLImageElement)) return;
                const thumb = img.closest('.foto-thumb');
                if (!thumb) return;
                const ph = document.createElement('span');
                ph.className = 'foto-thumb foto-thumb-kosong';
                ph.title = 'Foto tidak dapat dimuat';
                ph.setAttribute('role', 'img');
                ph.setAttribute('aria-label', 'Foto tidak dapat dimuat');
                ph.innerHTML = '<i class="bi bi-image" aria-hidden="true"></i>';
                thumb.replaceWith(ph);
            }, true);
        })();
    </script>
</body>
</html>