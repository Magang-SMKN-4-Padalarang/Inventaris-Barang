<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang      = Barang::count();
        $barangTersedia   = Barang::where('status_barang', 'Tersedia')->count();
        $barangBaik       = Barang::where('kondisi', 'Baik')->count();
        $barangRusak      = Barang::where('kondisi', 'Rusak')->count();
        $barangDiperbaiki = Barang::where('kondisi', 'Diperbaiki')->count();
        $sedangDipinjam = Peminjaman::whereNull('tgl_kembali')->count();
        $terlambat      = Peminjaman::whereNull('tgl_kembali')
                                    ->where('tgl_jatuh_tempo', '<', now())
                                    ->count();

        return view('admin/Dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'barangBaik',
            'barangRusak',
            'barangDiperbaiki',
            'sedangDipinjam',
            'terlambat'
        ));
    }
}