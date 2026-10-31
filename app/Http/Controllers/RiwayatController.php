<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Activity_log;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RiwayatController extends Controller
{
    private const PER_HALAMAN = 10;

    private const OPSI_STATUS = [
        'dipinjam'  => 'Dipinjam',
        'terlambat' => 'Terlambat',
        'selesai'   => 'Selesai',
    ];

    private const NAMA_BULAN = [
        1 => 'Januari', 
        2 => 'Februari', 
        3 => 'Maret', 
        4 => 'April',
        5 => 'Mei', 
        6 => 'Juni', 
        7 => 'Juli', 
        8 => 'Agustus',
        9 => 'September', 
        10 => 'Oktober', 
        11 => 'November', 
        12 => 'Desember',
    ];

    public function index(Request $request): View
    {
        [$cari, $status] = $this->bacaFilter($request);

        return view('admin.Riwayat', [
            'riwayat'    => $this->queryAdmin($cari, $status)->paginate(self::PER_HALAMAN)->withQueryString(),
            'cari'       => $cari,
            'status'     => $status,
            'opsiStatus' => self::OPSI_STATUS,
            'namaBulan'  => self::NAMA_BULAN,
        ]);
    }

    public function unduh(Request $request): View
    {
        $data = $request->validate([
            'periode' => ['required', 'in:mingguan,bulanan,tahunan'],
            'tanggal' => ['nullable', 'required_if:periode,mingguan', 'date'],
            'bulan'   => ['nullable', 'required_if:periode,bulanan', 'integer', 'between:1,12'],
            'tahun'   => ['nullable', 'required_if:periode,bulanan,tahunan', 'integer', 'between:2000,2100'],
        ]);

        [$cari, $status] = $this->bacaFilter($request);

        switch ($data['periode']) {
            case 'mingguan':
                $dasar = Carbon::parse($data['tanggal']);
                $awal  = $dasar->copy()->startOfWeek(Carbon::MONDAY);
                $akhir = $dasar->copy()->endOfWeek(Carbon::SUNDAY);
                $nama  = 'mingguan_' . $awal->format('Ymd') . '-' . $akhir->format('Ymd');
                $judul = 'Mingguan';
                break;

            case 'bulanan':
                $awal  = Carbon::create((int) $data['tahun'], (int) $data['bulan'], 1)->startOfMonth();
                $akhir = $awal->copy()->endOfMonth();
                $nama  = 'bulanan_' . $awal->format('Y-m');
                $judul = 'Bulanan (' . self::NAMA_BULAN[$awal->month] . ' ' . $awal->year . ')';
                break;

            default: // tahunan
                $awal  = Carbon::create((int) $data['tahun'], 1, 1)->startOfYear();
                $akhir = $awal->copy()->endOfYear();
                $nama  = 'tahunan_' . $awal->format('Y');
                $judul = 'Tahunan (' . $awal->year . ')';
        }

        $rows = $this->queryAdmin($cari, $status)
            ->whereBetween('tgl_pinjam', [$awal, $akhir])
            ->reorder('tgl_pinjam')
            ->get();

        $rows->each(fn(Peminjaman $r) => $r->setAttribute('status_label', $this->labelStatus($r)));

        $filter = ($cari !== '' || $status !== '') ? ', dengan filter' : '';

        Activity_log::catat_aktivitas_admin(
            'ekspor',
            'Peminjaman',
            null,
            "Laporan PDF riwayat peminjaman {$judul} ({$awal->format('d/m/Y')} - {$akhir->format('d/m/Y')}, {$rows->count()} data{$filter})"
        );

        return view('admin.Riwayat', [
            'laporan'   => true,
            'rows'      => $rows,
            'judul'     => $judul,
            'awal'      => $awal,
            'akhir'     => $akhir,
            'cari'      => $cari,
            'statusLbl' => $status !== '' ? self::OPSI_STATUS[$status] : '',
            'namaFile'  => "laporan_peminjaman_{$nama}",
            'ringkasan' => [
                'Dipinjam'  => $rows->where('status_label', 'Dipinjam')->count(),
                'Terlambat' => $rows->where('status_label', 'Terlambat')->count(),
                'Selesai'   => $rows->where('status_label', 'Selesai')->count(),
            ],
        ]);
    }

    public function peminjam(Request $request): View
    {
        $request->validate([
        'cari'     => ['nullable', 'string', 'max:100'],
        'kategori' => ['nullable', 'integer'],
    ]);

    $riwayat = Peminjaman::with(['barang', 'peminjam'])
        ->when($request->filled('cari'), function ($q) use ($request) {
            $q->whereHas('barang', fn ($b) => $b->where('nama_barang', 'like', '%' . $request->cari . '%'));
        })
        ->when($request->filled('kategori'), function ($q) use ($request) {
            $q->whereHas('barang', fn ($b) => $b->where('id_kategori', $request->kategori));
        })
        ->orderByDesc('tgl_pinjam')
        ->orderByDesc('id_peminjaman')
        ->paginate(self::PER_HALAMAN)
        ->withQueryString();   // supaya cari/kategori ikut terbawa saat pindah halaman

    $riwayat->getCollection()->each(function (Peminjaman $row) {
        $row->setAttribute(
            'nama_tampil',
            $this->samarkanNama($row->peminjam->nama_peminjam ?? '-')
        );
    });

    return view('peminjam.riwayat-peminjaman', [
        'riwayat'  => $riwayat,
        'kategori' => Kategori::orderBy('nama_kategori')->get(),
    ]);
    }

    private function bacaFilter(Request $request): array
    {
        $cari = mb_substr(trim($request->string('q')->toString()), 0, 100);

        $status = $request->string('status')->toString();
        if (! array_key_exists($status, self::OPSI_STATUS)) {
            $status = '';
        }

        return [$cari, $status];
    }

    private function labelStatus(Peminjaman $item): string
    {
        if ($item->tgl_kembali) {
            return 'Selesai';
        }
        if ($item->tgl_jatuh_tempo && $item->tgl_jatuh_tempo->isPast()) {
            return 'Terlambat';
        }
        return 'Dipinjam';
    }

    private function queryAdmin(string $cari, string $status): Builder
    {
        $query = Peminjaman::with(['barang', 'peminjam'])
            ->when($cari !== '', function (Builder $query) use ($cari) {
                $query->where(function (Builder $w) use ($cari) {
                    $w->whereHas('peminjam', fn(Builder $p) => $p->where('nama_peminjam', 'like', "%{$cari}%"))
                        ->orWhereHas('barang', function (Builder $b) use ($cari) {
                            $b->where('nama_barang', 'like', "%{$cari}%")
                                ->orWhere('kode_barcode', 'like', "%{$cari}%");
                        });
                });
            });

        return $this->filterStatus($query, $status)
            ->orderByDesc('tgl_pinjam');
    }

    private function ambilRiwayat(string $cari = '', string $status = ''): Collection
    {
        $query = Peminjaman::with(['barang', 'peminjam'])
            // Hanya cari nama barang. Nama peminjam sengaja tidak dicari
            // karena di halaman publik ini namanya disamarkan.
            ->when($cari !== '', fn(Builder $q) =>
                $q->whereHas('barang', fn(Builder $b) =>
                    $b->where('nama_barang', 'like', "%{$cari}%")));

        return $this->filterStatus($query, $status)
            ->orderByDesc('tgl_pinjam')
            ->get();
    }

    private function filterStatus(Builder $query, string $status): Builder
    {
        return $query
            ->when($status === 'selesai', fn(Builder $q) => $q->whereNotNull('tgl_kembali'))
            ->when($status === 'terlambat', fn(Builder $q) =>
                $q->whereNull('tgl_kembali')->where('tgl_jatuh_tempo', '<', now()))
            ->when($status === 'dipinjam', fn(Builder $q) =>
                $q->whereNull('tgl_kembali')
                    ->where(fn(Builder $w) => $w->whereNull('tgl_jatuh_tempo')
                        ->orWhere('tgl_jatuh_tempo', '>=', now())));
    }

    private function samarkanNama(string $nama): string
    {
        return collect(explode(' ', $nama))
            ->map(fn(string $kata) => mb_substr($kata, 0, 1) . str_repeat('*', max(mb_strlen($kata) - 1, 0)))
            ->implode(' ');
    }
}