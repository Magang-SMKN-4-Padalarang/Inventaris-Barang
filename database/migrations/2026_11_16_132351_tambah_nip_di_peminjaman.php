<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjam', function (Blueprint $table) {
            $table->string('nip', 18)->nullable()->after('nama_peminjam');
        });
    }

    public function down(): void
    {
        Schema::table('peminjam', function (Blueprint $table) {
            $table->dropColumn('nip');
        });
    }
};
