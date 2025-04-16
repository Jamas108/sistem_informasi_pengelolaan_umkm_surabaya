<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Legalitas;
use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataUmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Check if a NIK exists in the pelaku_umkm table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $pageTitle = 'Data UMKM';
        $dataumkms = Umkm::with('pelakuUmkm')->get();
        return view('adminkantor.dataumkm.index', compact('dataumkms', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Data UMKM UMKM';
        return view('adminkantor.dataumkm.create',);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log input data
        Log::info('Attempting to store UMKM data', ['request' => $request->all()]);

        try {
            $request->validate([
                'nik' => 'required|unique:pelaku_umkm,nik',
                'nama_lengkap' => 'required',
                'no_kk' => 'required',
                'tempat_lahir' => 'required',
                'tgl_lahir' => 'required|date',
                'jenis_kelamin' => 'required',
                'status_hubungan_keluarga' => 'required',
                'status_perkawinan' => 'required',
                'kelurahan' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'alamat_sesuai_ktp' => 'required',
                'no_telp' => 'required',
                'pendidikan_terakhir' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            throw $e;
        }

        // Log UMKM data
        Log::info('UMKM data structure', ['umkm' => $request->umkm ?? 'Missing']);

        // Validate UMKM data - at least one UMKM entry is required
        if (!isset($request->umkm) || empty($request->umkm)) {
            Log::warning('UMKM data missing in request');
            return redirect()->back()
                ->with('error', 'Minimal satu data UMKM harus diisi')
                ->withInput();
        }

        DB::beginTransaction();
        Log::info('Starting DB transaction');

        try {
            // Create user
            Log::info('Creating user', ['nik' => $request->nik]);
            $user = User::create([
                'username' => $request->nik,
                'nik' => $request->nik,
                'password' => Hash::make($request->nik),
                'role' => 'pelaku-umkm',
            ]);
            Log::info('User created successfully', ['user_id' => $user->id]);

            // Create pelaku UMKM
            Log::info('Creating pelaku UMKM record');
            $pelakuUmkm = PelakuUmkm::create([
                'users_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status_hubungan_keluarga' => $request->status_hubungan_keluarga,
                'status_perkawinan' => $request->status_perkawinan,
                'kelurahan' => $request->kelurahan,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'alamat_sesuai_ktp' => $request->alamat_sesuai_ktp,
                'no_telp' => $request->no_telp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'status_keaktifan' => $request->status_keaktifan ?? 'Aktif',
            ]);
            Log::info('Pelaku UMKM record created', ['id' => $pelakuUmkm->id]);

            // Process each UMKM entry
            foreach ($request->umkm as $key => $umkmData) {
                Log::info('Processing UMKM entry', ['index' => $key, 'data' => $umkmData]);

                // Validate required fields for each UMKM
                if (empty($umkmData['nama_usaha']) || empty($umkmData['alamat'])) {
                    Log::warning('Incomplete UMKM entry detected', ['index' => $key, 'data' => $umkmData]);
                    throw new \Exception("Data UMKM #" . ($key + 1) . " tidak lengkap. Nama Usaha dan Alamat harus diisi.");
                }

                // Create UMKM record
                $umkm = Umkm::create([
                    'pelaku_umkm_id' => $pelakuUmkm->id,
                    'nama_usaha' => $umkmData['nama_usaha'],
                    'alamat' => $umkmData['alamat'],
                    'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                    'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                    'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                    'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                    'status' => $umkmData['status'] ?? 'Aktif',
                    'jenis_produk' => $umkmData['jenis_produk'] ?? null,
                    'tipe_produk' => $umkmData['tipe_produk'] ?? null,
                ]);
                Log::info('UMKM entry created successfully', ['umkm_id' => $umkm->id]);
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            return redirect()->route('dataumkm.index')
                ->with('success', 'Data UMKM berhasil disimpan. Username dan password telah dibuat dengan NIK.');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();
            Log::error('Error occurred during UMKM creation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return with error message
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        return view('adminkantor.dataumkm.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pelakuUmkm = PelakuUmkm::with('dataUmkm')->findOrFail($id);
        return view('adminkantor.dataumkm.edit', compact('pelakuUmkm'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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

        // Find the Pelaku UMKM
        $pelakuUmkm = PelakuUmkm::findOrFail($id);

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

        // Handle UMKM data - update existing, add new ones, remove deleted ones
        if ($request->has('umkm')) {
            $existingUmkmIds = $pelakuUmkm->dataUmkm->pluck('id')->toArray();
            $submittedUmkmIds = [];

            foreach ($request->umkm as $index => $umkmData) {
                if (!empty($umkmData['id'])) {
                    // Update existing UMKM
                    $dataUmkm = Umkm::findOrFail($umkmData['id']);
                    $dataUmkm->update([
                        'nama_usaha' => $umkmData['nama_usaha'],
                        'alamat' => $umkmData['alamat'],
                        'jenis_produk' => $umkmData['jenis_produk'],
                        'tipe_produk' => $umkmData['tipe_produk'],
                        'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'],
                        'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'],
                        'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'],
                        'sektor_usaha' => $umkmData['sektor_usaha'],
                        'status' => $umkmData['status'],
                    ]);
                    $submittedUmkmIds[] = $dataUmkm->id;
                } else {
                    // Create new UMKM
                    $dataUmkm = $pelakuUmkm->dataUmkm()->create([
                        'nama_usaha' => $umkmData['nama_usaha'],
                        'alamat' => $umkmData['alamat'],
                        'jenis_produk' => $umkmData['jenis_produk'],
                        'tipe_produk' => $umkmData['tipe_produk'],
                        'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'],
                        'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'],
                        'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'],
                        'sektor_usaha' => $umkmData['sektor_usaha'],
                        'status' => $umkmData['status'],
                    ]);
                    $submittedUmkmIds[] = $dataUmkm->id;
                }
            }

            // Remove UMKMs that were deleted from the form
            $umkmsToDelete = array_diff($existingUmkmIds, $submittedUmkmIds);
            if (count($umkmsToDelete) > 0) {
                Umkm::whereIn('id', $umkmsToDelete)->delete();
            }
        }

        // Handle Omset data if it's submitted
        if ($request->has('omset') && isset($request->omset['umkm_id']) && !empty($request->omset['umkm_id'])) {
            $omsetData = $request->omset;

            // Create or update omset data
            Omset::updateOrCreate(
                [
                    'umkm_id' => $omsetData['umkm_id'],
                    'jangka_waktu' => $omsetData['jangka_waktu'],
                ],
                [
                    'total_omset' => str_replace(['.', ','], ['', '.'], $omsetData['total_omset']),
                    'keterangan' => $omsetData['keterangan'],
                ]
            );
        }

        // Handle Legalitas data if it's submitted
        if ($request->has('legalitas') && isset($request->legalitas['umkm_id']) && !empty($request->legalitas['umkm_id'])) {
            $legalitasData = $request->legalitas;

            // Create or update legalitas data
            Legalitas::updateOrCreate(
                [
                    'umkm_id' => $legalitasData['umkm_id'],
                ],
                [
                    'no_sk_nib' => $legalitasData['no_sk_nib'] ?? null,
                    'no_sk_siup' => $legalitasData['no_sk_siup'] ?? null,
                    'no_sk_tdp' => $legalitasData['no_sk_tdp'] ?? null,
                    'no_sk_pirt' => $legalitasData['no_sk_pirt'] ?? null,
                    'no_sk_bpom' => $legalitasData['no_sk_bpom'] ?? null,
                    'no_sk_halal' => $legalitasData['no_sk_halal'] ?? null,
                    'no_sk_merek' => $legalitasData['no_sk_merek'] ?? null,
                    'no_sk_haki' => $legalitasData['no_sk_haki'] ?? null,
                    'no_surat_keterangan' => $legalitasData['no_surat_keterangan'] ?? null,
                ]
            );
        }

        // Handle Intervensi data if it's submitted
        if ($request->has('intervensi') && isset($request->intervensi['umkm_id']) && !empty($request->intervensi['umkm_id'])) {
            $intervensiData = $request->intervensi;

            // Create new intervensi data (intervensi is typically not updated but added as new records)
            $pelakuUmkm->intervensi()->create([
                'umkm_id' => $intervensiData['umkm_id'],
                'tanggal_intervensi' => $intervensiData['tanggal_intervensi'],
                'jenis_intervensi' => $intervensiData['jenis_intervensi'],
                'nama_kegiatan' => $intervensiData['nama_kegiatan'],
                'omset' => str_replace(['.', ','], ['', '.'], $intervensiData['omset']),
            ]);
        }

        return redirect()->route('dataumkm.index')
            ->with('status', 'Data UMKM berhasil diperbarui')
            ->with('status_type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkNik(Request $request)
    {
        try {
            $nik = $request->input('nik');

            if (empty($nik)) {
                return response()->json([
                    'exists' => false,
                    'message' => 'NIK is required'
                ], 400);
            }

            // Check if NIK exists in the pelaku_umkm table
            $pelakuUmkm = \App\Models\PelakuUmkm::where('nik', $nik)->first();

            if ($pelakuUmkm) {
                return response()->json([
                    'exists' => true,
                    'data' => $pelakuUmkm
                ]);
            } else {
                return response()->json([
                    'exists' => false
                ]);
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error checking NIK: ' . $e->getMessage());

            return response()->json([
                'exists' => false,
                'message' => 'An error occurred while checking NIK: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get list of omset data for a specific Pelaku UMKM.
     *
     * @param string $id The ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOmsetList(string $id)
    {
        try {
            // Find the Pelaku UMKM
            $pelakuUmkm = PelakuUmkm::findOrFail($id);

            // Get all UMKMs associated with this Pelaku
            $umkmIds = $pelakuUmkm->dataUmkm()->pluck('id')->toArray();

            // Get all omset data for these UMKMs with UMKM name
            $omsetData = Omset::whereIn('umkm_id', $umkmIds)
                ->with('dataUmkm:id,nama_usaha')
                ->orderBy('jangka_waktu', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'umkm_id' => $item->umkm_id,
                        'nama_usaha' => $item->dataUmkm->nama_usaha,
                        'jangka_waktu' => $item->jangka_waktu,
                        'total_omset' => $item->total_omset,
                        'keterangan' => $item->keterangan,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $omsetData
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting omset list', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pelaku_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific omset data.
     *
     * @param string $id The ID of the Omset
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOmset(string $id)
    {
        try {
            // Find the omset data
            $omset = Omset::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $omset
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting omset data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'omset_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    // Update the saveOmset method in DataUmkmController
    public function saveOmset(Request $request, string $id)
    {
        // Log the incoming request data
        Log::info('Omset save request received', [
            'umkm_id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            // Validate the request
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'jangka_waktu' => 'required|date',
                'total_omset' => 'required',
                'keterangan' => 'required|string|max:50',
            ]);

            Log::info('Omset validation passed', ['validated_data' => $validated]);

            // Verify the UMKM exists
            $umkm = Umkm::where('id', $request->umkm_id)->first();

            if (!$umkm) {
                Log::warning('UMKM not found', [
                    'umkm_id' => $request->umkm_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'UMKM tidak ditemukan'
                ], 404);
            }

            Log::info('UMKM verified', ['umkm_id' => $umkm->id, 'nama_usaha' => $umkm->nama_usaha]);

            // Format the omset value (remove thousands separators)
            $formattedOmset = str_replace(['.', ','], ['', '.'], $request->total_omset);

            // Create omset data
            $omset = new Omset([
                'umkm_id' => $request->umkm_id,
                'jangka_waktu' => $request->jangka_waktu,
                'total_omset' => $formattedOmset,
                'keterangan' => $request->keterangan,
            ]);

            // Save the omset
            $omset->save();
            Log::info('Omset data saved successfully', ['omset_id' => $omset->id]);

            return response()->json([
                'success' => true,
                'message' => 'Data omset berhasil disimpan',
                'data' => $omset
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for omset data', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving omset data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
