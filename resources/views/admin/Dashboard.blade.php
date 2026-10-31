<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Dashboard Admin - Inventaris Barang</title>
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
            --ada: #0f9d7a;
            --pinjam: #d99a14;
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
            --pinjam: #f1b53d;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden; /* cegah scroll ke samping di layar kecil */
        }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
        }
        .btn-tema { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        .layout { display: flex; min-height: 100vh; }

        .sidebar { --bs-offcanvas-width: min(280px, 85vw); background: var(--kartu); }

        .sidebar-inner {
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            padding: 20px 16px;
            background: var(--kartu);
        }

        @media (min-width: 992px) {
            .sidebar {
                flex: 0 0 270px;
                position: sticky;
                top: 0;
                height: 100vh;
                border-right: 1px solid var(--bs-border-color);
            }
            .sidebar-inner { width: 270px; height: 100vh; overflow-y: auto; }
        }

        .logo { width: 56px; height: 56px; border-radius: 10px; object-fit: contain; flex-shrink: 0; }
        .brand-teks {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -.01em;
            color: var(--bs-body-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .side-judul { font-size: .85rem; font-weight: 700; color: var(--bs-secondary-color); padding: 0 14px; margin: 24px 0 8px; }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            min-height: 44px;
            border-radius: 10px;
            color: var(--bs-secondary-color);
            text-decoration: none;
            font-weight: 500;
            border: 1px solid transparent;
            transition: background .2s, color .2s;
        }
        .side-link i { font-size: 1.1rem; }
        .side-link:hover { color: var(--bs-body-color); background: var(--hover); }
        .side-link.active {
            color: var(--bs-body-color);
            background: var(--hover);
            border-color: var(--bs-border-color);
            box-shadow: inset 3px 0 0 var(--emas);
        }

        .sidebar-inner nav { margin-bottom: 24px; }

        .user-box {
            margin-top: auto;
            padding: 14px;
            border-radius: 14px;
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #1a1405;
            background: var(--emas);
            flex-shrink: 0;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: var(--nav-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--bs-border-color);
            padding-top: max(.75rem, env(safe-area-inset-top));
        }
        .topbar .btn-menu { min-width: 44px; min-height: 44px; }

        .content { flex: 1; min-width: 0; min-height: 100vh; display: flex; flex-direction: column; }

        h1 { font-size: clamp(1.75rem, 6vw, 3.2rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.08; overflow-wrap: break-word; }
        h2 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.015em; }
        .lead { max-width: 28rem; color: var(--bs-secondary-color); font-size: clamp(1rem, 2.2vw, 1.15rem); }

        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .papan-kepala { display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: .25rem 1rem; }
        .kondisi { list-style: none; margin: 0; padding: 0; }
        .kondisi li { display: grid; grid-template-columns: 6.5rem 1fr 2.5rem; align-items: center; gap: 14px; padding: .7rem 0; border-bottom: 1px solid var(--bs-border-color); }
        .kondisi li:last-child { border-bottom: 0; }
        .kondisi .nama { font-weight: 500; }
        .kondisi .jumlah { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; }
        .jalur { height: 6px; border-radius: 3px; background: var(--bs-border-color); overflow: hidden; }
        .jalur > span { display: block; height: 100%; border-radius: 3px; background: var(--c); }
        .k-ada { --c: var(--ada); }
        .k-pinjam { --c: var(--pinjam); }

        .layanan { display: block; padding: 2rem; border-radius: 14px; background: var(--emas); color: #1a1405; text-decoration: none; transition: transform .2s, background .2s; }
        .layanan:hover { background: #f7c55c; color: #1a1405; transform: translateY(-3px); }
        .layanan h3 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.01em; }
        .layanan-alt { background: transparent; border: 1.5px solid var(--bs-secondary-color); color: var(--bs-body-color); }
        .layanan-alt p { color: var(--bs-secondary-color); }
        .layanan-alt:hover { background: var(--hover); border-color: var(--bs-body-color); color: var(--bs-body-color); }

        .daftar .list-group-item { background: transparent; color: var(--bs-body-color); display: grid; grid-template-columns: 16rem 1fr; gap: 20px; padding: 1.1rem .5rem; min-height: 56px; }
        .daftar .list-group-item:hover { background: var(--hover); }
        .daftar span { color: var(--bs-secondary-color); }

        .aturan { border: 1.5px solid rgba(241, 181, 61, .45); background: rgba(241, 181, 61, .1); color: var(--bs-secondary-color); border-radius: 10px; }
        .aturan strong { color: #b97d00; }
        [data-bs-theme="dark"] .aturan strong { color: var(--emas); }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            border-radius: 10px; min-height: 44px; font-weight: 700;
        }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
            .lead { max-width: 36rem; }
            .daftar .list-group-item { grid-template-columns: 12rem 1fr; gap: 16px; }
        }

        @media (max-width: 767.98px) {
            .layanan { padding: 1.5rem; }
            .daftar .list-group-item { grid-template-columns: 1fr; gap: 2px; }
            .kondisi li { grid-template-columns: 5.5rem 1fr 2rem; gap: 10px; }
            .aturan { padding: 1rem !important; margin-top: 2rem !important; margin-bottom: 2rem !important; }
            .topbar { padding-left: max(1rem, env(safe-area-inset-left)) !important; padding-right: max(1rem, env(safe-area-inset-right)) !important; }
        }

        @media (max-width: 399.98px) {
            .logo { width: 44px; height: 44px; }
            .brand-teks { font-size: 1.1rem; }
            .kondisi li { grid-template-columns: 4.75rem 1fr 1.75rem; gap: 8px; }
            .papan { border-radius: 12px; }
            .layanan { padding: 1.25rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .topbar { padding-top: .35rem !important; padding-bottom: .35rem !important; }
        }

        @media (min-width: 1400px) {
            .isi { max-width: 1200px !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            .layanan, .side-link { transition: none; }
            .layanan:hover { transform: none; }
        }
    </style>
</head>

<body>
    @php
        $user     = auth()->user();
        $namaUser = $user->name ?? ($user->username ?? 'Admin');

        // Data ringkasan
        $total  = (int) ($totalBarang ?? 0);
        $ada    = (int) ($barangTersedia ?? 0);
        $pinjam = (int) ($sedangDipinjam ?? 0);
        $dasar  = max($total, 1);
        $lebar  = fn ($n) => round(min($n / $dasar * 100, 100), 2);

        // Link Daftar Barang: pakai route barang.index jika ada, kalau tidak ke kategori
        $linkBarang = Route::has('barang.index') ? route('barang.index') : route('kategori.index');
    @endphp

    <div class="layout">

        <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu" aria-label="Menu admin">

            <div class="offcanvas-header justify-content-end pb-0 d-lg-none">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div class="sidebar-inner">

                    <a href="{{ route('dashboard') }}"
                       class="d-flex align-items-center gap-2 text-decoration-none text-reset">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                        <span class="brand-teks">InventarisBarang</span>
                    </a>

                    <div class="side-judul">Menu</div>

                    <nav class="nav flex-column gap-1" aria-label="Navigasi admin">
                        <a class="side-link active" href="{{ route('dashboard') }}" aria-current="page">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a class="side-link" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                        <a class="side-link" href="{{ $linkBarang }}">
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

                    <section class="row g-4 g-lg-5 align-items-center py-4 py-md-5">
                        <div class="col-12 col-xl-5">
                            <h1>Selamat datang, {{ $namaUser }}.</h1>
                            <p class="lead mt-3">Kelola kategori, data barang, peminjaman, dan pengembalian di satu tempat.</p>
                        </div>

                        <div class="col-12 col-xl-7">
                            <div class="papan p-3 p-md-4">
                                <div class="papan-kepala mb-2">
                                    <h2 class="h5 fw-bold mb-0">Kondisi barang saat ini</h2>
                                    <span class="small text-secondary">{{ $total }} barang terdaftar</span>
                                </div>

                                <ul class="kondisi">
                                    <li class="k-ada">
                                        <span class="nama">Tersedia</span>
                                        <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($ada) }}%"></span></span>
                                        <span class="jumlah">{{ $ada }}</span>
                                    </li>
                                    <li class="k-pinjam">
                                        <span class="nama">Dipinjam</span>
                                        <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($pinjam) }}%"></span></span>
                                        <span class="jumlah">{{ $pinjam }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section aria-labelledby="judul-menu">
                        <h2 id="judul-menu" class="mb-3">Menu utama</h2>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <a class="layanan h-100" href="{{ route('kategori.index') }}">
                                    <h3 class="mb-2">Kategori</h3>
                                    <p class="mb-0">Tambah dan atur kategori untuk mengelompokkan barang.</p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6">
                                <a class="layanan layanan-alt h-100" href="{{ $linkBarang }}">
                                    <h3 class="mb-2">Daftar Barang</h3>
                                    <p class="mb-0">Lihat barang beserta kategori dan statusnya.</p>
                                </a>
                            </div>
                        </div>

                        <div class="list-group list-group-flush daftar mt-4 border-top border-bottom">
                            <a href="{{ route('admin.riwayat') }}" class="list-group-item list-group-item-action">
                                <strong>Riwayat peminjaman dan pengembalian</strong>
                                <span>Pantau transaksi yang berjalan dan yang sudah selesai.</span>
                            </a>
                            <a href="{{ route('peminjaman.index') }}" class="list-group-item list-group-item-action">
                                <strong>Halaman peminjaman</strong>
                                <span>Buka layanan peminjaman yang dipakai siswa.</span>
                            </a>
                        </div>
                    </section>

                    <div class="aturan px-4 py-3 mt-5 mb-5 small">
                        <strong>Ketentuan keterlambatan:</strong>
                        batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.
                    </div>

                </div>
            </main>

            <footer class="py-3 mt-auto px-3 px-md-5">
                <div class="isi mx-auto d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start" style="max-width: 1120px;">
                    <span>&copy;2026 smkn4padalarang | Lab PPLG PK</span>
                </div>
            </footer>

        </div>
    </div>

    {{-- Modal konfirmasi logout --}}
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
    </script>
</body>
</html>