<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';

    public $timestamps = false;

    protected $fillable = [
        'id_role',
        'nama',
        'email',
        'password',
        'alamat',
        'no_telepon',
    ];

    protected $hidden = [
        'password',
    ];

    public function pemilikMobil()
    {
        return $this->hasOne(PemilikMobil::class, 'id_user', 'id');
    }

    public function verifikasi()
    {
        return $this->hasOne(VerifikasiAkun::class, 'id_user', 'id');
    }
}
