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
            --bs-btn-disabled-color: #1a1405; --bs-btn-disabled-bg: var(--emas); --bs-btn-disabled-border-color: var(--emas);
            border-radius: 10px; padding: 11px 20px; min-height: 44px; font-weight: 700;
            transition: transform .2s, background .2s;
        }
        .btn-emas:hover { transform: translateY(-2px); }
        .btn-emas:disabled { transform: none; }
        .btn-tema { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        .btn-reset { display: inline-flex; align-items: center; justify-content: center; }
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
        .page-link:hover { color: var(--bs-body-color); background: var(--hover); border-color: var(--bs-secondary-color); }
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

        /* ===== Tabel per jenis barang ===== */
        .col-foto { width: 100px; }
        .foto-thumb {
            display: inline-flex; align-items: center; justify-content: center;
            width: 56px; height: 56px; padding: 0; overflow: hidden; vertical-align: middle;
            border-radius: 12px; border: 1px solid var(--bs-border-color);
            background: var(--hover); color: var(--bs-secondary-color); font-size: 1.35rem;
        }
        .tabel-jenis .foto-thumb { width: 72px; height: 72px; font-size: 1.6rem; }
        .foto-thumb-md { width: 64px; height: 64px; }
        button.foto-thumb { cursor: zoom-in; transition: border-color .2s; }
        button.foto-thumb:hover { border-color: var(--biru); }
        .foto-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .foto-thumb-kosong { border-style: dashed; }
        .jenis-label { margin-top: 3px; font-size: .8rem; font-weight: 500; color: var(--bs-secondary-color); }
        .jenis-label i { margin-right: 4px; }
        .jenis-label.belum-foto { color: var(--emas-teks); }
        .total-unit { font-size: 1.4rem; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; letter-spacing: -.02em; }
        .total-unit small { margin-left: 4px; font-size: .8rem; font-weight: 500; letter-spacing: 0; color: var(--bs-secondary-color); }
        .stok-chip { font-size: .78rem; padding: .35em .7em; }
        .foto-besar { max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 12px; }

        /* ===== Modal detail unit ===== */
        .detail-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .detail-meta .badge { font-size: .78rem; padding: .4em .75em; }

        /* ===== Komponen pilih foto ===== */
        .foto-kotak {
            position: relative; display: flex; align-items: center; justify-content: center;
            width: 100%; aspect-ratio: 16 / 10; max-height: 240px; overflow: hidden; text-align: center;
            border: 2px dashed var(--bs-border-color); border-radius: 14px;
            background: var(--hover); color: var(--bs-secondary-color);
            cursor: pointer; transition: border-color .2s, background .2s;
        }
        .foto-kotak:hover, .foto-kotak.seret { border-color: var(--emas); background: color-mix(in srgb, var(--emas) 10%, transparent); }
        .foto-kotak:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }
        .foto-kotak.berisi { border-style: solid; background: var(--bs-body-bg); }
        .foto-kotak.berisi:hover { border-color: var(--bs-secondary-color); background: var(--bs-body-bg); }
        .foto-kotak.terkunci { cursor: default; }
        .foto-kotak.terkunci:hover { border-color: var(--bs-border-color); }
        .foto-img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .foto-kosong { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 16px; }
        .foto-kosong i { font-size: 2.1rem; line-height: 1; margin-bottom: 4px; color: var(--emas-teks); }
        .foto-kosong strong { font-size: .95rem; color: var(--bs-body-color); }
        .foto-kosong small { font-size: .8rem; }
        .foto-badge {
            position: absolute; top: 10px; left: 10px; padding: .3em .75em; border-radius: 999px;
            font-size: .75rem; font-weight: 700; color: var(--bs-body-color);
            background: var(--kartu); border: 1px solid var(--bs-border-color);
        }
        .foto-badge.baru { color: #1a1405; background: var(--emas); border-color: var(--emas); }
        .foto-aksi { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .foto-aksi .btn { min-height: 40px; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: .9rem; }
        .foto-info { margin-top: 8px; font-size: .85rem; color: var(--bs-secondary-color); }
        .foto-info:empty { display: none; }
        .foto-info.salah { color: var(--merah); }
        .foto-info.sukses { color: var(--ada); }
        .foto-catatan {
            display: flex; align-items: flex-start; gap: 10px; margin-top: 12px; padding: 10px 12px;
            font-size: .85rem; border-radius: 12px;
            border: 1px solid color-mix(in srgb, var(--emas-teks) 40%, transparent);
            background: color-mix(in srgb, var(--emas-teks) 10%, transparent);
        }
        .foto-catatan i { margin-top: 2px; color: var(--emas-teks); }
        .foto-hint {
            display: flex; align-items: center; gap: 10px; padding: 14px; font-size: .9rem;
            border: 1px dashed var(--bs-border-color); border-radius: 12px; color: var(--bs-secondary-color);
        }
        .foto-hint i { font-size: 1.25rem; }

        /* ===== Preview kode barcode (form tambah) ===== */
        .preview-kode {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: .85rem; font-weight: 600; padding: 3px 8px; border-radius: 8px;
            color: var(--bs-body-color); background: var(--hover); border: 1px solid var(--bs-border-color);
            word-break: break-all;
        }

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

            .tabel-jenis tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: auto 1fr auto;
                grid-template-areas: "foto nama total" "foto kategori total" "stok stok stok" "aksi aksi aksi";
                column-gap: 12px; row-gap: 4px;
                padding: 16px 4px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-jenis tbody tr[hidden] { display: none !important; }
            .tabel-jenis .foto-thumb { width: 64px; height: 64px; }

            .tabel-unit tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: auto 1fr auto;
                grid-template-areas: "pilih kode status" "nama nama nama" "aksi aksi aksi";
                column-gap: 12px; row-gap: 6px;
                padding: 16px 4px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-unit tbody tr[hidden] { display: none !important; }

            .tabel-data tbody tr:not(.baris-kosong):last-child { border-bottom: 0; }
            .tabel-data tbody tr:not(.baris-kosong) td { display: block; padding: 0; border: 0; width: auto !important; }

            .col-no { display: none !important; }
            .col-pilih { grid-area: pilih; align-self: start; padding-top: 2px !important; }
            .col-foto { grid-area: foto; align-self: start; }
            .tabel-data tbody tr.terpilih { background-color: color-mix(in srgb, var(--emas) 12%, transparent); border-radius: 10px; }
            .tabel-data tbody tr.terpilih > td { background-color: transparent; }
            .col-nama { grid-area: nama; font-weight: 700; min-width: 0; overflow-wrap: anywhere; }
            .col-status { grid-area: status; align-self: start; }
            .col-kode { grid-area: kode; }
            .col-kategori { grid-area: kategori; color: var(--bs-secondary-color); }
            .col-total { grid-area: total; text-align: right; align-self: start; }
            .col-stok { grid-area: stok; margin-top: 4px; }
            .col-aksi { grid-area: aksi; text-align: left !important; margin-top: 8px; }
            .col-aksi > div { display: flex !important; width: 100%; }
            .col-aksi .tombol-aksi { flex: 1 1 0; min-height: 44px; padding-left: 6px; padding-right: 6px; }

            .tabel-data tbody tr.baris-kosong { display: table-row; }
            .tabel-data tbody tr.baris-kosong[hidden] { display: none; }
            .tabel-data tbody tr.baris-kosong td { display: table-cell; }

            .cari-tombol { display: flex; gap: 8px; width: 100%; }
            .cari-tombol .btn { flex: 1 1 0; }
            .info-halaman { width: 100%; text-align: center; }
            .nav-halaman { width: 100%; display: flex; justify-content: center; }
            .modal-footer .btn { flex: 1 1 0; }
            .foto-aksi .btn { flex: 1 1 0; justify-content: center; }
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
            .btn-emas, .side-link, .foto-kotak, button.foto-thumb { transition: none; }
            .btn-emas:hover { transform: none; }
        }
    </style>
</head>

<body>
    @php
        $user       = auth()->user();
        $namaUser   = $user->name ?? ($user->username ?? 'Admin');
        $linkBarang = Route::has('barang.index') ? route('barang.index') : route('kategori.index');

        // $grup dikirim dari BarangController@index (data asli dari database)
        $totalUnit  = array_sum(array_column($grup, 'unit'));

        // Ruang & lemari dibaca dari kode barcode tiap unit: RUANG-LEMARI-KODE-0001
        // $tempatJenis : id jenis => daftar pasangan "RUANG|LEMARI" yang dipakai unit-unitnya
        $tempatJenis = [];
        $opsiRuang   = [];
        $opsiLemari  = [];
        foreach ($grup as $g) {
            $pasangan = [];
            foreach ($g['units'] as $u) {
                $bagian = explode('-', (string) $u['kode']);
                if (count($bagian) < 3) continue;
                $pasangan[$bagian[0] . '|' . $bagian[1]] = true;
                $opsiRuang[$bagian[0]]  = true;
                $opsiLemari[$bagian[1]] = true;
            }
            $tempatJenis[$g['id']] = array_keys($pasangan);
        }
        $opsiRuang  = array_keys($opsiRuang);
        $opsiLemari = array_keys($opsiLemari);
        natcasesort($opsiRuang);
        natcasesort($opsiLemari);
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
                            <p class="lead mt-3 mb-0">Lihat barang per jenis, lalu buka detail untuk mengatur setiap unit.</p>
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
                                    <div class="small text-body-secondary">Barang dikelompokkan per jenis</div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge rounded-pill px-3 py-2 badge-jumlah"><span id="jmlJenis">{{ count($grup) }}</span> jenis</span>
                                    <span class="badge rounded-pill px-3 py-2 badge-jumlah">{{ $totalUnit }} unit</span>
                                </div>
                            </div>
                        </div>

                        <div class="papan-isi">

                            {{-- Pencarian, filter, dan urutan disaring langsung di browser --}}
                            <form method="GET" action="{{ route('barang.index') }}" id="formCari" class="row g-2 align-items-center mb-4">
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-text input-cari"><i class="bi bi-search"></i></span>
                                        <input type="text" name="q" value="{{ request('q') }}" class="form-control input-cari"
                                               placeholder="Cari jenis atau kode barang..." aria-label="Cari jenis atau kode barang" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-6 col-md-3 col-lg">
                                    <select name="kategori" class="form-select" aria-label="Filter kategori">
                                        <option value="">Semua kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id_kategori }}" @selected(request('kategori') == $k->id_kategori)>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-3 col-lg">
                                    <select name="ruang" class="form-select" aria-label="Filter ruang">
                                        <option value="">Semua ruang</option>
                                        @foreach ($opsiRuang as $r)
                                            <option value="{{ $r }}" @selected(request('ruang') === $r)>{{ $r }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-3 col-lg">
                                    <select name="lemari" class="form-select" aria-label="Filter lemari">
                                        <option value="">Semua lemari</option>
                                        @foreach ($opsiLemari as $l)
                                            <option value="{{ $l }}" @selected(request('lemari') === $l)>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-3 col-lg">
                                    <select name="urut" class="form-select" aria-label="Urutkan daftar">
                                        <option value="default">Urutan awal</option>
                                        <option value="nama-az">Nama A-Z</option>
                                        <option value="nama-za">Nama Z-A</option>
                                        <option value="unit-banyak">Unit terbanyak</option>
                                        <option value="unit-sedikit">Unit tersedikit</option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-auto">
                                    <div class="cari-tombol d-md-flex gap-2">
                                        <button type="submit" class="btn btn-emas">
                                            <i class="bi bi-search me-1"></i> Cari
                                        </button>

                                        <button type="button" id="btnResetCari" class="btn btn-outline-secondary btn-reset" hidden>
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover tabel-data tabel-jenis">
                                    <thead>
                                        <tr>
                                            <th style="width: 75px;">No.</th>
                                            <th style="width: 100px;">Foto</th>
                                            <th>Jenis Barang</th>
                                            <th>Kategori</th>
                                            <th style="width: 120px;">Total Unit</th>
                                            <th style="width: 190px;">Ketersediaan</th>
                                            <th style="width: 230px;" class="text-end">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($grup as $g)
                                            @php $tempat = $tempatJenis[$g['id']] ?? []; @endphp
                                            <tr class="baris-jenis"
                                                data-id="{{ $g['id'] }}"
                                                data-kategori="{{ $g['kat_id'] }}"
                                                data-nama="{{ mb_strtolower($g['nama']) }}"
                                                data-unit="{{ $g['unit'] }}"
                                                data-urut="{{ $loop->index }}"
                                                data-tempat="{{ implode(' ', $tempat) }}"
                                                data-cari="{{ mb_strtolower($g['nama'] . ' ' . implode(' ', array_column($g['units'], 'kode'))) }}">
                                                <td class="col-no"><span class="nomor">{{ $loop->iteration }}</span></td>

                                                <td class="col-foto">
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

                                                <td class="col-nama fw-semibold text-break">
                                                    {{ $g['nama'] }}
                                                    @if ($tempat)
                                                        <div class="jenis-label">
                                                            <i class="bi bi-geo-alt" aria-hidden="true"></i>{{ str_replace('|', ' · ', $tempat[0]) }}@if (count($tempat) > 1) +{{ count($tempat) - 1 }} lokasi @endif
                                                        </div>
                                                    @endif
                                                    @unless ($g['foto'])
                                                        <div class="jenis-label belum-foto"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>Belum ada foto</div>
                                                    @endunless
                                                </td>

                                                <td class="col-kategori">{{ $g['kat_nama'] }}</td>

                                                <td class="col-total">
                                                    <div class="total-unit">{{ $g['unit'] }}<small>unit</small></div>
                                                </td>

                                                <td class="col-stok">
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge rounded-pill badge-status stok-chip b-hijau">{{ $g['tersedia'] }} tersedia</span>
                                                        @if ($g['dipinjam'])
                                                            <span class="badge rounded-pill badge-status stok-chip b-emas">{{ $g['dipinjam'] }} dipinjam</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="col-aksi text-end">
                                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                        <button type="button" class="btn btn-outline-info tombol-aksi btn-detail" data-id="{{ $g['id'] }}">
                                                            <i class="bi bi-list-ul"></i> Detail
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary tombol-aksi btn-edit-foto" data-id="{{ $g['id'] }}">
                                                            <i class="bi bi-image"></i> Edit Foto
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr class="baris-kosong" id="kosongJenis" hidden>
                                            <td colspan="7" class="text-center kosong">
                                                <i class="bi bi-inbox"></i>
                                                <div class="fw-bold mb-1">Jenis barang tidak ditemukan</div>
                                                <div class="small">Coba kata kunci, kategori, ruang, atau lemari yang lain.</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination (dihitung di browser, ikut hasil pencarian/filter) --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                                <div class="small text-body-secondary info-halaman" id="infoHalaman" aria-live="polite"></div>
                                <nav class="nav-halaman" aria-label="Halaman daftar barang">
                                    <ul class="pagination" id="paginasi"></ul>
                                </nav>
                            </div>

                        </div>
                    </section>

                </div>
            </main>

            <footer class="py-3 mt-auto px-3 px-md-5">
                <div class="isi mx-auto d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start" style="max-width: 1120px;">
                    <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
                </div>
            </footer>

        </div>
    </div>

    {{-- ================= MODAL DETAIL: SELURUH UNIT DALAM SATU JENIS ================= --}}
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
                                <span class="badge rounded-pill badge-jumlah" id="detTotal"></span>
                                <span class="badge rounded-pill badge-status b-hijau" id="detTersedia"></span>
                                <span class="badge rounded-pill badge-status b-emas" id="detDipinjam"></span>
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
                                       placeholder="Cari kode barcode unit..." aria-label="Cari unit" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="bar-seleksi d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div class="form-check mb-0 d-flex align-items-center gap-2 ps-0">
                            <input class="form-check-input m-0" type="checkbox" id="pilihSemua">
                            <label class="form-check-label small" for="pilihSemua">Pilih semua unit yang tampil</label>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <select id="ukuranStiker" class="form-select form-select-sm w-auto" aria-label="Ukuran stiker">
                                @foreach ($ukuranStiker as $kode => $opsi)
                                    <option value="{{ $kode }}">{{ $opsi['label'] }}</option>
                                @endforeach
                            </select>
                            <small class="text-body-secondary"><span id="jumlahDipilih">0</span> dipilih</small>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBatalPilih" hidden>
                                Batal pilih
                            </button>
                            <button type="button" class="btn btn-emas btn-sm" id="btnCetakTerpilih" disabled>
                                <i class="bi bi-printer me-1"></i> Cetak terpilih
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover tabel-data tabel-unit">
                            <thead>
                                <tr>
                                    <th class="col-pilih"><span class="visually-hidden">Pilih</span></th>
                                    <th style="width: 220px;">Kode Barcode</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 130px;">Status</th>
                                    <th style="width: 280px;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-emas" id="btnDetailEditFoto"><i class="bi bi-image me-1"></i> Edit Foto</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT FOTO JENIS BARANG ================= --}}
    <div class="modal fade" id="modalEditFoto" tabindex="-1" aria-labelledby="judulEditFoto" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            {{-- action diisi lewat JS (route barang.foto), id unit dikirim sebagai ids[] --}}
            <form method="POST" action="{{ route('barang.foto') }}" id="formEditFoto" class="modal-content" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="judulEditFoto">
                            <i class="bi bi-image me-2"></i> Edit Foto
                        </h2>
                        <div class="small text-body-secondary mt-1">Foto berlaku untuk semua unit dalam satu jenis.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="small text-body-secondary">Jenis barang</div>
                        <div class="fw-bold text-break" id="fotoJenisNama"></div>
                        <div class="small text-body-secondary" id="fotoJenisMeta"></div>
                    </div>

                    <div class="foto-picker" id="pickerEditFoto" data-label-asal="Foto saat ini">
                        <input type="file" name="foto_barang" id="editFotoJenis" class="foto-input visually-hidden"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-label="Pilih file foto">
                        <div class="foto-kotak" role="button" tabindex="0" aria-label="Pilih atau seret foto pengganti ke area ini">
                            <img class="foto-img" alt="Pratinjau foto jenis barang" hidden>
                            <div class="foto-kosong">
                                <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                                <strong>Belum ada foto. Klik atau seret foto ke sini</strong>
                                <small>JPG, JPEG, PNG, atau WEBP &middot; maksimal 2 MB</small>
                            </div>
                            <span class="foto-badge" hidden></span>
                        </div>
                        <div class="foto-aksi">
                            <button type="button" class="btn btn-outline-secondary btn-sm foto-pilih">
                                <i class="bi bi-image" aria-hidden="true"></i> <span class="foto-pilih-teks">Ganti foto</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm foto-batal" hidden>
                                <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <span class="foto-batal-teks">Batalkan, pakai foto lama</span>
                            </button>
                        </div>
                        <div class="foto-info" aria-live="polite"></div>
                    </div>

                    <div class="foto-catatan">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        <span id="catatanEditFoto"></span>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emas" id="btnSimpanFoto" disabled><i class="bi bi-check-lg me-1"></i> Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH BARANG ================= --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="judulModalTambah" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form method="POST" action="{{ route('barang.store') }}" id="formTambah" class="modal-content" enctype="multipart/form-data">
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
                        <label for="tambahJenis" class="form-label fw-semibold">Pilih Jenis yang Sudah Ada <span class="fw-normal text-body-secondary">(opsional)</span></label>
                        {{-- Mengirim id_jenis_barang jika memilih jenis yang sudah ada --}}
                        <select id="tambahJenis" name="id_jenis_barang" class="form-select">
                            <option value="">-- Barang jenis baru --</option>
                            @foreach ($grup as $g)
                                <option value="{{ $g['id'] }}">{{ $g['nama'] }} ({{ $g['unit'] }} unit)</option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilih jenis agar nama dan kategori terisi otomatis dan unit baru masuk ke jenis itu.</div>
                    </div>

                    <div class="mb-3">
                        <label for="tambahNama" class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" id="tambahNama" value="{{ old('nama_barang') }}"
                               class="form-control" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="tambahSingkat" class="form-label fw-semibold">Kode Barang <span class="fw-normal text-body-secondary">(singkatan nama)</span></label>
                        <input type="text" name="kode_barang" id="tambahSingkat" value="{{ old('kode_barang') }}"
                               class="form-control text-uppercase" maxlength="6" placeholder="Otomatis, mis. KMP"
                               autocomplete="off">
                        <div class="form-text">Terisi otomatis dari nama barang (Komputer → KMP). Boleh diubah, mis. jadi KMPTR.</div>
                    </div>

                    {{-- Kategori: dropdown, atau pilih "Kategori baru" lalu ketik manual --}}
                    <div class="mb-3">
                        <label for="tambahKategori" class="form-label fw-semibold">Kategori</label>
                        <select name="id_kategori" id="tambahKategori" class="form-select" required>
                            <option value="">-- Pilih kategori --</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}"
                                        @selected((string) old('id_kategori') === (string) $k->id_kategori)>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                            <option value="__baru__" @selected(old('id_kategori') === '__baru__')>+ Kategori baru (ketik manual)</option>
                        </select>

                        <input type="text" name="kategori_baru" id="tambahKategoriBaru" value="{{ old('kategori_baru') }}"
                               class="form-control mt-2" maxlength="50" placeholder="Ketik nama kategori baru"
                               aria-label="Nama kategori baru" autocomplete="off" hidden>
                    </div>

                    {{-- Ruang & lemari: dropdown dari ENUM database, atau pilih "baru" lalu ketik manual --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="tambahRuang" class="form-label fw-semibold">Ruang</label>
                            <select name="ruang_pilih" id="tambahRuang" class="form-select" required>
                                <option value="">-- Pilih ruang --</option>
                                @foreach ($daftarRuang as $r)
                                    <option value="{{ $r }}" @selected(old('ruang_pilih') === $r)>{{ $r }}</option>
                                @endforeach
                                <option value="__baru__" @selected(old('ruang_pilih') === '__baru__')>+ Ruang baru (ketik manual)</option>
                            </select>
                            <input type="text" name="ruang_baru" id="tambahRuangBaru" value="{{ old('ruang_baru') }}"
                                   class="form-control text-uppercase mt-2" maxlength="20" placeholder="Ketik ruang baru"
                                   aria-label="Ruang baru" autocomplete="off" hidden>
                        </div>

                        <div class="col-6">
                            <label for="tambahLemari" class="form-label fw-semibold">Lemari</label>
                            <select name="lemari_pilih" id="tambahLemari" class="form-select" required>
                                <option value="">-- Pilih lemari --</option>
                                @foreach ($daftarLemari as $l)
                                    <option value="{{ $l }}" @selected(old('lemari_pilih') === $l)>{{ $l }}</option>
                                @endforeach
                                <option value="__baru__" @selected(old('lemari_pilih') === '__baru__')>+ Lemari baru (ketik manual)</option>
                            </select>
                            <input type="text" name="lemari_baru" id="tambahLemariBaru" value="{{ old('lemari_baru') }}"
                                   class="form-control text-uppercase mt-2" maxlength="20" placeholder="Ketik lemari baru"
                                   aria-label="Lemari baru" autocomplete="off" hidden>
                        </div>

                        <div class="col-12">
                            <div class="form-text mt-0">
                                Pilih dari daftar atau pilih "baru" lalu ketik. Kode barcode:
                                <span class="preview-kode" id="previewKode">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Barang <span class="fw-normal text-body-secondary">(opsional)</span></label>

                        <div class="foto-picker" id="pickerTambahFoto" data-label-asal="Foto jenis ini">
                            <input type="file" name="foto_barang" id="tambahFoto" class="foto-input visually-hidden"
                                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-label="Pilih file foto">
                            <div class="foto-kotak" role="button" tabindex="0" aria-label="Pilih atau seret foto ke area ini">
                                <img class="foto-img" alt="Pratinjau foto barang" hidden>
                                <div class="foto-kosong">
                                    <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                                    <strong>Klik atau seret foto ke sini</strong>
                                    <small>JPG, JPEG, PNG, atau WEBP &middot; maksimal 2 MB</small>
                                </div>
                                <span class="foto-badge" hidden></span>
                            </div>
                            <div class="foto-aksi">
                                <button type="button" class="btn btn-outline-secondary btn-sm foto-pilih">
                                    <i class="bi bi-image" aria-hidden="true"></i> <span class="foto-pilih-teks">Pilih foto</span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm foto-batal" hidden>
                                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> <span class="foto-batal-teks">Hapus pilihan</span>
                                </button>
                            </div>
                            <div class="foto-info" aria-live="polite"></div>
                        </div>

                        <div class="foto-hint mt-2">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <span id="catatanTambahFoto"></span>
                        </div>
                    </div>

                    <small class="text-body-secondary">
                        Kode barcode dibuat otomatis (mis. PPLG1-LMR1-KMP-0001) dan status awal "Tersedia".
                    </small>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emas"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL EDIT UNIT BARANG (dibuka dari Detail) ================= --}}
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

        /* =====================================================================
           Tampilan per jenis barang (data asli dari BarangController@index)
           ===================================================================== */
        (function () {
            const byId = id => document.getElementById(id);

            const DAFTAR_GRUP = @json($grup);
            const GRUP = Object.fromEntries(DAFTAR_GRUP.map(g => [String(g.id), g]));

            // '__ID__' diganti id unit saat dipakai
            const URL_CETAK = @json(route('barang.cetak', '__ID__'));
            const URL_UBAH = @json(route('barang.update', '__ID__'));
            const URL_HAPUS = @json(route('barang.destroy', '__ID__'));
            const URL_CETAK_MASSAL = @json(route('barang.cetak-massal'));
            const URL_FOTO = @json(route('barang.foto'));
            const url = (tpl, id) => tpl.replace('__ID__', id);

            const MAKS = 100;
            const MAKS_MB = 2;
            const TIPE = ['image/jpeg', 'image/png', 'image/webp'];
            const EKSTENSI = /\.(jpe?g|png|webp)$/i;
            const ukuran = b => b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB';

            /* ---------- Komponen pilih foto ---------- */
            class FotoPicker {
                constructor(root) {
                    this.root = root;
                    this.input = root.querySelector('.foto-input');
                    this.kotak = root.querySelector('.foto-kotak');
                    this.img = root.querySelector('.foto-img');
                    this.kosong = root.querySelector('.foto-kosong');
                    this.badge = root.querySelector('.foto-badge');
                    this.btnPilih = root.querySelector('.foto-pilih');
                    this.teksPilih = root.querySelector('.foto-pilih-teks');
                    this.btnBatal = root.querySelector('.foto-batal');
                    this.teksBatal = root.querySelector('.foto-batal-teks');
                    this.info = root.querySelector('.foto-info');
                    this.labelAsal = root.dataset.labelAsal || 'Foto saat ini';
                    this.asal = null;
                    this.url = null;
                    this.terkunci = false;

                    this.btnPilih.addEventListener('click', () => this.buka());
                    this.kotak.addEventListener('click', () => this.buka());
                    this.kotak.addEventListener('keydown', e => {
                        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.buka(); }
                    });
                    this.btnBatal.addEventListener('click', () => this.batal());
                    this.input.addEventListener('change', () => {
                        const f = this.input.files[0];
                        if (f) this.pilih(f);
                    });

                    ['dragenter', 'dragover'].forEach(n => this.kotak.addEventListener(n, e => {
                        if (this.terkunci) return;
                        e.preventDefault();
                        this.kotak.classList.add('seret');
                    }));
                    ['dragleave', 'drop'].forEach(n => this.kotak.addEventListener(n, e => {
                        e.preventDefault();
                        this.kotak.classList.remove('seret');
                    }));
                    this.kotak.addEventListener('drop', e => {
                        if (this.terkunci) return;
                        const f = e.dataTransfer.files[0];
                        if (!f) return;
                        try {
                            const dt = new DataTransfer();
                            dt.items.add(f);
                            this.input.files = dt.files;
                        } catch (err) {}
                        this.pilih(f);
                    });

                    this.img.addEventListener('error', () => {
                        this.img.hidden = true;
                        this.kosong.hidden = false;
                        this.badge.hidden = true;
                        this.kotak.classList.remove('berisi');
                        this.pesan('Foto tidak dapat dimuat. Pilih foto lain.', 'salah');
                    });

                    this.tampil();
                }

                buka() { if (!this.terkunci) this.input.click(); }

                pesan(teks, jenis) {
                    this.info.textContent = teks || '';
                    this.info.className = 'foto-info' + (jenis ? ' ' + jenis : '');
                }

                pilih(file) {
                    let salah = '';
                    if (!TIPE.includes(file.type) || !EKSTENSI.test(file.name)) {
                        salah = 'Format tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.';
                    } else if (file.size > MAKS_MB * 1048576) {
                        salah = `Ukuran ${ukuran(file.size)} melebihi batas ${MAKS_MB} MB.`;
                    }
                    if (salah) {
                        this.batal();
                        this.pesan(salah, 'salah');
                        popup({ icon: 'error', title: 'Foto tidak valid', text: salah });
                        return;
                    }
                    if (this.url) URL.revokeObjectURL(this.url);
                    this.url = URL.createObjectURL(file);
                    this.tampil();
                    this.pesan(`${file.name} (${ukuran(file.size)}) siap disimpan.`, 'sukses');
                }

                setAsal(src) {
                    this.batal();
                    this.asal = src || null;
                    this.tampil();
                }

                setTerkunci(nilai) {
                    this.terkunci = !!nilai;
                    this.tampil();
                }

                batal() {
                    if (this.url) URL.revokeObjectURL(this.url);
                    this.url = null;
                    this.input.value = '';
                    this.pesan('');
                    this.tampil();
                }

                tampil() {
                    const sumber = this.url || this.asal;
                    const baru = !!this.url;

                    if (sumber) {
                        this.img.src = sumber;
                        this.img.hidden = false;
                        this.kosong.hidden = true;
                    } else {
                        this.img.removeAttribute('src');
                        this.img.hidden = true;
                        this.kosong.hidden = false;
                    }

                    this.kotak.classList.toggle('berisi', !!sumber);
                    this.kotak.classList.toggle('terkunci', this.terkunci);
                    this.kotak.tabIndex = this.terkunci ? -1 : 0;
                    this.kotak.setAttribute('aria-disabled', String(this.terkunci));

                    this.badge.hidden = !sumber;
                    this.badge.textContent = baru ? 'Foto baru' : this.labelAsal;
                    this.badge.classList.toggle('baru', baru);

                    this.teksPilih.textContent = baru ? 'Pilih ulang' : (this.asal ? 'Ganti foto' : 'Pilih foto');
                    this.teksBatal.textContent = this.asal ? 'Batalkan, pakai foto lama' : 'Hapus pilihan';
                    this.btnPilih.hidden = this.terkunci;
                    this.btnBatal.hidden = this.terkunci || !baru;
                    this.input.disabled = this.terkunci;

                    this.root.dispatchEvent(new CustomEvent('foto:berubah', { detail: { baru } }));
                }
            }

            /* ---------- Thumbnail ---------- */
            const buatThumb = (g, { zoom = true, kelas = '' } = {}) => {
                if (g.foto) {
                    const el = document.createElement(zoom ? 'button' : 'span');
                    el.className = 'foto-thumb ' + kelas + (zoom ? ' btn-lihat-foto' : '');
                    if (zoom) {
                        el.type = 'button';
                        el.dataset.foto = g.foto;
                        el.dataset.judul = g.nama;
                        el.setAttribute('aria-label', 'Lihat foto ' + g.nama);
                    }
                    const im = document.createElement('img');
                    im.src = g.foto;
                    im.alt = zoom ? '' : g.nama;
                    im.loading = 'lazy';
                    el.append(im);
                    return el;
                }
                const s = document.createElement('span');
                s.className = 'foto-thumb foto-thumb-kosong ' + kelas;
                s.title = 'Belum ada foto';
                s.setAttribute('role', 'img');
                s.setAttribute('aria-label', 'Belum ada foto');
                s.innerHTML = '<i class="bi bi-image" aria-hidden="true"></i>';
                return s;
            };

            /* ---------- Modal berpindah-pindah (Detail -> Edit / Edit Foto -> kembali ke Detail) ---------- */
            const elDetail = byId('modalDetail');
            const elEdit = byId('modalEdit');
            const elEditFoto = byId('modalEditFoto');
            const modalDetail = new bootstrap.Modal(elDetail);
            const modalEdit = bootstrap.Modal.getOrCreateInstance(elEdit);
            const modalEditFoto = bootstrap.Modal.getOrCreateInstance(elEditFoto);

            let jenisAktif = null;   // id jenis yang sedang dibuka di Detail
            let pindah = false;      // true saat Detail ditutup hanya untuk membuka modal lain

            const dariDetailKe = (instansi, siap) => {
                pindah = true;
                elDetail.addEventListener('hidden.bs.modal', () => {
                    siap();
                    instansi.show();
                }, { once: true });
                modalDetail.hide();
            };

            elDetail.addEventListener('hidden.bs.modal', () => {
                if (pindah) { pindah = false; return; }
                jenisAktif = null;
            });
            const kembaliKeDetail = () => { if (jenisAktif) modalDetail.show(); };
            elEdit.addEventListener('hidden.bs.modal', kembaliKeDetail);
            elEditFoto.addEventListener('hidden.bs.modal', kembaliKeDetail);

            /* ---------- Pilih unit untuk cetak massal ---------- */
            const KUNCI = 'barcodeTerpilih';
            const detBody = byId('detBody');
            const pilihSemua = byId('pilihSemua');
            const labelPilih = byId('jumlahDipilih');
            const btnCetak = byId('btnCetakTerpilih');
            const btnBatalPilih = byId('btnBatalPilih');
            const pilihUkuran = byId('ukuranStiker');

            try {
                const tersimpan = localStorage.getItem('ukuranStiker');
                if (tersimpan && [...pilihUkuran.options].some(o => o.value === tersimpan)) pilihUkuran.value = tersimpan;
            } catch (e) {}
            pilihUkuran.addEventListener('change', () => {
                try { localStorage.setItem('ukuranStiker', pilihUkuran.value); } catch (e) {}
            });

            const baca = () => {
                try {
                    const data = JSON.parse(sessionStorage.getItem(KUNCI));
                    return new Set(Array.isArray(data) ? data.map(Number).filter(Number.isInteger) : []);
                } catch (e) { return new Set(); }
            };
            const dipilih = baca();
            const simpan = () => {
                try { sessionStorage.setItem(KUNCI, JSON.stringify([...dipilih])); } catch (e) {}
            };
            const batas = () => popup({
                icon: 'info', title: 'Batas tercapai',
                text: `Maksimal ${MAKS} barang per sekali cetak.`,
            });

            const semuaBaris = () => [...detBody.querySelectorAll('tr[data-id]')];
            const barisTampak = () => semuaBaris().filter(tr => !tr.hidden);

            const renderPilih = () => {
                semuaBaris().forEach(tr => {
                    const aktif = dipilih.has(Number(tr.dataset.id));
                    tr.querySelector('.cek-barang').checked = aktif;
                    tr.classList.toggle('terpilih', aktif);
                });
                const cek = barisTampak().map(tr => tr.querySelector('.cek-barang'));
                pilihSemua.checked = cek.length > 0 && cek.every(c => c.checked);
                pilihSemua.indeterminate = !pilihSemua.checked && cek.some(c => c.checked);
                labelPilih.textContent = dipilih.size;
                btnCetak.disabled = dipilih.size === 0;
                btnBatalPilih.hidden = dipilih.size === 0;
            };

            detBody.addEventListener('change', e => {
                const c = e.target.closest('.cek-barang');
                if (!c) return;
                const id = Number(c.value);
                if (c.checked && !dipilih.has(id) && dipilih.size >= MAKS) {
                    c.checked = false;
                    return batas();
                }
                c.checked ? dipilih.add(id) : dipilih.delete(id);
                simpan();
                renderPilih();
            });

            pilihSemua.addEventListener('change', () => {
                for (const tr of barisTampak()) {
                    const id = Number(tr.dataset.id);
                    if (!pilihSemua.checked) { dipilih.delete(id); continue; }
                    if (!dipilih.has(id) && dipilih.size >= MAKS) { batas(); break; }
                    dipilih.add(id);
                }
                simpan();
                renderPilih();
            });

            btnBatalPilih.addEventListener('click', () => {
                dipilih.clear();
                simpan();
                renderPilih();
            });

            btnCetak.addEventListener('click', () => {
                if (!dipilih.size) return;
                const query = new URLSearchParams();
                dipilih.forEach(id => query.append('ids[]', id));
                query.set('ukuran', pilihUkuran.value);
                window.open(`${URL_CETAK_MASSAL}?${query}`, '_blank');
            });

            /* ---------- Modal Detail ---------- */
            const kosongUnit = document.createElement('tr');
            kosongUnit.className = 'baris-kosong';
            kosongUnit.hidden = true;
            kosongUnit.innerHTML = '<td colspan="5" class="text-center kosong"><i class="bi bi-search"></i>'
                + '<div class="fw-bold mb-1">Unit tidak ditemukan</div><div class="small">Coba kode barcode yang lain.</div></td>';

            const barisUnit = u => {
                const tr = document.createElement('tr');
                tr.dataset.id = u.id;
                tr.dataset.cari = (u.kode + ' ' + u.nama).toLowerCase();
                tr.innerHTML =
                    '<td class="col-pilih"><input type="checkbox" class="form-check-input cek-barang"></td>'
                    + '<td class="col-kode"><span class="kode"></span></td>'
                    + '<td class="col-nama fw-semibold text-break"></td>'
                    + '<td class="col-status"><span class="badge rounded-pill badge-status"></span></td>'
                    + '<td class="col-aksi text-end"><div class="d-inline-flex flex-wrap justify-content-end gap-2">'
                    + '<button type="button" class="btn btn-outline-info tombol-aksi btn-cetak-unit"><i class="bi bi-upc-scan"></i> Cetak</button>'
                    + '<button type="button" class="btn btn-outline-secondary tombol-aksi btn-edit-unit"><i class="bi bi-pencil-square"></i> Edit</button>'
                    + '<button type="button" class="btn btn-outline-danger tombol-aksi btn-hapus-unit"><i class="bi bi-trash3"></i> Hapus</button>'
                    + '</div></td>';
                const cek = tr.querySelector('.cek-barang');
                cek.value = u.id;
                cek.setAttribute('aria-label', 'Pilih ' + u.kode);
                tr.querySelector('.kode').textContent = u.kode;
                tr.querySelector('.col-nama').textContent = u.nama;
                const badge = tr.querySelector('.badge-status');
                badge.textContent = u.status;
                badge.classList.add(u.status === 'Tersedia' ? 'b-hijau' : 'b-emas');
                return tr;
            };

            const bukaDetail = id => {
                const g = GRUP[id];
                if (!g) return;
                jenisAktif = String(id);
                byId('judulDetail').textContent = g.nama;
                byId('detFoto').replaceChildren(buatThumb(g, { zoom: false, kelas: 'foto-thumb-md' }));
                byId('detKategori').textContent = g.kat_nama;
                byId('detTotal').textContent = g.unit + ' unit';
                byId('detTersedia').textContent = g.tersedia + ' tersedia';
                byId('detDipinjam').textContent = g.dipinjam + ' dipinjam';
                byId('detDipinjam').hidden = !g.dipinjam;
                byId('detCari').value = '';
                detBody.replaceChildren(...g.units.map(barisUnit), kosongUnit);
                kosongUnit.hidden = true;
                renderPilih();
                modalDetail.show();
            };

            byId('detCari').addEventListener('input', e => {
                const q = e.target.value.trim().toLowerCase();
                semuaBaris().forEach(tr => { tr.hidden = !!q && !tr.dataset.cari.includes(q); });
                kosongUnit.hidden = barisTampak().length > 0;
                renderPilih();
            });

            // Tombol Cetak / Edit / Hapus per unit
            detBody.addEventListener('click', e => {
                const tombol = e.target.closest('button');
                const tr = e.target.closest('tr[data-id]');
                if (!tombol || !tr) return;
                const g = GRUP[jenisAktif];
                const u = g && g.units.find(x => String(x.id) === tr.dataset.id);
                if (!u) return;

                if (tombol.classList.contains('btn-cetak-unit')) {
                    window.open(url(URL_CETAK, u.id), '_blank');
                } else if (tombol.classList.contains('btn-edit-unit')) {
                    dariDetailKe(modalEdit, () => {
                        formEdit.action = url(URL_UBAH, u.id);
                        byId('editNama').value = u.nama;
                        byId('editKategori').value = g.kat_id;
                    });
                } else if (tombol.classList.contains('btn-hapus-unit')) {
                    popup({
                        icon: 'warning',
                        title: 'Yakin hapus barang?',
                        text: `${u.kode} (${u.nama})`,
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        customClass: { confirmButton: 'swal-merah' },
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Tidak',
                    }).then(r => {
                        if (r.isConfirmed) {
                            formHapus.action = url(URL_HAPUS, u.id);
                            formHapus.submit();
                        }
                    });
                }
            });

            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', () => bukaDetail(btn.dataset.id));
            });

            /* ---------- Modal Edit Foto (disimpan ke server: public/uploads/barang) ---------- */
            const elPickerFoto = byId('pickerEditFoto');
            const pickerFoto = new FotoPicker(elPickerFoto);
            const formEditFoto = byId('formEditFoto');
            const btnSimpanFoto = byId('btnSimpanFoto');
            const catatanFoto = byId('catatanEditFoto');
            let jenisFoto = null;

            const catatFoto = () => {
                const g = GRUP[jenisFoto];
                if (!g) return;
                catatanFoto.textContent = pickerFoto.url
                    ? `Foto baru akan menggantikan foto lama dan dipakai oleh ${g.unit} unit jenis ini.`
                    : `Foto ini dipakai oleh ${g.unit} unit jenis ini. Jika diganti, semua unit ikut berubah.`;
            };

            elPickerFoto.addEventListener('foto:berubah', e => {
                btnSimpanFoto.disabled = !e.detail.baru;
                catatFoto();
            });

            const siapkanEditFoto = id => {
                const g = GRUP[id];
                jenisFoto = String(id);
                byId('fotoJenisNama').textContent = g.nama;
                byId('fotoJenisMeta').textContent = `${g.kat_nama}, ${g.unit} unit`;
                pickerFoto.setAsal(g.foto);
                catatFoto();
            };

            const bukaEditFoto = id => {
                if (!GRUP[id]) return;
                siapkanEditFoto(id);
                modalEditFoto.show();
            };

            document.querySelectorAll('.btn-edit-foto').forEach(btn => {
                btn.addEventListener('click', () => bukaEditFoto(btn.dataset.id));
            });
            byId('btnDetailEditFoto').addEventListener('click', () => {
                if (!jenisAktif) return;
                dariDetailKe(modalEditFoto, () => siapkanEditFoto(jenisAktif));
            });
            elEditFoto.addEventListener('hidden.bs.modal', () => pickerFoto.batal());

            formEditFoto.addEventListener('submit', e => {
                e.preventDefault();
                const g = GRUP[jenisFoto];
                const file = byId('editFotoJenis').files[0];
                if (!g || !file) return;

                popup({
                    icon: 'question',
                    title: 'Yakin ingin mengganti foto?',
                    text: `${g.nama} (${g.unit} unit)`,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ganti',
                    cancelButtonText: 'Batal',
                }).then(r => {
                    if (!r.isConfirmed) return;
                    formEditFoto.action = URL_FOTO;
                    formEditFoto.querySelectorAll('input[name="ids[]"]').forEach(i => i.remove());
                    g.units.forEach(u => {
                        const h = document.createElement('input');
                        h.type = 'hidden';
                        h.name = 'ids[]';
                        h.value = u.id;
                        formEditFoto.append(h);
                    });
                    jenisAktif = null;
                    formEditFoto.submit();
                });
            });

            /* ---------- Form Tambah: jenis, kategori, ruang, lemari, kode barang, foto, preview ---------- */
            const OPSI_BARU = '__baru__';
            const selJenis = byId('tambahJenis');
            const inNama = byId('tambahNama');
            const inSingkat = byId('tambahSingkat');
            const selKatTambah = byId('tambahKategori');
            const inKatBaru = byId('tambahKategoriBaru');
            const selRuang = byId('tambahRuang');
            const inRuangBaru = byId('tambahRuangBaru');
            const selLemari = byId('tambahLemari');
            const inLemariBaru = byId('tambahLemariBaru');
            const previewKode = byId('previewKode');
            const pickerTambah = new FotoPicker(byId('pickerTambahFoto'));
            const catatanTambah = byId('catatanTambahFoto');

            const bersih = t => t.toUpperCase().replace(/[^A-Z0-9]/g, '');
            const nilaiPilihan = (sel, input) => sel.value === OPSI_BARU ? input.value : sel.value;

            // Harus sama dengan BarangController::singkatan()
            // huruf pertama + konsonan berikutnya, maks 3 huruf: Komputer -> KMP
            const singkatan = nama => {
                const huruf = nama.toUpperCase().replace(/[^A-Z]/g, '');
                if (!huruf) return '';
                let hasil = huruf[0];
                for (let i = 1; i < huruf.length && hasil.length < 3; i++) {
                    if (!'AEIOU'.includes(huruf[i])) hasil += huruf[i];
                }
                return hasil.length < 3 ? huruf.slice(0, 3) : hasil;
            };

            // Tampilkan input manual hanya jika dropdown memilih "baru"
            const aturBaru = (sel, input) => {
                const baru = sel.value === OPSI_BARU;
                input.hidden = !baru;
                input.disabled = !baru;
                input.required = baru;
            };

            let singkatManual = !!inSingkat.value;   // true jika admin mengetik sendiri kode barangnya

            const updatePreview = () => {
                const ruang = bersih(nilaiPilihan(selRuang, inRuangBaru)) || 'RUANG';
                const lemari = bersih(nilaiPilihan(selLemari, inLemariBaru)) || 'LEMARI';
                const kode = bersih(inSingkat.value) || 'KODE';
                previewKode.textContent = `${ruang}-${lemari}-${kode}-####`;
            };

            const isiSingkat = () => {
                if (!singkatManual) inSingkat.value = singkatan(inNama.value);
            };

            const sinkronFotoTambah = () => {
                const g = GRUP[selJenis.value];
                pickerTambah.setAsal(g ? g.foto : null);
                pickerTambah.setTerkunci(!!(g && g.foto));
                catatanTambah.textContent = !g
                    ? 'Foto bersifat opsional dan akan dipakai oleh semua unit jenis ini.'
                    : g.foto
                        ? 'Jenis ini sudah punya foto. Unit baru otomatis memakainya. Untuk mengganti, gunakan Edit Foto.'
                        : 'Jenis ini belum punya foto. Foto yang dipilih akan dipakai oleh semua unit jenis ini.';
            };

            selJenis.addEventListener('change', () => {
                const g = GRUP[selJenis.value];
                sinkronFotoTambah();
                if (g) {
                    inNama.value = g.nama;
                    if (g.kat_id) selKatTambah.value = g.kat_id;
                    aturBaru(selKatTambah, inKatBaru);
                    // Jenis lama: pakai singkatan yang sudah dipakai unit-unitnya agar kode konsisten
                    singkatManual = false;
                    inSingkat.value = g.singkat || singkatan(g.nama);
                }
                updatePreview();
            });

            // Jika nama diubah manual, dianggap jenis baru dan singkatan ikut berubah
            inNama.addEventListener('input', () => {
                if (selJenis.value) {
                    selJenis.value = '';
                    sinkronFotoTambah();
                }
                isiSingkat();
                updatePreview();
            });

            inSingkat.addEventListener('input', () => {
                singkatManual = inSingkat.value.trim() !== '';
                if (!singkatManual) isiSingkat();
                updatePreview();
            });

            selKatTambah.addEventListener('change', () => aturBaru(selKatTambah, inKatBaru));
            selRuang.addEventListener('change', () => { aturBaru(selRuang, inRuangBaru); updatePreview(); });
            selLemari.addEventListener('change', () => { aturBaru(selLemari, inLemariBaru); updatePreview(); });
            [inRuangBaru, inLemariBaru].forEach(el => el.addEventListener('input', updatePreview));

            byId('modalTambah').addEventListener('hidden.bs.modal', () => {
                pickerTambah.batal();
                singkatManual = false;
                isiSingkat();
                updatePreview();
            });

            sinkronFotoTambah();
            aturBaru(selKatTambah, inKatBaru);
            aturBaru(selRuang, inRuangBaru);
            aturBaru(selLemari, inLemariBaru);
            isiSingkat();
            updatePreview();

            /* ---------- Pencarian, filter, urutan, dan pagination daftar jenis (di browser) ---------- */
            const formCari = byId('formCari');
            const inCari = formCari.querySelector('[name="q"]');
            const selKat = formCari.querySelector('[name="kategori"]');
            const selRuangCari = formCari.querySelector('[name="ruang"]');
            const selLemariCari = formCari.querySelector('[name="lemari"]');
            const selUrut = formCari.querySelector('[name="urut"]');
            const btnReset = byId('btnResetCari');
            const barisJenis = [...document.querySelectorAll('.tabel-jenis tbody tr.baris-jenis')];
            const kosongJenis = byId('kosongJenis');
            const tbodyJenis = kosongJenis.parentElement;
            const jmlJenis = byId('jmlJenis');
            const paginasi = byId('paginasi');
            const infoHalaman = byId('infoHalaman');

            const PER_HALAMAN = 10;
            let halaman = 1;
            let cocok = [];

            const banding = (a, b) => a.dataset.nama.localeCompare(b.dataset.nama, 'id', { numeric: true });
            const URUTAN = {
                'default':      (a, b) => Number(a.dataset.urut) - Number(b.dataset.urut),
                'nama-az':      (a, b) => banding(a, b),
                'nama-za':      (a, b) => banding(b, a),
                'unit-banyak':  (a, b) => (Number(b.dataset.unit) - Number(a.dataset.unit)) || banding(a, b),
                'unit-sedikit': (a, b) => (Number(a.dataset.unit) - Number(b.dataset.unit)) || banding(a, b),
            };

            // Cocok jika ada SATU unit yang berada di ruang DAN lemari yang dipilih
            const cocokTempat = (tr, ruang, lemari) => {
                if (!ruang && !lemari) return true;
                return (tr.dataset.tempat || '').split(' ').filter(Boolean).some(t => {
                    const [r, l] = t.split('|');
                    return (!ruang || r === ruang) && (!lemari || l === lemari);
                });
            };

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
                jmlJenis.textContent = total;
                infoHalaman.textContent = total
                    ? `Menampilkan ${mulai + 1}-${Math.min(mulai + PER_HALAMAN, total)} dari ${total} jenis`
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
                document.querySelector('.tabel-jenis').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            const saring = () => {
                const q = inCari.value.trim().toLowerCase();
                const k = selKat.value;
                const r = selRuangCari.value;
                const l = selLemariCari.value;
                const u = selUrut.value;

                // Urutkan dulu, lalu susun ulang barisnya di tabel
                const urut = [...barisJenis].sort(URUTAN[u] || URUTAN['default']);
                urut.forEach(tr => tbodyJenis.insertBefore(tr, kosongJenis));

                cocok = urut.filter(tr =>
                    (!q || tr.dataset.cari.includes(q))
                    && (!k || tr.dataset.kategori === k)
                    && cocokTempat(tr, r, l));

                halaman = 1;
                renderHalaman();
                btnReset.hidden = !(q || k || r || l || u !== 'default');
            };

            formCari.addEventListener('submit', e => { e.preventDefault(); saring(); });
            inCari.addEventListener('input', saring);
            [selKat, selRuangCari, selLemariCari, selUrut].forEach(el => el.addEventListener('change', saring));
            btnReset.addEventListener('click', () => {
                inCari.value = '';
                selKat.value = '';
                selRuangCari.value = '';
                selLemariCari.value = '';
                selUrut.value = 'default';
                saring();
            });
            saring();

            /* ---------- Lihat foto ukuran besar ---------- */
            const modalLihat = new bootstrap.Modal(byId('modalLihatFoto'));
            document.addEventListener('click', e => {
                const btn = e.target.closest('.btn-lihat-foto');
                if (!btn) return;
                byId('fotoBesar').src = btn.dataset.foto;
                byId('fotoBesar').alt = btn.dataset.judul;
                byId('judulLihatFoto').textContent = btn.dataset.judul;
                modalLihat.show();
            });

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