<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventaris Barang</title>
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

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --emas: #f1b53d;
            --kertas: #eef3fb;
            --tinta: #0b1b35;
        }

        [data-bs-theme="light"] {
            --bs-body-bg: #eef3fb;
            --bs-body-color: #0b2447;
            --bs-secondary-color: #4a6087;
            --bs-border-color: rgba(11, 36, 71, .16);
            --kartu: #ffffff;
            --nav-bg: rgba(238, 243, 251, .92);
            --slot: #c6d2e6;
            --hover: rgba(11, 36, 71, .06);
            --profil-bg: #ffffff;
            --profil-overlay: rgba(5, 18, 39, .65);
            --profil-muted: #617493;
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --nav-bg: rgba(10, 25, 48, .92);
            --slot: #06101f;
            --hover: rgba(173, 198, 245, .1);
            --profil-bg: #102445;
            --profil-overlay: rgba(0, 7, 19, .78);
            --profil-muted: #9fb2d3;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #6ea8ff;
            outline-offset: 3px;
        }

        section[id] {
            scroll-margin-top: 96px;
        }

        .wadah {
            max-width: 1120px;
        }

        .navbar {
            background: var(--nav-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--bs-border-color);
            padding-top: .75rem;
            padding-bottom: .75rem;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -.01em;
            color: var(--bs-body-color);
        }

        .logo {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: contain;
        }

        .navbar .nav-link {
            color: var(--bs-secondary-color);
            font-weight: 500;
            border-bottom: 2px solid transparent;
            padding-left: 0;
            padding-right: 0;
        }

        .navbar .nav-link:hover {
            color: var(--bs-body-color);
            border-color: var(--emas);
        }

        .navbar .btn-outline-secondary {
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
        }

        [data-bs-theme="dark"] .ikon-bulan,
        [data-bs-theme="light"] .ikon-matahari {
            display: none;
        }

        h1 {
            font-size: clamp(2rem, 6vw, 4.2rem);
            font-weight: 800;
            letter-spacing: -.025em;
            line-height: 1.06;
        }

        h2 {
            font-size: clamp(1.6rem, 3.4vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1.15;
        }

        .lead {
            max-width: 34rem;
            color: var(--bs-secondary-color);
            font-size: clamp(1rem, 2.2vw, 1.2rem);
        }

        .btn-emas {
            background: var(--emas);
            color: #1a1405;
            font-weight: 700;
            padding: 14px 26px;
            border-radius: 10px;
            border: 0;
            transition: transform .2s, background .2s;
        }

        .btn-emas:hover {
            background: #f7c55c;
            color: #1a1405;
            transform: translateY(-2px);
        }

        /* CONTOH STRUK */
        .printer {
            width: 100%;
            max-width: 340px;
        }

        .slot {
            height: 16px;
            border-radius: 8px;
            background: var(--slot);
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, .5),
                        0 0 0 1px var(--bs-border-color);
        }

        .keluar {
            margin: 0 12px;
            overflow: hidden;
        }

        .struk {
            background: var(--kertas);
            color: var(--tinta);
            padding: 22px 22px 26px;
            border-radius: 0 0 6px 6px;
            font-family: ui-monospace, monospace;
            font-size: .82rem;
            animation: cetak 2s steps(20, end) .4s both;
        }

        .struk-judul {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .kode {
            margin: 4px 0 12px;
            color: #3b5a80;
            font-size: .74rem;
            letter-spacing: .03em;
            word-break: break-all;
        }

        .rinci div {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 7px 0;
            border-bottom: 1px dashed #a4b6d3;
        }

        .rinci dt {
            color: #4b6485;
            font-weight: 400;
        }

        .rinci dd {
            font-weight: 700;
            text-align: right;
            margin: 0;
        }

        .qr-wrap {
            position: relative;
            width: 132px;
            margin: 18px auto 10px;
        }

        #qr svg {
            display: block;
            width: 100%;
            height: auto;
        }

        .scan {
            position: absolute;
            left: -8px;
            right: -8px;
            top: 0;
            height: 2px;
            background: #2f6fe4;
            box-shadow: 0 0 10px #2f6fe4;
            opacity: 0;
            animation: sapu 1.4s ease-in-out 2.6s both;
        }

        .hasil {
            text-align: center;
            color: #1d4fa8;
            font-weight: 700;
            opacity: 0;
            animation: muncul .5s ease 3.8s both;
            margin: 0;
        }

        @keyframes cetak {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        @keyframes sapu {
            0% { top: 0; opacity: 0; }
            10%, 90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        @keyframes muncul {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #cara {
            background: var(--kartu);
            border-top: 1px solid var(--bs-border-color);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .nomor {
            font-size: 2.6rem;
            font-weight: 800;
            color: #b9780a;
            line-height: 1;
        }

        [data-bs-theme="dark"] .nomor {
            color: var(--emas);
        }

        .langkah h3 {
            font-size: 1.2rem;
            margin: 14px 0 8px;
        }

        .langkah p {
            color: var(--bs-secondary-color);
            max-width: 22rem;
        }

        .tentang-teks p {
            color: var(--bs-secondary-color);
            max-width: 32rem;
        }

        .fakta .baris {
            display: grid;
            grid-template-columns: 9.5rem 1fr;
            gap: 16px;
            padding: 16px 0;
            border-top: 1px solid var(--bs-border-color);
        }

        .fakta .baris:last-child {
            border-bottom: 1px solid var(--bs-border-color);
        }

        .fakta dt {
            color: var(--bs-secondary-color);
            font-weight: 400;
        }

        .fakta dd {
            font-weight: 600;
            margin: 0;
        }

        footer {
            border-top: 1px solid var(--bs-border-color);
            color: var(--bs-secondary-color);
            font-size: .9rem;
        }

        footer a {
            color: var(--bs-secondary-color);
        }

        footer a:hover {
            color: var(--bs-body-color);
        }

        .tombol-tim {
            position: fixed;
            z-index: 1040;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 14px 13px;
            border: 1px solid rgba(241, 181, 61, .65);
            border-right: 0;
            border-radius: 14px 0 0 14px;
            background: var(--emas);
            color: #15233c;
            font-size: .88rem;
            font-weight: 800;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .24);
            transition: padding .2s, background .2s;
        }

        .tombol-tim:hover {
            padding-right: 19px;
            background: #f7c55c;
            color: #15233c;
        }

        .tombol-tim svg {
            width: 21px;
            height: 21px;
            flex-shrink: 0;
        }

        #modalTim .modal-dialog {
            max-width: 1000px;
        }

        #modalTim .modal-content {
            background: var(--profil-bg);
            color: var(--bs-body-color);
            border: 1px solid rgba(241, 181, 61, .35);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 24px 90px rgba(0, 0, 0, .35);
        }

        #modalTim .modal-header {
            position: relative;
            padding: 30px 32px 25px;
            gap: 16px;
            border-bottom: 1px solid var(--bs-border-color);
            background:
                radial-gradient(ellipse at top right,
                    rgba(241, 181, 61, .13),
                    transparent 55%);
        }

        .tim-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #b9780a;
            font-size: .76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .15em;
        }

        [data-bs-theme="dark"] .tim-label {
            color: var(--emas);
        }

        .tim-label::before {
            content: "";
            width: 24px;
            height: 2px;
            background: var(--emas);
        }

        .judul-tim {
            margin: 0;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -.035em;
        }

        .deskripsi-tim {
            max-width: 570px;
            margin: 10px 0 0;
            color: var(--profil-muted);
            line-height: 1.7;
        }

        #modalTim .btn-close {
            border: 1px solid var(--bs-border-color);
            border-radius: 50%;
            padding: 13px;
            margin: 0;
            background-color: var(--hover);
            flex-shrink: 0;
            align-self: flex-start;
        }

        [data-bs-theme="dark"] #modalTim .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #modalTim .modal-body {
            padding: 28px 32px 32px;
        }

        .kartu-anggota {
            position: relative;
            height: 100%;
            padding: 25px 20px;
            border: 1px solid var(--bs-border-color);
            border-radius: 17px;
            background: var(--bs-body-bg);
            text-align: center;
            overflow: hidden;
            transition: transform .22s, border-color .22s,
                        box-shadow .22s;
        }

        .kartu-anggota::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 3px;
            background: var(--emas);
            transform: scaleX(.25);
            transform-origin: center;
            transition: transform .22s;
        }

        .kartu-anggota:hover {
            transform: translateY(-5px);
            border-color: rgba(241, 181, 61, .65);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .10);
        }

        .kartu-anggota:hover::before {
            transform: scaleX(1);
        }

        .foto-anggota {
            width: 112px;
            height: 112px;
            margin: 4px auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 3px solid var(--emas);
            background: linear-gradient(145deg, #203c62, #0a1930);
            color: var(--emas);
            overflow: hidden;
            box-shadow: 0 0 0 6px rgba(241, 181, 61, .09);
        }

        .foto-anggota img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .foto-anggota svg {
            width: 48px;
            height: 48px;
        }

        .foto-anggota img ~ svg {
            display: none;
        }

        .nama-anggota {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .peran-anggota {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 11px;
            border-radius: 30px;
            background: rgba(241, 181, 61, .13);
            color: #b9780a;
            font-size: .76rem;
            font-weight: 800;
        }

        [data-bs-theme="dark"] .peran-anggota {
            color: var(--emas);
        }

        .bio-anggota {
            margin: 15px 0 0;
            color: var(--profil-muted);
            font-size: .9rem;
            line-height: 1.7;
        }

        .footer-tim {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding-top: 23px;
            margin-top: 25px;
            border-top: 1px solid var(--bs-border-color);
            color: var(--profil-muted);
            text-align: center;
            font-size: .85rem;
        }

        .footer-tim svg {
            width: 18px;
            height: 18px;
            color: var(--emas);
            flex-shrink: 0;
        }

        /* RESPONSIF */
        @media (min-width: 768px) {
            .langkah .col-md-4 + .col-md-4 {
                border-left: 1px solid var(--bs-border-color);
                padding-left: 2rem;
            }
        }

        @media (max-width: 767.98px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .logo {
                width: 46px;
                height: 46px;
            }

            .navbar .nav-link {
                padding-top: .5rem;
                padding-bottom: .5rem;
            }

            .navbar-nav .btn {
                width: 100%;
            }

            .langkah .col-md-4 + .col-md-4 {
                border-top: 1px solid var(--bs-border-color);
                padding-top: 1.5rem;
            }

            .nomor {
                font-size: 2.2rem;
            }

            #modalTim .modal-header {
                padding: 24px 22px 20px;
            }

            #modalTim .modal-body {
                padding: 22px;
            }

            .kartu-anggota {
                padding: 22px 16px;
            }

            .foto-anggota {
                width: 94px;
                height: 94px;
            }
        }

        @media (max-width: 575.98px) {
            .fakta .baris {
                grid-template-columns: 1fr;
                gap: 2px;
                padding: 14px 0;
            }

            .btn-emas {
                width: 100%;
                text-align: center;
            }

            .keluar {
                margin: 0 8px;
            }

            .tombol-tim {
                top: auto;
                bottom: 100px;
                transform: none;
                padding: 12px 11px;
                border-radius: 12px 0 0 12px;
                font-size: .8rem;
            }

            .tombol-tim svg {
                width: 19px;
                height: 19px;
            }

            #modalTim .modal-dialog {
                margin: 12px;
            }

            #modalTim .modal-content {
                border-radius: 17px;
            }

            #modalTim .modal-header {
                padding: 22px 18px 18px;
            }

            #modalTim .modal-body {
                padding: 18px;
            }

            .judul-tim {
                font-size: 1.8rem;
            }

            .foto-anggota {
                width: 84px;
                height: 84px;
            }

            .nama-anggota {
                font-size: 1rem;
            }

            .bio-anggota {
                font-size: .85rem;
            }

            .footer-tim {
                font-size: .78rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            .struk,
            .scan,
            .hasil {
                animation: none;
            }

            .scan {
                display: none;
            }

            .hasil {
                opacity: 1;
            }

            .btn-emas,
            .tombol-tim,
            .kartu-anggota,
            .kartu-anggota::before {
                transition: none;
            }

            .btn-emas:hover,
            .kartu-anggota:hover {
                transform: none;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-md sticky-top" aria-label="Menu utama">
        <div class="container wadah">
            <a class="navbar-brand d-flex align-items-center gap-2"
               href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo Inventaris Barang"
                     width="56"
                     height="56"
                     class="logo">
                <span>InventarisBarang</span>
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menu"
                    aria-controls="menu"
                    aria-expanded="false"
                    aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-md-center gap-md-4 py-3 py-md-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#cara">Cara pakai</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>

                    <li class="nav-item mt-2 mt-md-0">
                        <a class="btn btn-outline-secondary btn-sm"
                           href="{{ route('login') }}">
                            Login admin
                        </a>
                    </li>

                    <li class="nav-item mt-2 mt-md-0">
                        <button type="button"
                                id="btnTema"
                                class="btn btn-outline-secondary btn-sm btn-tema"
                                aria-label="Ganti mode terang atau gelap">
                            <svg class="ikon-matahari"
                                 width="16" height="16"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 aria-hidden="true">
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
                            </svg>

                            <svg class="ikon-bulan"
                                 width="16" height="16"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
                            </svg>

                            <span id="labelTema">Mode</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">

        {{-- HERO --}}
        <section class="container wadah py-4 py-md-5">
            <div class="row g-4 g-lg-5 align-items-center py-lg-4">
                <div class="col-lg-6">
                    <h1>Pinjam alat lab cukup dengan scan barcode.</h1>

                    <p class="lead mt-3 mt-md-4">
                        Peminjaman dan pengembalian barang tercatat otomatis,
                        tanpa buku tulis. Setiap peminjaman mendapat struk
                        ber-QR, dan barang harus kembali dalam 24 jam.
                    </p>

                    <div class="mt-4">
                        <a class="btn btn-emas"
                           href="{{ route('peminjaman.index') }}">
                            Mulai meminjam
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="printer mx-auto" aria-hidden="true">
                        <div class="slot"></div>

                        <div class="keluar">
                            <div class="struk">
                                <div class="struk-judul">Bukti peminjaman</div>
                                <div class="kode" id="kodeContoh"></div>

                                <dl class="rinci mb-0">
                                    <div>
                                        <dt>Barang</dt>
                                        <dd>Multimeter digital</dd>
                                    </div>
                                    <div>
                                        <dt>Peminjam</dt>
                                        <dd>Contoh Siswa, XI RPL 2</dd>
                                    </div>
                                    <div>
                                        <dt>Dipinjam</dt>
                                        <dd id="tPinjam"></dd>
                                    </div>
                                    <div>
                                        <dt>Batas kembali</dt>
                                        <dd id="tBatas"></dd>
                                    </div>
                                </dl>

                                <div class="qr-wrap">
                                    <div id="qr"></div>
                                    <div class="scan"></div>
                                </div>

                                <p class="hasil">
                                    Struk terbaca, siap dikembalikan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="text-center text-secondary small mt-3 mb-0">
                        Contoh struk. Struk asli dibuat setelah peminjaman disimpan.
                    </p>
                </div>
            </div>
        </section>

        {{-- CARA PAKAI --}}
        <section class="py-4 py-md-5" id="cara">
            <div class="container wadah py-lg-4">
                <h2>Cara pakai</h2>

                <div class="row g-4 mt-1 mt-md-3 langkah">
                    <div class="col-md-4">
                        <div class="nomor">1</div>
                        <h3>Scan barang dan isi data</h3>
                        <p>
                            Pilih tipe peminjam, scan barcode pada barang,
                            lalu isi nama dan data diri.
                        </p>
                    </div>

                    <div class="col-md-4">
                        <div class="nomor">2</div>
                        <h3>Simpan struk</h3>
                        <p>
                            Setelah data disimpan, struk dengan kode QR tampil.
                            Cetak atau simpan struk itu.
                        </p>
                    </div>

                    <div class="col-md-4">
                        <div class="nomor">3</div>
                        <h3>Scan struk saat mengembalikan</h3>
                        <p>
                            Bawa barang kembali dalam 24 jam, scan QR pada struk,
                            lalu konfirmasi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 py-md-5" id="tentang">
            <div class="container wadah py-lg-4">
                <div class="row g-4 g-lg-5 align-items-start">
                    <div class="col-lg-6 tentang-teks">
                        <h2>Tentang Inventaris Barang</h2>

                        <p class="mt-3">
                            Sistem ini mencatat siapa meminjam apa dan kapan
                            barang harus kembali. Barang yang sedang dipinjam
                            otomatis tidak bisa dipinjam orang lain sampai
                            dikembalikan.
                        </p>

                        <p>
                            Petugas melihat data barang dan riwayat peminjaman
                            lewat login admin.
                        </p>
                    </div>

                    <div class="col-lg-6">
                        <dl class="fakta mb-0">
                            <div class="baris">
                                <dt>Siapa yang bisa meminjam</dt>
                                <dd>Siswa, guru, staff, dan orang tua atau tamu</dd>
                            </div>

                            <div class="baris">
                                <dt>Lama pinjam</dt>
                                <dd>24 jam sejak barang dipinjam</dd>
                            </div>

                            <div class="baris">
                                <dt>Jika terlambat</dt>
                                <dd>
                                    Peringatan muncul saat barang dikembalikan.
                                    Siswa wajib membersihkan lab.
                                </dd>
                            </div>

                            <div class="baris">
                                <dt>Pengembalian</dt>
                                <dd>Scan QR pada struk, lalu konfirmasi</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <button type="button"
            class="tombol-tim"
            data-bs-toggle="modal"
            data-bs-target="#modalTim"
            aria-label="Lihat profil tim pembuat website">

        <svg viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="1.8"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>

        <span>Tim Kami</span>
    </button>

    <div class="modal fade"
         id="modalTim"
         tabindex="-1"
         aria-labelledby="judulTim"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <div class="tim-label">Di balik sistem</div>

                        <h2 class="judul-tim" id="judulTim">
                            Kenali Tim Kami
                        </h2>

                        <p class="deskripsi-tim">
                            Kami adalah siswa SMKN 4 Padalarang yang sedang
                            menjalani PKL selama enam bulan di PT Inovasi Edukasi Karya Anak Negeri.
                            Website Sistem Inventaris Barang ini kami kembangkan selama satu bulan
                            sebagai bentuk penerapan ilmu dan keterampilan yang telah kami pelajari.
                        </p>
                    </div>

                    <button type="button"
                            class="btn-close ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Tutup profil tim"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- ANGGOTA 1 --}}
                        {{-- Foto: public/images/anggota1.jpg --}}
                        <div class="col-12 col-md-4">
                            <article class="kartu-anggota">
                                <div class="foto-anggota">
                                    <img src="{{ asset('images/anggota1.jpg') }}"
                                         alt="Foto Nama Anggota 1"
                                         loading="lazy"
                                         onerror="this.remove()">
                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">
                                        <circle cx="12" cy="8" r="4"/>
                                        <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                    </svg>
                                </div>

                                <h3 class="nama-anggota">Andravito Brilian Satriya</h3>

                                <span class="peran-anggota">
                                    Fullstack Developer
                                </span>

                                <p class="bio-anggota">
                                    Mengembangkan frontend dan backend,
                                    serta mengelola server, database, model,
                                    migrasi, dan log aktivitas.
                                </p>
                            </article>
                        </div>

                        {{-- ANGGOTA 2 --}}
                        {{-- Foto: public/images/anggota2.jpeg --}}
                        <div class="col-12 col-md-4">
                            <article class="kartu-anggota">
                                <div class="foto-anggota">
                                    <img src="{{ asset('images/anggota2.jpeg') }}"
                                         alt="Foto Nama Anggota 2"
                                         loading="lazy"
                                         onerror="this.remove()">
                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">
                                        <circle cx="12" cy="8" r="4"/>
                                        <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                    </svg>
                                </div>

                                <h3 class="nama-anggota">Naura Fauziah</h3>

                                <span class="peran-anggota">
                                    Fullstack Developer
                                </span>

                                <p class="bio-anggota">
                                   Merancang UI/UX dan alur sistem aplikasi, 
                                   mengembangkan frontend dan backend,
                                   membangun fitur barcode scanner, 
                                   serta mengelola database dan data inventaris.
                                </p>
                            </article>
                        </div>

                        {{-- ANGGOTA 3 --}}
                        {{-- Foto: public/images/anggota3.jpeg --}}
                        <div class="col-12 col-md-4">
                            <article class="kartu-anggota">
                                <div class="foto-anggota">
                                    <img src="{{ asset('images/anggota3.jpeg') }}"
                                         alt="Foto Nama Anggota 3"
                                         loading="lazy"
                                         onerror="this.remove()">
                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">
                                        <circle cx="12" cy="8" r="4"/>
                                        <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                    </svg>
                                </div>

                                <h3 class="nama-anggota">Zikhal Syabil Arabi</h3>

                                <span class="peran-anggota">
                                    Fullstack Developer
                                </span>

                                <p class="bio-anggota">
                                    Mengembangkan frontend dan backend serta
                                    membangun fitur-fitur pada sistem inventaris barang.
                                </p>
                            </article>
                        </div>

                    </div>

                    <div class="footer-tim">
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M12 3l2.7 5.47 6.03.88-4.36 4.25 1.03 6.01L12 16.77l-5.4 2.84 1.03-6.01-4.36-4.25 6.03-.88L12 3z"/>
                        </svg>

                        <span>
                            Dibuat dengan semangat kolaborasi untuk
                            Inventaris Barang.
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BOOTSTRAP --}}
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        const html = document.documentElement;
        const label = document.getElementById('labelTema');

        const tampilkanLabel = () => {
            label.textContent =
                html.getAttribute('data-bs-theme') === 'dark'
                    ? 'Mode terang'
                    : 'Mode gelap';
        };

        tampilkanLabel();

        document.getElementById('btnTema').addEventListener('click', () => {
            const baru =
                html.getAttribute('data-bs-theme') === 'dark'
                    ? 'light'
                    : 'dark';

            html.setAttribute('data-bs-theme', baru);

            try {
                localStorage.setItem('tema', baru);
            } catch (e) {}

            tampilkanLabel();
        });

        const menu = document.getElementById('menu');

        menu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                bootstrap.Collapse.getInstance(menu)?.hide();
            });
        });

        const sekarang = new Date();
        const batas = new Date(
            sekarang.getTime() + 24 * 60 * 60 * 1000
        );

        const format = d =>
            d.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            }).replace('.', ':');

        const pad = n => String(n).padStart(2, '0');

        const kode = `TRX-${sekarang.getFullYear()}${pad(sekarang.getMonth() + 1)}${pad(sekarang.getDate())}-00001-A7F3K`;

        document.getElementById('kodeContoh').textContent = kode;
        document.getElementById('tPinjam').textContent = format(sekarang);
        document.getElementById('tBatas').textContent = format(batas);

        (function (teks) {
            const N = 25;
            let h = 2166136261;

            for (const c of teks) {
                h ^= c.charCodeAt(0);
                h = Math.imul(h, 16777619);
            }

            const acak = () => {
                h ^= h << 13;
                h ^= h >>> 17;
                h ^= h << 5;
                return (h >>> 0) / 4294967296;
            };

            const pojok = [
                [0, 0],
                [N - 7, 0],
                [0, N - 7]
            ];

            const dalam = (x, y, ox, oy) =>
                x >= ox && x < ox + 7 &&
                y >= oy && y < oy + 7;

            const dekat = (x, y, ox, oy) =>
                x >= ox - 1 && x <= ox + 7 &&
                y >= oy - 1 && y <= oy + 7;

            let d = '';

            for (let y = 0; y < N; y++) {
                for (let x = 0; x < N; x++) {
                    let gelap;

                    const p = pojok.find(([ox, oy]) =>
                        dalam(x, y, ox, oy)
                    );

                    if (p) {
                        const dx = x - p[0];
                        const dy = y - p[1];

                        gelap =
                            dx === 0 ||
                            dx === 6 ||
                            dy === 0 ||
                            dy === 6 ||
                            (
                                dx >= 2 && dx <= 4 &&
                                dy >= 2 && dy <= 4
                            );
                    } else if (
                        pojok.some(([ox, oy]) =>
                            dekat(x, y, ox, oy)
                        )
                    ) {
                        gelap = false;
                    } else {
                        gelap = acak() > 0.52;
                    }

                    if (gelap) {
                        d += `M${x} ${y}h1v1h-1z`;
                    }
                }
            }

            document.getElementById('qr').innerHTML =
                `<svg viewBox="0 0 ${N} ${N}" shape-rendering="crispEdges" role="img" aria-label="Contoh kode QR"><path d="${d}" fill="#0b1b35"/></svg>`;
        })(kode);
    </script>
</body>
</html>