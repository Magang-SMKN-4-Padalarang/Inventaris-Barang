<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengembalian - Inventaris Barang</title>
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
    <link href="	{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        /* Palet biru tua: terang */
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
            --bahaya: #c62a47;
            --sukses: #0f7a4d;
        }
        /* Palet biru tua: gelap */
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
            --bahaya: #ff7b93;
            --sukses: #4fd69c;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden; /* cegah scroll ke samping di layar kecil */
        }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        /* Wadah utama: hormati area aman (notch) di HP */
        .wadah {
            max-width: 1120px;
            padding-left: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-left));
            padding-right: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-right));
        }

        /* Navbar */
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

        /* Tombol utama */
        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            --bs-btn-disabled-color: #1a1405; --bs-btn-disabled-bg: var(--emas); --bs-btn-disabled-border-color: var(--emas);
            font-weight: 700;
        }
        .btn-bahaya {
            --bs-btn-color: #fff; --bs-btn-bg: #d6334f; --bs-btn-border-color: #d6334f;
            --bs-btn-hover-color: #fff; --bs-btn-hover-bg: #b92a43; --bs-btn-hover-border-color: #b92a43;
            --bs-btn-active-color: #fff; --bs-btn-active-bg: #b92a43; --bs-btn-active-border-color: #b92a43;
            font-weight: 700;
        }

        h1 { font-size: clamp(1.6rem, 6vw, 2.6rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.1; overflow-wrap: break-word; }

        /* Kartu & formulir */
        .card { background: var(--kartu); border-color: var(--bs-border-color); border-radius: 16px; }
        .form-control { background-color: var(--input); border-color: var(--bs-border-color); min-height: 44px; }
        .form-control:focus { border-color: var(--aksen); box-shadow: 0 0 0 .2rem rgba(110, 168, 255, .25); }
        .kode-struk { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .08em; color: var(--aksen); }
        .kode-struk::placeholder { letter-spacing: 0; }

        /* Area siaga scanner */
        #areaScan {
            position: relative;
            width: 100%;
            flex: 1;
            min-height: 11rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            border: 2px dashed var(--bs-border-color);
            border-radius: 12px;
            overflow: hidden;
            background: var(--input);
            text-align: center;
            cursor: text;
        }
        #areaScan.siap { border-style: solid; border-color: var(--aksen); }
        #areaScan .garis-scan {
            position: absolute; left: 8%; right: 8%; top: 0; height: 2px;
            background: var(--emas); box-shadow: 0 0 12px var(--emas);
            opacity: 0; animation: sapu 2.4s ease-in-out infinite;
        }
        #areaScan.siap .garis-scan { opacity: 1; }
        @keyframes sapu { 0%, 100% { top: 12%; } 50% { top: 88%; } }
        #statusScanner { font-weight: 600; }
        #statusScanner.siap { color: var(--sukses); }
        #statusScanner.mati { color: var(--emas-teks); }
        #kosong { flex: 1; display: flex; align-items: center; justify-content: center; min-height: 11rem; }

        /* Baris detail: nilai panjang boleh turun baris dan tidak melebar keluar kartu */
        .detail-baris { display: flex; justify-content: space-between; gap: 1rem; padding: .75rem 0; border-bottom: 1px solid var(--bs-border-color); }
        .detail-baris:last-of-type { border-bottom: 0; }
        .detail-baris span:first-child { color: var(--bs-secondary-color); flex-shrink: 0; }
        .detail-baris strong { min-width: 0; text-align: right; overflow-wrap: anywhere; }

        /* Info ketentuan, gaya sama dengan kotak 24 jam di form peminjaman */
        .info-24jam { border: 1.5px solid rgba(241, 181, 61, .45); background: rgba(241, 181, 61, .1); }
        .info-24jam strong { color: var(--emas-teks); }

        /* Modal terlambat */
        .modal-content { background: var(--kartu); border-color: var(--bs-border-color); }
        .modal-terlambat .modal-content {
            border: 2px solid var(--bahaya); animation: denyut 1s ease-in-out infinite;
            max-height: calc(100vh - 1.5rem); max-height: calc(100dvh - 1.5rem); overflow-y: auto; /* bisa di-scroll di layar pendek */
        }
        .modal-terlambat .ikon-alert {
            width: 4.5rem; height: 4.5rem; border-radius: 50%;
            background: rgba(214, 51, 79, .14);
            border: 2px solid var(--bahaya);
            color: var(--bahaya);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2.4rem; font-weight: 800;
        }
        .sanksi-box { border: 1.5px solid rgba(241, 181, 61, .45); background: rgba(241, 181, 61, .1); border-radius: .75rem; }
        .sanksi-box .judul-sanksi { color: var(--emas-teks); }
        @keyframes denyut {
            0%, 100% { box-shadow: 0 0 0 0 rgba(214, 51, 79, .55); }
            50%      { box-shadow: 0 0 0 14px rgba(214, 51, 79, 0); }
        }

        /* Layar berkedip merah saat terlambat (di atas backdrop modal, di bawah kotak modal) */
        body.layar-darurat::after {
            content: ""; position: fixed; inset: 0; z-index: 1052;
            background: rgba(240, 40, 80, 0.45);
            pointer-events: none;
            animation: kedip-merah 1s ease-in-out infinite;
        }
        @keyframes kedip-merah { 0%, 100% { opacity: 0; } 50% { opacity: 1; } }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        @media (prefers-reduced-motion: reduce) {
            .modal-terlambat .modal-content { animation: none; }
            body.layar-darurat::after { animation: none; opacity: .5; }
            #areaScan .garis-scan { animation: none; }
        }

        /* ====== Responsif ====== */

        /* Tablet portrait & HP (di bawah 768px) */
        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            .btn-kembali { width: 100%; }

            /* Font input minimal 16px agar iOS tidak otomatis zoom saat difokuskan */
            .form-control { font-size: 1rem; }

            #areaScan { min-height: 8.5rem; padding: 1.1rem .75rem; }
            #kosong { min-height: 7rem; }
        }

        /* HP (di bawah 576px) */
        @media (max-width: 575.98px) {
            .card { border-radius: 12px; }
            .kode-struk { letter-spacing: .03em; }
            .input-group .btn { padding-left: 1rem; padding-right: 1rem; }

            /* Detail: label di atas, nilai di bawah supaya teks panjang tidak sempit */
            .detail-baris { flex-direction: column; gap: .1rem; padding: .6rem 0; }
            .detail-baris strong { text-align: left; }

            #tombolKonfirmasi { min-height: 52px; }

            /* Modal terlambat lebih ringkas */
            .modal-dialog { margin: .75rem; }
            .modal-terlambat .modal-content { padding: 1.25rem !important; }
            .modal-terlambat .ikon-alert { width: 3.5rem; height: 3.5rem; font-size: 1.9rem; }
            .modal-terlambat h2 { font-size: 1.5rem; }
            .modal-terlambat .btn { min-height: 48px; }
        }

        /* HP kecil (di bawah 400px) */
        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
            .card { padding: 1rem !important; }
            .input-group .form-control-lg { font-size: .95rem; }
            footer { font-size: .8rem; }
        }

        /* Layar pendek (HP landscape): kurangi jarak vertikal */
        @media (max-height: 480px) and (orientation: landscape) {
            .navbar { padding-top: .35rem; padding-bottom: .35rem; }
            .logo { width: 38px; height: 38px; }
            main { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .modal-terlambat .ikon-alert { display: none; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Header / Navbar -->
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

    <!-- Isi halaman -->
    <main class="container wadah flex-grow-1 py-4 py-md-5 d-flex flex-column">
        <div>
            <a class="btn btn-outline-secondary btn-sm btn-kembali mb-4" href="{{ url('/peminjam') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                Kembali ke dashboard
            </a>

            <h1 class="mb-2">Pengembalian Barang</h1>
            <p class="text-secondary mb-4">Scan QR dari struk saat peminjaman, lalu konfirmasi pengembalian.</p>
        </div>

        <div class="row g-4 flex-grow-1">

            <!-- Kolom kiri: scanner -->
            <div class="col-12 col-lg-6 d-flex">
                <div class="card p-3 p-md-4 w-100 d-flex flex-column">
                    <h2 class="h5 fw-bold mb-3">Scan struk</h2>

                    <div id="areaScan" class="mb-3">
                        <div class="garis-scan" aria-hidden="true"></div>
                        <div class="fw-bold fs-5 mb-1">Arahkan scanner ke QR pada struk</div>
                        <div id="statusScanner" class="small mati" role="status" aria-live="polite">Klik di sini untuk mengaktifkan scanner</div>
                    </div>

                    <label for="inputKode" class="form-label">Kode struk</label>
                    <div class="input-group">
                        <input id="inputKode" type="text" class="form-control form-control-lg kode-struk" placeholder="TRX-20261021-00002-FB842" autocomplete="off" autofocus>
                        <button id="tombolCari" class="btn btn-emas px-4">Cari</button>
                    </div>
                    <div class="small text-secondary mt-2">Scanner belum terbaca? Ketik kode di struk lalu tekan Enter.</div>
                </div>
            </div>

            <!-- Kolom kanan: detail transaksi -->
            <div class="col-12 col-lg-6 d-flex">
                <div class="card p-3 p-md-4 w-100 d-flex flex-column">
                    <h2 class="h5 fw-bold mb-3">Detail peminjaman</h2>

                    <div id="kosong" class="text-secondary text-center">
                        Belum ada struk yang discan.
                    </div>

                    <div id="galat" class="alert alert-danger py-2 d-none" role="alert"></div>

                    <div id="detail" class="d-none my-auto">
                        <div class="detail-baris"><span>Kode</span><strong id="dKode" class="kode-struk"></strong></div>
                        <div class="detail-baris"><span>Nama peminjam</span><strong id="dNama"></strong></div>
                        <div class="detail-baris" id="barisKelas"><span>Kelas</span><strong id="dKelas"></strong></div>
                        <div class="detail-baris"><span>Barang</span><strong id="dBarang" class="text-end"></strong></div>
                        <div class="detail-baris"><span>Batas pengembalian</span><strong id="dBatas"></strong></div>
                        <div class="detail-baris"><span>Status</span><strong id="dStatus"></strong></div>

                        <button id="tombolKonfirmasi" class="btn btn-emas btn-lg rounded-pill w-100 mt-3">
                            Konfirmasi pengembalian
                        </button>
                    </div>

                    <div id="sukses" class="alert alert-success py-2 d-none mt-2 mb-0" role="status"></div>
                </div>
            </div>
        </div>

        <!-- Ketentuan keterlambatan -->
        <div class="info-24jam p-3 rounded-3 mt-4">
            <strong>Ketentuan keterlambatan</strong>
            <div class="small text-secondary mt-1">Batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.</div>
        </div>
    </main>

    <!-- Modal alert terlambat (tanpa kelas "fade" agar langsung muncul) -->
    <div class="modal modal-terlambat" id="modalTerlambat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulTerlambat" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3"><span class="ikon-alert" aria-hidden="true">!</span></div>
                <h2 id="judulTerlambat" class="h3 fw-bold mb-2" style="color: var(--bahaya);">Anda terlambat!</h2>
                <p class="mb-1">Barang dikembalikan melewati batas waktu.</p>
                <p class="fs-5 fw-bold mb-4" id="lamaTerlambat"></p>

                <!-- Sanksi hanya tampil untuk siswa -->
                <div id="kotakSanksi" class="sanksi-box p-3 mb-4 text-start d-none">
                    <div class="fw-bold mb-1 judul-sanksi">Sanksi</div>
                    <div>Wajib <strong>membersihkan lab</strong> sebelum meninggalkan ruangan. Lapor ke petugas setelah selesai.</div>
                </div>

                <div class="d-grid gap-2">
                    <button id="tombolMengerti" class="btn btn-bahaya btn-lg rounded-pill">Saya mengerti</button>
                    <button id="tombolBisu" class="btn btn-sm btn-outline-secondary">Matikan suara</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Suara sirine: taruh file di public/audio/sirine.mp3 -->
    <audio id="suaraTerlambat" src="{{ asset('audio/sirine.mp3') }}" preload="auto" loop></audio>

    <!-- Footer -->
    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
    (function () {
        const URL_CEK = "{{ url('/peminjam/kembali/cek') }}";
        const URL_PROSES = "{{ url('/peminjam/kembali/proses') }}";
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        const $ = (id) => document.getElementById(id);
        const modalTerlambat = new bootstrap.Modal($('modalTerlambat'));

        /* ---------- Ganti tema terang/gelap ---------- */
        const html = document.documentElement;
        const labelTema = $('labelTema');
        const tampilkanLabel = () => {
            labelTema.textContent = html.getAttribute('data-bs-theme') === 'dark' ? 'Mode terang' : 'Mode gelap';
        };
        tampilkanLabel();
        $('btnTema').addEventListener('click', () => {
            const baru = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', baru);
            try { localStorage.setItem('tema', baru); } catch (e) {}
            tampilkanLabel();
        });

        let transaksi = null;
        let sedangMemproses = false;

        /* ---------- Tipe peminjam ---------- */
        // Alert terlambat berlaku untuk semua peminjam, tetapi sanksi hanya untuk siswa.
        // Server harus mengirim field "tipe" (isi dari tipe_peminjam) di dalam data.
        function apakahSiswa(d) {
            if (d.tipe === undefined || d.tipe === null) {
                console.warn('[Pengembalian] Field "tipe" tidak dikirim server, dianggap bukan siswa.');
                return false;
            }
            return String(d.tipe).trim().toLowerCase().includes('siswa');
        }

        /* ---------- Suara alarm (file audio) ---------- */
        const suara = $('suaraTerlambat');
        suara.loop = true;          // berulang terus
        let sirineAktif = false;

        // Browser memblokir suara sebelum ada interaksi pengguna.
        // Audio "dibuka kuncinya" dengan memutarnya bisu saat interaksi pertama.
        function siapkanAudio() {
            if (suara.dataset.siap || sirineAktif) return;
            suara.muted = true;
            suara.play().then(() => {
                suara.dataset.siap = '1';
                if (sirineAktif) {     
                    suara.muted = false;
                    return;
                }
                suara.pause();
                suara.currentTime = 0;
                suara.muted = false;
            }).catch(() => { suara.muted = false; });
        }

        function mulaiSirene() {
            sirineAktif = true;
            suara.muted = false;
            suara.currentTime = 0;
            suara.play().catch(() => {});
            // tanpa timer: berbunyi terus sampai "Saya mengerti" / "Matikan suara" diklik
        }

        function hentikanSirene() {
            sirineAktif = false;
            suara.pause();
            suara.currentTime = 0;
        }

        const input = $('inputKode');
        const area = $('areaScan');
        const status = $('statusScanner');
        let modalTerbuka = false;

        function fokus() {
            if (!modalTerbuka) input.focus();
        }

        function perbaruiStatus() {
            const siap = document.activeElement === input;
            area.classList.toggle('siap', siap);
            status.classList.toggle('siap', siap);
            status.classList.toggle('mati', !siap);
            status.textContent = siap ? 'Scanner siap, silakan scan struk' : 'Klik di sini untuk mengaktifkan scanner';
        }

        function ekstrakKode(teks) {
            teks = (teks || '').trim();

            const m = teks.match(/TRX-\d{8}-\d+-[A-Z0-9]+/i);
            if (m) return m[0].toUpperCase();

            try {
                const obj = JSON.parse(teks);
                if (obj && obj.kode) return String(obj.kode).trim();
            } catch (_) { /* bukan JSON */ }

            if (/^https?:\/\//i.test(teks)) {
                try {
                    const u = new URL(teks);
                    const seg = u.pathname.split('/').filter(Boolean).pop();
                    if (seg) return decodeURIComponent(seg).trim();
                } catch (_) { /* URL tidak valid */ }
            }

            return teks;
        }

        async function cariTransaksi(mentah) {
            if (!mentah || sedangMemproses) return;
            sedangMemproses = true;
            siapkanAudio();
            sembunyikanPesan();
            input.value = '';

            const kode = ekstrakKode(mentah);
            console.log('[Pengembalian] Terbaca dari scanner:', mentah, '→ dikirim:', kode);

            try {
                const res = await fetch(URL_CEK, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ kode })
                });

                const teks = await res.text();
                let json;
                try {
                    json = JSON.parse(teks);
                } catch (_) {
                    console.error('[Pengembalian] Respons bukan JSON. Status', res.status, teks.slice(0, 300));
                    if (res.status === 419) throw new Error('Sesi habis (419). Muat ulang halaman lalu coba lagi.');
                    if (res.status === 404) throw new Error('Route /peminjam/kembali/cek tidak ditemukan (404).');
                    if (res.status === 405) throw new Error('Route harus menerima method POST (405).');
                    if (res.redirected || res.status === 302) throw new Error('Anda belum login atau sesi berakhir.');
                    throw new Error('Server error (' + res.status + '). Cek storage/logs/laravel.log');
                }

                console.log('[Pengembalian] Respons server:', res.status, json);

                const berhasil = json.ok ?? json.success;
                if (!res.ok || !berhasil) {
                    throw new Error(json.pesan || json.message || 'Struk tidak ditemukan.');
                }
                if (!json.data) throw new Error('Respons server tidak berisi data peminjaman.');

                transaksi = json.data;
                tampilkanDetail(transaksi);
            } catch (e) {
                console.error('[Pengembalian] Galat:', e);
                tampilkanGalat(e.message || 'Terjadi kesalahan. Coba lagi.');
            } finally {
                setTimeout(() => { sedangMemproses = false; }, 800);
                fokus();
            }
        }

        function hitungTerlambat(batasISO) {
            const selisihMs = Date.now() - new Date(batasISO).getTime();
            if (isNaN(selisihMs) || selisihMs <= 0) return null;
            const menit = Math.floor(selisihMs / 60000);
            const hari = Math.floor(menit / 1440);
            const jam = Math.floor((menit % 1440) / 60);
            const sisaMenit = menit % 60;
            const bagian = [];
            if (hari) bagian.push(hari + ' hari');
            if (jam) bagian.push(jam + ' jam');
            if (sisaMenit || !bagian.length) bagian.push(sisaMenit + ' menit');
            return bagian.join(' ');
        }

        function tampilkanDetail(d) {
            $('kosong').classList.add('d-none');
            $('galat').classList.add('d-none');
            $('detail').classList.remove('d-none');
            const tk = $('tombolKonfirmasi');
            tk.classList.remove('d-none'); tk.disabled = false; tk.textContent = 'Konfirmasi pengembalian';

            const siswa = apakahSiswa(d);

            $('dKode').textContent = d.kode;
            $('dNama').textContent = d.nama;
            $('barisKelas').classList.toggle('d-none', !siswa);
            $('dKelas').textContent = d.kelas || '-';
            $('dBarang').textContent = Array.isArray(d.barang) ? d.barang.join(', ') : d.barang;
            $('dBatas').textContent = new Date(d.batas_kembali).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });

            const lama = hitungTerlambat(d.batas_kembali);
            const dStatus = $('dStatus');
            if (lama) {
                dStatus.textContent = 'Terlambat ' + lama;
                dStatus.style.color = 'var(--bahaya)';
            } else {
                dStatus.textContent = 'Tepat waktu';
                dStatus.style.color = 'var(--sukses)';
            }

            if (lama) bukaAlertTerlambat(lama, siswa);
        }

        function bukaAlertTerlambat(lama, siswa) {
            $('lamaTerlambat').textContent = 'Terlambat ' + lama;
            $('kotakSanksi').classList.toggle('d-none', !siswa);
            $('tombolBisu').textContent = 'Matikan suara';
            modalTerbuka = true;
            document.body.classList.add('layar-darurat');
            modalTerlambat.show();
            mulaiSirene();
        }

        function tutupAlertTerlambat() {
            hentikanSirene();
            modalTerlambat.hide();
        }

        async function konfirmasi() {
            if (!transaksi) return;
            const tombol = $('tombolKonfirmasi');
            tombol.disabled = true;
            tombol.textContent = 'Memproses...';

            try {
                const res = await fetch(URL_PROSES, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ kode: transaksi.kode })
                });

                const teks = await res.text();
                let json;
                try {
                    json = JSON.parse(teks);
                } catch (_) {
                    console.error('[Pengembalian] Respons proses bukan JSON. Status', res.status, teks.slice(0, 300));
                    throw new Error('Server error (' + res.status + '). Cek storage/logs/laravel.log');
                }
                console.log('[Pengembalian] Respons proses:', res.status, json);

                const berhasil = json.ok ?? json.success;
                if (!res.ok || !berhasil) throw new Error(json.pesan || json.message || 'Pengembalian gagal diproses.');

                const lama = hitungTerlambat(transaksi.batas_kembali);
                const siswa = apakahSiswa(transaksi);
                const sukses = $('sukses');
                sukses.classList.remove('d-none');
                if (lama && siswa) {
                    sukses.textContent = 'Barang berhasil dikembalikan. Jangan lupa laksanakan sanksi: membersihkan lab.';
                } else if (lama) {
                    sukses.textContent = 'Barang berhasil dikembalikan, tetapi melewati batas waktu.';
                } else {
                    sukses.textContent = 'Barang berhasil dikembalikan. Terima kasih!';
                }
                tombol.classList.add('d-none');
                transaksi = null;
                fokus();
            } catch (e) {
                tombol.disabled = false;
                tombol.textContent = 'Konfirmasi pengembalian';
                tampilkanGalat(e.message);
            }
        }

        function tampilkanGalat(pesan) {
            const g = $('galat');
            g.textContent = pesan;
            g.classList.remove('d-none');
        }
        function sembunyikanPesan() {
            $('galat').classList.add('d-none');
            $('sukses').classList.add('d-none');
        }

        /* ---------- Event ---------- */
        input.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            if (modalTerbuka) return;
            siapkanAudio();
            cariTransaksi(input.value.trim());
        });
        $('tombolCari').addEventListener('click', () => { siapkanAudio(); cariTransaksi(input.value.trim()); });
        area.addEventListener('click', fokus);

        input.addEventListener('focus', perbaruiStatus);
        input.addEventListener('blur', () => {
            perbaruiStatus();
            setTimeout(() => {
                const aktif = document.activeElement;
                if (!modalTerbuka && (!aktif || aktif === document.body)) fokus();
            }, 150);
        });

        document.addEventListener('keydown', siapkanAudio, { once: true });
        document.addEventListener('pointerdown', siapkanAudio, { once: true });

        $('tombolKonfirmasi').addEventListener('click', konfirmasi);
        $('tombolMengerti').addEventListener('click', tutupAlertTerlambat);
        $('tombolBisu').addEventListener('click', () => { hentikanSirene(); $('tombolBisu').textContent = 'Suara dimatikan'; });
        $('modalTerlambat').addEventListener('shown.bs.modal', () => { modalTerbuka = true; });
        $('modalTerlambat').addEventListener('hidden.bs.modal', () => { modalTerbuka = false; document.body.classList.remove('layar-darurat'); hentikanSirene(); fokus(); });

        fokus();
        perbaruiStatus();
    })();
    </script>
</body>
</html>