<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlaimAsuransi extends Model
{
    protected $table = 'klaim_asuransi';

    protected $fillable = [
        'id_booking',
        'id_pemilik_mobil',
        'deskripsi_kerusakan',
        'estimasi_biaya',
        'biaya_disetujui',
        'foto_bukti',
        'status',
        'catatan_klaim',
        'submitted_at',
        'processed_at',
    ];

    protected $casts = [
        'foto_bukti' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'id_pemilik_mobil');
    }
}
