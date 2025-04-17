<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Omset extends Model
{
    use HasFactory;

     protected $table = 'omset'; // Deklarasi nama tabel secara eksplisit


    protected $fillable = [
        'umkm_id',
        'jangka_waktu',
        'total_omset',
        'keterangan',

    ];
    public function dataUmkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id', 'id');
    }
}
