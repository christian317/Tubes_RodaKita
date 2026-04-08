<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $table = 'mobil';
    public $timestamps = false;
    
    protected $fillable = [
        'id_brand', 'id_kategori', 'id_pemilik_mobil', 'model', 'plat_nomer', 
        'harga_sewa', 'transmisi', 'kapasitas_penumpang', 'tahun', 
        'status_katalog', 'status_mobil', 'gambar', 'deskripsi'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class, 'id_brand');
    }
    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
    public function pemilik() {
        return $this->belongsTo(User::class, 'id_pemilik_mobil', 'id');
    }
}
