<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // data satu form (dipakai form khusus guru / staff)
        Schema::table('peminjam', function (Blueprint $table) {
            $table->string('keperluan', 150)->nullable()->after('keterangan');
            $table->string('lama_peminjaman', 50)->nullable()->after('keperluan');
            $table->string('catatan', 500)->nullable()->after('lama_peminjaman');
        });

        // data per barang pada form
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->unsignedSmallInteger('jumlah')->default(1)->after('id_peminjam');
            $table->string('ket', 150)->nullable()->after('jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['jumlah', 'ket']);
        });

        Schema::table('peminjam', function (Blueprint $table) {
            $table->dropColumn(['keperluan', 'lama_peminjaman', 'catatan']);
        });
    }
};
