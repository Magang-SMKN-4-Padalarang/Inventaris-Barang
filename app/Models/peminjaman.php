<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $timestamps = true;

    protected $fillable = ['id_barang', 'id_peminjam', 'tgl_pinjam', 'tgl_jatuh_tempo', 'tgl_kembali', 'status_peminjaman'];

    // Relasi ke Barang dan Peminjam
    public function barang() { return $this->belongsTo(Barang::class, 'id_barang', 'id_barang'); }
    public function peminjam() { return $this->belongsTo(Peminjam::class, 'id_peminjam', 'id_peminjam'); }
}
