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
        'alamat',
        'kelurahan',
        'rt',
        'rw',
        'alamat_sesuai_ktp',
        'no_telp',
        'pendidikan_terakhir',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
