<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjam extends Model
{
    protected $table = 'peminjam';
    protected $primaryKey = 'id_peminjam';
    public $timestamps = true;

    protected $fillable = ['nama_peminjam', 'tipe_peminjam', 'keterangan', 'no_hp'];
}
