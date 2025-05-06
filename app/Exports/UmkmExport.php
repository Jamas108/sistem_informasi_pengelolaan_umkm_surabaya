<?php

namespace App\Exports;

use App\Models\PelakuUmkm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class UmkmExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $rowNumber = 0;

    public function collection()
    {
        // Reset counter
        $this->rowNumber = 0;

        // Dapatkan semua pelaku UMKM dengan UMKMnya
        $pelakuUmkms = PelakuUmkm::with(['dataUmkm', 'dataUmkm.produkUmkm'])->get();

        // Buat collection baru untuk menampung data yang sudah di-flatten
        $flattenedData = new Collection();

        // Iterasi setiap pelaku UMKM
        foreach ($pelakuUmkms as $pelakuUmkm) {
            // Jika pelaku memiliki UMKM
            if ($pelakuUmkm->dataUmkm->count() > 0) {
                // Iterasi setiap UMKM milik pelaku
                foreach ($pelakuUmkm->dataUmkm as $umkm) {
                    // Tambahkan entri baru yang berisi pelaku dan satu UMKMnya
                    $flattenedData->push([
                        'pelakuUmkm' => $pelakuUmkm,
                        'umkm' => $umkm
                    ]);
                }
            } else {
                // Jika pelaku tidak memiliki UMKM, tetap tampilkan data pelaku
                $flattenedData->push([
                    'pelakuUmkm' => $pelakuUmkm,
                    'umkm' => null
                ]);
            }
        }

        return $flattenedData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama UMKM',
            'Alamat UMKM',
            'Pengelolaan Usaha',
            'Klasifikasi Kinerja Usaha',
            'Jumlah Tenaga Kerja',
            'Sektor Usaha',
            'Status UMKM',
            'Nama Pemilik UMKM',
            'NIK Pemilik UMKM',
            'No Telepon Pemilik UMKM',
        ];
    }

    public function map($item): array
    {
        $pelakuUmkm = $item['pelakuUmkm'];
        $umkm = $item['umkm'];

        // Increment row number
        $this->rowNumber++;

        return [
            $this->rowNumber, // No increment
            $umkm ? $umkm->nama_usaha : '-',
            $umkm ? $umkm->alamat : '-',
            $umkm ? $umkm->pengelolaan_usaha : '-',
            $umkm ? $umkm->klasifikasi_kinerja_usaha : '-',
            $umkm ? $umkm->jumlah_tenaga_kerja : '-',
            $umkm ? $umkm->sektor_usaha : '-',
            $umkm ? $umkm->status : '-',
            $pelakuUmkm->nama_lengkap,
            $pelakuUmkm->nik,
            $pelakuUmkm->no_telp,
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