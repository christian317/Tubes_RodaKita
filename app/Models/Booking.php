<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'id_user',
        'id_mobil',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'tipe_layanan',
        'foto_ktp',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_booking', 'id');
    }

    public function kondisiPengambilan()
    {
        return $this->hasOne(KondisiMobil::class, 'id_booking')->where('tipe', 'pengambilan');
    }

    public function kondisiPengembalian()
    {
        return $this->hasOne(KondisiMobil::class, 'id_booking')->where('tipe', 'pengembalian');
    }

    public function ulasanMobil()
    {
        return $this->hasOne(Ulasan::class, 'id_booking')->where('tipe', 'mobil');
    }

    public function ulasanPelanggan()
    {
        return $this->hasOne(Ulasan::class, 'id_booking')->where('tipe', 'pelanggan');
    }
}
