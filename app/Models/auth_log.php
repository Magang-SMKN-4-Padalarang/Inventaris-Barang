<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class auth_log extends Model
{
    protected $table = 'auth_log';
    protected $primaryKey = 'id_auth_log';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_admin',
        'username_input',
        'aksi',
        'ip_address',
        'user_agent',
        'keterangan'
    ];
}
