<?php

namespace App\Exports;

use App\Models\Intervensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class IntervensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    private $rowNumber = 0;
    private $kegiatanId = null;
    private $kegiatanName = null;

    public function __construct($kegiatanId = null)
    {
        $this->rowNumber = 0;
        $this->kegiatanId = $kegiatanId;

        // Jika ada filter, ambil nama kegiatan untuk judul sheet
        if ($this->kegiatanId) {
            $kegiatan = \App\Models\Kegiatan::find($this->kegiatanId);
            if ($kegiatan) {
                $this->kegiatanName = $kegiatan->nama_kegiatan;
            }
        }
    }

    public function collection()
    {
        // Reset counter
        $this->rowNumber = 0;

        // Query dasar
        $query = Intervensi::with(['kegiatan', 'dataUmkm', 'dataUmkm.pelakuUmkm']);

        // Terapkan filter kegiatan jika ada
        if ($this->kegiatanId) {
            $query->where('kegiatan_id', $this->kegiatanId);
        }

        // Ambil data
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama UMKM',
            'Pemilik UMKM',
            'Nama Kegiatan',
            'Jenis Kegiatan',
            'No Pendaftaran',
            'Omset',
        ];
    }

    public function map($intervensi): array
    {
        // Increment row number
        $this->rowNumber++;

        // Format omset to currency if needed
        $formattedOmset = 'Rp ' . number_format($intervensi->omset, 0, ',', '.');

        return [
            $this->rowNumber,
            $intervensi->dataUmkm ? $intervensi->dataUmkm->nama_usaha : '-',
            $intervensi->dataUmkm && $intervensi->dataUmkm->pelakuUmkm ? $intervensi->dataUmkm->pelakuUmkm->nama_lengkap : '-',
            $intervensi->kegiatan ? $intervensi->kegiatan->nama_kegiatan : '-',
            $intervensi->kegiatan ? $intervensi->kegiatan->jenis_kegiatan : '-',
            $intervensi->no_pendaftaran_kegiatan,
            $formattedOmset,
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
        // Jika ada filter, gunakan nama kegiatan sebagai judul sheet
        if ($this->kegiatanName) {
            return 'Kegiatan ' . $this->kegiatanName;
        }

        return 'Data Intervensi';
    }
}