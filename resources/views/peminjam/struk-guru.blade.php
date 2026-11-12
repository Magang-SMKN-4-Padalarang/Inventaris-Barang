@php
    // formulir cetak guru / staff (A4). Dipakai bersama dengan struk.blade.php (struk thermal siswa & lainnya)
    $peminjam = $peminjaman->peminjam;
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $tglPinjam = $peminjaman->tgl_pinjam->format('j') . ' ' . $bulan[(int) $peminjaman->tgl_pinjam->format('n')] . ' ' . $peminjaman->tgl_pinjam->format('Y');

    // minimal 5 baris agar masih ada ruang menulis tangan; maksimal sesuai jumlah barang (10)
    $jumlahBaris = max(5, $items->count());
    $baris = $items->values();

    // baris catatan: minimal 3 garis titik seperti formulir kertas
    $catatan = collect(preg_split('/\R/', (string) $peminjam->catatan))->map(fn ($l) => trim($l))->filter()->values();
    $garisCatatan = max(3, $catatan->count());
@endphp
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Formulir Peminjaman - Inventaris Barang</title>
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

        /* layar kecil: lembar A4 diperkecil agar muat (cetak tetap ukuran asli) */
        .area-kertas { overflow-x: hidden; }

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
        @media (min-width: 1400px) { .wadah { max-width: 1200px; } }
        @media screen { .kertas { zoom: var(--skala, 1); } }

        /* hanya formulir yang tercetak */
        @media print {
            @page { size: A4 portrait; margin: 0; }
            html, body { background: #fff !important; }
            body { display: block !important; min-height: 0 !important; }
            .tanpa-cetak { display: none !important; }
            main, .wadah, .area-kertas { padding: 0 !important; margin: 0 !important; max-width: none !important; overflow: visible !important; }
            .kertas { margin: 0 !important; box-shadow: none !important; zoom: 1 !important; }
        }
    </style>

    <!-- ===== FORMULIR A4 (mengikuti form_peminjaman_staff_guru.html) ===== -->
    <style id="css-kertas">
        .kertas, .kertas * { box-sizing: border-box; }
        .kertas {
            width: 210mm; min-height: 296mm; margin: 0 auto; padding: 19mm 10mm 12mm;
            background: #fff; color: #000; font-family: Arial, Helvetica, sans-serif;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .3);
        }
        .kertas .kop { height: 28.5mm; border: 1px solid #111; display: grid; grid-template-columns: 25mm 1fr 25mm; align-items: center; padding: 1mm 3mm; text-align: center; }
        .kertas .lambang { width: 22mm; height: 25mm; object-fit: contain; justify-self: center; display: block; }
        .kertas .lambang.kanan { width: 23mm; }
        .kertas .nama-sekolah { font-size: 10.8pt; font-weight: 700; line-height: 1.35; white-space: nowrap; }
        .kertas .program { font-size: 10.5pt; font-weight: 700; line-height: 1.55; }
        .kertas .alamat, .kertas .kontak { font-size: 8.5pt; line-height: 1.55; }
        .kertas .judul-form { border: 1px solid #111; margin-top: 4.8mm; height: 5.5mm; text-align: center; font-size: 9pt; font-weight: 700; padding-top: .7mm; }
        .kertas .info-peminjam { border: 1px solid #111; margin-top: 4.8mm; height: 17mm; padding: .8mm 1mm; font-size: 9pt; font-weight: 700; }
        .kertas .baris-info { display: grid; grid-template-columns: 44mm 4mm 1fr; height: 5.1mm; align-items: center; }
        .kertas .baris-info span:last-child { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .kertas table { width: 100%; border-collapse: collapse; margin-top: 4.8mm; table-layout: fixed; font-size: 9pt; }
        .kertas th, .kertas td { border: 1px solid #111; }
        .kertas th { height: 9.8mm; background: #d9d9d9; font-weight: 700; text-align: center; }
        .kertas td { height: 9.5mm; padding: 0 1.5mm; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .kertas td:first-child, .kertas td.tengah { text-align: center; }
        .kertas.padat td { height: 8.3mm; }
        .kertas .col-no { width: 5.3%; } .kertas .col-nama { width: 30.2%; } .kertas .col-kode { width: 23.8%; }
        .kertas .col-jml { width: 13.7%; } .kertas .col-ket { width: 27%; }
        .kertas .catatan { width: 64%; min-height: 19.5mm; border: 1px solid #111; margin: 4.8mm 0 0 auto; padding: 1mm; font-size: 8.5pt; }
        .kertas .catatan strong { font-style: italic; }
        .kertas .garis-catatan { border-bottom: 1px dotted #111; min-height: 5.2mm; line-height: 5.2mm; overflow-wrap: anywhere; }
        .kertas .kotak-barcode { width: 64%; border: 1px solid #111; margin: 2.5mm 0 0 auto; padding: 1.5mm 2mm; text-align: center; }
        .kertas .kotak-barcode svg { display: block; width: 100%; height: 11mm; }
        .kertas .tanda-tangan { display: grid; grid-template-columns: 1fr 1fr; margin-top: 8mm; text-align: center; font-size: 9pt; font-weight: 700; }
        .kertas .judul-ttd { min-height: 17mm; }
        .kertas .nama-ttd { margin-top: 6mm; }
        .kertas .tgl-kembali { text-align: right; margin: 6mm 9mm 0 0; font-size: 9pt; font-weight: 700; }
        .kertas .tgl-kembali .titik { display: inline-block; width: 38mm; border-bottom: 1px dotted #111; height: 1em; vertical-align: bottom; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-md sticky-top tanpa-cetak" aria-label="Menu utama">
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
        <div class="d-grid gap-2 d-sm-flex justify-content-center mb-4 aksi tanpa-cetak">
            <button type="button" class="btn btn-emas px-4" onclick="window.print()">Cetak formulir</button>
            <a href="{{ url('/peminjam') }}" class="btn btn-outline-secondary px-4">Kembali ke dashboard</a>
        </div>

        <div class="area-kertas" id="area-kertas">
            <article class="kertas {{ $jumlahBaris > 7 ? 'padat' : '' }}" id="formulir-peminjaman" aria-label="Formulir peminjaman alat">
                <header class="kop">
                    <img class="lambang" src="{{ asset('images/kop-sekolah.jpg') }}" alt="">
                    <div>
                        <div class="nama-sekolah">SEKOLAH MENENGAH KEJURUAN NEGERI 4 PADALARANG</div>
                        <div class="program">PENGEMBANGAN PERANGKAT LUNAK DAN GIM</div>
                        <div class="alamat">Jl. Raya Padalarang No.451 Telp. (022) 6805406 Padalarang 40553</div>
                        <div class="kontak">Email : smkn4.padalarang@gmail.com</div>
                        <div class="kontak">Website : http//www.smkn4padalarang.sch.id</div>
                    </div>
                    <img class="lambang kanan" src="{{ asset('images/kop-pplg.jpg') }}" alt="">
                </header>

                <div class="judul-form">PEMINJAMAN ALAT</div>

                <section class="info-peminjam">
                    <div class="baris-info"><span>NAMA PEMINJAM</span><span>:</span><span>{{ $peminjam->nama_peminjam }}</span></div>
                    <div class="baris-info"><span>KEPERLUAN</span><span>:</span><span>{{ $peminjam->keperluan }}</span></div>
                    <div class="baris-info"><span>LAMA PEMINJAMAN</span><span>:</span><span>{{ $peminjam->lama_peminjaman }}</span></div>
                </section>

                <table aria-label="Daftar barang yang dipinjam">
                    <colgroup><col class="col-no"><col class="col-nama"><col class="col-kode"><col class="col-jml"><col class="col-ket"></colgroup>
                    <thead><tr><th>NO</th><th>NAMA BARANG</th><th>KODE</th><th>JUMLAH</th><th>KET</th></tr></thead>
                    <tbody>
                        @for ($i = 0; $i < $jumlahBaris; $i++)
                            @php $item = $baris[$i] ?? null; @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item?->barang?->nama_barang }}</td>
                                <td>{{ $item?->barang?->kode_barcode }}</td>
                                <td class="tengah">{{ $item?->jumlah }}</td>
                                <td>{{ $item?->ket }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                <section class="catatan">
                    <strong>Catatan:</strong>
                    @for ($i = 0; $i < $garisCatatan; $i++)
                        <div class="garis-catatan">{{ $catatan[$i] ?? '' }}</div>
                    @endfor
                </section>

                <div class="kotak-barcode">
                    <svg id="barcode" role="img" aria-label="Barcode transaksi {{ $kodeForm }}"></svg>
                </div>

                <section class="tanda-tangan">
                    <div>
                        <div class="judul-ttd">Kepala Bengkel PPLG</div>
                        <div class="nama-ttd">Irlan Maulana B</div>
                    </div>
                    <div>
                        <div class="judul-ttd">Padalarang, {{ $tglPinjam }}<br>Peminjam</div>
                        <div class="nama-ttd">{{ $peminjam->nama_peminjam }}</div>
                    </div>
                </section>
                <div class="tgl-kembali">Pengembalian: <span class="titik"></span></div>
            </article>
        </div>

        <p class="text-center text-secondary small mt-4 mb-0 tanpa-cetak">
            Kode transaksi: <span class="kode-struk" style="overflow-wrap: anywhere;">{{ $kodeForm }}</span><br>
            Simpan formulir ini. Barcode di atas dipakai untuk mengembalikan {{ $items->count() > 1 ? 'semua barang sekaligus' : 'barang' }}.
        </p>
    </main>

    @if (session('berhasil'))
    <div class="modal fade tanpa-cetak" id="modalBerhasil" tabindex="-1" aria-labelledby="judulBerhasil" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <h2 class="h4 fw-bold mb-2" id="judulBerhasil" style="color: var(--ada);">Peminjaman berhasil</h2>
                    <p class="mb-1">{{ session('berhasil') }} ({{ $items->count() }} barang)</p>
                    <p class="text-secondary small mb-4">
                        Kode transaksi: <span class="kode-struk" style="overflow-wrap: anywhere;">{{ $kodeForm }}</span><br>
                        Cetak formulir, tulis tanggal pengembalian, lalu minta tanda tangan.
                    </p>
                    <button type="button" class="btn btn-emas px-4 w-100" data-bs-dismiss="modal">Lihat formulir</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <footer class="py-3 mt-auto tanpa-cetak">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jsbarcode/JsBarcode.all.min.js') }}"></script>
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

        // barcode transaksi (CODE128) di bawah kotak catatan
        (function buatBarcode() {
            const svg = document.getElementById('barcode');
            JsBarcode(svg, @json($kodeForm), {
                format: 'CODE128', width: 2, height: 60, displayValue: true,
                fontSize: 16, margin: 0, textMargin: 2, lineColor: '#000', background: '#fff',
            });
            // biarkan SVG mengikuti lebar kotak (vektor, tetap tajam saat dicetak)
            const w = parseFloat(svg.getAttribute('width')), h = parseFloat(svg.getAttribute('height'));
            if (!svg.getAttribute('viewBox') && w && h) svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
            svg.removeAttribute('width'); svg.removeAttribute('height');
            svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');
        })();

        // layar kecil: perkecil lembar A4 agar muat tanpa geser (hanya tampilan layar)
        (function skalakan() {
            const area = document.getElementById('area-kertas');
            const kertas = document.getElementById('formulir-peminjaman');
            const atur = () => {
                kertas.style.removeProperty('--skala');
                const lebarKertas = kertas.offsetWidth;
                const tersedia = area.clientWidth;
                const skala = Math.min(1, tersedia / lebarKertas);
                kertas.style.setProperty('--skala', skala.toFixed(4));
            };
            atur();
            window.addEventListener('resize', atur);
        })();

        @if (session('berhasil'))
            new bootstrap.Modal(document.getElementById('modalBerhasil')).show();
        @endif

        @if (! empty($autoCetak))
            window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 600); });
        @endif
    </script>
</body>
</html>
