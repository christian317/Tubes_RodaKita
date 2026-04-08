<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   // Arahkan ke tabel Anda
    protected $table = 'user';
    
    // Matikan timestamps jika di tabel user Anda tidak ada created_at/updated_at
    public $timestamps = false; 

    protected $fillable = [
        'id_role', 
        'nama', 
        'email', 
        'password', 
        'alamat', 
        'no_telepon'
    ];

    protected $hidden = [
        'password',
    ];

    public function pemilikMobil()
    {
    
        return $this->hasOne(PemilikMobil::class, 'id_user', 'id');
    }
}
