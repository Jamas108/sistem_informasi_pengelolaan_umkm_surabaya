<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'lokasi_kegiatan',
        'jenis_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'poster',
        'status_kegiatan',
        'kuota_pendaftaran',
        'bukti_pendaftaran_path',
    ];

    public function intervensi()
    {
        return $this->hasMany(Intervensi::class, 'kegiatan_id');
    }
}