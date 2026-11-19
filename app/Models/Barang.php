<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = true;

    protected $fillable = [
        'kode_barcode',
        'nama_barang',
        'merk_tipe',
        'id_kategori',
        'id_jenis_barang',
        'status_barang',
        'kondisi',
        'keterangan',
        'catatan',
        'terkunci',
        'ruangan',
        'lemari',
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
        return $this->belongsTo(Jenis_barang::class, 'id_jenis_barang', 'id_jenis_barang');
    }
}