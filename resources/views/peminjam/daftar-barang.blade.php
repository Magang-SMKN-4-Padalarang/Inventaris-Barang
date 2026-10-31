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
    <link href="	{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
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

        .badge-status { font-weight: 700; border: 1px solid; }
        .badge-tersedia { background: rgba(79, 214, 156, .12); border-color: rgba(79, 214, 156, .4); color: var(--sukses); }
        .badge-dipinjam { background: rgba(241, 181, 61, .12); border-color: rgba(241, 181, 61, .45); color: var(--emas-teks); }

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
            .tabel-barang td[data-label="Nama Barang"] { font-weight: 700; }
            .tabel-barang td.kosong { display: block; text-align: center; }
            .tabel-barang td.kosong::before { content: none; }

            .info-bawah { align-items: stretch !important; text-align: center; }
            .info-bawah .pagination { justify-content: center; }
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

        <form method="GET" action="{{ route('peminjam.daftar-barang') }}" class="filter-bar row g-2 mb-4">
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
                    <a href="{{ route('peminjam.daftar-barang') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tabel-barang">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Status Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $item)
                            <tr>
                                <td data-label="No">{{ $barang->firstItem() + $loop->index }}</td>
                                <td data-label="Nama Barang">{{ $item->nama_barang }}</td>
                                <td data-label="Kategori">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td data-label="Status">
                                    <span class="badge rounded-pill badge-status {{ $item->status_barang === 'Tersedia' ? 'badge-tersedia' : 'badge-dipinjam' }}">
                                        {{ $item->status_barang }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="kosong text-center text-secondary py-5">
                                    @if (request()->filled('cari') || request()->filled('kategori'))
                                        Tidak ada barang yang cocok dengan pencarian.
                                    @else
                                        Belum ada data barang.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="info-bawah d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
            <p class="text-secondary small mb-0">Total {{ $barang->total() }} barang.</p>
            @if ($barang->hasPages())
                {{ $barang->links('pagination::bootstrap-5') }}
            @endif
        </div>

    </main>

    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

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
    </script>
</body>
</html>