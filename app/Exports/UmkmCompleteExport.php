<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UmkmCompleteExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data Pelaku UMKM' => new UmkmExport(),
            'Data UMKM' => new DetailUmkmExport(),
        ];
    }
}
