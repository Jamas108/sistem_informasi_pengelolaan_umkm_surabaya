<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    use HasFactory;

    protected $table = 'intervensi';

    protected $fillable = [
        'umkm_id',
        'kegiatan_id',
        'omset',
        'dokumentasi_kegiatan',
        'no_pendaftaran_kegiatan',
        'created_at'
    ];

    protected $casts = [
        'tanggal_intervensi' => 'date',
        'omset' => 'decimal:2',
        'dokumentasi_kegiatan' => 'array' // This should be enough on its own
    ];

    // Relasi ke UMKM
    public function dataUmkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    // Relasi ke Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id')->withTrashed();
    }

    // Scope untuk filter berdasarkan UMKM
    public function scopeByUmkm($query, $umkmId)
    {
        return $query->where('umkm_id', $umkmId);
    }

    // Scope untuk filter berdasarkan Kegiatan
    public function scopeByKegiatan($query, $kegiatanId)
    {
        return $query->where('kegiatan_id', $kegiatanId);
    }

    public static function getTotalOmsetByUmkm($umkmId)
    {
        return self::where('umkm_id', $umkmId)->sum('omset');
    }

    /*
    // Comment out these methods since we're using the array cast
    // Mutator untuk dokumentasi kegiatan
    public function setDokumentasiKegiatanAttribute($value)
    {
        // Jika value adalah array, convert ke JSON
        $this->attributes['dokumentasi_kegiatan'] = is_array($value)
            ? json_encode($value)
            : $value;
    }

    // Accessor untuk dokumentasi kegiatan
    public function getDokumentasiKegiatanAttribute($value)
    {
        // Jika value adalah JSON string, decode ke array
        return is_string($value)
            ? json_decode($value, true)
            : $value;
    }
    */
}
