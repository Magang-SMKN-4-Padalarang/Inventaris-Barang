<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jenis_barang extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang';

    protected $primaryKey = 'id_jenis_barang';

    protected $fillable = [
        'nama_jenis_barang',
        'foto_barang',
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_jenis_barang', 'id_jenis_barang');
    }
}
