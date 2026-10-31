<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Peminjaman Barang - Inventaris Barang</title>
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
        .halaman-form { max-width: 640px; }

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

        .card { background: var(--kartu); border-color: var(--bs-border-color); border-radius: 16px; }
        .form-control, .form-select { background-color: var(--input); border-color: var(--bs-border-color); min-height: 44px; }
        .form-control:focus, .form-select:focus { border-color: var(--aksen); box-shadow: 0 0 0 .2rem rgba(110, 168, 255, .25); }
        .form-select option { background-color: var(--kartu); color: var(--bs-body-color); }
        .kode-struk { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .08em; color: var(--aksen); }
        .kode-struk::placeholder { letter-spacing: 0; font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

        .info-24jam { border: 1.5px solid rgba(241, 181, 61, .45); background: rgba(241, 181, 61, .1); }
        .info-24jam strong { color: var(--emas-teks); }

        .modal-content { background: var(--kartu); border-color: var(--bs-border-color); }
        #ringkasan dd { overflow-wrap: anywhere; } /* nilai panjang (nama, kode) tidak melebar keluar */

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }


        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            .btn-kembali { width: 100%; }

            .form-control, .form-select { font-size: 1rem; }
        }

        @media (max-width: 575.98px) {
            .card { border-radius: 12px; }
            .kode-struk { letter-spacing: .04em; }
            .info-24jam { padding: .85rem !important; }

            .modal-dialog { margin: .75rem; }
            .modal-footer { flex-direction: column-reverse; }
            .modal-footer .btn { width: 100%; margin: 0; }
            #ringkasan dt { margin-bottom: 0; }
            #ringkasan dd { margin-bottom: .6rem; font-weight: 500; }
        }

        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
            .card { padding: 1rem !important; }
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
            <a class="btn btn-outline-secondary btn-sm btn-kembali mb-4" href="{{ url('/peminjam') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                Kembali ke dashboard
            </a>

            <h1 class="mb-2">Peminjaman Barang</h1>
            <p class="text-secondary mb-4">Pilih tipe peminjam, scan barcode barang, lalu isi data peminjam.</p>

            <form method="POST" action="{{ url('/peminjam/pinjam') }}" class="card p-3 p-md-4" id="form-pinjam">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                @endif

                <div class="mb-3">
                    <label for="tipe_peminjam" class="form-label">Saya meminjam sebagai</label>
                    <select id="tipe_peminjam" name="tipe_peminjam" class="form-select @error('tipe_peminjam') is-invalid @enderror" required>
                        <option value="">Pilih tipe peminjam</option>
                        @foreach ($daftarTipe as $tipe)
                            <option value="{{ $tipe }}" @selected(old('tipe_peminjam') === $tipe)>{{ $tipe }}</option>
                        @endforeach
                    </select>
                    @error('tipe_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div id="bagian-form" class="d-none">

                    <div class="mb-3">
                        <label for="kode_barcode" class="form-label">Kode barang (scan barcode)</label>
                        <input type="text" id="kode_barcode" name="kode_barcode" value="{{ old('kode_barcode') }}"
                               class="form-control form-control-lg kode-struk @error('kode_barcode') is-invalid @enderror"
                               placeholder="Scan barcode atau ketik kode barang" autocomplete="off" required>
                        @error('kode_barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="hasil-barang" class="small mt-2" aria-live="polite"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_peminjam" class="form-label">Nama lengkap</label>
                        <input type="text" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}"
                               class="form-control @error('nama_peminjam') is-invalid @enderror" maxlength="100" autocomplete="name" required>
                        @error('nama_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div data-tipe="Siswa" class="d-none">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" inputmode="numeric" class="form-control @error('nis') is-invalid @enderror" maxlength="30" required>
                                @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" class="form-control @error('kelas') is-invalid @enderror" maxlength="30" placeholder="Contoh: XI RPL 2" required>
                                @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div data-tipe="Guru" class="d-none">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP <span class="text-secondary">(opsional)</span></label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" inputmode="numeric" class="form-control @error('nip') is-invalid @enderror" maxlength="30">
                            @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div data-tipe="Staff" class="d-none">
                        <div class="mb-3">
                            <label for="bagian" class="form-label">Bagian / jabatan</label>
                            <input type="text" id="bagian" name="bagian" value="{{ old('bagian') }}" class="form-control @error('bagian') is-invalid @enderror" maxlength="100" placeholder="Contoh: Tata Usaha" required>
                            @error('bagian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div data-tipe="Lainnya" class="d-none">
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP</label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" autocomplete="tel" class="form-control @error('no_hp') is-invalid @enderror" maxlength="20" required>
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keperluan" class="form-label">Keperluan atau nama siswa yang diwakili</label>
                            <input type="text" id="keperluan" name="keperluan" value="{{ old('keperluan') }}" class="form-control @error('keperluan') is-invalid @enderror" maxlength="150" required>
                            @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-label">Lama peminjaman</div>
                        <div class="info-24jam p-3 rounded-3">
                            <strong>24 jam (1 hari)</strong>
                            <div class="small text-secondary mt-1">Barang wajib dikembalikan dalam 24 jam. Jika lebih dari itu dianggap terlambat dan wajib membersihkan lab.</div>
                        </div>
                    </div>

                    <button type="submit" id="tombol-simpan" class="btn btn-emas btn-lg rounded-pill w-100" disabled>Pinjam dan buat struk</button>
                </div>
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

        const formPinjam      = document.getElementById('form-pinjam');
        const pilihTipe       = document.getElementById('tipe_peminjam');
        const bagianForm      = document.getElementById('bagian-form');
        const inputKode       = document.getElementById('kode_barcode');
        const hasilBarang     = document.getElementById('hasil-barang');
        const tombolSimpan    = document.getElementById('tombol-simpan');
        const ringkasan       = document.getElementById('ringkasan');
        const tombolYakin     = document.getElementById('tombol-yakin');
        const modalKonfirmasi = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

        let namaBarang = '';
        let timerCek   = null;  
        let idCek      = 0;     

        function aturTipe() {
            const tipe = pilihTipe.value;
            bagianForm.classList.toggle('d-none', !tipe);

            document.querySelectorAll('[data-tipe]').forEach(grup => {
                const aktif = grup.dataset.tipe === tipe;
                grup.classList.toggle('d-none', !aktif);
                grup.querySelectorAll('input').forEach(input => input.disabled = !aktif);
            });

            if (tipe) inputKode.focus();
        }

        async function cekBarang() {
            const kode = inputKode.value.trim();
            const id = ++idCek;             
            tombolSimpan.disabled = true;
            namaBarang = '';

            if (!kode) { hasilBarang.className = 'small mt-2'; hasilBarang.textContent = ''; return; }

            hasilBarang.className = 'small mt-2 text-secondary';
            hasilBarang.textContent = 'Memeriksa barang...';

            try {
                const respon = await fetch(`{{ url('/peminjam/barang') }}/${encodeURIComponent(kode)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await respon.json();
                if (id !== idCek) return;

                if (data.ok) {
                    namaBarang = data.nama;
                    hasilBarang.className = 'small mt-2 text-success';
                    hasilBarang.textContent = `${data.nama} tersedia.`;
                    tombolSimpan.disabled = false;
                } else {
                    hasilBarang.className = 'small mt-2 text-danger';
                    hasilBarang.textContent = data.pesan;
                }
            } catch (e) {
                if (id !== idCek) return;
                hasilBarang.className = 'small mt-2 text-danger';
                hasilBarang.textContent = 'Gagal memeriksa barang. Periksa koneksi lalu coba lagi.';
            }
        }

        function tambahBaris(label, nilai) {
            if (!nilai) return;
            const dt = document.createElement('dt');
            dt.className = 'col-12 col-sm-5 text-secondary fw-normal';
            dt.textContent = label;
            const dd = document.createElement('dd');
            dd.className = 'col-12 col-sm-7';
            dd.textContent = nilai;
            ringkasan.append(dt, dd);
        }

        formPinjam.addEventListener('submit', e => {
            e.preventDefault();
            ringkasan.replaceChildren();

            tambahBaris('Tipe peminjam', pilihTipe.value);
            tambahBaris('Barang', `${namaBarang} (${inputKode.value.trim()})`);
            tambahBaris('Nama', document.getElementById('nama_peminjam').value.trim());

            document.querySelectorAll('[data-tipe]:not(.d-none) input').forEach(input => {
                const label = document.querySelector(`label[for="${input.id}"]`).textContent.replace('(opsional)', '').trim();
                tambahBaris(label, input.value.trim());
            });

            const batas = new Date(Date.now() + 24 * 60 * 60 * 1000);
            tambahBaris('Lama pinjam', '24 jam (1 hari)');
            tambahBaris('Batas pengembalian', batas.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }));

            modalKonfirmasi.show();
        });

        tombolYakin.addEventListener('click', () => {
            tombolYakin.disabled = true;
            tombolYakin.textContent = 'Menyimpan...';
            formPinjam.submit();
        });

        inputKode.addEventListener('input', () => {
            tombolSimpan.disabled = true;
            clearTimeout(timerCek);
            timerCek = setTimeout(cekBarang, 400);
        });

        inputKode.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); clearTimeout(timerCek); cekBarang(); }
        });

        pilihTipe.addEventListener('change', aturTipe);

        window.addEventListener('pageshow', () => {
            tombolYakin.disabled = false;
            tombolYakin.textContent = 'Ya, simpan';
        });

        aturTipe();
        if (inputKode.value.trim()) cekBarang();
    </script>
</body>
</html>