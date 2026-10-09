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
        Schema::create('activity_log', function (Blueprint $table) {
            $table->integer('id_activity_log')->autoIncrement();
            $table->integer('id_admin')->nullable();
            $table->enum('aktor', ['admin', 'peminjam', 'sistem']);
            $table->enum('aksi', ['tambah', 'ubah', 'hapus', 'pinjam', 'kembali', 'ekspor', 'cetak_barcode']);
            $table->string('nama_tabel', 50)->nullable();
            $table->integer('id_data')->nullable();
            $table->string('deskripsi', 255);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Index & Foreign Key
            $table->index('created_at', 'idx_activity_created');
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
