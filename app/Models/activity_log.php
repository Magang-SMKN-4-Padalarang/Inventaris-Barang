<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_log extends Model
{
    protected $table = 'activity_log';
    protected $primaryKey = 'id_activity_log';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_admin',
        'aktor',
        'aksi',
        'nama_tabel',
        'id_data',
        'deskripsi',
        'ip_address'
    ];
}
