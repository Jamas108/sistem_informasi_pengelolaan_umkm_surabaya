<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendaftarExport;

class AdminLapPesertaPendaftaranController extends Controller
{
    /**
     * Display a listing of pendaftar for a specific kegiatan.
     */
    public function index($kegiatanId)
    {
        // Temukan kegiatan
        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        // Ambil semua intervensi (pendaftar) untuk kegiatan ini
        $intervensis = Intervensi::where('kegiatan_id', $kegiatanId)
            ->with(['dataUmkm', 'dataUmkm.pelakuUmkm'])
            ->get();

        return view('adminkantor.datakegiatan.peserta-kegiatan', [
            'kegiatan' => $kegiatan,
            'intervensis' => $intervensis
        ]);
    }

    /**
     * Update status kehadiran pendaftar
     */
    public function updateStatus(Request $request, $intervensiId)
    {
        // Validasi request
        $validatedData = $request->validate([
            'status' => 'required|string|in:Terdaftar,Hadir,Tidak Hadir',
        ]);

        // Temukan intervensi
        $intervensi = Intervensi::findOrFail($intervensiId);

        // Update status
        $intervensi->status = $validatedData['status'];
        $intervensi->save();

        // Kembali ke halaman pendaftar dengan pesan sukses
        return redirect()->back()->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Remove peserta from kegiatan (delete the intervensi record)
     */
    public function destroy($intervensiId)
    {
        // Find the intervensi record
        $intervensi = Intervensi::findOrFail($intervensiId);

        // Store kegiatan_id before deleting to redirect back
        $kegiatanId = $intervensi->kegiatan_id;

        // Delete the record
        $intervensi->delete();

        // Redirect back with success message
        return redirect()->route('pendaftar.index', $kegiatanId)
            ->with('success', 'Peserta berhasil dihapus dari kegiatan.');
    }

    public function printAttendance($kegiatanId)
    {
        // Temukan kegiatan
        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        // Ambil semua intervensi (pendaftar) untuk kegiatan ini
        $intervensis = Intervensi::where('kegiatan_id', $kegiatanId)
            ->with(['dataUmkm', 'dataUmkm.pelakuUmkm'])
            ->get();

        // Generate PDF khusus untuk daftar hadir
        $pdf = PDF::loadView('adminkantor.datakegiatan.daftar-hadir', [
            'kegiatan' => $kegiatan,
            'intervensis' => $intervensis
        ])->setPaper('a4', 'portrait');

        // Stream PDF (tampilkan di browser)
        // Fix: Use Str::slug instead of str_slug
        $filename = 'daftar-hadir-' . Str::slug($kegiatan->nama_kegiatan) . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Export data pendaftar ke PDF
     */
    // public function exportPDF($kegiatanId)
    // {
    //     // Temukan kegiatan
    //     $kegiatan = Kegiatan::findOrFail($kegiatanId);

    //     // Ambil semua intervensi (pendaftar) untuk kegiatan ini
    //     $intervensis = Intervensi::where('kegiatan_id', $kegiatanId)
    //         ->with(['dataUmkm', 'dataUmkm.pelakuUmkm'])
    //         ->get();

    //     // Generate PDF
    //     $pdf = PDF::loadView('adminkantor.datakegiatan.pendaftar-pdf', [
    //         'kegiatan' => $kegiatan,
    //         'intervensis' => $intervensis
    //     ])->setPaper('a4', 'landscape');

    //     // Download PDF dengan nama yang sesuai
    //     return $pdf->download('pendaftar-' . str_slug($kegiatan->nama_kegiatan) . '.pdf');
    // }

    /**
     * Export data pendaftar ke Excel
     */
    // public function exportExcel($kegiatanId)
    // {
    //     // Temukan kegiatan
    //     $kegiatan = Kegiatan::findOrFail($kegiatanId);

    //     // Generate nama file
    //     $filename = 'pendaftar-' . str_slug($kegiatan->nama_kegiatan) . '.xlsx';

    //     // Export ke Excel menggunakan Laravel Excel
    //     return Excel::download(new PendaftarExport($kegiatanId), $filename);
    // }

    /**
     * Cetak daftar hadir
     */
    // public function printAttendance($kegiatanId)
    // {
    //     // Temukan kegiatan
    //     $kegiatan = Kegiatan::findOrFail($kegiatanId);

    //     // Ambil semua intervensi (pendaftar) untuk kegiatan ini
    //     $intervensis = Intervensi::where('kegiatan_id', $kegiatanId)
    //         ->with(['dataUmkm', 'dataUmkm.pelakuUmkm'])
    //         ->get();

    //     // Generate PDF khusus untuk daftar hadir
    //     $pdf = PDF::loadView('adminkantor.datakegiatan.daftar-hadir', [
    //         'kegiatan' => $kegiatan,
    //         'intervensis' => $intervensis
    //     ])->setPaper('a4', 'portrait');

    //     // Stream PDF (tampilkan di browser)
    //     return $pdf->stream('daftar-hadir-' . str_slug($kegiatan->nama_kegiatan) . '.pdf');
    // }
}
