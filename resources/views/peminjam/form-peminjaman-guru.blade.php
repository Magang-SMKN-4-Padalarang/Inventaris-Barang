<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Peminjaman Guru / Staf - Inventaris Barang</title>
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
            --garis-form: rgba(11, 36, 71, .32);
            --kepala-tabel: rgba(241, 181, 61, .22);
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
            --garis-form: rgba(173, 198, 245, .3);
            --kepala-tabel: rgba(241, 181, 61, .16);
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
        .halaman-form { max-width: 860px; }

        .navbar { background: var(--nav-bg); backdrop-filter: blur(10px); border-bottom: 1px solid var(--bs-border-color); padding-top: .75rem; padding-bottom: .75rem; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; letter-spacing: -.01em; color: var(--bs-body-color); min-width: 0; }
        .navbar-brand span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .logo { width: 56px; height: 56px; border-radius: 10px; object-fit: contain; flex-shrink: 0; }
        .navbar-toggler { min-width: 44px; min-height: 44px; border-color: var(--bs-border-color); }
        .navbar .nav-link { color: var(--bs-secondary-color); font-weight: 500; border-bottom: 2px solid transparent; padding-left: 0; padding-right: 0; }
        .navbar .nav-link:hover { color: var(--bs-body-color); border-color: var(--emas); }
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
            --bs-btn-disabled-color: #1a1405; --bs-btn-disabled-bg: var(--emas); --bs-btn-disabled-border-color: var(--emas);
            font-weight: 700;
        }

        h1 { font-size: clamp(1.7rem, 6vw, 2.6rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.1; overflow-wrap: break-word; }

        .form-control, .form-select { background-color: var(--input); border-color: var(--bs-border-color); min-height: 44px; color: var(--bs-body-color); }
        .form-control:focus, .form-select:focus { border-color: var(--aksen); box-shadow: 0 0 0 .2rem rgba(110, 168, 255, .25); background-color: var(--input); color: var(--bs-body-color); }
        .kode-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .06em; }
        .kode-mono::placeholder { letter-spacing: 0; font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
        .modal-content { background: var(--kartu); border-color: var(--bs-border-color); }
        #ringkasan dd { overflow-wrap: anywhere; }

        /* ===== LEMBAR FORMULIR (meniru formulir kertas, warna mengikuti tema web) ===== */
        .lembar {
            background: var(--kartu);
            border: 1.5px solid var(--garis-form);
            border-radius: 16px;
            overflow: hidden;
        }
        .kop {
            display: grid; grid-template-columns: 84px 1fr 84px; align-items: center; gap: .75rem;
            padding: .9rem 1.1rem; text-align: center; border-bottom: 1.5px solid var(--garis-form);
        }
        .kop .lambang {
            width: 72px; height: 72px; justify-self: center; object-fit: contain;
            background: #fff; border-radius: 12px; padding: 4px; /* logo berlatar putih, tetap rapi di mode gelap */
        }
        .kop .sekolah { font-weight: 800; font-size: 1rem; line-height: 1.35; letter-spacing: .01em; }
        .kop .program { font-weight: 700; color: var(--emas-teks); line-height: 1.5; }
        .kop .alamat { font-size: .8rem; color: var(--bs-secondary-color); line-height: 1.55; }

        .judul-form {
            text-align: center; font-weight: 800; letter-spacing: .14em; padding: .55rem 1rem;
            background: var(--kepala-tabel); color: var(--emas-teks); border-bottom: 1.5px solid var(--garis-form);
        }

        .blok { padding: 1.1rem 1.25rem; border-bottom: 1.5px solid var(--garis-form); }
        .blok:last-child { border-bottom: 0; }

        .baris-info { display: grid; grid-template-columns: 11.5rem 1rem 1fr; align-items: center; gap: .25rem; margin-bottom: .75rem; }
        .baris-info:last-child { margin-bottom: 0; }
        .baris-info .label { font-weight: 700; font-size: .85rem; letter-spacing: .06em; text-transform: uppercase; }
        .baris-info .titik { font-weight: 700; text-align: center; }

        .pilih-tipe { display: flex; gap: .5rem; flex-wrap: wrap; }
        .pilih-tipe input { position: absolute; opacity: 0; pointer-events: none; }
        .pilih-tipe label {
            min-width: 96px; min-height: 44px; display: inline-flex; align-items: center; justify-content: center;
            padding: 0 1.1rem; border: 1.5px solid var(--bs-border-color); border-radius: 999px; background: var(--input);
            font-weight: 600; cursor: pointer; user-select: none;
        }
        .pilih-tipe label:hover { background: var(--hover); }
        .pilih-tipe input:checked + label { background: var(--emas); border-color: var(--emas); color: #1a1405; font-weight: 800; }
        .pilih-tipe input:focus-visible + label { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        .panel-scan {
            border: 1.5px dashed rgba(241, 181, 61, .6); background: rgba(241, 181, 61, .08);
            border-radius: 12px; padding: .9rem 1rem; margin: 1.1rem 1.25rem 0;
        }
        .panel-scan .judul { font-weight: 700; display: flex; align-items: center; gap: .5rem; justify-content: space-between; flex-wrap: wrap; margin-bottom: .5rem; }
        .panel-scan .hitung { font-size: .85rem; color: var(--emas-teks); font-weight: 700; }
        #pesan-scan { min-height: 1.4em; font-size: .9rem; margin-top: .5rem; }

        .tabel-wrap { padding: 1.1rem 1.25rem; }
        .tabel-barang { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .tabel-barang th, .tabel-barang td { border: 1.5px solid var(--garis-form); padding: .35rem .5rem; vertical-align: middle; }
        .tabel-barang th { background: var(--kepala-tabel); text-align: center; font-size: .8rem; letter-spacing: .08em; height: 2.6rem; }
        .tabel-barang td.no { text-align: center; font-weight: 700; }
        .tabel-barang .nama { font-weight: 600; overflow-wrap: anywhere; }
        .tabel-barang .kode { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: .85rem; color: var(--aksen); overflow-wrap: anywhere; }
        .tabel-barang .form-control { min-height: 38px; padding: .25rem .5rem; }
        .tabel-barang .kosong td { text-align: center; color: var(--bs-secondary-color); padding: 1.4rem .5rem; }
        .tabel-barang .col-no { width: 6%; } .tabel-barang .col-nama { width: 29%; } .tabel-barang .col-kode { width: 23%; }
        .tabel-barang .col-jml { width: 13%; } .tabel-barang .col-ket { width: 22%; } .tabel-barang .col-aksi { width: 7%; }
        .btn-hapus { min-width: 40px; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }

        .area-catatan label { font-weight: 700; font-style: italic; margin-bottom: .35rem; }
        .kotak-barcode {
            margin-top: 1rem; border: 1.5px dashed var(--garis-form); border-radius: 12px; padding: 1rem;
            text-align: center; color: var(--bs-secondary-color); font-size: .9rem;
        }
        .kotak-barcode svg { display: block; margin: 0 auto .35rem; opacity: .55; }

        .tanda-tangan { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: center; font-weight: 700; }
        .tanda-tangan .ruang { min-height: 4rem; }
        .tanda-tangan .nama { overflow-wrap: anywhere; }
        .pengembalian { text-align: right; font-weight: 700; margin-top: 1.1rem; }
        .pengembalian .isi { display: inline-block; min-width: 9rem; border-bottom: 1.5px dotted var(--garis-form); height: 1.2em; vertical-align: bottom; }
        .pengembalian small { display: block; font-weight: 400; color: var(--bs-secondary-color); }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }

        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            .btn-kembali { width: 100%; }
            .form-control, .form-select { font-size: 1rem; }

            .kop { grid-template-columns: 56px 1fr 56px; padding: .75rem; gap: .5rem; }
            .kop .lambang { width: 52px; height: 52px; }
            .kop .sekolah { font-size: .8rem; }
            .kop .program { font-size: .78rem; }
            .kop .alamat { font-size: .68rem; }

            .baris-info { grid-template-columns: 1fr; gap: .25rem; margin-bottom: .9rem; }
            .baris-info .titik { display: none; }

            /* tabel jadi kartu per barang */
            .tabel-barang, .tabel-barang tbody { display: block; }
            .tabel-barang thead { display: none; }
            .tabel-barang tr.item {
                display: grid; grid-template-columns: 2.2rem 1fr 1fr 2.8rem; gap: .35rem .6rem;
                grid-template-areas: "no nama nama aksi" ". kode kode ." ". jml ket .";
                border: 1.5px solid var(--garis-form); border-radius: 12px; padding: .7rem; margin-bottom: .7rem;
            }
            .tabel-barang tr.item td { border: 0; padding: 0; }
            .tabel-barang td.no { grid-area: no; align-self: start; }
            .tabel-barang td.c-nama { grid-area: nama; }
            .tabel-barang td.c-kode { grid-area: kode; }
            .tabel-barang td.c-jml { grid-area: jml; }
            .tabel-barang td.c-ket { grid-area: ket; }
            .tabel-barang td.c-aksi { grid-area: aksi; text-align: right; }
            .tabel-barang td[data-label]::before { content: attr(data-label); display: block; font-size: .7rem; letter-spacing: .08em; color: var(--bs-secondary-color); }
            .tabel-barang tr.kosong { display: block; border: 1.5px dashed var(--garis-form); border-radius: 12px; }
            .tabel-barang tr.kosong td { display: block; border: 0; }
        }

        @media (max-width: 575.98px) {
            .blok, .tabel-wrap { padding: .9rem; }
            .panel-scan { margin: .9rem .9rem 0; }
            .lembar { border-radius: 12px; }
            .tanda-tangan { font-size: .9rem; }
            .modal-dialog { margin: .75rem; }
            .modal-footer { flex-direction: column-reverse; }
            .modal-footer .btn { width: 100%; margin: 0; }
            #ringkasan dt { margin-bottom: 0; }
            #ringkasan dd { margin-bottom: .6rem; font-weight: 500; }
        }

        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
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

    <main class="container wadah flex-grow-1 py-4 py-md-5">
        <div class="mx-auto halaman-form">
            <a class="btn btn-outline-secondary btn-sm btn-kembali mb-4" href="{{ url('/peminjam/pinjam') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                Kembali
            </a>

            <h1 class="mb-2">Peminjaman Guru / Staf</h1>
            <p class="text-secondary mb-4">Isi data peminjam, lalu scan barcode setiap barang (maksimal {{ $maksBarang }} barang sekali pinjam). Formulir siap dicetak setelah disimpan.</p>

            <form method="POST" action="{{ url('/peminjam/pinjam-guru') }}" id="form-guru" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->unique() as $pesan)
                            <div>{{ $pesan }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="lembar">
                    <header class="kop">
                        <img class="lambang" src="{{ asset('images/kop-sekolah.jpg') }}" alt="" width="72" height="72">
                        <div>
                            <div class="sekolah">SEKOLAH MENENGAH KEJURUAN NEGERI 4 PADALARANG</div>
                            <div class="program">PENGEMBANGAN PERANGKAT LUNAK DAN GIM</div>
                            <div class="alamat">Jl. Raya Padalarang No.451 Telp. (022) 6805406 Padalarang 40553</div>
                        </div>
                        <img class="lambang" src="{{ asset('images/kop-pplg.jpg') }}" alt="" width="72" height="72">
                    </header>

                    <div class="judul-form">PEMINJAMAN ALAT</div>

                    <section class="blok" aria-label="Data peminjam">
                        <div class="baris-info">
                            <span class="label" id="lbl-tipe">Peminjam sebagai</span><span class="titik" aria-hidden="true">:</span>
                            <div class="pilih-tipe" role="radiogroup" aria-labelledby="lbl-tipe">
                                @foreach ($daftarTipe as $tipe)
                                    <input type="radio" name="tipe_peminjam" id="tipe-{{ strtolower($tipe) }}" value="{{ $tipe }}"
                                           @checked(old('tipe_peminjam', $tipeAwal) === $tipe) required>
                                    <label for="tipe-{{ strtolower($tipe) }}">{{ $tipe === 'Staff' ? 'Staf' : $tipe }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="baris-info">
                            <label class="label" for="nama_peminjam">Nama peminjam</label><span class="titik" aria-hidden="true">:</span>
                            <input type="text" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" class="form-control" maxlength="100" autocomplete="name" required>
                        </div>
                        <div class="baris-info">
                            <label class="label" for="keperluan">Keperluan</label><span class="titik" aria-hidden="true">:</span>
                            <input type="text" id="keperluan" name="keperluan" value="{{ old('keperluan') }}" class="form-control" maxlength="150" placeholder="Contoh: Praktikum jaringan kelas XI" required>
                        </div>
                        <div class="baris-info">
                            <label class="label" for="lama_peminjaman">Lama peminjaman</label><span class="titik" aria-hidden="true">:</span>
                            <input type="text" id="lama_peminjaman" name="lama_peminjaman" value="{{ old('lama_peminjaman') }}" class="form-control" maxlength="50" placeholder="Contoh: 3 hari" required>
                        </div>
                    </section>

                    <div class="panel-scan">
                        <div class="judul">
                            <label for="scan-barcode">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align: -3px;"><path d="M3 5v14M7 5v14M11 5v14M15 5v14M19 5v14M21 5v14"/></svg>
                                Scan barcode barang
                            </label>
                            <span class="hitung" id="hitung-barang" aria-live="polite">0 / {{ $maksBarang }} barang</span>
                        </div>
                        <div class="input-group">
                            <input type="text" id="scan-barcode" class="form-control form-control-lg kode-mono" placeholder="Arahkan scanner ke barcode, lalu Enter" autocomplete="off" autocapitalize="off" spellcheck="false" inputmode="text">
                            <button type="button" id="tombol-tambah" class="btn btn-emas">Tambah</button>
                        </div>
                        <div id="pesan-scan" role="status" aria-live="polite"></div>
                    </div>

                    <div class="tabel-wrap">
                        <table class="tabel-barang" aria-label="Daftar barang yang dipinjam">
                            <colgroup><col class="col-no"><col class="col-nama"><col class="col-kode"><col class="col-jml"><col class="col-ket"><col class="col-aksi"></colgroup>
                            <thead>
                                <tr><th>NO</th><th>NAMA BARANG</th><th>KODE</th><th>JUMLAH</th><th>KET</th><th><span class="visually-hidden">Hapus</span></th></tr>
                            </thead>
                            <tbody id="isi-tabel"></tbody>
                        </table>
                    </div>

                    <section class="blok area-catatan">
                        <label for="catatan" class="form-label">Catatan:</label>
                        <textarea id="catatan" name="catatan" rows="3" maxlength="500" class="form-control">{{ old('catatan') }}</textarea>

                        <div class="kotak-barcode">
                            <svg width="150" height="38" viewBox="0 0 150 38" fill="currentColor" aria-hidden="true">
                                <rect x="0" width="3" height="38"/><rect x="6" width="2" height="38"/><rect x="11" width="5" height="38"/><rect x="19" width="2" height="38"/><rect x="24" width="3" height="38"/><rect x="31" width="6" height="38"/><rect x="40" width="2" height="38"/><rect x="45" width="4" height="38"/><rect x="52" width="2" height="38"/><rect x="57" width="3" height="38"/><rect x="63" width="5" height="38"/><rect x="71" width="2" height="38"/><rect x="76" width="4" height="38"/><rect x="83" width="3" height="38"/><rect x="89" width="2" height="38"/><rect x="94" width="6" height="38"/><rect x="103" width="2" height="38"/><rect x="108" width="3" height="38"/><rect x="114" width="5" height="38"/><rect x="122" width="2" height="38"/><rect x="127" width="4" height="38"/><rect x="134" width="2" height="38"/><rect x="139" width="3" height="38"/><rect x="145" width="5" height="38"/>
                            </svg>
                            Barcode transaksi akan tampil di sini setelah data disimpan.
                        </div>
                    </section>

                    <section class="blok" aria-label="Tanda tangan">
                        <div class="tanda-tangan">
                            <div>
                                <div class="ruang">Kepala Bengkel PPLG</div>
                                <div>Irlan Maulana B</div>
                            </div>
                            <div>
                                <div class="ruang">Padalarang, <span id="tgl-hari-ini">-</span><br>Peminjam</div>
                                <div class="nama" id="nama-ttd">Nama Peminjam</div>
                            </div>
                        </div>
                        <div class="pengembalian">
                            Pengembalian: <span class="isi" aria-hidden="true"></span>
                            <small>Tanggal kembali ditulis tangan di formulir cetak.</small>
                        </div>
                    </section>
                </div>

                <button type="submit" id="tombol-simpan" class="btn btn-emas btn-lg rounded-pill w-100 mt-4" disabled>Simpan dan buat formulir</button>
            </form>
        </div>
    </main>

    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="judulKonfirmasi" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="judulKonfirmasi">Yakin data sudah benar?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small">Periksa kembali data berikut sebelum disimpan.</p>
                    <dl class="row mb-0" id="ringkasan"></dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Periksa lagi</button>
                    <button type="button" class="btn btn-emas" id="tombol-yakin">Ya, simpan</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const MAKS = @json($maksBarang);
        const URL_CEK = @json(url('/peminjam/barang'));
        const BARANG_LAMA = @json(old('barang', []));

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

        const form        = document.getElementById('form-guru');
        const inputScan   = document.getElementById('scan-barcode');
        const tombolTambah = document.getElementById('tombol-tambah');
        const pesanScan   = document.getElementById('pesan-scan');
        const isiTabel    = document.getElementById('isi-tabel');
        const hitung      = document.getElementById('hitung-barang');
        const tombolSimpan = document.getElementById('tombol-simpan');
        const inputNama   = document.getElementById('nama_peminjam');
        const ringkasan   = document.getElementById('ringkasan');
        const tombolYakin = document.getElementById('tombol-yakin');
        const modalKonfirmasi = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

        // tanggal pinjam = hari ini (otomatis)
        document.getElementById('tgl-hari-ini').textContent =
            new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        // daftar barang hasil scan: { kode, nama, jumlah, ket }
        let daftar = [];
        let sibuk = false;

        function pesan(teks, jenis) {
            pesanScan.className = jenis ? 'text-' + jenis : 'text-secondary';
            pesanScan.textContent = teks;
        }

        function el(tag, kelas, teks) {
            const e = document.createElement(tag);
            if (kelas) e.className = kelas;
            if (teks !== undefined) e.textContent = teks;
            return e;
        }

        function gambarTabel() {
            isiTabel.replaceChildren();

            if (daftar.length === 0) {
                const tr = el('tr', 'kosong');
                const td = el('td', '', 'Belum ada barang. Scan barcode barang untuk menambahkannya.');
                td.colSpan = 6;
                tr.appendChild(td);
                isiTabel.appendChild(tr);
            }

            daftar.forEach((b, i) => {
                const tr = el('tr', 'item');

                tr.appendChild(el('td', 'no', String(i + 1)));
                const tdNama = el('td', 'nama c-nama', b.nama);
                tdNama.dataset.label = 'NAMA BARANG';
                tr.appendChild(tdNama);

                const tdKode = el('td', 'kode c-kode', b.kode);
                tdKode.dataset.label = 'KODE';
                const hidden = document.createElement('input');
                hidden.type = 'hidden'; hidden.name = `barang[${i}][kode]`; hidden.value = b.kode;
                tdKode.appendChild(hidden);
                tr.appendChild(tdKode);

                const tdJml = el('td', 'c-jml'); tdJml.dataset.label = 'JUMLAH';
                const jml = el('input', 'form-control');
                jml.type = 'number'; jml.name = `barang[${i}][jumlah]`; jml.min = 1; jml.max = 999; jml.step = 1;
                jml.inputMode = 'numeric'; jml.required = true; jml.value = b.jumlah;
                jml.setAttribute('aria-label', `Jumlah ${b.nama}`);
                jml.addEventListener('input', () => { b.jumlah = jml.value; });
                tdJml.appendChild(jml); tr.appendChild(tdJml);

                const tdKet = el('td', 'c-ket'); tdKet.dataset.label = 'KET';
                const ket = el('input', 'form-control');
                ket.type = 'text'; ket.name = `barang[${i}][ket]`; ket.maxLength = 150; ket.value = b.ket;
                ket.setAttribute('aria-label', `Keterangan ${b.nama}`);
                ket.addEventListener('input', () => { b.ket = ket.value; });
                tdKet.appendChild(ket); tr.appendChild(tdKet);

                const tdAksi = el('td', 'c-aksi');
                const hapus = el('button', 'btn btn-outline-secondary btn-sm btn-hapus');
                hapus.type = 'button';
                hapus.setAttribute('aria-label', `Hapus ${b.nama}`);
                hapus.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>';
                hapus.addEventListener('click', () => {
                    daftar.splice(i, 1);
                    gambarTabel();
                    pesan(`${b.nama} dihapus dari daftar.`);
                    inputScan.focus();
                });
                tdAksi.appendChild(hapus); tr.appendChild(tdAksi);

                isiTabel.appendChild(tr);
            });

            hitung.textContent = `${daftar.length} / ${MAKS} barang`;
            tombolSimpan.disabled = daftar.length === 0;
            inputScan.disabled = daftar.length >= MAKS;
            tombolTambah.disabled = daftar.length >= MAKS;
        }

        async function periksa(kode) {
            const respon = await fetch(`${URL_CEK}/${encodeURIComponent(kode)}`, { headers: { 'Accept': 'application/json' } });
            return respon.json();
        }

        async function tambahDariScan() {
            const kode = inputScan.value.trim();
            if (!kode || sibuk) return;

            if (daftar.length >= MAKS) { pesan(`Maksimal ${MAKS} barang sekali pinjam.`, 'danger'); return; }
            if (daftar.some(b => b.kode.toLowerCase() === kode.toLowerCase())) {
                pesan('Barang ini sudah ada di daftar.', 'warning');
                inputScan.select();
                return;
            }

            sibuk = true;
            pesan('Memeriksa barang...');
            try {
                const data = await periksa(kode);
                if (data.ok) {
                    daftar.push({ kode, nama: data.nama, jumlah: 1, ket: '' });
                    gambarTabel();
                    pesan(`${data.nama} ditambahkan.`, 'success');
                    inputScan.value = '';
                } else {
                    pesan(data.pesan, 'danger');
                    inputScan.select();
                }
            } catch (e) {
                pesan('Gagal memeriksa barang. Periksa koneksi lalu coba lagi.', 'danger');
            } finally {
                sibuk = false;
                if (!inputScan.disabled) inputScan.focus();
            }
        }

        // scanner mengirim Enter di akhir kode
        inputScan.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); tambahDariScan(); }
        });
        tombolTambah.addEventListener('click', tambahDariScan);

        // nama di kolom tanda tangan mengikuti isian
        inputNama.addEventListener('input', () => {
            document.getElementById('nama-ttd').textContent = inputNama.value.trim() || 'Nama Peminjam';
        });

        function tambahBaris(label, nilai) {
            if (!nilai) return;
            const dt = el('dt', 'col-12 col-sm-4 text-secondary fw-normal', label);
            const dd = el('dd', 'col-12 col-sm-8', nilai);
            ringkasan.append(dt, dd);
        }

        form.addEventListener('submit', e => {
            e.preventDefault();

            if (daftar.length === 0) { pesan('Scan barcode minimal satu barang.', 'danger'); inputScan.focus(); return; }
            if (!form.reportValidity()) return;

            ringkasan.replaceChildren();
            const tipe = form.querySelector('input[name="tipe_peminjam"]:checked');
            tambahBaris('Peminjam sebagai', tipe ? tipe.nextElementSibling.textContent : '');
            tambahBaris('Nama', inputNama.value.trim());
            tambahBaris('Keperluan', document.getElementById('keperluan').value.trim());
            tambahBaris('Lama pinjam', document.getElementById('lama_peminjaman').value.trim());
            daftar.forEach((b, i) => {
                const ket = b.ket.trim() ? `, ${b.ket.trim()}` : '';
                tambahBaris(`Barang ${i + 1}`, `${b.nama} (${b.kode}) x${b.jumlah}${ket}`);
            });
            tambahBaris('Catatan', document.getElementById('catatan').value.trim());
            tambahBaris('Tanggal pinjam', document.getElementById('tgl-hari-ini').textContent);

            modalKonfirmasi.show();
        });

        tombolYakin.addEventListener('click', () => {
            tombolYakin.disabled = true;
            tombolYakin.textContent = 'Menyimpan...';
            form.submit();
        });

        window.addEventListener('pageshow', () => {
            tombolYakin.disabled = false;
            tombolYakin.textContent = 'Ya, simpan';
        });

        // setelah gagal validasi di server, isi barang dikembalikan dan dicek ulang
        (async function pulihkan() {
            gambarTabel();
            for (const item of BARANG_LAMA) {
                const kode = String(item.kode || '').trim();
                if (!kode || daftar.length >= MAKS) continue;
                try {
                    const data = await periksa(kode);
                    daftar.push({
                        kode,
                        nama: data.ok ? data.nama : (data.pesan || 'Barang tidak tersedia'),
                        jumlah: item.jumlah || 1,
                        ket: item.ket || '',
                    });
                } catch (e) {
                    daftar.push({ kode, nama: kode, jumlah: item.jumlah || 1, ket: item.ket || '' });
                }
            }
            gambarTabel();
            inputNama.dispatchEvent(new Event('input'));
            if (!inputNama.value) inputNama.focus(); else inputScan.focus();
        })();
    </script>
</body>
</html>
