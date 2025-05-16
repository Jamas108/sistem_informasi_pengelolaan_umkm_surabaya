<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class PelakuKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::query();

       // Apply filters based on request parameters
       if ($request->has('jenis_kegiatan') && $request->jenis_kegiatan != '') {
           $query->where('jenis_kegiatan', $request->jenis_kegiatan);
       }

       if ($request->has('status_kegiatan') && $request->status_kegiatan != '') {
           $query->where('status_kegiatan', $request->status_kegiatan);
       }

       $kegiatans = $query->get();

       // Get filtered data
        return view('pelakuumkm.kegiatan.index', compact('kegiatans'));    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Ambil intervensi untuk kegiatan ini
        $intervensis = Intervensi::where('kegiatan_id', $id)
            ->with('dataUmkm')
            ->get();

            return view('pelakuumkm.kegiatan.show', [
                'kegiatan' => $kegiatan,
                'intervensis' => $intervensis
            ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
