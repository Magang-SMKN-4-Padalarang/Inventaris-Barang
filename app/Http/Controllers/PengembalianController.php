<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Activity_log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{

    public function index()
    {
        return view('peminjam.pengembalian');
    }

    /**
     * Pengembalian hanya diterima dengan kode struk (TRX-...), yang
     * ditandatangani HMAC. Barcode barang sengaja tidak diterima karena
     * tertempel di barang dan berurutan, jadi siapa pun bisa menebaknya
     * dan menandai barang "kembali" tanpa memegang struk.
     */
    private function cariPeminjaman(string $kode, bool $kunci = false): ?Peminjaman
    {
        $valid = Peminjaman::cariDariKode(strtoupper(trim($kode)));

        if (! $valid) {
            return null;
        }

        $q = Peminjaman::with(['barang', 'peminjam']);
        if ($kunci) {
            $q->lockForUpdate();
        }

        return $q->find($valid->id_peminjaman);
    }

    private function apakahSiswa(Peminjaman $pinjam): bool
    {
        return strtolower(trim($pinjam->peminjam->tipe_peminjam ?? '')) === 'siswa';
    }

    public function cek(Request $request): JsonResponse
    {
        $request->validate(['kode' => 'required|string|max:100']);

        $pinjam = $this->cariPeminjaman($request->kode);

        if (!$pinjam) {
            return response()->json(['ok' => false, 'pesan' => 'Struk tidak ditemukan.'], 404);
        }

        if ($pinjam->tgl_kembali !== null) {
            return response()->json(['ok' => false, 'pesan' => 'Barang pada struk ini sudah dikembalikan.'], 422);
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'kode'          => $request->kode,
                'nama'          => $pinjam->peminjam->nama_peminjam ?? '-',
                'tipe'          => $pinjam->peminjam->tipe_peminjam ?? '',  
                'kelas'         => $pinjam->peminjam->keterangan ?? '-',
                'barang'        => [$pinjam->barang->nama_barang ?? '-'],
                'batas_kembali' => $pinjam->tgl_jatuh_tempo->toIso8601String(),
            ],
        ]);
    }

    public function proses(Request $request): JsonResponse
    {
        $request->validate(['kode' => 'required|string|max:100']);

        return DB::transaction(function () use ($request) {
            $pinjam = $this->cariPeminjaman($request->kode, true);

            if (!$pinjam) {
                return response()->json(['ok' => false, 'pesan' => 'Struk tidak ditemukan.'], 404);
            }

            if ($pinjam->tgl_kembali !== null) {
                return response()->json(['ok' => false, 'pesan' => 'Barang sudah dikembalikan sebelumnya.'], 422);
            }

            $sekarang  = now();
            $batas     = $pinjam->tgl_jatuh_tempo;
            $terlambat = $sekarang->greaterThan($batas);

            $pinjam->update([
                'tgl_kembali'       => $sekarang,
                'status_peminjaman' => $terlambat ? 'Terlambat' : 'Selesai',
            ]);

            $pinjam->barang?->update(['status_barang' => 'Tersedia']);

            $menit = $terlambat ? (int) $batas->diffInMinutes($sekarang, true) : 0;

            Activity_log::catat_aktivitas_peminjam(
                'kembali',
                'peminjaman',
                $pinjam->id_peminjaman,
                "Kembali {$pinjam->barang?->kode_barcode} ({$pinjam->barang?->nama_barang}) oleh {$pinjam->peminjam?->nama_peminjam}, "
                    . ($terlambat ? "terlambat {$menit} menit" : 'tepat waktu')
            );

            return response()->json([
                'ok'              => true,
                'terlambat'       => $terlambat,
                'menit_terlambat' => $menit,
                'sanksi'          => ($terlambat && $this->apakahSiswa($pinjam)) ? 'Membersihkan lab' : null,
            ]);
        });
    }
}
