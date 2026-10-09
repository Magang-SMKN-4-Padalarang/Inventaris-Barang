<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    public $timestamps = true;

    protected $fillable = [
        'nama_admin',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
