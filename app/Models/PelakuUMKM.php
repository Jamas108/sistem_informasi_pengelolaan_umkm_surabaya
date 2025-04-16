<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelakuUmkm extends Model
{
    use HasFactory;


    protected $table = 'pelaku_umkm'; // Deklarasi nama tabel secara eksplisit


    protected $fillable = [
        'users_id',
        'nama_lengkap',
        'nik',
        'no_kk',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'status_hubungan_keluarga',
        'status_perkawinan',
        'kelurahan',
        'rt',
        'rw',
        'alamat_sesuai_ktp',
        'no_telp',
        'pendidikan_terakhir',
        'status_keaktifan',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function dataUmkm()
    {
        return $this->hasMany(Umkm::class, 'pelaku_umkm_id');
    }
    
    public function omset()
    {
        return $this->hasMany(Omset::class, 'umkm_id');
    }
}
