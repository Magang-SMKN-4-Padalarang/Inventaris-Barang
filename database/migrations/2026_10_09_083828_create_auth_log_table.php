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
        Schema::create('auth_log', function (Blueprint $table) {
            $table->integer('id_auth_log')->autoIncrement();
            $table->integer('id_admin')->nullable();
            $table->string('username_input', 50);
            $table->enum('aksi', ['login_berhasil', 'login_gagal', 'logout']);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Index & Foreign Key
            $table->index('created_at', 'idx_auth_created');
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_log');
    }
};
