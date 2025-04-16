<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';

    protected $fillable = [
        'pelaku_umkm_id',
        'nama_usaha',
        'alamat',
        'status',
        'jenis_produk',
        'tipe_produk',
        'jumlah_tenaga_kerja',
        'klasifikasi_kinerja_usaha',
        'pengelolaan_usaha',


    ];

    public function pelakuUmkm()
    {
        return $this->belongsTo(PelakuUmkm::class, 'pelaku_umkm_id');
    }

    public function omset()
{
    return $this->hasMany(Omset::class, 'umkm_id');
}
}
