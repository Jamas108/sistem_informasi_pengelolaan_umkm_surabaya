<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukUmkm extends Model
{
    use HasFactory;

    protected $table = 'produk_umkm';

    protected $fillable = [
        'umkm_id',
        'tipe_produk',
        'jenis_produk',
        'status',
    ];

    /**
     * Get the UMKM that owns the legalitas.
     */
    public function dataUmkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}