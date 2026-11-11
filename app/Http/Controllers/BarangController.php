<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Jenis_barang;
use App\Models\Kategori;
use App\Models\Activity_log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private const MAKS_CETAK = 100;
    private const FOLDER_FOTO = 'uploads/barang'; // relatif terhadap public/

    // Nama kolom ENUM di tabel barang. Ganti kalau nama kolom di databasemu beda.
    private const COL_RUANG  = 'ruangan';
    private const COL_LEMARI = 'lemari';

    // Nilai option "ketik manual" di dropdown (ruang, lemari, kategori)
    private const OPSI_BARU = '__baru__';
    private const UKURAN = [
        'besar' => [
            'label' => 'Besar 60×35 mm (21/lembar)',
            'w' => 60, 'h' => 35, 'kolom' => 3, 'baris' => 7, 'gap_x' => 5, 'gap_y' => 4,
            'judul_pt' => 7, 'nama_pt' => 9, 'aksen' => 1.5,
            'bc_w' => 54, 'bc_h' => 18, 'bc_font' => 14,
        ],
        'sedang' => [
            'label' => 'Sedang 45×25 mm (36/lembar)',
            'w' => 45, 'h' => 25, 'kolom' => 4, 'baris' => 9, 'gap_x' => 3, 'gap_y' => 3,
            'judul_pt' => 5.5, 'nama_pt' => 7, 'aksen' => 1,
            'bc_w' => 41, 'bc_h' => 12, 'bc_font' => 16,
        ],
        'kecil' => [
            'label' => 'Kecil 35×18 mm (65/lembar)',
            'w' => 35, 'h' => 18, 'kolom' => 5, 'baris' => 13, 'gap_x' => 2, 'gap_y' => 2,
            'judul_pt' => 0, 'nama_pt' => 6, 'aksen' => 0,   // tanpa judul & garis aksen
            'bc_w' => 32, 'bc_h' => 10, 'bc_font' => 18,
        ],
    ];

    public function index(Request $request)
    {
        // Jenis barang diambil dari tabel jenis_barang (bukan pengelompokan virtual).
        // Pagination/pencarian/filter tetap dilakukan di browser.
        $jenis = Jenis_barang::orderBy('nama_jenis_barang')->get();

        $unitPerJenis = Barang::with('kategori')
            ->orderBy('kode_barcode')
            ->get()
            ->groupBy('id_jenis_barang');

        $grup = $jenis
            ->map(function ($j) use ($unitPerJenis) {
                $units    = $unitPerJenis->get($j->id_jenis_barang, collect());
                $f        = $units->first();
                $dipinjam = $units->where('status_barang', 'Dipinjam')->count();
                $foto     = $j->foto_barang;
                // Singkatan nama barang diambil dari segmen ke-3 kode unit pertama (RUANG-LEMARI-SINGKAT-0001)
                $bagian   = explode('-', (string) $f?->kode_barcode);

                return [
                    'id'       => $j->id_jenis_barang,
                    'nama'     => $j->nama_jenis_barang,
                    'kat_id'   => $f?->id_kategori,
                    'kat_nama' => $f?->kategori?->nama_kategori ?? '-',
                    'foto'     => $foto ? asset($foto) : null,
                    'singkat'  => count($bagian) === 4 ? $bagian[2] : null,
                    'unit'     => $units->count(),
                    'tersedia' => $units->count() - $dipinjam,
                    'dipinjam' => $dipinjam,
                    'units'    => $units->map(fn ($u) => [
                        'id'     => $u->id_barang,
                        'kode'   => $u->kode_barcode,
                        'nama'   => $u->nama_barang,
                        'status' => $u->status_barang,
                    ])->values()->all(),
                ];
            })
            ->filter(fn ($g) => $g['unit'] > 0) // jenis tanpa unit tidak ditampilkan
            ->values()
            ->all();

        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.barang.index', [
            'grup'         => $grup,
            'kategori'     => $kategori,
            'ukuranStiker' => self::UKURAN,
            'daftarRuang'  => $this->nilaiEnum(self::COL_RUANG),
            'daftarLemari' => $this->nilaiEnum(self::COL_LEMARI),
        ]);
    }

    public function store(Request $request)
    {
        $kategoriBaru = $request->input('id_kategori') === self::OPSI_BARU;
        $baru         = self::OPSI_BARU;
        $regexKode    = 'regex:/^[A-Za-z0-9\- ]+$/';

        $data = $request->validate(
            [
                'nama_barang'        => ['required', 'string', 'max:100'],
                'id_jenis_barang'    => ['nullable', 'integer', 'exists:jenis_barang,id_jenis_barang'],
                'foto_barang'        => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

                'id_kategori'        => $kategoriBaru
                    ? ['required']
                    : ['required', 'integer', 'exists:kategori,id_kategori'],
                'kategori_baru'      => ['nullable', "required_if:id_kategori,{$baru}", 'string', 'max:50'],
                'kode_barang'        => ['nullable', 'string', 'max:6', 'regex:/^[A-Za-z0-9]+$/'],

                'ruang_pilih'        => ['required', 'string', 'max:20'],
                'ruang_baru'         => ['nullable', "required_if:ruang_pilih,{$baru}", 'string', 'max:20', $regexKode],
                'lemari_pilih'       => ['required', 'string', 'max:20'],
                'lemari_baru'        => ['nullable', "required_if:lemari_pilih,{$baru}", 'string', 'max:20', $regexKode],
            ],
            [
                'nama_barang.required'            => 'Nama barang wajib diisi.',
                'nama_barang.max'                 => 'Nama barang maksimal 100 karakter.',
                'id_jenis_barang.exists'          => 'Jenis barang yang dipilih tidak valid.',
                'foto_barang.image'               => 'File harus berupa gambar.',
                'foto_barang.mimes'               => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
                'foto_barang.max'                 => 'Ukuran foto maksimal 2 MB.',
                'id_kategori.required'            => 'Kategori wajib dipilih.',
                'id_kategori.exists'              => 'Kategori yang dipilih tidak valid.',
                'kategori_baru.required_if'       => 'Nama kategori baru wajib diisi.',
                'kategori_baru.max'               => 'Nama kategori maksimal 50 karakter.',
                'kode_barang.max'                 => 'Kode barang maksimal 6 karakter.',
                'kode_barang.regex'               => 'Kode barang hanya boleh huruf dan angka.',
                'ruang_pilih.required'            => 'Ruang wajib dipilih.',
                'ruang_baru.required_if'          => 'Ketik nama ruang baru.',
                'ruang_baru.regex'                => 'Ruang hanya boleh huruf, angka, spasi, atau tanda minus.',
                'lemari_pilih.required'           => 'Lemari wajib dipilih.',
                'lemari_baru.required_if'         => 'Ketik nama lemari baru.',
                'lemari_baru.regex'               => 'Lemari hanya boleh huruf, angka, spasi, atau tanda minus.',
            ]
        );

        // Ruang & lemari: dari dropdown, atau ketikan manual jika memilih "baru"
        $ruangInput  = $data['ruang_pilih']  === $baru ? ($data['ruang_baru']  ?? '') : $data['ruang_pilih'];
        $lemariInput = $data['lemari_pilih'] === $baru ? ($data['lemari_baru'] ?? '') : $data['lemari_pilih'];

        $ruang  = $this->bersihkanKode($ruangInput);
        $lemari = $this->bersihkanKode($lemariInput);
        if ($ruang === '' || $lemari === '') {
            return back()->withInput()->withErrors([
                'ruang_pilih' => 'Ruang dan lemari harus berisi huruf atau angka.',
            ]);
        }

        // Singkatan nama barang untuk kode barcode (mis. Komputer -> KMP).
        // Diisi otomatis di form dan boleh diubah; kalau kosong dihitung ulang di sini.
        $singkat = $this->bersihkanKode($data['kode_barang'] ?? '');
        if ($singkat === '') {
            $singkat = $this->singkatan($data['nama_barang']);
        }

        // Pastikan nilainya ada di ENUM. Kalau belum, ENUM di database ditambah otomatis.
        // (ALTER TABLE menyebabkan commit implisit, jadi dilakukan sebelum transaksi.)
        try {
            $ruangEnum  = $this->pastikanNilaiEnum(self::COL_RUANG, $ruang);
            $lemariEnum = $this->pastikanNilaiEnum(self::COL_LEMARI, $lemari);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors([
                'ruang_pilih' => 'Ruang/lemari baru gagal ditambahkan ke database. Cek hak akses ALTER pada user database.',
            ]);
        }

        // Foto (opsional) dipindah dulu; dihapus lagi jika ternyata tidak dipakai / gagal
        $pathBaru = null;
        $file     = $request->file('foto_barang');
        if ($file) {
            $namaFile = 'barang_' . time() . '_' . Str::random(8) . '.' . $file->extension();
            $file->move(public_path(self::FOLDER_FOTO), $namaFile);
            $pathBaru = self::FOLDER_FOTO . '/' . $namaFile;
        }

        $fotoDipakai = false;

        try {
            $barang = DB::transaction(function () use ($data, $pathBaru, $ruang, $lemari, $ruangEnum, $lemariEnum, $singkat, $kategoriBaru, &$fotoDipakai) {
                $jenis = null;

                if (! empty($data['id_jenis_barang'])) {
                    $jenis = Jenis_barang::find($data['id_jenis_barang']);
                    $nama  = $jenis ? $jenis->nama_jenis_barang : trim($data['nama_barang']);
                } else {
                    $nama  = trim($data['nama_barang']);
                    $jenis = Jenis_barang::where('nama_jenis_barang', $nama)->first();
                }

                if (! $jenis) {
                    $jenis = new Jenis_barang();
                    $jenis->nama_jenis_barang = $nama;
                    $jenis->save();
                }

                if ($pathBaru && empty($jenis->foto_barang)) {
                    $jenis->foto_barang = $pathBaru;
                    $jenis->save();
                    $fotoDipakai = true;
                }

                // Kategori: pilih yang ada, atau buat baru dari ketikan manual
                if ($kategoriBaru) {
                    $namaKat  = trim($data['kategori_baru']);
                    $kategori = Kategori::where('nama_kategori', $namaKat)->first();

                    if (! $kategori) {
                        $kategori = new Kategori();
                        $kategori->nama_kategori = $namaKat;

                        $kategori->save();
                    }
                } else {
                    $kategori = Kategori::findOrFail($data['id_kategori']);
                }

                $unit = new Barang();
                $unit->nama_barang     = $nama;
                $unit->id_kategori     = $kategori->id_kategori;
                $unit->id_jenis_barang = $jenis->getKey();
                $unit->{self::COL_RUANG}  = $ruangEnum;
                $unit->{self::COL_LEMARI} = $lemariEnum;
                $unit->kode_barcode    = $this->buatKodeBarcode($ruang, $lemari, $singkat);
                $unit->status_barang   = 'Tersedia';
                $unit->save();

                return $unit;
            });
        } catch (\Throwable $e) {
            if ($pathBaru) {
                File::delete(public_path($pathBaru));
            }
            throw $e;
        }

        if ($pathBaru && ! $fotoDipakai) {
            File::delete(public_path($pathBaru));
        }

        Activity_log::catat_aktivitas_admin(
            'tambah',
            'barang',
            $barang->id_barang,
            "Tambah barang {$barang->kode_barcode} ({$barang->nama_barang})"
        );

        return redirect()->route('barang.index')
            ->with('success', "Barang berhasil ditambahkan dengan kode {$barang->kode_barcode}.")
            ->with('cetak_url', route('barang.cetak', $barang->id_barang));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $barang->fill($data);

        if (! $barang->isDirty()) {
            return redirect()->route('barang.index')->with('info', 'Data tidak terubah');
        }

        $lama = $barang->getOriginal('nama_barang');
        $barang->save();

        Activity_log::catat_aktivitas_admin(
            'ubah',
            'barang',
            $barang->id_barang,
            "Ubah barang {$barang->kode_barcode}: '{$lama}' → '{$barang->nama_barang}'"
        );

        return redirect()->route('barang.index')->with('success', 'Data terupdate.');
    }

    /**
     * Ganti foto satu jenis barang. Blade mengirim id unit (ids[]);
     * dari situ dicari id_jenis_barang, lalu path disimpan di jenis_barang.foto_barang.
     * File fisik disimpan di public/uploads/barang.
     */
    public function updateFoto(Request $request)
    {
        $data = $request->validate([
            'ids'         => ['required', 'array', 'min:1'],
            'ids.*'       => ['integer', 'distinct', 'exists:barang,id_barang'],
            'foto_barang' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'ids.required'         => 'Jenis barang tidak valid.',
            'foto_barang.required' => 'Pilih foto terlebih dahulu.',
            'foto_barang.image'    => 'File harus berupa gambar.',
            'foto_barang.mimes'    => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto_barang.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        $unit = Barang::whereIn('id_barang', $data['ids'])->get();
        abort_if($unit->isEmpty(), 404);

        // Foto milik jenis barang, bukan milik unit
        $idJenis = $unit->pluck('id_jenis_barang')->filter()->unique()->values();

        if ($idJenis->isEmpty()) {
            return redirect()->route('barang.index')
                ->with('error', 'Unit ini belum terhubung ke jenis barang (id_jenis_barang kosong).');
        }

        $fotoLama = Jenis_barang::whereIn('id_jenis_barang', $idJenis)
            ->pluck('foto_barang')->filter()->unique()->values();

        $file = $request->file('foto_barang');
        $nama = 'barang_' . time() . '_' . Str::random(8) . '.' . $file->extension();
        $file->move(public_path(self::FOLDER_FOTO), $nama);
        $pathBaru = self::FOLDER_FOTO . '/' . $nama;

        try {
            $terubah = Jenis_barang::whereIn('id_jenis_barang', $idJenis)
                ->update(['foto_barang' => $pathBaru]);

            if ($terubah === 0) {
                throw new \RuntimeException('Tidak ada baris jenis_barang yang diperbarui.');
            }
        } catch (QueryException | \RuntimeException $e) {
            report($e); // tercatat di storage/logs/laravel.log

            // Gagal: hapus file baru, foto lama tetap aman
            File::delete(public_path($pathBaru));

            return redirect()->route('barang.index')
                ->with('error', 'Foto gagal disimpan. Coba lagi.');
        }

        // Berhasil: hapus file lama hanya jika tidak dipakai jenis lain
        foreach ($fotoLama as $path) {
            if (Str::startsWith($path, self::FOLDER_FOTO . '/')
                && ! Jenis_barang::where('foto_barang', $path)->exists()) {
                File::delete(public_path($path));
            }
        }

        $pertama = $unit->first();
        Activity_log::catat_aktivitas_admin(
            'ubah',
            'barang',
            $pertama->id_barang,
            "Ganti foto {$pertama->nama_barang} ({$unit->count()} unit)"
        );

        return redirect()->route('barang.index')->with('success', 'Foto berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->status_barang === 'Dipinjam') {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena sedang dipinjam');
        }

        if ($barang->peminjaman()->exists()) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena memiliki riwayat peminjaman');
        }

        Activity_log::catat_aktivitas_admin(
            'hapus',
            'barang',
            $barang->id_barang,
            "hapus barang {$barang->kode_barcode} ({$barang->nama_barang})"
        );

        try {
            $barang->delete();
        } catch (QueryException $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena masih terhubung dengan data lain');
        }

        // Foto milik jenis_barang, jadi file foto tidak ikut dihapus bersama unit.

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function cetak(Request $request, Barang $barang)
    {
        return view('admin.barang.cetak', compact('barang'));
    }

    public function catatCetak(Barang $barang)
    {
        Activity_log::catat_aktivitas_admin(
            'cetak_barcode',
            'barang',
            $barang->id_barang,
            "Cetak Barcode {$barang->kode_barcode} ({$barang->nama_barang})"
        );

        return response()->noContent();
    }

    public function cetakMassal(Request $request)
    {
        $kunci = $request->validate([
            'ukuran' => ['nullable', Rule::in(array_keys(self::UKURAN))],
        ])['ukuran'] ?? 'besar';

        $ukuran    = self::UKURAN[$kunci];
        $perLembar = $ukuran['kolom'] * $ukuran['baris'];
        $barang    = $this->ambilBarangTerpilih($request);

        return view('admin.barang.cetak-massal', [
            'lembar'    => $barang->chunk($perLembar),
            'jumlah'    => $barang->count(),
            'ids'       => $barang->pluck('id_barang')->values()->all(),
            'u'         => $ukuran,
            'kunci'     => $kunci,
            'pilihan'   => self::UKURAN,
            'perLembar' => $perLembar,
            'autoCetak' => ! $request->boolean('ubah'),
        ]);
    }

    public function catatCetakMassal(Request $request)
    {
        $barang  = $this->ambilBarangTerpilih($request);
        $waktu   = now();
        $idAdmin = Auth::id();
        $ip      = $request->ip();

        Activity_log::insert(
            $barang->map(fn ($b) => [
                'id_admin'   => $idAdmin,
                'aktor'      => 'admin',
                'aksi'       => 'cetak_barcode',
                'nama_tabel' => 'barang',
                'id_data'    => $b->id_barang,
                'deskripsi'  => mb_substr("Cetak Barcode {$b->kode_barcode} ({$b->nama_barang}) [massal]", 0, 255),
                'ip_address' => $ip,
                'created_at' => $waktu,
            ])->all()
        );

        return response()->noContent();
    }

    private function ambilBarangTerpilih(Request $request)
    {
        $data = $request->validate([
            'ids'   => ['required', 'array', 'min:1', 'max:' . self::MAKS_CETAK],
            'ids.*' => ['integer', 'min:1', 'distinct'],
        ], [
            'ids.required'  => 'Pilih minimal satu barang.',
            'ids.max'       => 'Maksimal ' . self::MAKS_CETAK . ' barang per sekali cetak.',
            'ids.*.integer' => 'Data barang tidak valid.',
        ]);

        $barang = Barang::whereIn('id_barang', $data['ids'])
            ->orderBy('id_barang')
            ->get(['id_barang', 'kode_barcode', 'nama_barang']);

        abort_if($barang->isEmpty(), 404);

        return $barang;
    }

    private function rules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:100'],
            'id_kategori' => ['required', 'integer', 'exists:kategori,id_kategori'],
        ];
    }

    private function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max'      => 'Nama barang maksimal 100 karakter.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori yang dipilih tidak valid.',
        ];
    }

    /**
     * Baca definisi kolom ENUM dari database.
     * @return array{0: string[], 1: array} [daftar nilai, info kolom]
     */
    private function infoEnum(string $kolom): array
    {
        $info = null;
        foreach (DB::select('SHOW COLUMNS FROM `barang`') as $row) {
            $row = array_change_key_case((array) $row, CASE_LOWER);
            if (($row['field'] ?? null) === $kolom) {
                $info = $row;
                break;
            }
        }

        if (! $info || ! preg_match('/^enum\((.*)\)$/is', $info['type'], $m)) {
            throw new \RuntimeException("Kolom {$kolom} di tabel barang bukan ENUM.");
        }

        preg_match_all("/'((?:[^']|'')*)'/", $m[1], $cocok);
        $nilai = array_map(fn ($v) => str_replace("''", "'", $v), $cocok[1]);

        return [$nilai, $info];
    }

    /** Daftar nilai ENUM untuk ditampilkan di dropdown (kosong jika gagal dibaca). */
    private function nilaiEnum(string $kolom): array
    {
        try {
            return $this->infoEnum($kolom)[0];
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    /**
     * Pastikan $nilai (sudah dibersihkan, mis. "PPLG1") ada di ENUM.
     * Kalau belum, tambahkan lewat ALTER TABLE. Mengembalikan nilai persis seperti yang
     * tersimpan di ENUM (mis. "PPLG-1" kalau di ENUM memang ditulis begitu).
     */
    private function pastikanNilaiEnum(string $kolom, string $nilai): string
    {
        [$daftar, $info] = $this->infoEnum($kolom);

        foreach ($daftar as $ada) {
            if ($this->bersihkanKode($ada) === $nilai) {
                return $ada;
            }
        }

        $daftar[] = $nilai;
        $pdo      = DB::getPdo();
        $semua    = implode(',', array_map(fn ($v) => $pdo->quote($v), $daftar));
        $boleh    = strtoupper($info['null'] ?? 'YES') === 'YES';

        $sql = "ALTER TABLE `barang` MODIFY `{$kolom}` ENUM({$semua})" . ($boleh ? ' NULL' : ' NOT NULL');
        if (array_key_exists('default', $info) && $info['default'] !== null) {
            $sql .= ' DEFAULT ' . $pdo->quote($info['default']);
        } elseif ($boleh) {
            $sql .= ' DEFAULT NULL';
        }

        DB::statement($sql);

        return $nilai;
    }

    /** "pplg 1" / "pplg-1" -> "PPLG1" */
    private function bersihkanKode(string $teks): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $teks));
    }

    /**
     * Singkatan dari nama barang: huruf pertama + konsonan berikutnya, maksimal 3 huruf.
     * Komputer -> KMP, Kabel HDMI -> KBL, Proyektor -> PRY
     * (harus sama dengan fungsi singkatan() di JavaScript pada view).
     */
    private function singkatan(string $nama): string
    {
        $huruf = strtoupper(preg_replace('/[^A-Za-z]/', '', $nama));
        if ($huruf === '') {
            return 'BRG';
        }

        $hasil = $huruf[0];
        for ($i = 1; $i < strlen($huruf) && strlen($hasil) < 3; $i++) {
            if (! in_array($huruf[$i], ['A', 'E', 'I', 'O', 'U'], true)) {
                $hasil .= $huruf[$i];
            }
        }

        // Konsonan kurang dari 3 (mis. "Aki"): pakai 3 huruf pertama
        return strlen($hasil) < 3 ? substr($huruf, 0, 3) : $hasil;
    }

    /** Format: RUANG-LEMARI-SINGKATAN-0001 (nomor urut per kombinasi prefix) */
    private function buatKodeBarcode(string $ruang, string $lemari, string $singkat): string
    {
        $prefix = "{$ruang}-{$lemari}-{$singkat}";

        $terakhir = Barang::lockForUpdate()
            ->where('kode_barcode', 'like', $prefix . '-%')
            ->orderByDesc('kode_barcode')
            ->value('kode_barcode');

        $nomor = $terakhir ? ((int) substr($terakhir, strlen($prefix) + 1)) + 1 : 1;

        return $prefix . '-' . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}