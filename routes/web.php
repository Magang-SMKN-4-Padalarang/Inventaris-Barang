<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LogController;

Route::get('/', [MenuController::class, 'index'])->name('menu');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process')
    ->middleware('throttle:5,1');

Route::get('/peminjam', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::get('/peminjam/pinjam', [PeminjamanController::class, 'formPinjam']);
Route::post('/peminjam/pinjam', [PeminjamanController::class, 'simpan'])->middleware('throttle:10,1');
Route::get('/peminjam/barang/{kode}', [PeminjamanController::class, 'cekBarang'])->middleware('throttle:60,1');
Route::get('/peminjam/struk/{kode}', [PeminjamanController::class, 'struk'])->middleware('throttle:30,1');

Route::get('/peminjam/kembali', [PengembalianController::class, 'index']);
Route::post('/peminjam/kembali/cek', [PengembalianController::class, 'cek'])->middleware('throttle:60,1');
Route::post('/peminjam/kembali/proses', [PengembalianController::class, 'proses'])->middleware('throttle:10,1');

Route::get('/peminjam/daftar-barang', [PeminjamanController::class, 'katalog'])
    ->name('peminjam.daftar-barang');

Route::redirect('/peminjam/katalog', '/peminjam/daftar-barang');

Route::get('/peminjam/riwayat', [RiwayatController::class, 'peminjam'])
    ->name('peminjaman.riwayat')
    ->middleware('throttle:60,1');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('barang/cetak-massal', [BarangController::class, 'cetakMassal'])
        ->name('barang.cetak-massal')
        ->middleware('throttle:30,1');

    Route::post('barang/cetak-massal/catat', [BarangController::class, 'catatCetakMassal'])
        ->name('barang.cetak-massal.catat')
        ->middleware('throttle:30,1');

    Route::get('barang/{barang}/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

    Route::post('barang/{barang}/cetak/catat', [BarangController::class, 'catatCetak'])
        ->name('barang.cetak.catat')
        ->middleware('throttle:30,1');

    Route::resource('barang', BarangController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/admin/riwayat', [RiwayatController::class, 'index'])
        ->name('admin.riwayat');

    Route::get('/admin/riwayat/unduh', [RiwayatController::class, 'unduh'])
        ->name('admin.riwayat.unduh')
        ->middleware('throttle:30,1');

    Route::get('/admin/log', [LogController::class, 'index'])
        ->name('admin.log')
        ->middleware('throttle:30,1');
});