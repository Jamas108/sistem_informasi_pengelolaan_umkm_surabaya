<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use App\Models\Legalitas;
use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.dataumkm.index', compact('dataumkms', 'pageTitle'));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminlapangan.dataumkm.index', compact('dataumkms', 'pageTitle'));
        }
    }

    public function ExportUmkmIndex()
    {
        $pageTitle = 'Data UMKM';
        $dataumkms = Umkm::with('pelakuUmkm')->get();
        return view('adminkantor.export.dataumkm', compact('dataumkms', 'pageTitle'));
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
                'role' => 'pelakuumkm',
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

        $pelakuUmkm = PelakuUmkm::with('dataUmkm')->findOrFail($id);

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.dataumkm.show', compact('pelakuUmkm',));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminlapangan.dataumkm.show', compact('pelakuUmkm'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pelakuUmkm = PelakuUmkm::with('dataUmkm')->findOrFail($id);
        $kegiatans = Kegiatan::all();

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.dataumkm.edit', compact('pelakuUmkm', 'kegiatans'));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminlapangan.dataumkm.edit', compact('pelakuUmkm', 'kegiatans'));
        }
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

        // UPDATED: For consistency, call the storeIntervensi method if intervention data is present
        if ($request->has('intervensi') && isset($request->intervensi['umkm_id']) && !empty($request->intervensi['umkm_id'])) {
            // Create a new request with just the intervention data
            $intervensiRequest = new Request([
                'umkm_id' => $request->intervensi['umkm_id'],
                'kegiatan_id' => $request->intervensi['kegiatan_id'] ?? null,
            ]);

            // If kegiatan_id is not provided but nama_kegiatan is, try to find or create the kegiatan
            if (!isset($request->intervensi['kegiatan_id']) && isset($request->intervensi['nama_kegiatan'])) {
                // Find or create a Kegiatan based on nama_kegiatan
                $kegiatan = Kegiatan::firstOrCreate(
                    ['nama_kegiatan' => $request->intervensi['nama_kegiatan']],
                    [
                        'status_kegiatan' => 'Pendaftaran', // Default status for admin-created kegiatan
                        'tanggal_kegiatan' => isset($request->intervensi['tanggal_intervensi'])
                            ? $request->intervensi['tanggal_intervensi']
                            : now(),
                        'kuota_pendaftaran' => 100, // Default quota
                    ]
                );
                $intervensiRequest->merge(['kegiatan_id' => $kegiatan->id]);
            }

            // Call the storeIntervensi method to handle the intervention creation
            $result = $this->storeIntervensi($intervensiRequest);

            // If there was an error and a redirect was returned, extract the error message
            if ($result instanceof \Illuminate\Http\RedirectResponse && $result->getSession()->has('error')) {
                return redirect()->route('dataumkm.index')
                    ->with('status', $result->getSession()->get('error'))
                    ->with('status_type', 'error');
            }
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

    public function getUmkmOptions(string $pelakuId)
    {
        try {
            // Temukan semua UMKM untuk Pelaku UMKM tertentu
            $umkms = Umkm::where('pelaku_umkm_id', $pelakuId)
                ->select('id', 'nama_usaha', 'sektor_usaha', 'status')
                ->get();

            // Log jumlah UMKM
            Log::info('UMKM options retrieved', [
                'pelaku_id' => $pelakuId,
                'umkm_count' => $umkms->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $umkms->map(function ($umkm) {
                    return [
                        'id' => $umkm->id,
                        'nama_usaha' => $umkm->nama_usaha,
                        'sektor_usaha' => $umkm->sektor_usaha,
                        'status' => $umkm->status
                    ];
                })
            ]);
        } catch (\Exception $e) {
            // Log error
            Log::error('Error getting UMKM options', [
                'pelaku_id' => $pelakuId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}
