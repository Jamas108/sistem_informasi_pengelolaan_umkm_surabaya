<?php

namespace App\Exports;

use App\Models\Umkm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailUmkmExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function collection()
    {
        return Umkm::with(['pelakuUmkm', 'produkUmkm'])->get();
    }

    public function headings(): array
    {
        return [
            'ID UMKM',
            'NIK Pemilik',
            'Nama Pemilik',
            'Nama Usaha',
            'Alamat Usaha',
            'Pengelolaan Usaha',
            'Klasifikasi Kinerja',
            'Jumlah Tenaga Kerja',
            'Sektor Usaha',
            'Status Usaha',
        ];
    }

    public function map($umkm): array
    {
        return [
            $umkm->id,
            $umkm->pelakuUmkm ? $umkm->pelakuUmkm->nik : '-',
            $umkm->pelakuUmkm ? $umkm->pelakuUmkm->nama_lengkap : '-',
            $umkm->nama_usaha,
            $umkm->alamat,
            $umkm->pengelolaan_usaha,
            $umkm->klasifikasi_kinerja_usaha,
            $umkm->jumlah_tenaga_kerja,
            $umkm->sektor_usaha,
            $umkm->status,
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
        return 'Data UMKM';
    }
}