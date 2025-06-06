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
    public function index(Request $request)
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
    $query = Umkm::where('pelaku_umkm_id', $pelakuUmkm->id);

    // Apply filters based on request parameters
    if ($request->has('sector') && $request->sector != '') {
        $query->where('sektor_usaha', $request->sector);
    }

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Get filtered data
    $dataumkms = $query->get();

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

            session()->flash('success', 'Berhasil Mendaftar UMKM, Tunggu Konfirmasi Persetujuan Dari Admin');
            return redirect()->route('pelakukelolaumkm.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
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

            session()->flash('success', 'Berhasil Memperbarui Data UMKM');
            return redirect()->route('pelakukelolaumkm.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();

        }
    }

    /**
    /**
     * Remove the specified resource from storage.
     */
   /**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
    // Log delete attempt
    Log::info('Attempting to delete UMKM', ['umkm_id' => $id]);

    // Begin transaction to ensure data consistency
    DB::beginTransaction();

    try {
        // Find the UMKM by ID
        $umkm = Umkm::findOrFail($id);

        // Get user and check authorization
        $user = Auth::user();
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // Verify ownership
        if ($pelakuUmkm->id !== $umkm->pelaku_umkm_id) {
            Log::warning('Unauthorized deletion attempt', [
                'user_id' => $user->id,
                'umkm_id' => $id
            ]);
            return redirect()->route('pelakukelolaumkm.index')
                ->with('status', 'Anda tidak memiliki akses untuk menghapus UMKM ini')
                ->with('status_type', 'danger');
        }

        // First, delete all products associated with this UMKM
        $deletedProductsCount = ProdukUmkm::where('umkm_id', $id)->delete();
        Log::info('Deleted associated products', [
            'umkm_id' => $id,
            'products_deleted' => $deletedProductsCount
        ]);

        // Now delete the UMKM itself
        $umkm->delete();
        Log::info('UMKM deleted successfully', ['umkm_id' => $id]);

        // Commit all changes
        DB::commit();

        // Redirect with success message
        return redirect()->route('pelakukelolaumkm.index')
            ->with('status', 'UMKM berhasil dihapus')
            ->with('status_type', 'success');
    } catch (\Exception $e) {
        // Rollback in case of any exceptions
        DB::rollBack();
        Log::error('Error deleting UMKM', [
            'umkm_id' => $id,
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        // Redirect with error message
        return redirect()->route('pelakukelolaumkm.index')
            ->with('status', 'Terjadi kesalahan: ' . $e->getMessage())
            ->with('status_type', 'danger');
    }
}
}
