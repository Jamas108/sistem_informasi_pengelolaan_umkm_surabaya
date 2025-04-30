<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PelakuKelolaUmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Find the PelakuUmkm record associated with the logged-in user
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // If no PelakuUmkm record found, return with empty data
        if (!$pelakuUmkm) {
            return view('pelakuumkm.kelolaumkm.index', [
                'dataumkms' => collect(), // Empty collection
                'pageTitle' => 'Kelola UMKM'
            ]);
        }

        // Retrieve UMKM data for the specific PelakuUmkm
        $dataumkms = Umkm::where('pelaku_umkm_id', $pelakuUmkm->id)->get();

        return view('pelakuumkm.kelolaumkm.index', [
            'dataumkms' => $dataumkms,
            'pageTitle' => 'Kelola UMKM'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Find the PelakuUmkm record associated with the logged-in user
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->firstOrFail();

        return view('pelakuumkm.kelolaumkm.create', [
            'pelakuUmkm' => $pelakuUmkm,
            'pageTitle' => 'Tambah UMKM Baru'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log store attempt
        Log::info('Storing new UMKM data', ['request_data' => $request->except(['_token'])]);

        // Get the currently logged-in user
        $user = Auth::user();

        // Find the PelakuUmkm record associated with the logged-in user
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // If no PelakuUmkm record found, return with error
        if (!$pelakuUmkm) {
            Log::error('No PelakuUmkm record found for user', ['user_id' => $user->id]);
            return redirect()->route('pelakukelolaumkm.index')
                ->with('status', 'Data pelaku UMKM tidak ditemukan')
                ->with('status_type', 'danger');
        }

        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        try {
            $successCount = 0; // Counter for successful UMKM creations

            // Handle UMKM data - create new ones
            if ($request->has('umkm')) {
                foreach ($request->umkm as $index => $umkmData) {
                    Log::info('Processing UMKM data', ['index' => $index, 'data' => $umkmData]);

                    // Skip empty entries (if any)
                    if (empty($umkmData['nama_usaha']) && empty($umkmData['alamat'])) {
                        Log::info('Skipping empty UMKM entry', ['index' => $index]);
                        continue;
                    }

                    // Create new UMKM
                    $dataUmkm = Umkm::create([
                        'pelaku_umkm_id' => $pelakuUmkm->id,
                        'nama_usaha' => $umkmData['nama_usaha'],
                        'alamat' => $umkmData['alamat'],
                        'jenis_produk' => $umkmData['jenis_produk'] ?? null,
                        'tipe_produk' => $umkmData['tipe_produk'] ?? null,
                        'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                        'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                        'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                        'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                        'status' => 'Menunggu Verifikasi',
                    ]);

                    Log::info('Created new UMKM', ['umkm_id' => $dataUmkm->id]);
                    $successCount++;

                }
            }

            // Commit all changes
            DB::commit();
            Log::info('All store operations completed successfully', ['success_count' => $successCount]);

            // Redirect with success message
            $successMessage = $successCount > 1
                ? "$successCount UMKM berhasil ditambahkan dan menunggu verifikasi admin"
                : "UMKM berhasil ditambahkan dan menunggu verifikasi admin";

            return redirect()->route('pelakukelolaumkm.index')
                ->with('status', $successMessage)
                ->with('status_type', 'success');
        } catch (\Exception $e) {
            // Rollback in case of any exceptions
            DB::rollBack();
            Log::error('Error storing UMKM data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Redirect with error message
            return redirect()->back()
                ->with('status', 'Terjadi kesalahan: ' . $e->getMessage())
                ->with('status_type', 'danger')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Find the PelakuUmkm record associated with the logged-in user
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->firstOrFail();

        return view('pelakuumkm.kelolaumkm.edit', compact('pelakuUmkm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Log update attempt
        Log::info('Updating UMKM data', ['id' => $id, 'request_data' => $request->all()]);

        // Validate the request data
        $request->validate([
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'no_kk' => 'required|string|max:16',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|max:20',
            'status_hubungan_keluarga' => 'required|string|max:50',
            'status_perkawinan' => 'required|string|max:50',
            'alamat_sesuai_ktp' => 'required|string',
            'kelurahan' => 'required|string|max:255',
            'rw' => 'required|string|max:10',
            'rt' => 'required|string|max:10',
            'no_telp' => 'required|string|max:15',
            'pendidikan_terakhir' => 'required|string|max:50',
            'status_keaktifan' => 'required|string|max:50',
        ]);

        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Find the Pelaku UMKM
            $pelakuUmkm = PelakuUmkm::findOrFail($id);

            // Update the associated User's NIK if it has changed
            if ($pelakuUmkm->nik != $request->nik) {
                $user = User::where('id', $pelakuUmkm->users_id)->first();
                if ($user) {
                    $user->update([
                        'nik' => $request->nik
                    ]);
                    Log::info('Updated associated user NIK', ['user_id' => $user->id]);
                }
            }

            // Update Pelaku UMKM data
            $pelakuUmkm->update([
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap,
                'no_kk' => $request->no_kk,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status_hubungan_keluarga' => $request->status_hubungan_keluarga,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat_sesuai_ktp' => $request->alamat_sesuai_ktp,
                'kelurahan' => $request->kelurahan,
                'rw' => $request->rw,
                'rt' => $request->rt,
                'no_telp' => $request->no_telp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'status_keaktifan' => $request->status_keaktifan,
            ]);

            Log::info('Updated pelaku UMKM data', ['pelaku_id' => $pelakuUmkm->id]);

            // Handle UMKM data - update existing, add new ones, remove deleted ones
            if ($request->has('umkm')) {
                $existingUmkmIds = $pelakuUmkm->dataUmkm->pluck('id')->toArray();
                $submittedUmkmIds = [];

                foreach ($request->umkm as $index => $umkmData) {
                    Log::info('Processing UMKM data', ['index' => $index, 'data' => $umkmData]);

                    // Skip empty entries (if any)
                    if (empty($umkmData['nama_usaha']) && empty($umkmData['alamat'])) {
                        Log::info('Skipping empty UMKM entry', ['index' => $index]);
                        continue;
                    }

                    if (!empty($umkmData['id'])) {
                        // Update existing UMKM
                        $dataUmkm = Umkm::findOrFail($umkmData['id']);
                        $dataUmkm->update([
                            'nama_usaha' => $umkmData['nama_usaha'],
                            'alamat' => $umkmData['alamat'],
                            'jenis_produk' => $umkmData['jenis_produk'] ?? null,
                            'tipe_produk' => $umkmData['tipe_produk'] ?? null,
                            'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                            'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                            'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                            'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                            'status' => $umkmData['status'] ?? 'Aktif',
                            'status' => 'Menunggu Verifikasi',
                        ]);
                        Log::info('Updated existing UMKM', ['umkm_id' => $dataUmkm->id]);
                        $submittedUmkmIds[] = $dataUmkm->id;
                    } else {
                        // Create new UMKM
                        $dataUmkm = Umkm::create([
                            'pelaku_umkm_id' => $pelakuUmkm->id,
                            'nama_usaha' => $umkmData['nama_usaha'],
                            'alamat' => $umkmData['alamat'],
                            'jenis_produk' => $umkmData['jenis_produk'] ?? null,
                            'tipe_produk' => $umkmData['tipe_produk'] ?? null,
                            'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                            'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                            'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                            'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                            'status' => 'Menunggu Verifikasi',
                        ]);
                        Log::info('Created new UMKM', ['umkm_id' => $dataUmkm->id]);
                        $submittedUmkmIds[] = $dataUmkm->id;
                    }
                }

                // Identify UMKMs to delete (those in existing but not in submitted)
                $umkmsToDelete = array_diff($existingUmkmIds, $submittedUmkmIds);
                if (count($umkmsToDelete) > 0) {
                    Log::info('Deleting UMKMs', ['umkm_ids' => $umkmsToDelete]);

                    // First delete related data (omset, legalitas, intervensi)
                    foreach ($umkmsToDelete as $umkmId) {
                        Omset::where('umkm_id', $umkmId)->delete();
                    }

                    // Then delete the UMKM records
                    Umkm::whereIn('id', $umkmsToDelete)->delete();
                }
            }

            // Handle Omset data
            if ($request->has('omset') && isset($request->omset['umkm_id']) && !empty($request->omset['umkm_id'])) {
                $omsetData = $request->omset;
                Log::info('Processing Omset data', ['data' => $omsetData]);

                // Prepare omset value by removing formatting
                $omsetValue = isset($omsetData['total_omset']) ?
                    str_replace(['.', ','], ['', '.'], $omsetData['total_omset']) : 0;

                // Create or update omset data
                Omset::updateOrCreate(
                    [
                        'umkm_id' => $omsetData['umkm_id'],
                        'jangka_waktu' => $omsetData['jangka_waktu'],
                    ],
                    [
                        'omset' => $omsetValue,
                        'keterangan' => $omsetData['keterangan'] ?? 'aktif',
                    ]
                );

                Log::info('Saved Omset data');
            }

            // Commit all changes
            DB::commit();
            Log::info('All update operations completed successfully');

            // Redirect with success message
            return redirect()->route('pelakukelolaumkm.index')
                ->with('status', 'Data UMKM berhasil diperbarui')
                ->with('status_type', 'success');
        } catch (\Exception $e) {
            // Rollback in case of any exceptions
            DB::rollBack();
            Log::error('Error updating UMKM data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Redirect with error message
            return redirect()->back()
                ->with('status', 'Terjadi kesalahan: ' . $e->getMessage())
                ->with('status_type', 'danger')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
