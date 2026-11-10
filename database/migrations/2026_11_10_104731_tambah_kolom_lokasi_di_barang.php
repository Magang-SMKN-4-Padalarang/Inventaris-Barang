<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('ruangan', 20)->nullable()->after('id_jenis_barang');
            $table->string('lemari', 20)->nullable()->after('ruangan');
            $table->string('singkatan', 10)->nullable()->after('lemari');
            $table->unsignedInteger('nomor_urut')->nullable()->after('singkatan');

            $table->index(['ruangan', 'lemari', 'singkatan', 'nomor_urut'], 'barang_lokasi_nomor_idx');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropIndex('barang_lokasi_nomor_idx');
            $table->dropColumn(['ruangan', 'lemari', 'singkatan', 'nomor_urut']);
        });
    }
};
