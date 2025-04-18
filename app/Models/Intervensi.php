<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    use HasFactory;
    protected $table = 'intervensi'; // Deklarasi nama tabel secara eksplisit


    protected $fillable = [
        'users_id',
        'umkm_id',
        'nama_kegiatan',
        'tgl_intervensi',
        'jenis_intervensi',
        'omset',

    ];
    public function dataUmkm()
    {
        return $this->hasMany(Umkm::class, 'pelaku_umkm_id');
    }
}
