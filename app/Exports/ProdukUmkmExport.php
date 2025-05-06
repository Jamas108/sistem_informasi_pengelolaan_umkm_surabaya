<?php

namespace App\Exports;

use App\Models\ProdukUmkm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProdukUmkmExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function collection()
    {
        return ProdukUmkm::with(['umkm', 'umkm.pelakuUmkm'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Produk',
            'ID UMKM',
            'Nama Usaha',
            'NIK Pemilik',
            'Nama Pemilik',
            'Jenis Produk',
            'Tipe Produk',
            'Status Produk'
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->id,
            $produk->umkm_id,
            $produk->umkm ? $produk->umkm->nama_usaha : '-',
            $produk->umkm && $produk->umkm->pelakuUmkm ? $produk->umkm->pelakuUmkm->nik : '-',
            $produk->umkm && $produk->umkm->pelakuUmkm ? $produk->umkm->pelakuUmkm->nama_lengkap : '-',
            $produk->jenis_produk,
            $produk->tipe_produk,
            $produk->status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Produk UMKM';
    }
}