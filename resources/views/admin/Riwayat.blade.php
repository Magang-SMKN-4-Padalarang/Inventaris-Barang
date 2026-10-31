@if (! empty($laporan))
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $namaFile }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <style>
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body { margin: 0; font-family: system-ui, -apple-system, 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #111; background: #e9eef7; }

        .bar { position: sticky; top: 0; z-index: 10; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 20px; background: #0b2447; color: #fff; }
        .bar .teks { font-size: 13px; }
        .bar .teks small { display: block; opacity: .75; margin-top: 2px; }
        .bar .tombol { display: flex; gap: 8px; }
        .bar button { cursor: pointer; border: 0; border-radius: 8px; padding: 10px 16px; font-size: 14px; font-weight: 700; }
        .bar .utama { background: #f1b53d; color: #1a1405; }
        .bar .sekunder { background: rgba(255,255,255,.15); color: #fff; }

        .kertas { width: 297mm; max-width: 100%; margin: 18px auto; padding: 14mm; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,.15); }

        .kop { border-bottom: 2px solid #0b2447; padding-bottom: 8px; margin-bottom: 12px; }
        .kop h1 { margin: 0; font-size: 20px; color: #0b2447; letter-spacing: .02em; }
        .kop .sub { margin-top: 3px; font-size: 12px; color: #555; }

        table.info { border-collapse: collapse; margin-bottom: 10px; }
        table.info td { padding: 2px 12px 2px 0; vertical-align: top; }
        table.info td:first-child { color: #555; width: 110px; }

        table.ringkasan { border-collapse: collapse; margin-bottom: 14px; }
        table.ringkasan td { border: 1px solid #999; padding: 6px 18px; text-align: center; }
        table.ringkasan .angka { font-size: 17px; font-weight: 700; }
        table.ringkasan .label { font-size: 10px; color: #555; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #dfe7f5; border: 1px solid #888; padding: 7px 6px; text-align: left; font-size: 11px; }
        table.data td { border: 1px solid #aaa; padding: 6px; vertical-align: top; overflow-wrap: anywhere; }
        table.data thead { display: table-header-group; }
        table.data tr { break-inside: avoid; page-break-inside: avoid; }
        table.data tbody tr:nth-child(even) td { background: #f5f8fd; }
        .c { text-align: center; }

        .s-selesai   { color: #0a7a5c; font-weight: 700; }
        .s-terlambat { color: #c0245c; font-weight: 700; }
        .s-dipinjam  { color: #a06a00; font-weight: 700; }

        .kosong { text-align: center; padding: 30px !important; color: #555; }
        .foot { margin-top: 12px; font-size: 10px; color: #666; border-top: 1px solid #bbb; padding-top: 5px; }

        @page { size: A4 landscape; margin: 10mm; }

        @media print {
            body { background: #fff; }
            .bar { display: none !important; }
            .kertas { width: auto; margin: 0; padding: 0; box-shadow: none; }
        }

        @media (max-width: 900px) {
            .kertas { padding: 12px; overflow-x: auto; }
            table.data { min-width: 760px; }
        }
    </style>
</head>
<body>

    <div class="bar">
        <div class="teks">
            Pratinjau laporan
            <small>Pada dialog cetak, pilih tujuan <strong>Simpan sebagai PDF</strong> (Save as PDF).</small>
        </div>
        <div class="tombol">
            <button type="button" class="sekunder" onclick="window.close()">Tutup</button>
            <button type="button" class="utama" onclick="window.print()">Simpan / Cetak PDF</button>
        </div>
    </div>

    <div class="kertas">

        <div class="kop">
            <h1>LAPORAN RIWAYAT PEMINJAMAN</h1>
            <div class="sub">Inventaris Barang &mdash; SMKN 4 Padalarang | Lab PPLG PK</div>
        </div>

        <table class="info">
            <tr><td>Jenis laporan</td><td>: {{ $judul }}</td></tr>
            <tr><td>Periode</td><td>: {{ $awal->format('d/m/Y') }} s.d. {{ $akhir->format('d/m/Y') }} (berdasarkan tanggal pinjam)</td></tr>
            @if ($cari !== '')
                <tr><td>Pencarian</td><td>: "{{ $cari }}"</td></tr>
            @endif
            @if ($statusLbl !== '')
                <tr><td>Filter status</td><td>: {{ $statusLbl }}</td></tr>
            @endif
            <tr><td>Dicetak pada</td><td>: {{ now()->format('d/m/Y H:i') }}</td></tr>
        </table>

        <table class="ringkasan">
            <tr>
                <td><div class="angka">{{ $rows->count() }}</div><div class="label">Total transaksi</div></td>
                @foreach ($ringkasan as $label => $jumlah)
                    <td><div class="angka">{{ $jumlah }}</div><div class="label">{{ $label }}</div></td>
                @endforeach
            </tr>
        </table>

        <table class="data">
            <thead>
                <tr>
                    <th class="c" style="width: 34px;">No.</th>
                    <th style="width: 110px;">Kode</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th style="width: 105px;">Tgl Pinjam</th>
                    <th style="width: 105px;">Jatuh Tempo</th>
                    <th style="width: 105px;">Tgl Kembali</th>
                    <th class="c" style="width: 70px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $item)
                    <tr>
                        <td class="c">{{ $loop->iteration }}</td>
                        <td>{{ $item->kode_transaksi }}</td>
                        <td>{{ $item->peminjam->nama_peminjam ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->tgl_pinjam?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $item->tgl_jatuh_tempo?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $item->tgl_kembali?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="c s-{{ strtolower($item->status_label) }}">{{ $item->status_label }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="kosong">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="foot">&copy; 2026 smkn4padalarang | Lab PPLG PK</div>
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 500);
        });
    </script>
</body>
</html>
@else
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Peminjaman - Inventaris Barang</title>
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

        .ikon-kotak { width: 44px; height: 44px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; background: color-mix(in srgb, var(--biru) 14%, transparent); color: var(--biru); }
        .badge-jumlah { background: var(--hover); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); font-weight: 600; }

        .tabel-data { margin-bottom: 0; vertical-align: middle; --bs-table-bg: transparent; --bs-table-color: var(--bs-body-color); --bs-table-border-color: var(--bs-border-color); --bs-table-hover-bg: var(--hover); --bs-table-hover-color: var(--bs-body-color); }
        .tabel-data thead th { padding: 14px 16px; color: var(--bs-secondary-color); background: var(--hover); font-size: .85rem; font-weight: 700; white-space: nowrap; border-bottom: 1px solid var(--bs-border-color); }
        .tabel-data tbody td { padding: 16px; border-color: var(--bs-border-color); }
        .nomor { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 10px; background: var(--hover); font-weight: 700; font-size: .85rem; font-variant-numeric: tabular-nums; }

        .badge-status { font-weight: 600; border: 1px solid; padding: .45em .85em; }
        .b-hijau { color: var(--ada); background: color-mix(in srgb, var(--ada) 12%, transparent); border-color: color-mix(in srgb, var(--ada) 40%, transparent); }
        .b-emas { color: var(--emas-teks); background: color-mix(in srgb, var(--emas-teks) 12%, transparent); border-color: color-mix(in srgb, var(--emas-teks) 40%, transparent); }
        .b-merah { color: var(--telat); background: color-mix(in srgb, var(--telat) 12%, transparent); border-color: color-mix(in srgb, var(--telat) 40%, transparent); }

        .col-aksi { white-space: nowrap; }

        .kosong { padding: 48px 20px !important; color: var(--bs-secondary-color); }
        .kosong i { display: block; font-size: 2.5rem; margin-bottom: 12px; opacity: .65; }
        .alert { border-radius: 12px; }

        .papan-isi { padding: 24px; }

        .input-cari, .form-control, .form-select { min-height: 44px; background-color: var(--kartu); border-color: var(--bs-border-color); color: var(--bs-body-color); border-radius: 10px; }
        .input-cari:focus, .form-control:focus, .form-select:focus { background-color: var(--kartu); color: var(--bs-body-color); border-color: var(--biru); box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 20%, transparent); }
        .input-group > .input-group-text.input-cari { border-radius: 10px 0 0 10px; color: var(--bs-secondary-color); }
        .input-group > .form-control.input-cari { border-radius: 0 10px 10px 0; }

        .pagination { gap: 5px; margin-bottom: 0; flex-wrap: wrap; }
        .page-link { min-width: 40px; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; color: var(--bs-body-color); background: var(--kartu); border-color: var(--bs-border-color); border-radius: 8px !important; }
        .page-item.active .page-link { color: #1a1405; background: var(--emas); border-color: var(--emas); }
        .page-item.disabled .page-link { color: var(--bs-secondary-color); background: var(--hover); }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .modal-header, .modal-footer { border-color: var(--bs-border-color); }
        .form-text { color: var(--bs-secondary-color); }

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

            .aksi-halaman { width: 100%; }
            .aksi-halaman .btn { flex: 1 1 0; }

            .cari-tombol { display: flex; gap: 8px; width: 100%; }
            .cari-tombol .btn { flex: 1 1 0; }
            .info-halaman { width: 100%; text-align: center; }
            .nav-halaman { width: 100%; display: flex; justify-content: center; }

            .tabel-data thead { display: none; }
            .tabel-data, .tabel-data tbody { display: block; width: 100%; }

            .tabel-data tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: 1fr auto;
                grid-template-areas: "peminjam status" "barang barang" "pinjam pinjam" "kembali kembali" "aksi aksi";
                column-gap: 12px; row-gap: 4px;
                padding: 16px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-data tbody tr:not(.baris-kosong):last-child { border-bottom: 0; }
            .tabel-data tbody tr:not(.baris-kosong) td { display: block; padding: 0; border: 0; }

            .col-no { display: none !important; }
            .col-peminjam { grid-area: peminjam; font-weight: 700; min-width: 0; overflow-wrap: anywhere; }
            .col-status { grid-area: status; align-self: start; }
            .col-barang { grid-area: barang; color: var(--bs-secondary-color); overflow-wrap: anywhere; }
            .col-pinjam { grid-area: pinjam; font-size: .9rem; margin-top: 8px; }
            .col-kembali { grid-area: kembali; font-size: .9rem; }
            .col-pinjam::before, .col-kembali::before { content: attr(data-label) ": "; color: var(--bs-secondary-color); }
            .col-aksi { grid-area: aksi; margin-top: 12px; }
            .col-aksi .btn { width: 100%; min-height: 44px; }

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
            .btn-emas, .side-link { transition: none; }
            .btn-emas:hover { transform: none; }
        }

        .struk {
            background: #fff; color: #111;
            max-width: 340px; margin: 0 auto; padding: 20px 18px;
            font-family: 'Courier New', ui-monospace, monospace; font-size: .9rem;
            border-radius: 6px;
        }
        .struk-judul { text-align: center; font-weight: 700; font-size: 1.05rem; letter-spacing: .04em; }
        .struk-sub   { text-align: center; font-size: .8rem; color: #555; }
        .struk hr    { border: 0; border-top: 1px dashed #777; opacity: 1; margin: 12px 0; }
        .struk-baris { display: flex; justify-content: space-between; gap: 12px; padding: 3px 0; }
        .struk-baris span:first-child { color: #555; flex-shrink: 0; }
        .struk-baris span:last-child  { text-align: right; font-weight: 700; overflow-wrap: anywhere; }
        .struk-status { text-align: center; font-weight: 700; border: 1px dashed #111; padding: 6px; margin-top: 4px; }
        .struk-foot   { text-align: center; font-size: .75rem; color: #555; margin-top: 12px; }
        .struk-qr     { display: flex; justify-content: center; padding: 4px 0; }
        .struk-qr canvas, .struk-qr img { max-width: 100%; height: auto !important; }
        .struk-kode   { text-align: center; font-weight: 700; letter-spacing: .08em; overflow-wrap: anywhere; margin-top: 6px; }

        @page { size: A4 portrait; margin: 12mm; }

        @media print {
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            [data-bs-theme="dark"], [data-bs-theme="light"] {
                --bs-body-bg: #fff;
                --bs-body-color: #000;
                --bs-secondary-color: #444;
            }

            body { background: #fff !important; color: #000 !important; }
            .sidebar, .topbar, footer, .no-print, .alert { display: none !important; }
            .col-aksi { display: none !important; }
            .layout, .content { display: block !important; min-height: 0; }
            main { padding: 0 !important; }
            .isi { max-width: none !important; }

            .papan { background: #fff; border: 0; border-radius: 0; overflow: visible; }

            .tabel-data th, .tabel-data td {
                padding: 7px 8px !important;
                color: #000 !important;
                background: #fff !important;
                border: 1px solid #999 !important;
                font-size: 12px;
            }
            .tabel-data thead th { background: #e4eaf5 !important; }
            .badge-status { color: #000 !important; background: #fff !important; border: 1px solid #666 !important; }

            thead { display: table-header-group; }
            tr { break-inside: avoid; }

            body.cetak-struk .layout,
            body.cetak-struk .modal-backdrop,
            body.cetak-struk #modalLogout,
            body.cetak-struk #modalUnduh { display: none !important; }
            body.cetak-struk #modalStruk {
                position: static !important; display: block !important;
                overflow: visible !important; opacity: 1 !important;
            }
            body.cetak-struk #modalStruk .modal-dialog {
                margin: 0 auto !important; max-width: 340px;
                transform: none !important; height: auto !important;
            }
            body.cetak-struk #modalStruk .modal-content {
                border: 0 !important; background: #fff !important;
                max-height: none !important; overflow: visible !important;
            }
            body.cetak-struk #modalStruk .modal-body { padding: 0 !important; overflow: visible !important; }
        }
    </style>
</head>

<body>
    @php
        $user       = auth()->user();
        $namaUser   = $user->name ?? ($user->username ?? 'Admin');
        $linkBarang = Route::has('barang.index') ? route('barang.index') : route('kategori.index');

        $adaFilter = $cari !== '' || $status !== '';

        // Daftar nama bulan untuk modal unduh (cadangan jika controller tidak mengirim $namaBulan)
        $namaBulan = $namaBulan ?? [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Status transaksi + warna badge
        $statusTransaksi = function ($item) {
            if ($item->tgl_kembali) {
                return ['Selesai', 'b-hijau'];
            }
            if ($item->tgl_jatuh_tempo && $item->tgl_jatuh_tempo->isPast()) {
                return ['Terlambat', 'b-merah'];
            }
            return ['Dipinjam', 'b-emas'];
        };
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
                        <a class="side-link active" href="{{ route('admin.riwayat') }}" aria-current="page">
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

                    <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 py-4 py-md-5">
                        <div style="min-width: 0;">
                            <h1>Riwayat Peminjaman</h1>
                            <p class="lead mt-3 mb-0">Catatan transaksi peminjaman dan pengembalian barang.</p>
                        </div>

                        <div class="aksi-halaman d-flex gap-2 flex-wrap no-print">
                            <a href="{{ route('dashboard') }}"
                               class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center"
                               style="min-height: 44px;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>

                            <button type="button" class="btn btn-emas"
                                    data-bs-toggle="modal" data-bs-target="#modalUnduh">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Laporan (PDF)
                            </button>
                        </div>
                    </section>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <section class="papan mb-5" aria-labelledby="judul-daftar">

                        <div class="papan-kepala no-print">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="ikon-kotak"><i class="bi bi-clock-history"></i></div>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h2 id="judul-daftar" class="h5 fw-bold mb-1">Daftar Transaksi</h2>
                                    <div class="small text-body-secondary">Peminjaman yang berjalan dan yang sudah selesai</div>
                                </div>

                                <span class="badge rounded-pill px-3 py-2 badge-jumlah">
                                    {{ $riwayat->total() }} transaksi
                                </span>
                            </div>
                        </div>

                        <div class="papan-isi no-print">

                            <form method="GET" action="{{ route('admin.riwayat') }}" class="row g-2 align-items-center mb-4">
                                <div class="col-12 col-md-5 col-lg-4">
                                    <div class="input-group">
                                        <span class="input-group-text input-cari"><i class="bi bi-search"></i></span>
                                        <input type="text" name="q" value="{{ $cari }}" class="form-control input-cari"
                                               placeholder="Cari peminjam, barang, atau kode..." maxlength="100"
                                               aria-label="Cari riwayat">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-3">
                                    <select name="status" class="form-select" aria-label="Filter status">
                                        <option value="">Semua status</option>
                                        @foreach ($opsiStatus as $nilai => $label)
                                            <option value="{{ $nilai }}" @selected($status === $nilai)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-auto">
                                    <div class="cari-tombol d-md-flex gap-2">
                                        <button type="submit" class="btn btn-emas">
                                            <i class="bi bi-search me-1"></i> Cari
                                        </button>

                                        @if ($adaFilter)
                                            <a href="{{ route('admin.riwayat') }}"
                                               class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover tabel-data">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Peminjam</th>
                                            <th>Barang</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                            <th class="col-aksi">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($riwayat as $item)
                                            @php [$statusItem, $kelas] = $statusTransaksi($item); @endphp
                                            <tr>
                                                <td class="col-no"><span class="nomor">{{ $riwayat->firstItem() + $loop->index }}</span></td>
                                                <td class="col-peminjam fw-semibold">{{ $item->peminjam->nama_peminjam ?? '-' }}</td>
                                                <td class="col-barang">{{ $item->barang->nama_barang ?? '-' }}</td>
                                                <td class="col-pinjam" data-label="Dipinjam">{{ $item->tgl_pinjam?->format('d/m/Y H:i') ?? '-' }}</td>
                                                <td class="col-kembali" data-label="Dikembalikan">{{ $item->tgl_kembali?->format('d/m/Y H:i') ?? '-' }}</td>
                                                <td class="col-status">
                                                    <span class="badge rounded-pill badge-status {{ $kelas }}">{{ $statusItem }}</span>
                                                </td>
                                                <td class="col-aksi">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#modalStruk"
                                                            data-kode="{{ $item->kode_transaksi }}"
                                                            data-peminjam="{{ $item->peminjam->nama_peminjam ?? '-' }}"
                                                            data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                                            data-pinjam="{{ $item->tgl_pinjam?->format('d/m/Y H:i') ?? '-' }}"
                                                            data-tempo="{{ $item->tgl_jatuh_tempo?->format('d/m/Y H:i') ?? '-' }}"
                                                            data-kembali="{{ $item->tgl_kembali?->format('d/m/Y H:i') ?? 'Belum dikembalikan' }}"
                                                            data-status="{{ $statusItem }}">
                                                        <i class="bi bi-receipt me-1"></i> Lihat selengkapnya
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="baris-kosong">
                                                <td colspan="7" class="text-center kosong">
                                                    <i class="bi bi-inbox"></i>
                                                    @if ($adaFilter)
                                                        <div class="fw-bold mb-1">Tidak ada transaksi yang cocok</div>
                                                        <div class="small">Coba ubah kata kunci atau reset filter.</div>
                                                    @else
                                                        <div class="fw-bold mb-1">Belum ada riwayat peminjaman</div>
                                                        <div class="small">Transaksi akan muncul di sini setelah ada peminjaman.</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($riwayat->hasPages())
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                                    <small class="text-body-secondary info-halaman">
                                        Menampilkan {{ $riwayat->firstItem() ?? 0 }}–{{ $riwayat->lastItem() ?? 0 }}
                                        dari {{ $riwayat->total() }} transaksi
                                    </small>

                                    <div class="nav-halaman">
                                        {{ $riwayat->links('pagination::bootstrap-5') }}
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

    <div class="modal fade no-print" id="modalUnduh" tabindex="-1" aria-labelledby="judulUnduh" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="{{ route('admin.riwayat.unduh') }}" id="formUnduh" target="_blank">
                    <input type="hidden" name="q" value="{{ $cari }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="judulUnduh">Unduh Laporan PDF</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="uPeriode" class="form-label fw-semibold">Periode</label>
                            <select name="periode" id="uPeriode" class="form-select">
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan" selected>Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>

                        <div class="mb-3" id="grupTanggal">
                            <label for="uTanggal" class="form-label fw-semibold">Pilih tanggal</label>
                            <input type="date" name="tanggal" id="uTanggal" class="form-control" value="{{ now()->toDateString() }}">
                            <div class="form-text">Laporan mencakup satu minggu (Senin–Minggu) yang memuat tanggal ini.</div>
                        </div>

                        <div class="mb-3" id="grupBulan">
                            <label for="uBulan" class="form-label fw-semibold">Bulan</label>
                            <select name="bulan" id="uBulan" class="form-select">
                                @foreach ($namaBulan as $angka => $nama)
                                    <option value="{{ $angka }}" @selected(now()->month === $angka)>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-1" id="grupTahun">
                            <label for="uTahun" class="form-label fw-semibold">Tahun</label>
                            <input type="number" name="tahun" id="uTahun" class="form-control"
                                   value="{{ now()->year }}" min="2000" max="2100">
                        </div>

                        <div class="form-text mt-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Laporan terbuka di tab baru, lalu pilih <strong>Simpan sebagai PDF</strong> pada dialog cetak. Dihitung berdasarkan tanggal pinjam.
                            @if ($adaFilter) Pencarian dan filter status yang sedang aktif ikut diterapkan. @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-emas">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Buat PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalStruk" tabindex="-1" aria-labelledby="judulStruk" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header no-print">
                    <h2 class="modal-title h5" id="judulStruk">Struk Peminjaman</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="struk">
                        <div class="struk-judul">INVENTARIS BARANG</div>
                        <div class="struk-sub">SMKN 4 Padalarang | Lab PPLG PK</div>
                        <hr>
                        <div class="struk-qr"><div id="sQr"></div></div>
                        <div class="struk-kode" id="sKode">-</div>
                        <hr>
                        <div class="struk-baris"><span>Peminjam</span><span id="sPeminjam">-</span></div>
                        <div class="struk-baris"><span>Barang</span><span id="sBarang">-</span></div>
                        <hr>
                        <div class="struk-baris"><span>Dipinjam</span><span id="sPinjam">-</span></div>
                        <div class="struk-baris"><span>Jatuh tempo</span><span id="sTempo">-</span></div>
                        <div class="struk-baris"><span>Dikembalikan</span><span id="sKembali">-</span></div>
                        <hr>
                        <div class="struk-status" id="sStatus">-</div>
                        <div class="struk-foot">Simpan struk ini sebagai bukti peminjaman.</div>
                    </div>
                </div>

                <div class="modal-footer no-print">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-emas" id="btnCetakStruk">
                        <i class="bi bi-printer me-1"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/qrcodejs/qrcode.min.js') }}"></script>
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

        (function () {
            const periode = document.getElementById('uPeriode');
            const grup = {
                tanggal: document.getElementById('grupTanggal'),
                bulan:   document.getElementById('grupBulan'),
                tahun:   document.getElementById('grupTahun'),
            };

            function atur() {
                const p = periode.value;
                const tampil = {
                    tanggal: p === 'mingguan',
                    bulan:   p === 'bulanan',
                    tahun:   p !== 'mingguan',
                };
                for (const k in grup) {
                    grup[k].hidden = !tampil[k];
                    grup[k].querySelectorAll('input, select').forEach(el => el.disabled = !tampil[k]);
                }
            }

            periode.addEventListener('change', atur);
            atur();

            const form = document.getElementById('formUnduh');
            form.addEventListener('submit', () => {
                setTimeout(() => {
                    const inst = bootstrap.Modal.getInstance(document.getElementById('modalUnduh'));
                    if (inst) inst.hide();
                }, 400);
            });
        })();

        (function () {
            const modal = document.getElementById('modalStruk');

            modal.addEventListener('show.bs.modal', (e) => {
                const d = e.relatedTarget.dataset;
                document.getElementById('sKode').textContent     = d.kode;
                const qr = document.getElementById('sQr');
                qr.innerHTML = '';
                new QRCode(qr, { text: d.kode, width: 150, height: 150 });
                document.getElementById('sPeminjam').textContent = d.peminjam;
                document.getElementById('sBarang').textContent   = d.barang;
                document.getElementById('sPinjam').textContent   = d.pinjam;
                document.getElementById('sTempo').textContent    = d.tempo;
                document.getElementById('sKembali').textContent  = d.kembali;
                document.getElementById('sStatus').textContent   = 'STATUS: ' + d.status.toUpperCase();
            });

            document.getElementById('btnCetakStruk').addEventListener('click', () => {
                document.body.classList.add('cetak-struk');
                window.print();
            });
        })();

        window.addEventListener('afterprint', () => document.body.classList.remove('cetak-struk'));
    </script>
</body>
</html>
@endif