<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PencairanKomisi extends Model
{
    protected $table = 'pencairan_komisi';
    protected $fillable = ['id_pemilik_mobil', 'jumlah', 'bukti_transfer', 'catatan'];

    public function pemilik()
    {
        return $this->belongsTo(PemilikMobil::class, 'id_pemilik_mobil', 'id_user');
    }
}