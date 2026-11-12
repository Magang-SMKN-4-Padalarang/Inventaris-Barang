<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Akun - Inventaris Barang</title>
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
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --nav-bg: rgba(10, 25, 48, .92);
            --hover: rgba(173, 198, 245, .1);
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
            overflow: hidden;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }

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

        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }

        .foto-besar {
            width: 96px; height: 96px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.25rem; font-weight: 800; color: #1a1405;
            background: var(--emas); overflow: hidden;
        }
        .foto-besar img { width: 100%; height: 100%; object-fit: cover; }

        .form-control { background: var(--bs-body-bg); color: var(--bs-body-color); border-color: var(--bs-border-color); min-height: 44px; }
        .form-control:focus { background: var(--bs-body-bg); color: var(--bs-body-color); }
        .table { --bs-table-bg: transparent; --bs-table-color: var(--bs-body-color); --bs-table-border-color: var(--bs-border-color); }

        .modal-content { color: var(--bs-body-color); background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            border-radius: 10px; min-height: 44px; font-weight: 700;
        }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }

        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
        }
        @media (max-width: 767.98px) {
            .topbar { padding-left: max(1rem, env(safe-area-inset-left)) !important; padding-right: max(1rem, env(safe-area-inset-right)) !important; }
        }
        @media (max-width: 399.98px) {
            .logo { width: 44px; height: 44px; }
            .brand-teks { font-size: 1.1rem; }
            .papan { border-radius: 12px; }
            footer { font-size: .8rem; }
        }
        @media (max-height: 480px) and (orientation: landscape) {
            .topbar { padding-top: .35rem !important; padding-bottom: .35rem !important; }
        }
        @media (min-width: 1400px) {
            .isi { max-width: 1200px !important; }
        }
        @media (prefers-reduced-motion: reduce) {
            .side-link { transition: none; }
        }
    </style>
</head>

<body>
    @php
        $namaUser = $user->nama_admin ?? ($user->username ?? 'Admin');
        $fotoUser = $user->foto ? asset('storage/' . $user->foto) : null;
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
                        <a class="side-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a class="side-link" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                        <a class="side-link" href="{{ route('barang.index') }}">
                            <i class="bi bi-box-seam"></i> Daftar Barang
                        </a>
                        <a class="side-link" href="{{ route('admin.riwayat') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                        <a class="side-link" href="{{ route('admin.log') }}">
                            <i class="bi bi-journal-text"></i> Log
                        </a>
                        <a class="side-link active" href="{{ route('akun') }}" aria-current="page">
                            <i class="bi bi-person-gear"></i> Akun
                        </a>
                    </nav>

                    <div class="user-box">
                        <a href="{{ route('akun') }}" class="d-flex align-items-center gap-3 mb-3 text-decoration-none text-reset">
                            <div class="avatar">
                                @if ($fotoUser)
                                    <img src="{{ $fotoUser }}" alt="">
                                @else
                                    {{ mb_strtoupper(mb_substr($namaUser, 0, 1)) }}
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-truncate">{{ $namaUser }}</div>
                                <small class="text-body-secondary">Admin</small>
                            </div>
                        </a>

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

                    @if (session('sukses'))
                        <div class="alert alert-success mt-4 mb-0" role="alert">{{ session('sukses') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mt-4 mb-0" role="alert">{{ session('error') }}</div>
                    @endif

                    <div class="py-4 py-md-5">
                        <h1 class="mb-4">Akun</h1>

                        <div class="row g-4">

                            {{-- Profil sendiri --}}
                            <div class="col-12 col-lg-7">
                                <div class="papan p-3 p-md-4 h-100">
                                    <h2 class="h5 fw-bold mb-3">Profil saya</h2>

                                    <form action="{{ route('akun.profil') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="foto-besar">
                                                @if ($fotoUser)
                                                    <img src="{{ $fotoUser }}" alt="Foto profil">
                                                @else
                                                    {{ mb_strtoupper(mb_substr($namaUser, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <label for="foto" class="form-label">Foto profil</label>
                                                <input type="file" id="foto" name="foto" accept="image/*"
                                                       class="form-control @error('foto') is-invalid @enderror">
                                                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                <div class="form-text">JPG, PNG, atau WEBP. Maksimal 2 MB.</div>

                                                @if ($user->foto)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapus_foto" value="1">
                                                        <label class="form-check-label small" for="hapus_foto">Hapus foto saat ini</label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama</label>
                                            <input type="text" id="name" name="name" value="{{ old('name', $user->nama_admin) }}"
                                                   class="form-control @error('name') is-invalid @enderror" required>
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                                                   autocomplete="username"
                                                   class="form-control @error('username') is-invalid @enderror" required>
                                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <button type="submit" class="btn btn-emas px-4">Simpan perubahan</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Ganti password --}}
                            <div class="col-12 col-lg-5">
                                <div class="papan p-3 p-md-4 h-100">
                                    <h2 class="h5 fw-bold mb-3">Ganti password</h2>

                                    <form action="{{ route('akun.password') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label for="password_lama" class="form-label">Password lama</label>
                                            <input type="password" id="password_lama" name="password_lama" autocomplete="current-password"
                                                   class="form-control @if ($errors->password->has('password_lama')) is-invalid @endif" required>
                                            @if ($errors->password->has('password_lama'))
                                                <div class="invalid-feedback">{{ $errors->password->first('password_lama') }}</div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_baru" class="form-label">Password baru</label>
                                            <input type="password" id="password_baru" name="password" autocomplete="new-password"
                                                   class="form-control @if ($errors->password->has('password')) is-invalid @endif" required>
                                            @if ($errors->password->has('password'))
                                                <div class="invalid-feedback">{{ $errors->password->first('password') }}</div>
                                            @endif
                                        </div>

                                        <div class="mb-4">
                                            <label for="password_baru_konfirmasi" class="form-label">Ulangi password baru</label>
                                            <input type="password" id="password_baru_konfirmasi" name="password_confirmation"
                                                   autocomplete="new-password" class="form-control" required>
                                        </div>

                                        <button type="submit" class="btn btn-outline-secondary px-4">Ganti password</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Daftar admin --}}
                            <div class="col-12">
                                <div class="papan p-3 p-md-4">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                        <h2 class="h5 fw-bold mb-0">Daftar admin</h2>
                                        <button type="button" class="btn btn-emas px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah admin
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width:64px"></th>
                                                    <th>Nama</th>
                                                    <th>Username</th>
                                                    <th class="text-end">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($admins as $admin)
                                                    <tr>
                                                        <td>
                                                            <div class="avatar">
                                                                @if ($admin->foto)
                                                                    <img src="{{ asset('storage/' . $admin->foto) }}" alt="">
                                                                @else
                                                                    {{ mb_strtoupper(mb_substr($admin->nama_admin ?? $admin->username, 0, 1)) }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="fw-semibold">
                                                            {{ $admin->nama_admin }}
                                                            @if ($admin->id_admin === $user->id_admin)
                                                                <span class="badge text-bg-warning ms-1">Kamu</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-body-secondary">{{ $admin->username }}</td>
                                                        <td class="text-end">
                                                            @if ($admin->id_admin !== $user->id_admin)
                                                                <form action="{{ route('akun.hapus', $admin) }}" method="POST"
                                                                      class="d-inline form-hapus" data-nama="{{ $admin->username }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                        <i class="bi bi-trash"></i> Hapus
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
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

    {{-- Modal tambah admin --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="judulTambah" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('akun.tambah') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h2 class="h5 fw-bold mb-0" id="judulTambah">Tambah admin</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="t_name" class="form-label">Nama</label>
                            <input type="text" id="t_name" name="name" value="{{ old('name') }}"
                                   class="form-control @if ($errors->tambah->has('name')) is-invalid @endif" required>
                            @if ($errors->tambah->has('name'))
                                <div class="invalid-feedback">{{ $errors->tambah->first('name') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="t_username" class="form-label">Username</label>
                            <input type="text" id="t_username" name="username" value="{{ old('username') }}" autocomplete="off"
                                   class="form-control @if ($errors->tambah->has('username')) is-invalid @endif" required>
                            @if ($errors->tambah->has('username'))
                                <div class="invalid-feedback">{{ $errors->tambah->first('username') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="t_password" class="form-label">Password</label>
                            <input type="password" id="t_password" name="password" autocomplete="new-password"
                                   class="form-control @if ($errors->tambah->has('password')) is-invalid @endif" required>
                            @if ($errors->tambah->has('password'))
                                <div class="invalid-feedback">{{ $errors->tambah->first('password') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="t_konfirmasi" class="form-label">Ulangi password</label>
                            <input type="password" id="t_konfirmasi" name="password_confirmation"
                                   autocomplete="new-password" class="form-control" required>
                        </div>

                        <div>
                            <label for="t_foto" class="form-label">Foto (opsional)</label>
                            <input type="file" id="t_foto" name="foto" accept="image/*"
                                   class="form-control @if ($errors->tambah->has('foto')) is-invalid @endif">
                            @if ($errors->tambah->has('foto'))
                                <div class="invalid-feedback">{{ $errors->tambah->first('foto') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-emas px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal konfirmasi hapus admin --}}
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulHapus" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="fs-1 mb-2 text-danger"><i class="bi bi-trash"></i></div>
                    <h2 class="h5 fw-bold mb-2" id="judulHapus">Hapus admin?</h2>
                    <p class="text-body-secondary small mb-4">Akun <strong id="namaHapus"></strong> akan dihapus permanen.</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger flex-fill" id="btnYaHapus">Ya, hapus</button>
                    </div>
                </div>
            </div>
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
            const modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
            let formLogout = null;
            document.querySelectorAll('.form-logout').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    formLogout = form;
                    modalLogout.show();
                });
            });
            document.getElementById('btnYaLogout').addEventListener('click', () => {
                if (formLogout) formLogout.submit();
            });

            const modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));
            let formHapus = null;
            document.querySelectorAll('.form-hapus').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    formHapus = form;
                    document.getElementById('namaHapus').textContent = form.dataset.nama;
                    modalHapus.show();
                });
            });
            document.getElementById('btnYaHapus').addEventListener('click', () => {
                if (formHapus) formHapus.submit();
            });
        })();

        @if ($errors->tambah->any())
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        @endif
    </script>
</body>
</html>