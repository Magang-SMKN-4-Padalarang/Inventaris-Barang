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
        Schema::create('barang', function (Blueprint $table) {
            $table->integer('id_barang')->autoIncrement();
            $table->string('kode_barcode', 50)->unique();
            $table->string('nama_barang', 100);
            $table->integer('id_kategori')->nullable();
            $table->enum('status_barang', ['Tersedia', 'Dipinjam'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
