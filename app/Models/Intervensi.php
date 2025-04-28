<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'intervensi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'umkm_id',
        'kegiatan_id',
        'omset',
        'dokumentasi_kegiatan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_intervensi' => 'date',
        'omset' => 'decimal:2',
    ];

    /**
     * Get the UMKM that owns the intervensi.
     */
    public function dataUmkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    /**
     * Get the kegiatan associated with the intervensi.
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}