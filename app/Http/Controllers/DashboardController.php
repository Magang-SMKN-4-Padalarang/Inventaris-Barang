<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang    = Barang::count();
        $barangTersedia = Barang::where('status_barang', 'Tersedia')->count();
        // Status 'Terlambat' hanya diisi saat barang dikembalikan, jadi barang yang
        // lewat jatuh tempo tapi belum kembali harus dihitung dari tgl_jatuh_tempo.
        $sedangDipinjam = Peminjaman::whereNull('tgl_kembali')->count();
        $terlambat      = Peminjaman::whereNull('tgl_kembali')
                                    ->where('tgl_jatuh_tempo', '<', now())
                                    ->count();

        return view('admin/Dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'sedangDipinjam',
            'terlambat'
        ));
    }
}