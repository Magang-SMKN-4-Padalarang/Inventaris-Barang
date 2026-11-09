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

                return [
                    'id'       => $j->id_jenis_barang,
                    'nama'     => $j->nama_jenis_barang,
                    'kat_id'   => $f?->id_kategori,
                    'kat_nama' => $f?->kategori?->nama_kategori ?? '-',
                    'foto'     => $foto ? asset($foto) : null,
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
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            $this->rules() + [
                'id_jenis_barang' => ['nullable', 'integer', 'exists:jenis_barang,id_jenis_barang'],
                'foto_barang'     => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            $this->messages() + [
                'id_jenis_barang.exists' => 'Jenis barang yang dipilih tidak valid.',
                'foto_barang.image'      => 'File harus berupa gambar.',
                'foto_barang.mimes'      => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
                'foto_barang.max'        => 'Ukuran foto maksimal 2 MB.',
            ]
        );

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
            $barang = DB::transaction(function () use ($data, $pathBaru, &$fotoDipakai) {
                $jenis = null;

                if (! empty($data['id_jenis_barang'])) {
                    // Admin memilih jenis yang sudah ada: pakai ID itu, jangan buat jenis baru
                    $jenis = Jenis_barang::find($data['id_jenis_barang']);
                    $nama  = $jenis ? $jenis->nama_jenis_barang : trim($data['nama_barang']);
                } else {
                    $nama  = trim($data['nama_barang']);
                    // Jenis baru. Jika nama yang sama sudah ada, pakai yang itu agar tidak dobel.
                    $jenis = Jenis_barang::where('nama_jenis_barang', $nama)->first();
                }

                if (! $jenis) {
                    $jenis = new Jenis_barang();
                    $jenis->nama_jenis_barang = $nama;
                    $jenis->save();
                }

                // Foto milik jenis. Jika jenis sudah punya foto, foto yang diunggah diabaikan
                // (untuk mengganti foto gunakan Edit Foto).
                if ($pathBaru && empty($jenis->foto_barang)) {
                    $jenis->foto_barang = $pathBaru;
                    $jenis->save();
                    $fotoDipakai = true;
                }

                // Diisi satu per satu agar tidak bergantung pada $fillable model Barang
                $unit = new Barang();
                $unit->nama_barang     = $nama;
                $unit->id_kategori     = $data['id_kategori'];
                $unit->id_jenis_barang = $jenis->getKey();
                $unit->kode_barcode    = $this->buatKodeBarcode();
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

        // Foto diunggah tetapi tidak dipakai (jenis sudah punya foto): buang file-nya
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
        $barang = $this->ambilBarangTerpilih($request);

        return view('admin.barang.cetak-massal', [
            'lembar' => $barang->chunk($perLembar),
            'jumlah' => $barang->count(),
            'ids'    => $barang->pluck('id_barang')->values()->all(),
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
            'ids.required' => 'Pilih minimal satu barang.',
            'ids.max'      => 'Maksimal ' . self::MAKS_CETAK . ' barang per sekali cetak.',
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

    private function buatKodeBarcode(): string
    {
        $terakhir = Barang::lockForUpdate()
            ->orderByDesc('id_barang')
            ->value('kode_barcode');

        $nomor = $terakhir ? ((int) substr($terakhir, 4)) + 1 : 1;

        return 'BRG-' . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}