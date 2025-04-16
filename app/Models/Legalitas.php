<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legalitas extends Model
{
    use HasFactory;


    protected $table = 'legalitas'; // Deklarasi nama tabel secara eksplisit


    protected $fillable = [
        'umkm_id',
        'no_sk_nib',
        'no_sk_siup',
        'no_sk_tdp',
        'no_sk_pirt',
        'no_sk_bpom',
        'no_sk_halal',
        'no_sk_merek',
        'no_sk_haki',
        'no_surat_keterangan',

    ];
    public function dataUmkm()
    {
        return $this->hasMany(Umkm::class, 'pelaku_umkm_id');
    }

}
