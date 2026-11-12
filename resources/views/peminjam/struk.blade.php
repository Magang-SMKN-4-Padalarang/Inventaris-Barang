<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Struk Peminjaman - Inventaris Barang</title>
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
            --nav-bg: rgba(238, 243, 251, .92);
            --hover: rgba(11, 36, 71, .06);
            --ada: #0f9d7a;
        }
        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --nav-bg: rgba(10, 25, 48, .92);
            --hover: rgba(173, 198, 245, .1);
            --ada: #4fd1ac;
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
        }
        .btn-tema { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        .kode-struk { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .08em; color: var(--ada); font-weight: 700; }

        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            font-weight: 700; border-radius: 10px;
        }
        .aksi .btn { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; }

        .modal-content { background: var(--kartu); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); border-radius: 16px; }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }

        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover, .navbar .nav-link.active { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            footer .wadah { text-align: center; }
        }

        @media (max-width: 575.98px) {
            .aksi .btn { width: 100%; }
            .modal-dialog { margin: .75rem; }
        }

        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .navbar { padding-top: .35rem; padding-bottom: .35rem; }
            .logo { width: 38px; height: 38px; }
        }

        @media (min-width: 1400px) {
            .wadah { max-width: 1200px; }
        }

        /* Jika tetap memakai Ctrl+P, hanya struk yang tercetak */
        @media print {
            @page { size: 58mm auto; margin: 0; }
            body { background: #fff !important; }
            body * { visibility: hidden; }
            #struk-peminjaman, #struk-peminjaman * { visibility: visible; }
            #struk-peminjaman { position: absolute; left: 0; top: 0; box-shadow: none; }
        }
    </style>

    <!-- ===== STRUK THERMAL 58mm (area cetak 48mm) =====
         CSS ini dipakai juga di jendela cetak (dibaca lewat id="css-struk"),
         jadi tampilan layar dan hasil cetak selalu sama. -->
    <style id="css-struk">
        .struk {
            box-sizing: border-box;
            width: 48mm;
            margin: 0 auto;
            background: #fff;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            padding: 5mm 2mm;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .25);
        }
        .struk .tengah { text-align: center; }
        .struk .tebal { font-weight: 700; }

        /* judul */
        .struk .judul { line-height: 1.6; margin-bottom: 4px; }

        /* garis pemisah dengan jarak atas-bawah yang lebih lega */
        .struk .garis-putus {
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
            font-size: 8px;
            line-height: 1;
            margin: 9px 0;
        }

        /* baris data: label di kiri, isi di kanan (turun rapi jika panjang) */
        .struk .baris {
            display: flex;
            align-items: flex-start;
            margin-bottom: 5px;
            word-break: break-word;
        }
        .struk .baris .lbl { display: inline-block; flex: 0 0 16mm; width: 16mm; }

        .struk .keperluan { margin: 8px 0 2px; }

        .struk .qr-struk { display: flex; justify-content: center; margin: 8px 0 6px; }
        .struk .qr-struk img { width: 42mm; height: 42mm; image-rendering: pixelated; display: block; }
        .struk .kode-kecil { font-size: 9px; line-height: 1.5; word-break: break-all; }

        /* pesan & catatan */
        .struk .pesan { line-height: 1.6; margin-bottom: 8px; }
        .struk .catatan-judul { margin-bottom: 3px; }
        .struk .catatan-item { margin-bottom: 4px; line-height: 1.55; }
        .struk .penutup { margin-top: 10px; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam') }}">Beranda</a></li>
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

    <main class="container wadah flex-grow-1 py-4 py-md-5">
        <div class="mx-auto" style="max-width: 520px;">

            <div class="d-flex justify-content-center">
                <div id="struk-peminjaman" class="struk">
                    <div class="tengah tebal judul">
                        PEMINJAMAN ALAT/BARANG<br>
                        LAB PPLG PK<br>
                        SMK Negeri 4 Padalarang
                    </div>
                    <div class="garis-putus">------------------------</div>

                    <div class="baris"><span class="lbl">Tgl Pinjam</span>: {{ $peminjaman->tgl_pinjam->format('d/m/Y H:i') }}</div>
                    <div class="baris"><span class="lbl">Nama</span>: {{ $peminjaman->peminjam->nama_peminjam }}</div>
                    <div class="baris"><span class="lbl">Kelas</span>: {{ $peminjaman->peminjam->keterangan }}</div>
                    <div class="baris"><span class="lbl">Barang</span>: {{ $peminjaman->barang->nama_barang }} ({{ $peminjaman->jumlah ?? 1 }})</div>

                    @if ($peminjaman->keperluan ?? false)
                        <div class="tengah tebal keperluan">{{ $peminjaman->keperluan }}</div>
                    @endif

                    <div class="garis-putus">------------------------</div>

                    <div id="qr" class="qr-struk"></div>
                    <div class="tengah kode-kecil">{{ $peminjaman->kode_transaksi }}</div>

                    <div class="garis-putus">------------------------</div>

                    <div class="tengah tebal pesan">
                        Simpan struk ini untuk<br>pengembalian barang.
                    </div>
                    <div class="tengah">
                        <div class="catatan-judul"><u>Catatan:</u></div>
                        <div class="catatan-item">- Jika struk ini hilang maka sanksinya membereskan LAB.</div>
                        <div class="catatan-item">- Alat/barang sepenuhnya tanggung jawab peminjam.</div>
                    </div>
                    <div class="tengah tebal penutup">LAB PPLG PK</div>
                </div>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-center mt-4 aksi tanpa-cetak">
                <button type="button" class="btn btn-emas px-4" onclick="cetakStrukThermal()">Cetak struk</button>
                <a href="{{ url('/peminjam') }}" class="btn btn-outline-secondary px-4">Kembali ke dashboard</a>
            </div>
        </div>
    </main>

    @if (session('berhasil'))
    <div class="modal fade" id="modalBerhasil" tabindex="-1" aria-labelledby="judulBerhasil" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <h2 class="h4 fw-bold mb-2" id="judulBerhasil" style="color: var(--ada);">Peminjaman berhasil</h2>
                    <p class="mb-1">{{ session('berhasil') }}</p>
                    <p class="text-secondary small mb-4">
                        Kode transaksi: <span class="kode-struk" style="overflow-wrap: anywhere;">{{ $peminjaman->kode_transaksi }}</span><br>
                        Cetak atau simpan struk untuk mengembalikan barang.
                    </p>
                    <button type="button" class="btn btn-emas px-4 w-100" data-bs-dismiss="modal">Lihat struk</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <footer class="py-3 mt-auto">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/qrcodejs/qrcode.min.js') }}"></script>
    <script>
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

       
        (function buatQR() {
            var wadah = document.getElementById('qr');
            new QRCode(wadah, {
                text: @json($peminjaman->kode_transaksi),
                width: 336,
                height: 336,
                correctLevel: QRCode.CorrectLevel.M
            });

            var canvas = wadah.querySelector('canvas');
            var dataUrl = '';
            if (canvas) {
                dataUrl = canvas.toDataURL('image/png');
            } else {
                var img0 = wadah.querySelector('img');
                if (img0) dataUrl = img0.src;
            }
            wadah.innerHTML = '';
            var img = document.createElement('img');
            img.alt = 'QR kode transaksi';
            img.src = dataUrl;
            wadah.appendChild(img);
        })();

        function cetakStrukThermal() {
            var struk = document.getElementById('struk-peminjaman').outerHTML;
            var css = document.getElementById('css-struk').textContent;

            var win = window.open('', '_blank', 'width=350,height=600');
            win.document.write(
                '<html><head><title>Struk</title><style>' +
                '@page{size:58mm auto;margin:0}' +
                'html,body{margin:0;padding:0;background:#fff}' +
                css +
                '.struk{box-shadow:none!important;padding:2mm 2mm!important}' +
                '</style></head><body>' + struk + '</body></html>'
            );
            win.document.close();
            win.focus();
            
            setTimeout(function () { win.print(); win.close(); }, 600);
        }

        @if (session('berhasil'))
            new bootstrap.Modal(document.getElementById('modalBerhasil')).show();
        @endif
    </script>
</body>
</html>