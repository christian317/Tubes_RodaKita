<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KondisiMobil extends Model
{
    protected $table = 'kondisi_mobil';

    protected $fillable = [
        'id_booking', 'tipe', 'odometer', 'indikator_bensin',
        'kondisi_eksterior', 'kondisi_interior', 'denda', 'catatan',
        'foto_kendaraan', // Kolom tunggal baru
    ];

    // JAWABAN UTAMA: Casting otomatis kolom teks menjadi array PHP
    protected $casts = [
        'foto_kendaraan' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }
}
