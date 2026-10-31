<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TIPE = ['Siswa', 'Guru', 'Staff', 'Lainnya'];

    public function up(): void
    {
        Schema::table('peminjam', function (Blueprint $table) {
            $table->string('tipe_peminjam', 20)->change();
        });

        foreach (self::TIPE as $tipe) {
            DB::table('peminjam')
                ->whereRaw('LOWER(tipe_peminjam) = ?', [strtolower($tipe)])
                ->update(['tipe_peminjam' => $tipe]);
        }
    }

    public function down(): void
    {
        foreach (self::TIPE as $tipe) {
            DB::table('peminjam')
                ->where('tipe_peminjam', $tipe)
                ->update(['tipe_peminjam' => strtolower($tipe)]);
        }

        Schema::table('peminjam', function (Blueprint $table) {
            $table->enum('tipe_peminjam', ['siswa', 'guru', 'staff', 'lainnya'])->change();
        });
    }
};
