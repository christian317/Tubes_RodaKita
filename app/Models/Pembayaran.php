<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_booking',
        'id_promo',
        'total_pembayaran',
        'potongan_harga',
        'status_pembayaran',
        'komisi_pemilik',
    ];

    public $timestamps = false;

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo');
    }
}
