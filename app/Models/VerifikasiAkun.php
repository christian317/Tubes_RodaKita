<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiAkun extends Model
{
    protected $table = 'verifikasi_akun';

    protected $fillable = [
        'id_user',
        'foto_ktp',
        'foto_sim',
        'foto_selfie',
        'status',
        'catatan_verifikasi',
        'verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
