<?php

namespace App\Exports;

use App\Models\PelakuUmkm;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UmkmViewExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('adminkantor.export.umkm', [
            'pelakuUmkms' => PelakuUmkm::with(['dataUmkm', 'dataUmkm.produkUmkm'])->get()
        ]);
    }
}