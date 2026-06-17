<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';

    protected $fillable = [
        'kode_promo',
        'tipe_potongan',
        'nominal_potongan',
        'minimal_transaksi',
        'kuota',
        'tanggal_kadaluarsa',
    ];

    /**
     * Relationship with Pembayaran
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_promo', 'id');
    }
}
