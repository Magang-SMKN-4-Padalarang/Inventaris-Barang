<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    // pilihan dropdown
    private const TIPE_PEMINJAM = ['Siswa', 'Guru', 'Staff', 'Lainnya'];

    // lewat dari 24 jam, terlambat
    private const LAMA_PINJAM_JAM = 24;

    private const TIPE_FORM_KHUSUS = ['Guru', 'Staff'];
    private const MAKS_BARANG_FORM = 10;

    public function index()
    {
        return view('peminjam.dashboard-peminjam', [
            'totalBarang'    => Barang::count(),
            'barangTersedia' => Barang::where('status_barang', 'Tersedia')->count(),
            'sedangDipinjam' => Barang::where('status_barang', 'Dipinjam')->count(),
            'terlambat'      => Peminjaman::where('status_peminjaman', 'Dipinjam')
                                    ->where('tgl_jatuh_tempo', '<', now())->count(),
        ]);
    }

    public function formPinjam()
    {
        return view('peminjam.form-peminjaman', [
            'daftarTipe' => array_values(array_diff(self::TIPE_PEMINJAM, self::TIPE_FORM_KHUSUS)),
        ]);
    }

    public function formPinjamGuru(Request $request)
    {
        $tipe = $request->query('tipe');

        return view('peminjam.form-peminjaman-guru', [
            'daftarTipe' => self::TIPE_FORM_KHUSUS,
            'tipeAwal'   => in_array($tipe, self::TIPE_FORM_KHUSUS, true) ? $tipe : null,
            'maksBarang' => self::MAKS_BARANG_FORM,
        ]);
    }

    public function cekBarang(string $kode)
    {
        $barang = Barang::where('kode_barcode', trim($kode))->first();

        if (! $barang) {
            return response()->json(['ok' => false, 'pesan' => 'Barang tidak ditemukan. Periksa kembali barcode.'], 404);
        }

        if ($barang->status_barang !== 'Tersedia') {
            return response()->json(['ok' => false, 'pesan' => "{$barang->nama_barang} sedang dipinjam."], 422);
        }

        // Foto disimpan per jenis barang (dipakai bersama semua unit satu jenis)
        $foto = $barang->id_jenis_barang
            ? DB::table('jenis_barang')
                ->where('id_jenis_barang', $barang->id_jenis_barang)
                ->value('foto_barang')
            : null;

        return response()->json([
            'ok'   => true,
            'nama' => $barang->nama_barang,
            'foto' => $foto ? asset($foto) : null,
        ]);
    }

    // Detail barang + lokasi, dipakai fitur "Cek barang" di dashboard peminjam
    public function detailBarang(string $kode)
    {
        $barang = Barang::with('kategori')->where('kode_barcode', trim($kode))->first();

        if (! $barang) {
            return response()->json(['ok' => false, 'pesan' => 'Barang tidak ditemukan. Periksa kembali barcode.'], 404);
        }

        // Foto disimpan per jenis barang
        $foto = $barang->id_jenis_barang
            ? DB::table('jenis_barang')
                ->where('id_jenis_barang', $barang->id_jenis_barang)
                ->value('foto_barang')
            : null;

        // Lokasi: pakai kolom ruang/lemari jika ada, kalau kosong ambil dari kode barcode
        // (format PPLG1-LMR1-KMP-0001 -> ruang PPLG1, lemari LMR1)
        $bagian = explode('-', $barang->kode_barcode);
        $ruang  = $barang->ruang  ?? ($bagian[0] ?? null);
        $lemari = $barang->lemari ?? ($bagian[1] ?? null);

        // Kalau sedang dipinjam, tampilkan batas pengembalian
        $jatuhTempo = null;
        $terlambat  = false;

        if ($barang->status_barang === 'Dipinjam') {
            $tempo = Peminjaman::where('id_barang', $barang->id_barang)
                ->where('status_peminjaman', 'Dipinjam')
                ->latest('tgl_pinjam')
                ->value('tgl_jatuh_tempo');

            if ($tempo) {
                $tempo      = \Carbon\Carbon::parse($tempo);
                $jatuhTempo = $tempo->format('d-m-Y H:i');
                $terlambat  = $tempo->isPast();
            }
        }

        return response()->json([
            'ok'          => true,
            'kode'        => $barang->kode_barcode,
            'nama'        => $barang->nama_barang,
            'kategori'    => $barang->kategori->nama_kategori ?? null,
            'merk_tipe'   => trim((string) ($barang->merk_tipe ?? '')) ?: null,
            'status'      => $barang->status_barang,
            'ruang'       => $ruang,
            'lemari'      => $lemari,
            'foto'        => $foto ? asset($foto) : null,
            'jatuh_tempo' => $jatuhTempo,
            'terlambat'   => $terlambat,
        ]);
    }

    public function simpan(Request $request)
    {
        $tipe = $request->input('tipe_peminjam');

        $request->validate([
            'tipe_peminjam' => ['required', Rule::in(self::TIPE_PEMINJAM)],
            'kode_barcode'  => ['required', 'string', 'max:50'],
            'nama_peminjam' => ['required', 'string', 'max:100'],
            'kelas'         => [Rule::requiredIf($tipe === 'Siswa'), 'nullable', 'string', 'max:30'],
            'nip'           => ['nullable', 'string', 'max:30'],
            'bagian'        => [Rule::requiredIf($tipe === 'Staff'), 'nullable', 'string', 'max:100'],
            'no_hp'         => [Rule::requiredIf($tipe === 'Lainnya'), 'nullable', 'string', 'max:20'],
            'keperluan'     => [Rule::requiredIf($tipe === 'Lainnya'), 'nullable', 'string', 'max:150'],
        ], [
            'tipe_peminjam.required' => 'Pilih tipe peminjam.',
            'tipe_peminjam.in'       => 'Tipe peminjam tidak valid.',
            'kode_barcode.required'  => 'Scan barcode barang terlebih dahulu.',
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
            'bagian.required'        => 'Bagian atau jabatan wajib diisi.',
            'no_hp.required'         => 'Nomor HP wajib diisi.',
            'keperluan.required'     => 'Keperluan wajib diisi.',
        ]);

        $keterangan = match ($tipe) {
            'Siswa' => "Kelas {$request->kelas}",
            'Guru'  => $request->filled('nip') ? "NIP {$request->nip}" : null,
            'Staff' => $request->bagian,
            default => $request->keperluan,
        };
        $noHp = $tipe === 'Lainnya' ? $request->no_hp : null;

        $peminjaman = DB::transaction(function () use ($request, $tipe, $keterangan, $noHp) {
            $barang = Barang::where('kode_barcode', trim($request->kode_barcode))->lockForUpdate()->first();

            if (! $barang) {
                throw ValidationException::withMessages(['kode_barcode' => 'Barang tidak ditemukan.']);
            }

            if ($barang->status_barang !== 'Tersedia') {
                throw ValidationException::withMessages(['kode_barcode' => "{$barang->nama_barang} sedang dipinjam."]);
            }

            $peminjam = Peminjam::create([
                'nama_peminjam' => $request->nama_peminjam,
                'tipe_peminjam' => $tipe,
                'keterangan'    => $keterangan,
                'no_hp'         => $noHp,
            ]);

            $barang->update(['status_barang' => 'Dipinjam']);

            $sekarang = now();

            $peminjaman = Peminjaman::create([
                'id_barang'         => $barang->id_barang,
                'id_peminjam'       => $peminjam->id_peminjam,
                'tgl_pinjam'        => $sekarang,
                'tgl_jatuh_tempo'   => $sekarang->copy()->addHours(self::LAMA_PINJAM_JAM),
                'status_peminjaman' => 'Dipinjam',
            ]);

            Activity_log::catat_aktivitas_peminjam(
                'pinjam',
                'peminjaman',
                $peminjaman->id_peminjaman,
                "Pinjam {$barang->kode_barcode} ({$barang->nama_barang}) oleh {$peminjam->nama_peminjam} [{$tipe}], jatuh tempo {$peminjaman->tgl_jatuh_tempo->format('d-m-Y H:i')}"
            );

            return $peminjaman;
        });

        return redirect('/peminjam/struk/' . $peminjaman->kode_transaksi)
            ->with('berhasil', 'Data peminjaman berhasil disimpan.');
    }

    public function simpanGuru(Request $request)
    {
        $request->validate([
            'tipe_peminjam'   => ['required', Rule::in(self::TIPE_FORM_KHUSUS)],
            'nama_peminjam'   => ['required', 'string', 'max:100'],
            'keperluan'       => ['required', 'string', 'max:150'],
            'lama_peminjaman' => ['required', 'string', 'max:50'],
            'catatan'         => ['nullable', 'string', 'max:500'],
            'barang'          => ['required', 'array', 'min:1', 'max:' . self::MAKS_BARANG_FORM],
            'barang.*.kode'   => ['required', 'string', 'max:50', 'distinct:ignore_case'],
            'barang.*.jumlah' => ['required', 'integer', 'min:1', 'max:999'],
            'barang.*.ket'    => ['nullable', 'string', 'max:150'],
        ], [
            'tipe_peminjam.required'   => 'Pilih Guru atau Staff.',
            'tipe_peminjam.in'         => 'Tipe peminjam tidak valid.',
            'nama_peminjam.required'   => 'Nama peminjam wajib diisi.',
            'keperluan.required'       => 'Keperluan wajib diisi.',
            'lama_peminjaman.required' => 'Lama peminjaman wajib diisi.',
            'barang.required'          => 'Scan barcode minimal satu barang.',
            'barang.min'               => 'Scan barcode minimal satu barang.',
            'barang.max'               => 'Maksimal ' . self::MAKS_BARANG_FORM . ' barang sekali pinjam.',
            'barang.*.kode.required'   => 'Kode barang tidak boleh kosong.',
            'barang.*.kode.distinct'   => 'Ada barang yang di-scan lebih dari sekali.',
            'barang.*.jumlah.required' => 'Jumlah wajib diisi.',
            'barang.*.jumlah.integer'  => 'Jumlah harus berupa angka.',
            'barang.*.jumlah.min'      => 'Jumlah minimal 1.',
        ]);

        $tipe  = $request->input('tipe_peminjam');
        $baris = array_values($request->input('barang'));

        $pertama = DB::transaction(function () use ($request, $tipe, $baris) {
            // kunci barang berurutan agar dua form bersamaan tidak saling menunggu
            $kodeUrut     = collect($baris)->pluck('kode')->map(fn ($k) => trim($k))->sort()->values();
            $daftarBarang = collect();

            foreach ($kodeUrut as $kode) {
                $barang = Barang::where('kode_barcode', $kode)->lockForUpdate()->first();

                if (! $barang) {
                    throw ValidationException::withMessages(['barang' => "Barang dengan kode {$kode} tidak ditemukan."]);
                }
                if ($barang->status_barang !== 'Tersedia') {
                    throw ValidationException::withMessages(['barang' => "{$barang->nama_barang} sedang dipinjam."]);
                }

                $daftarBarang[$kode] = $barang;
            }

            $peminjam = Peminjam::create([
                'nama_peminjam'   => $request->nama_peminjam,
                'tipe_peminjam'   => $tipe,
                'keterangan'      => $request->keperluan,
                'keperluan'       => $request->keperluan,
                'lama_peminjaman' => $request->lama_peminjaman,
                'catatan'         => $request->input('catatan'),
            ]);

            $sekarang   = now();
            $jatuhTempo = $this->hitungJatuhTempo($request->lama_peminjaman, $sekarang);
            $pertama    = null;

            // simpan sesuai urutan scan (nomor di form)
            foreach ($baris as $item) {
                $barang = $daftarBarang[trim($item['kode'])];

                $barang->update(['status_barang' => 'Dipinjam']);

                $peminjaman = Peminjaman::create([
                    'id_barang'         => $barang->id_barang,
                    'id_peminjam'       => $peminjam->id_peminjam,
                    'jumlah'            => (int) $item['jumlah'],
                    'ket'               => $item['ket'] ?? null,
                    'tgl_pinjam'        => $sekarang,
                    'tgl_jatuh_tempo'   => $jatuhTempo,
                    'status_peminjaman' => 'Dipinjam',
                ]);

                $pertama ??= $peminjaman;

                Activity_log::catat_aktivitas_peminjam(
                    'pinjam',
                    'peminjaman',
                    $peminjaman->id_peminjaman,
                    "Pinjam {$barang->kode_barcode} ({$barang->nama_barang}) oleh {$peminjam->nama_peminjam} [{$tipe}], jatuh tempo {$jatuhTempo->format('d-m-Y H:i')}"
                );
            }

            return $pertama;
        });

        return redirect('/peminjam/struk/' . $pertama->kode_transaksi)
            ->with('berhasil', 'Data peminjaman berhasil disimpan.');
    }

    private function hitungJatuhTempo(string $lama, $mulai)
    {
        $batas = $mulai->copy();

        if (preg_match('/(\d{1,3})\s*(jam|hari|minggu|bulan)/i', $lama, $m)) {
            $n = (int) $m[1];

            if ($n > 0) {
                return match (strtolower($m[2])) {
                    'jam'    => $batas->addHours($n),
                    'hari'   => $batas->addDays($n),
                    'minggu' => $batas->addWeeks($n),
                    default  => $batas->addMonths($n),
                };
            }
        }

        return $batas->addHours(self::LAMA_PINJAM_JAM);
    }

    public function struk(Request $request, string $kode)
    {
        $peminjaman = Peminjaman::cariDariKode($kode);

        abort_if($peminjaman === null, 404);

        $peminjaman->load(['barang', 'peminjam']);

        if (in_array($peminjaman->peminjam->tipe_peminjam, self::TIPE_FORM_KHUSUS, true)) {
            $items = Peminjaman::with('barang')
                ->where('id_peminjam', $peminjaman->id_peminjam)
                ->orderBy('id_peminjaman')
                ->get();

            return view('peminjam.struk-guru', [
                'peminjaman' => $peminjaman,
                'items'      => $items,
                'kodeForm'   => $items->first()->kode_transaksi,
                'autoCetak'  => $request->boolean('cetak'), // dari tombol cetak ulang di riwayat admin
            ]);
        }

        return view('peminjam.struk', compact('peminjaman'));
    }

    public function katalog(Request $request)
    {
        $request->validate([
            'cari'     => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'integer'],
        ]);

        $barang = Barang::with('kategori')
            ->when($request->filled('cari'), fn ($q) => $q->where('nama_barang', 'like', '%' . $request->cari . '%'))
            ->when($request->filled('kategori'), fn ($q) => $q->where('id_kategori', $request->kategori))
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.daftar-barang', [
            'barang'   => $barang,
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }
}