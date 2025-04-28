<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legalitas extends Model
{
    use HasFactory;

    protected $table = 'legalitas';

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

    /**
     * Get the UMKM that owns the legalitas.
     */
    public function dataUmkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}