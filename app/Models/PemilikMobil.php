<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemilikMobil extends Model
{
    protected $table = 'pemilik_mobil';

    // Karena Primary Key-nya adalah id_user (bukan id auto increment standar)
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_bank',
        'nomor_rekening',
        'nomor_ktp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
