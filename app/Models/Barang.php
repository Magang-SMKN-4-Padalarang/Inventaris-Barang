<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = true;

    protected $fillable = [
        'kode_barcode', 'nama_barang', 'id_kategori', 'status_barang',
        'ruangan', 'lemari'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_barang', 'id_barang');
    }

    public function jenis_barang()
    {
        return $this->belongsTo(jenis_barang::class, 'id_jenis_barang', 'id_jenis_barang');
    }
}
