<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\ProdukUmkm;
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
                        'pengelolaan_usaha' => $umkmData['pengelolaan_usaha'] ?? null,
                        'klasifikasi_kinerja_usaha' => $umkmData['klasifikasi_kinerja_usaha'] ?? null,
                        'jumlah_tenaga_kerja' => $umkmData['jumlah_tenaga_kerja'] ?? null,
                        'sektor_usaha' => $umkmData['sektor_usaha'] ?? null,
                        'status' => 'Menunggu Verifikasi',
                    ]);

                    Log::info('Created new UMKM', ['umkm_id' => $dataUmkm->id]);
                    $successCount++;

                    // Process products for this UMKM if they exist
                    if (isset($umkmData['products']) && !empty($umkmData['products'])) {
                        Log::info('Processing products for UMKM', [
                            'umkm_id' => $dataUmkm->id,
                            'product_count' => count($umkmData['products'])
                        ]);

                        foreach ($umkmData['products'] as $productData) {
                            // Create product record
                            $product = ProdukUmkm::create([
                                'umkm_id' => $dataUmkm->id,
                                'jenis_produk' => $productData['jenis_produk'],
                                'tipe_produk' => $productData['tipe_produk'],
                                'status' => $productData['status'] ?? 'Aktif',
                            ]);

                            Log::info('Product created successfully', ['product_id' => $product->id]);
                        }
                    }
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
        // Get the currently logged-in user
        $user = Auth::user();

        // Find the PelakuUmkm record associated with the logged-in user
        $umkm = Umkm::findOrFail($id);

        return view('pelakuumkm.kelolaumkm.show', compact('umkm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Get the currently logged-in user
        $user = Auth::user();

        $umkm = Umkm::findOrFail($id);

        return view('pelakuumkm.kelolaumkm.edit', compact('umkm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Log update attempt
        Log::info('Updating UMKM data', ['id' => $id, 'request_data' => $request->all()]);

        // Validate the request data - khusus untuk UMKM saja
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_produk' => 'nullable|string|max:255',
            'tipe_produk' => 'nullable|string|max:255',
            'pengelolaan_usaha' => 'nullable|string|max:255',
            'klasifikasi_kinerja_usaha' => 'nullable|string|max:255',
            'jumlah_tenaga_kerja' => 'nullable|integer',
            'sektor_usaha' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Find the UMKM by ID
            $dataUmkm = Umkm::findOrFail($id);

            // Update UMKM data
            $dataUmkm->update([
                'nama_usaha' => $request->nama_usaha,
                'alamat' => $request->alamat,
                'jenis_produk' => $request->jenis_produk,
                'tipe_produk' => $request->tipe_produk,
                'pengelolaan_usaha' => $request->pengelolaan_usaha,
                'klasifikasi_kinerja_usaha' => $request->klasifikasi_kinerja_usaha,
                'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
                'sektor_usaha' => $request->sektor_usaha,
                'status' => $request->status ?? $dataUmkm->status, // Mempertahankan status jika tidak diubah
            ]);

            Log::info('Updated UMKM data', ['umkm_id' => $dataUmkm->id]);

            // Commit all changes
            DB::commit();
            Log::info('All update operations completed successfully');

            // Redirect with success message - disesuaikan dengan rute yang benar
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
