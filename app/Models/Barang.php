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
        'ruangan', 'lemari', 'singkatan', 'nomor_urut',
    ];

    public static function susunKode(string $ruangan, string $lemari, string $singkatan, int $nomor): string
    {
        return $ruangan . '-' . $lemari . '-' . $singkatan . '-' . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
    }

    public static function nomorBerikutnya(string $ruangan, string $lemari, string $singkatan, ?int $kecualiId = null): int
    {
        $terakhir = static::where('ruangan', $ruangan)
            ->where('lemari', $lemari)
            ->where('singkatan', $singkatan)
            ->when($kecualiId, fn ($q) => $q->where('id_barang', '!=', $kecualiId))
            ->lockForUpdate()
            ->orderByDesc('nomor_urut')
            ->value('nomor_urut');

        return ((int) $terakhir) + 1;
    }

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
