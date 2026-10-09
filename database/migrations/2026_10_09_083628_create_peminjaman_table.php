<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->integer('id_peminjaman')->autoIncrement();
            $table->integer('id_barang');
            $table->integer('id_peminjam');
            $table->dateTime('tgl_pinjam');
            $table->dateTime('tgl_jatuh_tempo');
            $table->dateTime('tgl_kembali')->nullable();
            $table->enum('status_peminjaman', ['Dipinjam', 'Selesai', 'Terlambat'])->default('Dipinjam');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->foreign('id_peminjam')->references('id_peminjam')->on('peminjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
