<?php

namespace App\Http\Controllers;

use App\Models\PelakuUmkm;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminLapApprovalUMKMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Data UMKM';
        $dataumkms = Umkm::with('pelakuUmkm')->where('status', 'Menunggu Verifikasi')->get();
        return view('adminkantor.approval.approvalumkm', compact('dataumkms', 'pageTitle'));
    }

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

        $dataumkm = Umkm::with('pelakuUmkm')->findOrFail($id);
        return view('adminkantor.approval.show', compact('dataumkm'));
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

    public function approve(string $id)
    {
        try {
            DB::beginTransaction();

            $umkm = Umkm::findOrFail($id);
            $umkm->status = 'AKTIF';
            $umkm->save();

            DB::commit();

            return redirect()->route('approvalumkm.index')
                ->with('success', 'Pengajuan UMKM berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approval error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Reject UMKM application
     */
    public function reject(Request $request, string $id)
    {

        try {
            DB::beginTransaction();

            $umkm = Umkm::findOrFail($id);
            $umkm->status = 'DITOLAK';
            $umkm->save();

            DB::commit();

            return redirect()->route('approvalumkm.index')
                ->with('success', 'Pengajuan UMKM berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rejection error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak pengajuan: ' . $e->getMessage());
        }
    }
}
