<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="server-now" content="{{ now()->timestamp }}">
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
            --c-total: #1d4fa8;
            --c-baik: #0f9d7a;
            --c-rusak: #c2334d;
            --c-diperbaiki: #7c4dcc;
            --c-dipakai: #e2701a;
            --c-dipinjam: #d99a14;
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
            --c-total: #6ea8ff;
            --c-baik: #4fd1ac;
            --c-rusak: #ff7a8e;
            --c-diperbaiki: #b69cff;
            --c-dipakai: #ff9f5a;
            --c-dipinjam: #f1b53d;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; scroll-behavior: smooth; }
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

        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            font-weight: 700;
            border-radius: 10px;
        }

        h1 { font-size: clamp(1.75rem, 6vw, 3.2rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.08; overflow-wrap: break-word; }
        h2 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.015em; }
        .lead { max-width: 36rem; color: var(--bs-secondary-color); font-size: clamp(1rem, 2.2vw, 1.15rem); }

        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; overflow: hidden; }
        .papan-kepala { padding: 20px 24px; border-bottom: 1px solid var(--bs-border-color); }
        .papan-isi { padding: 24px; }
        .ikon-kotak { width: 44px; height: 44px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; background: color-mix(in srgb, var(--biru) 14%, transparent); color: var(--biru); }

        .kondisi-kepala { display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: .25rem 1rem; }

        .kondisi { list-style: none; margin: 0; padding: 0; }
        .kondisi li { display: grid; grid-template-columns: 6.5rem 1fr 2.75rem; align-items: center; gap: 14px; padding: .7rem 0; border-bottom: 1px solid var(--bs-border-color); }
        .kondisi li:last-child { border-bottom: 0; }
        .kondisi .nama { display: flex; align-items: center; gap: 8px; font-weight: 500; }
        .kondisi .nama::before { content: ""; width: 10px; height: 10px; border-radius: 50%; background: var(--c); flex-shrink: 0; }
        .kondisi .jumlah { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; color: var(--c); }
        .jalur { height: 6px; border-radius: 3px; background: var(--bs-border-color); overflow: hidden; }
        .jalur > span { display: block; height: 100%; border-radius: 3px; background: var(--c); }
        .k-total      { --c: var(--c-total); }
        .k-baik       { --c: var(--c-baik); }
        .k-rusak      { --c: var(--c-rusak); }
        .k-diperbaiki { --c: var(--c-diperbaiki); }
        .k-dipakai    { --c: var(--c-dipakai); }
        .k-dipinjam   { --c: var(--c-dipinjam); }
        .peringatan { color: var(--telat); font-weight: 500; }

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

        .alert-blokir {
            display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px; padding: 14px 16px;
            border-radius: 12px; color: var(--bs-body-color);
            border: 1.5px solid color-mix(in srgb, var(--telat) 45%, transparent);
            background: color-mix(in srgb, var(--telat) 10%, transparent);
        }
        .alert-blokir > i { font-size: 1.5rem; line-height: 1.1; color: var(--telat); flex-shrink: 0; }
        .alert-blokir .judul { font-weight: 800; color: var(--telat); }
        .alert-blokir .teks { font-size: .92rem; color: var(--bs-secondary-color); margin-top: 2px; overflow-wrap: anywhere; }
        .alert-blokir.kuning {
            border-color: color-mix(in srgb, var(--emas-teks) 50%, transparent);
            background: color-mix(in srgb, var(--emas-teks) 10%, transparent);
        }
        .alert-blokir.kuning > i, .alert-blokir.kuning .judul { color: var(--emas-teks); }

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

        #riwayat { scroll-margin-top: 100px; }

        .filter-bar .form-control,
        .filter-bar .form-select,
        .filter-bar .input-group-text { background-color: var(--input); border-color: var(--bs-border-color); min-height: 44px; }
        .filter-bar .form-select { color: var(--bs-body-color); border-radius: 10px; }
        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus { border-color: var(--biru); box-shadow: 0 0 0 .2rem rgba(110, 168, 255, .25); }
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

        .badge-riw { font-weight: 700; border: 1px solid; }
        .badge-selesai { background: rgba(79, 214, 156, .12); border-color: rgba(79, 214, 156, .4); color: var(--ada); }
        .badge-pinjam { background: rgba(241, 181, 61, .12); border-color: rgba(241, 181, 61, .45); color: var(--emas-teks); }
        .badge-telat { background: rgba(214, 51, 79, .1); border-color: rgba(214, 51, 79, .4); color: var(--telat); }

        .sisa-waktu { font-weight: 600; font-variant-numeric: tabular-nums; white-space: nowrap; }
        .sisa-aman   { color: var(--ada); }
        .sisa-hampir { color: var(--emas-teks); }
        .sisa-telat  { color: var(--telat); }

        .pagination {
            --bs-pagination-bg: transparent;
            --bs-pagination-color: var(--biru);
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

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }

        @media (max-width: 991.98px) {
            .papan-kepala, .papan-isi { padding: 20px; }
            .daftar .list-group-item { padding-left: 20px; padding-right: 20px; }
            .table thead th { padding: 12px; }
            .table tbody td { padding: 12px; font-size: .95rem; }
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
            .kondisi li { grid-template-columns: 6rem 1fr 2.25rem; gap: 10px; }
            .layanan { padding: 1.5rem; }
            .btn-kembali { width: 100%; }
            .aturan { padding: 1rem !important; }
            footer .wadah { text-align: center; }

            .form-control { font-size: 1rem; }
            .detail-barang { flex-direction: column; align-items: stretch; }
            .foto-thumb { width: 100%; height: 190px; }

            .info-bawah { align-items: stretch !important; text-align: center; }
            .info-bawah .pagination { justify-content: center; }
            .filter-bar .form-control, .filter-bar .form-select { font-size: 1rem; }

            .table-card { border-radius: 12px; }
            .tabel-riwayat, .tabel-riwayat tbody, .tabel-riwayat tr, .tabel-riwayat td { display: block; width: 100%; }
            .tabel-riwayat thead { display: none; }
            .tabel-riwayat tr { padding: .9rem 1rem; border-bottom: 1px solid var(--bs-border-color); }
            .tabel-riwayat tr:last-child { border-bottom: 0; }
            .tabel-riwayat td {
                display: flex; justify-content: space-between; align-items: center; gap: 1rem;
                padding: .3rem 0; border: 0; text-align: right; box-shadow: none;
                font-size: 1rem;
            }
            .tabel-riwayat td::before {
                content: attr(data-label);
                flex-shrink: 0; text-align: left;
                color: var(--bs-secondary-color); font-weight: 500; font-size: .9rem;
            }
            .tabel-riwayat td[data-label="No"] { display: none; }
            .tabel-riwayat td[data-label="Nama Barang"] { font-weight: 700; }
            .tabel-riwayat td.kosong { display: block; text-align: center; }
            .tabel-riwayat td.kosong::before { content: none; }
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
            .kondisi li { grid-template-columns: 5.5rem 1fr 2rem; gap: 8px; }
            .layanan { padding: 1.25rem; }
            .tabel-riwayat tr { padding: .8rem; }
            .tabel-riwayat td { font-size: .95rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .navbar { padding-top: .35rem; padding-bottom: .35rem; }
            .logo { width: 38px; height: 38px; }
        }

        @media (min-width: 1400px) { .wadah { max-width: 1200px; } }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .layanan { transition: none; }
            .layanan:hover { transform: none; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @php
        $total = (int) ($totalBarang ?? 0);
        $dasar = max($total, 1);
        $lebar = fn ($n) => round(min($n / $dasar * 100, 100), 2);

        $ringkasan = [
            ['kelas' => 'k-total',      'nama' => 'Total',      'jumlah' => $total],
            ['kelas' => 'k-baik',       'nama' => 'Baik',       'jumlah' => (int) ($barangBaik ?? 0)],
            ['kelas' => 'k-rusak',      'nama' => 'Rusak',      'jumlah' => (int) ($barangRusak ?? 0)],
            ['kelas' => 'k-diperbaiki', 'nama' => 'Diperbaiki', 'jumlah' => (int) ($barangDiperbaiki ?? 0)],
            ['kelas' => 'k-dipakai',    'nama' => 'Digunakan',  'jumlah' => (int) ($barangDipakai ?? 0)],
            ['kelas' => 'k-dipinjam',   'nama' => 'Dipinjam',   'jumlah' => (int) ($sedangDipinjam ?? 0)],
        ];
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
                    <li class="nav-item"><a class="nav-link" href="#riwayat">Riwayat</a></li>
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
                <div class="papan p-3 p-md-4" aria-labelledby="judul-kondisi">
                    <div class="kondisi-kepala mb-2">
                        <h2 id="judul-kondisi" class="h5 fw-bold mb-0">Peminjaman dan kondisi barang saat ini</h2>
                        <span class="small text-secondary">{{ $total }} barang terdaftar</span>
                    </div>

                    <ul class="kondisi">
                        @foreach ($ringkasan as $r)
                            <li class="{{ $r['kelas'] }}">
                                <span class="nama">{{ $r['nama'] }}</span>
                                <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($r['jumlah']) }}%"></span></span>
                                <span class="jumlah">{{ $r['jumlah'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

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
            </div>
        </section>

        <section id="riwayat" aria-labelledby="judul-riwayat" class="mb-4">
            <h2 id="judul-riwayat" class="mb-2">Riwayat Peminjaman dan Pengembalian</h2>
            <p class="text-secondary mb-4" style="max-width: 560px;">
                Pantau transaksi yang berjalan dan yang sudah selesai, lengkap dengan siapa yang sedang memegang barang.
            </p>

            <form method="GET" action="{{ url('/peminjam') }}#riwayat" class="filter-bar row g-2 mb-4">
                <div class="col-12 col-md">
                    <div class="input-group">
                        <span class="input-group-text text-secondary"><i class="bi bi-search"></i></span>
                        <input type="search" name="cari" value="{{ request('cari') }}"
                               class="form-control" placeholder="Cari nama barang..." maxlength="100"
                               aria-label="Cari nama barang">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <select name="kategori" class="form-select" aria-label="Filter kategori" onchange="this.form.submit()">
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
                    @if (request()->filled('cari') || request()->filled('kategori'))
                        <a href="{{ url('/peminjam') }}#riwayat" class="btn btn-outline-secondary d-inline-flex align-items-center">Reset</a>
                    @endif
                </div>
            </form>

            <div class="table-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 tabel-riwayat">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Sisa Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayat as $row)
                                @php
                                    if ($row->tgl_kembali) {
                                        $status = 'Selesai';
                                        $kelas  = 'badge-selesai';
                                    } elseif ($row->tgl_jatuh_tempo && $row->tgl_jatuh_tempo->isPast()) {
                                        $status = 'Terlambat';
                                        $kelas  = 'badge-telat';
                                    } else {
                                        $status = 'Dipinjam';
                                        $kelas  = 'badge-pinjam';
                                    }
                                @endphp
                                <tr>
                                    <td data-label="No">{{ $riwayat->firstItem() + $loop->index }}</td>
                                    <td data-label="Nama Barang">
                                        {{ $row->barang->nama_barang ?? '-' }}
                                        @if ($row->semuaItem->count() > 1)
                                            <small class="d-block text-secondary">+ {{ $row->semuaItem->count() - 1 }} barang lain</small>
                                        @endif
                                    </td>
                                    <td data-label="Peminjam">
                                        @php
                                            $pj    = $row->peminjam;
                                            $tipe  = $pj->tipe_peminjam ?? null;
                                            $ketPj = trim((string) ($pj->keterangan ?? ''));
                                            $jabPj  = trim((string) ($pj->jabatan ?? ''));
                                            $infoPj = $tipe;
                                            if ($tipe === 'Siswa' && $ketPj !== '') {
                                                $infoPj = 'Siswa - ' . (stripos($ketPj, 'kelas') === 0 ? $ketPj : 'Kelas ' . $ketPj);
                                            } elseif ($jabPj !== '') {
                                                $infoPj = $jabPj;
                                            } elseif ($tipe === 'Staff' && $ketPj !== '') {
                                                $infoPj = 'Staff - ' . $ketPj;
                                            }
                                        @endphp
                                        <div>
                                            <span class="fw-semibold">{{ $pj->nama_peminjam ?? '-' }}</span>
                                            @if ($infoPj)
                                                <small class="d-block text-secondary">{{ $infoPj }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td data-label="Tanggal Pinjam">{{ $row->tgl_pinjam?->format('d/m/Y H:i') }}</td>
                                    <td data-label="Tanggal Kembali">
                                        @if ($row->tgl_kembali)
                                            {{ $row->tgl_kembali->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td data-label="Sisa Waktu">
                                        @if ($row->tgl_kembali)
                                            <span class="text-secondary">Selesai</span>
                                        @elseif ($row->tgl_jatuh_tempo)
                                            <span class="sisa-waktu" data-tempo="{{ $row->tgl_jatuh_tempo->timestamp }}">...</span>
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td data-label="Status"><span class="badge rounded-pill badge-riw {{ $kelas }}">{{ $status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="kosong text-center text-secondary py-5">
                                        @if (request()->filled('cari') || request()->filled('kategori'))
                                            Tidak ada transaksi yang cocok dengan pencarian.
                                        @else
                                            Belum ada transaksi peminjaman.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="info-bawah d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                <p class="text-secondary small mb-0">Total {{ $riwayat->total() }} transaksi.</p>
                @if ($riwayat->hasPages())
                    {{ $riwayat->links('pagination::bootstrap-5') }}
                @endif
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

        (function () {
            const meta = document.querySelector('meta[name="server-now"]');
            const offset = meta ? (parseInt(meta.content, 10) * 1000 - Date.now()) : 0;

            const format = (detik) => {
                const h = Math.floor(detik / 86400);
                const j = Math.floor((detik % 86400) / 3600);
                const m = Math.floor((detik % 3600) / 60);
                const s = detik % 60;
                if (h > 0) return h + ' hari ' + j + ' jam ' + m + ' mnt';
                if (j > 0) return j + ' jam ' + m + ' mnt ' + s + ' dtk';
                return m + ' mnt ' + s + ' dtk';
            };

            function tick() {
                const sekarang = Date.now() + offset;

                document.querySelectorAll('.sisa-waktu').forEach(el => {
                    const tempo   = parseInt(el.dataset.tempo, 10) * 1000;
                    const selisih = Math.floor((tempo - sekarang) / 1000);
                    const telat   = selisih < 0;
                    const detik   = Math.abs(selisih);

                    el.textContent = telat ? 'Terlambat ' + format(detik) : format(detik) + ' lagi';
                    el.classList.toggle('sisa-telat', telat);
                    el.classList.toggle('sisa-hampir', !telat && detik < 86400);
                    el.classList.toggle('sisa-aman', !telat && detik >= 86400);

                    if (telat) {
                        const badge = el.closest('tr')?.querySelector('.badge-riw');
                        if (badge && badge.textContent.trim() !== 'Terlambat') {
                            badge.textContent = 'Terlambat';
                            badge.classList.remove('badge-pinjam');
                            badge.classList.add('badge-telat');
                        }
                    }
                });
            }

            tick();
            setInterval(tick, 1000);
        })();

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

            const spasi = t => String(t).replace(/([A-Za-z]+)(\d+)/g, '$1 $2');
            const labelRuang = k => {
                const t = String(k ?? '').trim();
                if (!t) return '';
                return /^LAB\b/i.test(t) ? spasi(t) : 'LAB ' + spasi(t);
            };
            const labelTempat = k => {
                const t = String(k ?? '').trim();
                if (!t) return '';
                return spasi(t.replace(/^LMR/i, 'LEMARI'));
            };

            const LABEL_BLOKIR = {
                rusak: 'Rusak',
                diperbaiki: 'Diperbaiki',
                terkunci: 'Terkunci',
                digunakan: 'Digunakan',
                dipinjam: 'Dipinjam',
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

            const blokAlert = b => {
                const kuning = b.jenis === 'dipinjam';
                const kotak = el('div', 'alert-blokir' + (kuning ? ' kuning' : ''));
                kotak.setAttribute('role', 'alert');

                const ikon = el('i', 'bi ' + (b.jenis === 'terkunci' ? 'bi-lock-fill' : 'bi-exclamation-triangle-fill'));
                ikon.setAttribute('aria-hidden', 'true');

                const isi = el('div');
                isi.style.minWidth = '0';
                isi.append(
                    el('div', 'judul', b.judul || 'Barang tidak bisa dipinjam'),
                    el('div', 'teks', b.pesan || '')
                );

                kotak.append(ikon, isi);
                return kotak;
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
                        const r = el('div', null, labelRuang(d.ruang));
                        r.append(el('small', null, 'Ruang'));
                        baris.append(r);
                    }
                    if (d.lemari) {
                        const l = el('div', null, labelTempat(d.lemari));
                        l.append(el('small', null, 'Tempat'));
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
                const blokir = d.blokir || null;
                const merk = String(d.merk_tipe ?? d.merk ?? d.merk_type ?? '').trim();

                const isi = el('div', 'detail-isi');
                isi.append(el('div', 'detail-nama', d.nama), el('span', 'detail-kode', d.kode));

                const status = el('div', 'mt-3');
                if (blokir) {
                    const kelasBlokir = blokir.jenis === 'dipinjam' ? 'b-emas' : 'b-merah';
                    status.append(el('span', 'badge rounded-pill badge-status ' + kelasBlokir, LABEL_BLOKIR[blokir.jenis] || 'Tidak tersedia'));
                } else {
                    status.append(el('span', 'badge rounded-pill badge-status b-hijau', 'Tersedia'));
                }
                if (blokir && blokir.jenis === 'dipinjam' && d.terlambat) {
                    status.append(' ', el('span', 'badge rounded-pill badge-status b-merah', 'Terlambat'));
                }
                if (d.terkunci && (!blokir || blokir.jenis !== 'terkunci')) {
                    status.append(' ', el('span', 'badge rounded-pill badge-status b-merah', 'Terkunci'));
                }
                if (d.kondisi) {
                    const kelasKondisi = {Baik: 'b-hijau', Rusak: 'b-merah', Diperbaiki: 'b-emas'}[d.kondisi] || 'b-emas';
                    status.append(' ', el('span', 'badge rounded-pill badge-status ' + kelasKondisi, 'Kondisi: ' + d.kondisi));
                }
                isi.append(status);

                const atas = el('div', 'detail-barang');
                atas.append(buatFoto(d.foto, d.nama), isi);

                const rincian = el('dl', 'rincian');
                rincian.append(itemRincian('Kategori', d.kategori || '-'));
                rincian.append(itemRincian('Merk/Type', merk || '-'));
                rincian.append(itemRincian('Jenis barang', d.nama));
                rincian.append(itemRincian('Kondisi', d.kondisi || '-'));
                rincian.append(itemRincian('Keterangan', d.keterangan || '-'));
                if (d.jatuh_tempo) {
                    rincian.append(itemRincian('Batas pengembalian', d.jatuh_tempo, d.terlambat ? 'telat' : ''));
                }

                const bagian = [];
                if (blokir) bagian.push(blokAlert(blokir));
                bagian.push(atas, blokLokasi(d), rincian);
                hasil.replaceChildren(...bagian);
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
                    } catch (e) {}
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