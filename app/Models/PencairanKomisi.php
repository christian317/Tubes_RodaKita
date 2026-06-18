<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PencairanKomisi extends Model
{
    protected $table = 'pencairan_komisi';

    protected $fillable = ['id_pemilik_mobil', 'jumlah', 'status', 'nama_bank', 'nomor_rekening', 'nama_rekening', 'bukti_transfer', 'catatan', 'catatan_admin'];

    public function pemilik()
    {
        return $this->belongsTo(PemilikMobil::class, 'id_pemilik_mobil', 'id_user');
    }
}
