<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Activity_log;
use Illuminate\Database\QueryException;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private const MAKS_CETAK = 100;
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
        $barang = Barang::with('kategori')
            ->when($request->q, function ($query, $cari) {
                $query->where(function ($w) use ($cari) {
                    $w->where('nama_barang', 'like', "%{$cari}%")
                        ->orWhere('kode_barcode', 'like', "%{$cari}%");
                });
            })
            ->when($request->kategori, fn($query, $id) => $query->where('id_kategori', $id))
            ->orderByDesc('id_barang')
            ->paginate(10)
            ->withQueryString();

        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.barang.index', compact('barang', 'kategori') + ['ukuranStiker' => self::UKURAN]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $barang = DB::transaction(function () use ($data) {
            $data['kode_barcode'] = $this->buatKodeBarcode();
            $data['status_barang'] = 'Tersedia';

            return Barang::create($data);
        });

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
