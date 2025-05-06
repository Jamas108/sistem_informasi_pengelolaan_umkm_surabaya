<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use App\Models\Legalitas;
use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\UmkmCompleteExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $datapelakuumkms = PelakuUmkm::all();


        // Filter out UMKM with status 'ditolak'
        // $dataumkms = Umkm::with('pelakuUmkm', 'produkUmkm')
        //     ->where('status', '!=', 'DITOLAK',)
        //     ->where('status', '!=', 'Menunggu Verifikasi',)
        //     ->get();

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.dataumkm.index', compact('datapelakuumkms', 'pageTitle'));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminlapangan.dataumkm.index', compact('datapelakuumkms', 'pageTitle'));
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
                ]);
                Log::info('UMKM entry created successfully', ['umkm_id' => $umkm->id]);

                // Process products for this UMKM if they exist
                if (isset($umkmData['products']) && !empty($umkmData['products'])) {
                    Log::info('Processing products for UMKM', [
                        'umkm_id' => $umkm->id,
                        'product_count' => count($umkmData['products'])
                    ]);

                    foreach ($umkmData['products'] as $productData) {
                        // Create product record
                        $product = ProdukUmkm::create([
                            'umkm_id' => $umkm->id,
                            'jenis_produk' => $productData['jenis_produk'],
                            'tipe_produk' => $productData['tipe_produk'],
                            'status' => $productData['status'] ?? 'Aktif',
                        ]);

                        Log::info('Product created successfully', ['product_id' => $product->id]);
                    }
                }
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
        $kegiatans = Kegiatan::all();

        $userRole = Auth::user()->role;

        // Menampilkan view yang berbeda berdasarkan role
        if ($userRole === 'adminkantor') {
            return view('adminkantor.dataumkm.show', compact('pelakuUmkm', 'kegiatans'));
        } elseif ($userRole === 'adminlapangan') {
            return view('adminlapangan.dataumkm.show', compact('pelakuUmkm', 'kegiatans'));
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        try {
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
                            'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                            'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                            'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                            'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                            'status' => $umkmData['status'] ?? 'AKTIF',
                        ]);
                        $submittedUmkmIds[] = $dataUmkm->id;
                    } else {
                        // Create new UMKM
                        $dataUmkm = $pelakuUmkm->dataUmkm()->create([
                            'nama_usaha' => $umkmData['nama_usaha'],
                            'alamat' => $umkmData['alamat'],
                            'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                            'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                            'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                            'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                            'status' => $umkmData['status'] ?? 'AKTIF',
                        ]);
                        $submittedUmkmIds[] = $dataUmkm->id;

                        // Process products for new UMKM if they exist
                        if (isset($umkmData['products']) && is_array($umkmData['products'])) {
                            foreach ($umkmData['products'] as $productData) {
                                if (!empty($productData['jenis_produk']) && !empty($productData['tipe_produk'])) {
                                    // Create product for this new UMKM
                                    ProdukUmkm::create([
                                        'umkm_id' => $dataUmkm->id,
                                        'jenis_produk' => $productData['jenis_produk'],
                                        'tipe_produk' => $productData['tipe_produk'],
                                        'status' => $productData['status'] ?? 'AKTIF',
                                    ]);
                                }
                            }
                        }
                    }
                }

                // Process deleted products if any
                if ($request->has('deleted_products') && is_array($request->deleted_products)) {
                    foreach ($request->deleted_products as $productId) {
                        $product = ProdukUmkm::find($productId);
                        if ($product) {
                            $product->delete();
                        }
                    }
                }

                // Process updated products if any
                if ($request->has('updated_products') && is_array($request->updated_products)) {
                    foreach ($request->updated_products as $productId => $productData) {
                        $product = ProdukUmkm::find($productId);
                        if ($product) {
                            $data = explode('|', $productData);
                            if (count($data) === 3) {
                                $product->update([
                                    'jenis_produk' => $data[0],
                                    'tipe_produk' => $data[1],
                                    'status' => $data[2]
                                ]);
                            }
                        }
                    }
                }

                // Process new products for existing UMKMs if any
                if ($request->has('new_products') && is_array($request->new_products)) {
                    foreach ($request->new_products as $umkmId => $products) {
                        foreach ($products as $productData) {
                            $data = explode('|', $productData);
                            if (count($data) === 3) {
                                ProdukUmkm::create([
                                    'umkm_id' => $umkmId,
                                    'jenis_produk' => $data[0],
                                    'tipe_produk' => $data[1],
                                    'status' => $data[2]
                                ]);
                            }
                        }
                    }
                }

                // Remove UMKMs that were deleted from the form
                $umkmsToDelete = array_diff($existingUmkmIds, $submittedUmkmIds);
                if (count($umkmsToDelete) > 0) {
                    // Delete associated products first
                    foreach ($umkmsToDelete as $umkmId) {
                        ProdukUmkm::where('umkm_id', $umkmId)->delete();
                    }
                    Umkm::whereIn('id', $umkmsToDelete)->delete();
                }
            }

            // Existing code for handling Omset, Legalitas, and Intervensi data...

            DB::commit();

            return redirect()->route('dataumkm.index')
                ->with('status', 'Data UMKM berhasil diperbarui')
                ->with('status_type', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating UMKM data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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

    public function exportUmkm()
{
    try {
        Log::info('Starting UMKM data export');

        return Excel::download(new UmkmCompleteExport(), 'data_umkm_' . date('YmdHis') . '.xlsx');
    } catch (\Exception $e) {
        Log::error('Error exporting UMKM data', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
    }
}
}
