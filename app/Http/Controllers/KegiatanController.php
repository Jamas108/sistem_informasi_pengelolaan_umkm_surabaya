<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua kegiatan dan perbarui statusnya
        $kegiatans = Kegiatan::all();

        // Log jumlah kegiatan yang diupdate
        Log::info('Memulai pembaruan status untuk ' . $kegiatans->count() . ' kegiatan');

        // Update status kegiatan berdasarkan waktu saat ini
        foreach ($kegiatans as $kegiatan) {
            $this->updateKegiatanStatus($kegiatan);
        }

        // Muat ulang data setelah update untuk memastikan perubahan terlihat
        $kegiatans = Kegiatan::all();

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.datakegiatan.index', compact('kegiatans'));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminkantor.datakegiatan.index', compact('kegiatans'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.datakegiatan.create');
        } elseif ($userRole === 'adminlapangan') {
            return view('adminkantor.datakegiatan.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string|max:100',
            'lokasi_kegiatan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image upload
            'status_kegiatan' => 'nullable|string|max:50',
            'kuota_pendaftaran' => 'required|integer|max:50'
        ]);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $validatedData['poster'] = $posterPath;
        }

        // Create the Kegiatan
        $kegiatan = Kegiatan::create($validatedData);

        // Redirect with success message
        return redirect()->route('datakegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
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


        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.datakegiatan.show', [
                'kegiatan' => $kegiatan,
                'intervensis' => $intervensis
            ]);
        } elseif ($userRole === 'adminlapangan') {
            return view('adminkantor.datakegiatan.show',  [
                'kegiatan' => $kegiatan,
                'intervensis' => $intervensis
            ]);
        } 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.datakegiatan.edit', compact('kegiatan'));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminkantor.datakegiatan.edit', compact('kegiatan'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Validate the request
        $validatedData = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'nullable|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image upload
            'status_kegiatan' => 'nullable|string|max:50'
        ]);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($kegiatan->poster) {
                Storage::disk('public')->delete($kegiatan->poster);
            }

            // Store new poster
            $posterPath = $request->file('poster')->store('posters', 'public');
            $validatedData['poster'] = $posterPath;
        }

        // Update the Kegiatan
        $kegiatan->update($validatedData);

        // Redirect with success message
        return redirect()->route('datakegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Delete poster if exists
        if ($kegiatan->poster) {
            Storage::disk('public')->delete($kegiatan->poster);
        }

        // Delete the Kegiatan
        $kegiatan->delete();

        // Redirect with success message
        return redirect()->route('datakegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }


    public function generateBuktiPendaftaran(Request $request, $id)
    {
        // Temukan kegiatan
        $kegiatan = Kegiatan::findOrFail($id);

        // Validasi status kegiatan
        if ($kegiatan->status_kegiatan !== 'Persiapan Acara') {
            return redirect()->back()->with('error', 'Bukti pendaftaran hanya dapat dibuat saat status Pendaftaran');
        }

        // Ambil semua intervensi untuk kegiatan ini
        $intervensis = Intervensi::where('kegiatan_id', $id)
            ->with('dataUmkm')
            ->get();

        // Buat folder untuk bukti pendaftaran
        $folderPath = 'bukti-pendaftaran/' . $kegiatan->id . '_' . now()->format('YmdHis');
        Storage::disk('public')->makeDirectory($folderPath);

        // Generate bukti pendaftaran untuk setiap UMKM
        foreach ($intervensis as $intervensi) {
            // Generate PDF untuk setiap UMKM
            $pdf = PDF::loadView('adminkantor.datakegiatan.bukti-pendaftaran-umkm', [
                'kegiatan' => $kegiatan,
                'intervensi' => $intervensi
            ])->setPaper('a4')->setWarnings(false);

            // Nama file bukti pendaftaran (pastikan berakhiran .pdf)
            $fileName = Str::slug($intervensi->dataUmkm->nama_usaha . '-bukti-pendaftaran') . '.pdf';
            $filePath = $folderPath . '/' . $fileName;

            // Simpan PDF
            Storage::disk('public')->put($filePath, $pdf->output());
        }

        // Simpan path folder bukti pendaftaran
        $kegiatan->bukti_pendaftaran_path = $folderPath;
        $kegiatan->save();

        return redirect()->route('datakegiatan.index')
            ->with('success', 'Bukti pendaftaran berhasil dibuat untuk ' . $intervensis->count() . ' UMKM');
    }

    protected function updateKegiatanStatus(Kegiatan $kegiatan)
    {
        // Skip jika tidak memiliki semua data yang diperlukan
        if (
            empty($kegiatan->tanggal_mulai) || empty($kegiatan->jam_mulai) ||
            empty($kegiatan->tanggal_selesai) || empty($kegiatan->jam_selesai)
        ) {
            return;
        }

        // Pastikan zona waktu menggunakan Asia/Jakarta (WIB)
        $now = now()->setTimezone('Asia/Jakarta');

        try {
            // Buat datetime untuk waktu mulai (tanggal_mulai + jam_mulai)
            $waktuMulai = \Carbon\Carbon::parse($kegiatan->tanggal_mulai . ' ' . $kegiatan->jam_mulai, 'Asia/Jakarta');

            // Buat datetime untuk waktu selesai (tanggal_selesai + jam_selesai)
            $waktuSelesai = \Carbon\Carbon::parse($kegiatan->tanggal_selesai . ' ' . $kegiatan->jam_selesai, 'Asia/Jakarta');

            $statusAsli = $kegiatan->status_kegiatan;

            // Log untuk debugging
            Log::info('Pembaruan Status Kegiatan', [
                'id' => $kegiatan->id,
                'nama' => $kegiatan->nama_kegiatan,
                'waktu_saat_ini' => $now->toDateTimeString(),
                'waktu_mulai' => $waktuMulai->toDateTimeString(),
                'waktu_selesai' => $waktuSelesai->toDateTimeString(),
                'status_awal' => $statusAsli
            ]);

            // Jika waktu sekarang sudah melewati waktu selesai
            if ($now->greaterThan($waktuSelesai)) {
                $kegiatan->status_kegiatan = 'Selesai';
                Log::info('Status berubah menjadi: Selesai');
            }
            // Jika waktu sekarang di antara waktu mulai dan waktu selesai
            else if ($now->greaterThanOrEqualTo($waktuMulai) && $now->lessThanOrEqualTo($waktuSelesai)) {
                $kegiatan->status_kegiatan = 'Sedang Berlangsung';
                Log::info('Status berubah menjadi: Sedang Berlangsung');
            } else {
                Log::info('Status tidak berubah');
            }

            // Simpan jika ada perubahan status
            if ($statusAsli !== $kegiatan->status_kegiatan) {
                $kegiatan->save();
                Log::info('Status kegiatan berhasil diperbarui', [
                    'id' => $kegiatan->id,
                    'status_baru' => $kegiatan->status_kegiatan
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui status kegiatan', [
                'id' => $kegiatan->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
