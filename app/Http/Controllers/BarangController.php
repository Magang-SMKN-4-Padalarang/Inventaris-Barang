<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Jenis_barang;
use App\Models\Kategori;
use App\Models\Activity_log;
use App\Support\Kode;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private const MAKS_CETAK = 100;
    private const FOLDER_FOTO = 'uploads/barang';
    private const DIGIT_NOMOR = 3;

    private const COL_RUANG = 'ruangan';
    private const COL_LEMARI = 'lemari';

    private const MAKS_NAMA = 50;
    private const OPSI_BARU = '__baru__';

    private const KONDISI = [
        'Baik',
        'Rusak',
        'Diperbaiki',
        'Dipakai',
        'Dipinjam'
    ];

    private const KETERANGAN = [
        'Tersedia',
        'Digunakan'
    ];

    private const UKURAN = [
        'besar' => [
            'label' => 'Besar 60×35 mm (21/lembar)',
            'w' => 60, 'h' => 35, 'kolom' => 3, 'baris' => 7,
            'gap_x' => 5, 'gap_y' => 4,
            'judul_pt' => 7, 'nama_pt' => 9, 'aksen' => 1.5,
            'bc_w' => 54, 'bc_h' => 18, 'bc_font' => 14,
        ],
        'sedang' => [
            'label' => 'Sedang 45×25 mm (36/lembar)',
            'w' => 45, 'h' => 25, 'kolom' => 4, 'baris' => 9,
            'gap_x' => 3, 'gap_y' => 3,
            'judul_pt' => 5.5, 'nama_pt' => 7, 'aksen' => 1,
            'bc_w' => 41, 'bc_h' => 12, 'bc_font' => 16,
        ],
        'kecil' => [
            'label' => 'Kecil 35×18 mm (65/lembar)',
            'w' => 35, 'h' => 18, 'kolom' => 5, 'baris' => 13,
            'gap_x' => 2, 'gap_y' => 2,
            'judul_pt' => 0, 'nama_pt' => 6, 'aksen' => 0,
            'bc_w' => 32, 'bc_h' => 10, 'bc_font' => 18,
        ],
    ];

    public function index(Request $request)
    {
        $jenis = Jenis_barang::orderBy('nama_jenis_barang')->get();

        $unitPerJenis = Barang::with('kategori')
            ->orderBy('kode_barcode')
            ->get()
            ->groupBy('id_jenis_barang');

        $grup = $jenis->map(function ($j) use ($unitPerJenis) {
            $units = $unitPerJenis->get(
                $j->id_jenis_barang,
                collect()
            );

            $f = $units->first();

            $dipinjam = $units->where(
                'status_barang',
                'Dipinjam'
            )->count();

            $foto = $j->foto_barang;

            $bagian = explode(
                '-',
                (string) $f?->kode_barcode
            );

            return [
                'id' => $j->id_jenis_barang,
                'nama' => $j->nama_jenis_barang,
                'kat_id' => $f?->id_kategori,
                'kat_nama' => $f?->kategori?->nama_kategori ?? '-',

                'merk_tipe' => $units
                    ->pluck('merk_tipe')
                    ->filter()
                    ->first() ?? '',

                'foto' => $foto ? asset($foto) : null,

                'singkat' => count($bagian) === 4
                    ? $bagian[2]
                    : null,

                'unit' => $units->count(),

                'tersedia' => $units->count() - $dipinjam,

                'dipinjam' => $dipinjam,

                'units' => $units->map(function ($u) {
                    return [
                        'id' => $u->id_barang,
                        'kode' => $u->kode_barcode,
                        'nama' => $u->nama_barang,
                        'merk_tipe' => $u->merk_tipe ?? '',
                        'status' => $u->status_barang,
                        'kondisi' => $u->kondisi ?: 'Baik',
                        'keterangan' => $u->keterangan ?: 'Tersedia',
                        'terkunci' => (bool) $u->terkunci,
                    ];
                })->values()->all(),
            ];
        })
        ->filter(fn ($g) => $g['unit'] > 0)
        ->values()
        ->all();

        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.barang.index', [
            'grup' => $grup,
            'kategori' => $kategori,
            'ukuranStiker' => self::UKURAN,
            'daftarRuang' => $this->nilaiEnum(self::COL_RUANG),
            'daftarLemari' => $this->nilaiEnum(self::COL_LEMARI),
        ]);
    }

    public function store(Request $request)
    {
        $baru = self::OPSI_BARU;
        $regexNama = 'regex:/^[A-Za-z0-9\- ]+$/';
        $maks = self::MAKS_NAMA;

        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:100'],
            'id_jenis_barang' => [
                'nullable',
                'integer',
                'exists:jenis_barang,id_jenis_barang'
            ],
            'foto_barang' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
            'id_kategori' => [
                'required',
                'integer',
                'exists:kategori,id_kategori'
            ],
            'merk_tipe' => ['nullable', 'string', 'max:100'],
            'kondisi' => ['required', Rule::in(self::KONDISI)],
            'kode_barang' => [
                'nullable',
                'string',
                'max:6',
                'regex:/^[A-Za-z0-9]+$/'
            ],
            'ruang_pilih' => ['required', 'string', "max:{$maks}"],
            'ruang_baru' => [
                'nullable',
                "required_if:ruang_pilih,{$baru}",
                'string',
                "max:{$maks}",
                $regexNama
            ],
            'lemari_pilih' => ['required', 'string', "max:{$maks}"],
            'lemari_baru' => [
                'nullable',
                "required_if:lemari_pilih,{$baru}",
                'string',
                "max:{$maks}",
                $regexNama
            ],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 100 karakter.',
            'id_jenis_barang.exists' => 'Jenis barang tidak valid.',
            'foto_barang.image' => 'File harus berupa gambar.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto_barang.max' => 'Ukuran foto maksimal 2 MB.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists' => 'Kategori tidak valid.',
            'merk_tipe.max' => 'Merk/Type maksimal 100 karakter.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
            'kondisi.in' => 'Kondisi barang tidak valid.',
            'kode_barang.max' => 'Kode barang maksimal 6 karakter.',
            'kode_barang.regex' => 'Kode barang hanya boleh huruf dan angka.',
            'ruang_pilih.required' => 'Ruang wajib dipilih.',
            'ruang_baru.required_if' => 'Ketik nama ruang baru.',
            'ruang_baru.regex' => 'Nama ruang tidak valid.',
            'lemari_pilih.required' => 'Tempat wajib dipilih.',
            'lemari_baru.required_if' => 'Ketik nama tempat baru.',
            'lemari_baru.regex' => 'Nama tempat tidak valid.',
        ]);

        $ruangInput = $data['ruang_pilih'] === $baru
            ? ($data['ruang_baru'] ?? '')
            : $data['ruang_pilih'];

        $lemariInput = $data['lemari_pilih'] === $baru
            ? ($data['lemari_baru'] ?? '')
            : $data['lemari_pilih'];

        $ruangNama = Kode::rapikan($ruangInput);
        $lemariNama = Kode::rapikan($lemariInput);

        if ($ruangNama === '' || $lemariNama === '') {
            return back()->withInput()->withErrors([
                'ruang_pilih' => 'Ruang dan tempat harus berisi huruf atau angka.',
            ]);
        }

        $singkat = $this->bersihkanKode(
            $data['kode_barang'] ?? ''
        );

        if ($singkat === '') {
            $singkat = $this->singkatan($data['nama_barang']);
        }

        $merkTipe = trim((string) ($data['merk_tipe'] ?? ''));
        $merkTipe = $merkTipe === '' ? null : $merkTipe;

        try {
            $ruangEnum = $this->pastikanNilaiEnum(
                self::COL_RUANG,
                $ruangNama
            );

            $lemariEnum = $this->pastikanNilaiEnum(
                self::COL_LEMARI,
                $lemariNama
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors([
                'ruang_pilih' => 'Ruang/tempat gagal ditambahkan ke database.',
            ]);
        }

        $ruangKode = $this->kodeEnum(
            self::COL_RUANG,
            $ruangEnum
        );

        $lemariKode = $this->kodeEnum(
            self::COL_LEMARI,
            $lemariEnum
        );

        $pathBaru = null;
        $file = $request->file('foto_barang');

        if ($file) {
            $namaFile = 'barang_' . time() . '_' .
                Str::random(8) . '.' . $file->extension();

            $file->move(
                public_path(self::FOLDER_FOTO),
                $namaFile
            );

            $pathBaru = self::FOLDER_FOTO . '/' . $namaFile;
        }

        $fotoDipakai = false;

        try {
            $barang = DB::transaction(function () use (
                $data,
                $pathBaru,
                $ruangKode,
                $lemariKode,
                $ruangEnum,
                $lemariEnum,
                $singkat,
                $merkTipe,
                &$fotoDipakai
            ) {
                $jenis = null;

                if (!empty($data['id_jenis_barang'])) {
                    $jenis = Jenis_barang::find(
                        $data['id_jenis_barang']
                    );

                    $nama = $jenis
                        ? $jenis->nama_jenis_barang
                        : trim($data['nama_barang']);
                } else {
                    $nama = trim($data['nama_barang']);

                    $jenis = Jenis_barang::where(
                        'nama_jenis_barang',
                        $nama
                    )->first();
                }

                if (!$jenis) {
                    $jenis = new Jenis_barang();
                    $jenis->nama_jenis_barang = $nama;
                    $jenis->save();
                }

                if ($pathBaru && empty($jenis->foto_barang)) {
                    $jenis->foto_barang = $pathBaru;
                    $jenis->save();
                    $fotoDipakai = true;
                }

                $kategori = Kategori::findOrFail(
                    $data['id_kategori']
                );

                $unit = new Barang();

                $unit->nama_barang = $nama;
                $unit->id_kategori = $kategori->id_kategori;
                $unit->id_jenis_barang = $jenis->getKey();
                $unit->merk_tipe = $merkTipe;
                $unit->kondisi = $data['kondisi'];

                $unit->{self::COL_RUANG} = $ruangEnum;
                $unit->{self::COL_LEMARI} = $lemariEnum;

                $unit->kode_barcode = $this->buatKodeBarcode(
                    $ruangKode,
                    $lemariKode,
                    $singkat
                );

                $unit->status_barang = 'Tersedia';
                $unit->keterangan = 'Tersedia';
                $unit->terkunci = false;

                $unit->save();

                return $unit;
            });
        } catch (\Throwable $e) {
            if ($pathBaru) {
                File::delete(public_path($pathBaru));
            }

            throw $e;
        }

        if ($pathBaru && !$fotoDipakai) {
            File::delete(public_path($pathBaru));
        }

        Activity_log::catat_aktivitas_admin(
            'tambah',
            'barang',
            $barang->id_barang,
            "Tambah barang {$barang->kode_barcode} ({$barang->nama_barang})"
        );

        return redirect()->route('barang.index')
            ->with(
                'success',
                "Barang berhasil ditambahkan dengan kode {$barang->kode_barcode}."
            )
            ->with(
                'cetak_url',
                route('barang.cetak', $barang->id_barang)
            );
    }

    public function tambahTempat(Request $request)
    {
        $maks = self::MAKS_NAMA;

        $data = $request->validate([
            'jenis' => ['required', Rule::in(['ruang', 'lemari'])],
            'nilai' => [
                'required',
                'string',
                "max:{$maks}",
                'regex:/^[A-Za-z0-9\- ]+$/'
            ],
        ]);

        $ruang = $data['jenis'] === 'ruang';
        $kolom = $ruang ? self::COL_RUANG : self::COL_LEMARI;
        $label = $ruang ? 'Ruang' : 'Tempat';

        $nilai = Kode::rapikan($data['nilai']);

        if ($nilai === '') {
            return redirect()->route('barang.index')
                ->with('error', "{$label} harus berisi huruf atau angka.");
        }

        try {
            [$daftar] = $this->infoEnum($kolom);

            $kunciBaru = $this->kodeEnum($kolom, $nilai);

            foreach ($daftar as $ada) {
                if ($this->kodeEnum($kolom, $ada) === $kunciBaru) {
                    return redirect()->route('barang.index')
                        ->with('info', "{$label} {$ada} sudah ada.");
                }
            }

            $tersimpan = $this->pastikanNilaiEnum(
                $kolom,
                $nilai
            );
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('barang.index')
                ->with('error', "{$label} gagal ditambahkan.");
        }

        return redirect()->route('barang.index')
            ->with('success', "{$label} {$tersimpan} berhasil ditambahkan.");
    }

    public function hapusTempat(Request $request)
    {
        $data = $request->validate([
            'jenis' => ['required', Rule::in(['ruang', 'lemari'])],
            'nilai' => ['required', 'string', 'max:' . self::MAKS_NAMA],
        ]);

        $kolom = $data['jenis'] === 'ruang'
            ? self::COL_RUANG
            : self::COL_LEMARI;

        $label = $data['jenis'] === 'ruang'
            ? 'Ruang'
            : 'Tempat';

        $nilai = $data['nilai'];

        try {
            [$daftar, $info] = $this->infoEnum($kolom);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('barang.index')
                ->with('error', "Daftar {$label} gagal dibaca.");
        }

        if (!in_array($nilai, $daftar, true)) {
            return redirect()->route('barang.index')
                ->with('error', "{$label} {$nilai} tidak ditemukan.");
        }

        $dipakai = Barang::where($kolom, $nilai)->count();

        if ($dipakai > 0) {
            return redirect()->route('barang.index')
                ->with(
                    'error',
                    "{$label} {$nilai} masih digunakan oleh {$dipakai} unit barang."
                );
        }

        $sisa = array_values(array_diff($daftar, [$nilai]));

        if (!$sisa) {
            return redirect()->route('barang.index')
                ->with('error', "Minimal harus ada satu {$label}.");
        }

        try {
            $this->tulisEnum($kolom, $sisa, $info);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('barang.index')
                ->with('error', "{$label} gagal dihapus.");
        }

        return redirect()->route('barang.index')
            ->with('success', "{$label} {$nilai} berhasil dihapus.");
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate(
            $this->rules(),
            $this->messages()
        );

        $merkTipe = trim((string) ($data['merk_tipe'] ?? ''));

        $data['merk_tipe'] = $merkTipe === ''
            ? null
            : $merkTipe;

        $lama = $barang->nama_barang;

        $barang->forceFill($data);

        if (!$barang->isDirty()) {
            return redirect()->route('barang.index')
                ->with('info', 'Tidak ada perubahan data.');
        }

        $kategoriBerubah = $barang->isDirty('id_kategori');

        DB::transaction(function () use (
            $barang,
            $kategoriBerubah,
            $data
        ) {
            $barang->save();

            if ($kategoriBerubah && $barang->id_jenis_barang) {
                Barang::where(
                    'id_jenis_barang',
                    $barang->id_jenis_barang
                )->update([
                    'id_kategori' => $data['id_kategori']
                ]);
            }
        });

        Activity_log::catat_aktivitas_admin(
            'ubah',
            'barang',
            $barang->id_barang,
            "Ubah barang {$barang->kode_barcode}: '{$lama}' menjadi '{$barang->nama_barang}' (keterangan: {$barang->keterangan})"
        );

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function keterangan(Request $request, $id)
    {
        $data = $request->validate([
            'keterangan' => [
                'required',
                Rule::in(self::KETERANGAN)
            ],
        ], [
            'keterangan.required' => 'Keterangan wajib dipilih.',
            'keterangan.in' => 'Keterangan tidak valid.',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->keterangan = $data['keterangan'];
        $barang->save();

        Activity_log::catat_aktivitas_admin(
            'ubah',
            'barang',
            $barang->id_barang,
            "Keterangan barang {$barang->kode_barcode} diubah menjadi {$barang->keterangan}"
        );

        return back()->with(
            'success',
            "Keterangan barang {$barang->kode_barcode} berhasil diubah menjadi {$barang->keterangan}."
        );
    }

    public function updateFoto(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'integer',
                'distinct',
                'exists:barang,id_barang'
            ],
            'foto_barang' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ], [
            'ids.required' => 'Jenis barang tidak valid.',
            'foto_barang.required' => 'Pilih foto terlebih dahulu.',
            'foto_barang.image' => 'File harus berupa gambar.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto_barang.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $unit = Barang::whereIn(
            'id_barang',
            $data['ids']
        )->get();

        abort_if($unit->isEmpty(), 404);

        $idJenis = $unit->pluck('id_jenis_barang')
            ->filter()
            ->unique()
            ->values();

        if ($idJenis->isEmpty()) {
            return redirect()->route('barang.index')
                ->with('error', 'Unit belum terhubung ke jenis barang.');
        }

        $fotoLama = Jenis_barang::whereIn(
            'id_jenis_barang',
            $idJenis
        )->pluck('foto_barang')
            ->filter()
            ->unique()
            ->values();

        $file = $request->file('foto_barang');

        $nama = 'barang_' . time() . '_' .
            Str::random(8) . '.' . $file->extension();

        $file->move(
            public_path(self::FOLDER_FOTO),
            $nama
        );

        $pathBaru = self::FOLDER_FOTO . '/' . $nama;

        try {
            $terubah = Jenis_barang::whereIn(
                'id_jenis_barang',
                $idJenis
            )->update([
                'foto_barang' => $pathBaru
            ]);

            if ($terubah === 0) {
                throw new \RuntimeException(
                    'Tidak ada foto yang diperbarui.'
                );
            }
        } catch (\Throwable $e) {
            report($e);
            File::delete(public_path($pathBaru));

            return redirect()->route('barang.index')
                ->with('error', 'Foto gagal disimpan.');
        }

        foreach ($fotoLama as $path) {
            if (
                Str::startsWith($path, self::FOLDER_FOTO . '/') &&
                !Jenis_barang::where('foto_barang', $path)->exists()
            ) {
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

        return redirect()->route('barang.index')
            ->with('success', 'Foto berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->status_barang === 'Dipinjam') {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena sedang dipinjam.');
        }

        if ($barang->peminjaman()->exists()) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang memiliki riwayat peminjaman.');
        }

        Activity_log::catat_aktivitas_admin(
            'hapus',
            'barang',
            $barang->id_barang,
            "Hapus barang {$barang->kode_barcode} ({$barang->nama_barang})"
        );

        try {
            $barang->delete();
        } catch (QueryException $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang masih terhubung dengan data lain.');
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function kunci(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'terkunci' => ['required', 'boolean'],
        ]);

        $kunci = (bool) $data['terkunci'];

        if (
            (bool) $barang->terkunci === $kunci &&
            $barang->keterangan === ($kunci ? 'Digunakan' : 'Tersedia')
        ) {
            return redirect()->route('barang.index')
                ->with('info', 'Status barang sudah sesuai.');
        }

        DB::transaction(function () use ($barang, $kunci) {
            $barang->terkunci = $kunci;
            $barang->keterangan = $kunci
                ? 'Digunakan'
                : 'Tersedia';

            $barang->save();
        });

        Activity_log::catat_aktivitas_admin(
            'ubah',
            'barang',
            $barang->id_barang,
            ($kunci ? 'Kunci' : 'Buka gembok') .
            " barang {$barang->kode_barcode}; keterangan {$barang->keterangan}"
        );

        return redirect()->route('barang.index')->with(
            'success',
            $kunci
                ? "Barang {$barang->kode_barcode} dikunci dan keterangannya menjadi Digunakan."
                : "Gembok barang {$barang->kode_barcode} dibuka dan keterangannya menjadi Tersedia."
        );
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
            'ukuran' => [
                'nullable',
                Rule::in(array_keys(self::UKURAN))
            ],
        ])['ukuran'] ?? 'besar';

        $ukuran = self::UKURAN[$kunci];
        $perLembar = $ukuran['kolom'] * $ukuran['baris'];

        $barang = $this->ambilBarangTerpilih($request);

        return view('admin.barang.cetak-massal', [
            'lembar' => $barang->chunk($perLembar),
            'jumlah' => $barang->count(),
            'ids' => $barang->pluck('id_barang')->values()->all(),
            'u' => $ukuran,
            'kunci' => $kunci,
            'pilihan' => self::UKURAN,
            'perLembar' => $perLembar,
            'autoCetak' => !$request->boolean('ubah'),
        ]);
    }

    public function catatCetakMassal(Request $request)
    {
        $barang = $this->ambilBarangTerpilih($request);

        $waktu = now();
        $idAdmin = Auth::id();
        $ip = $request->ip();

        Activity_log::insert(
            $barang->map(fn ($b) => [
                'id_admin' => $idAdmin,
                'aktor' => 'admin',
                'aksi' => 'cetak_barcode',
                'nama_tabel' => 'barang',
                'id_data' => $b->id_barang,
                'deskripsi' => mb_substr(
                    "Cetak Barcode {$b->kode_barcode} ({$b->nama_barang}) [massal]",
                    0,
                    255
                ),
                'ip_address' => $ip,
                'created_at' => $waktu,
            ])->all()
        );

        return response()->noContent();
    }

    private function ambilBarangTerpilih(Request $request)
    {
        $data = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
                'max:' . self::MAKS_CETAK
            ],
            'ids.*' => ['integer', 'min:1', 'distinct'],
        ], [
            'ids.required' => 'Pilih minimal satu barang.',
            'ids.max' => 'Maksimal ' . self::MAKS_CETAK . ' barang per cetak.',
            'ids.*.integer' => 'Data barang tidak valid.',
        ]);

        $barang = Barang::whereIn('id_barang', $data['ids'])
            ->orderBy('id_barang')
            ->get([
                'id_barang',
                'kode_barcode',
                'nama_barang'
            ]);

        abort_if($barang->isEmpty(), 404);

        return $barang;
    }

    private function rules(): array
    {
        return [
            'nama_barang' => [
                'required',
                'string',
                'max:100'
            ],
            'id_kategori' => [
                'required',
                'integer',
                'exists:kategori,id_kategori'
            ],
            'merk_tipe' => [
                'nullable',
                'string',
                'max:100'
            ],
            'kondisi' => [
                'required',
                Rule::in(self::KONDISI)
            ],

            'keterangan' => [
                'required',
                Rule::in(self::KETERANGAN)
            ],
        ];
    }

    private function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 100 karakter.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists' => 'Kategori tidak valid.',
            'merk_tipe.max' => 'Merk/Type maksimal 100 karakter.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
            'kondisi.in' => 'Kondisi barang tidak valid.',
            'keterangan.required' => 'Keterangan barang wajib dipilih.',
            'keterangan.in' => 'Keterangan barang tidak valid.',
        ];
    }

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

        if (
            !$info ||
            !preg_match('/^enum\((.*)\)$/is', $info['type'], $m)
        ) {
            throw new \RuntimeException(
                "Kolom {$kolom} bukan ENUM."
            );
        }

        preg_match_all(
            "/'((?:[^']|'')*)'/",
            $m[1],
            $cocok
        );

        $nilai = array_map(
            fn ($v) => str_replace("''", "'", $v),
            $cocok[1]
        );

        return [$nilai, $info];
    }

    private function nilaiEnum(string $kolom): array
    {
        try {
            return $this->infoEnum($kolom)[0];
        } catch (\Throwable $e) {
            report($e);
            return [];
        }
    }

    private function tulisEnum(
        string $kolom,
        array $daftar,
        array $info
    ): void {
        $pdo = DB::getPdo();

        $semua = implode(
            ',',
            array_map(fn ($v) => $pdo->quote($v), $daftar)
        );

        $boleh = strtoupper($info['null'] ?? 'YES') === 'YES';

        $sql = "ALTER TABLE `barang` MODIFY `{$kolom}` ENUM({$semua})" .
            ($boleh ? ' NULL' : ' NOT NULL');

        $default = $info['default'] ?? null;

        if ($default !== null && in_array($default, $daftar, true)) {
            $sql .= ' DEFAULT ' . $pdo->quote($default);
        } elseif ($boleh) {
            $sql .= ' DEFAULT NULL';
        }

        DB::statement($sql);
    }

    private function kodeEnum(
        string $kolom,
        string $nilai
    ): string {
        $label = $kolom === self::COL_RUANG
            ? Kode::labelRuang($nilai)
            : Kode::labelTempat($nilai);

        return Kode::singkat($label);
    }

    private function pastikanNilaiEnum(
        string $kolom,
        string $nilai
    ): string {
        [$daftar, $info] = $this->infoEnum($kolom);

        $kunci = $this->kodeEnum($kolom, $nilai);

        foreach ($daftar as $ada) {
            if (
                $ada === $nilai ||
                $this->kodeEnum($kolom, $ada) === $kunci
            ) {
                return $ada;
            }
        }

        $daftar[] = $nilai;

        $this->tulisEnum($kolom, $daftar, $info);

        return $nilai;
    }

    private function bersihkanKode(string $teks): string
    {
        return strtoupper(
            preg_replace('/[^A-Za-z0-9]/', '', $teks)
        );
    }

    private function singkatan(string $nama): string
    {
        $huruf = strtoupper(
            preg_replace('/[^A-Za-z]/', '', $nama)
        );

        if ($huruf === '') {
            return 'BRG';
        }

        $hasil = $huruf[0];

        for (
            $i = 1;
            $i < strlen($huruf) && strlen($hasil) < 3;
            $i++
        ) {
            if (!in_array($huruf[$i], ['A', 'E', 'I', 'O', 'U'], true)) {
                $hasil .= $huruf[$i];
            }
        }

        return strlen($hasil) < 3
            ? substr($huruf, 0, 3)
            : $hasil;
    }

    private function buatKodeBarcode(
        string $ruang,
        string $lemari,
        string $singkat
    ): string {
        $prefix = "{$ruang}-{$lemari}-{$singkat}";

        $terakhir = Barang::lockForUpdate()
            ->where('kode_barcode', 'like', $prefix . '-%')
            ->selectRaw(
                "MAX(CAST(SUBSTRING_INDEX(kode_barcode, '-', -1) AS UNSIGNED)) AS maks"
            )
            ->value('maks');

        $nomor = ((int) $terakhir) + 1;

        return $prefix . '-' . str_pad(
            (string) $nomor,
            self::DIGIT_NOMOR,
            '0',
            STR_PAD_LEFT
        );
    }
}
