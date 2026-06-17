<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    protected $fillable = ['id_booking', 'tipe', 'rating', 'catatan'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }
}