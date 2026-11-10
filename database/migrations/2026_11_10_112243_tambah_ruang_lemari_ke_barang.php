<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('ruang', 20)->nullable()->after('id_kategori');
            $table->string('lemari', 20)->nullable()->after('ruang');
        });

        // Kode lama "BRG-0001" cuma 8 karakter, yang baru bisa sampai ~25.
        // Kalau error "doctrine/dbal", jalankan: composer require doctrine/dbal
        // (Laravel 10 ke atas tidak butuh).
        Schema::table('barang', function (Blueprint $table) {
            $table->string('kode_barcode', 50)->change();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->string('kode_kategori', 5)->nullable()->after('nama_kategori');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['ruang', 'lemari']);
        });
        Schema::table('kategori', function (Blueprint $table) {
            $table->dropColumn('kode_kategori');
        });
    }
};