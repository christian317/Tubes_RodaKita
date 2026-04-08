<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'id_user',
        'id_mobil',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'status',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil');
    }
}
