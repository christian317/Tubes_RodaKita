<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalLiburan extends Model
{
    protected $table = 'jadwal_liburans';

    // Tambahkan jam_mulai dan jam_selesai
    protected $fillable = ['id_booking', 'tanggal', 'jam_mulai', 'jam_selesai', 'kegiatan', 'lokasi'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }
}
