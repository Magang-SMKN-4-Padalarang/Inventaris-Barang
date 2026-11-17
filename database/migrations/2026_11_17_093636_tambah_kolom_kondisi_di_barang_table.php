<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Simpan di database/migrations/ lalu jalankan: php artisan migrate
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            if (! Schema::hasColumn('barang', 'kondisi')) {
                // string (bukan enum) supaya nilai kondisi mudah ditambah di kemudian hari
                $table->string('kondisi', 20)->default('Baik');
            }
        });

        // Kalau kolom kondisi sudah ada tetapi bertipe ENUM lama (Baik/Rusak/Diperbaiki),
        // ubah jadi string agar "Dipakai" dan "Dipinjam" bisa disimpan.
        if (Schema::hasColumn('barang', 'kondisi')) {
            \Illuminate\Support\Facades\DB::statement(
                "ALTER TABLE `barang` MODIFY `kondisi` VARCHAR(20) NOT NULL DEFAULT 'Baik'"
            );
        }
    }

    public function down(): void
    {
        // sengaja dikosongkan agar data kondisi tidak hilang
    }
};