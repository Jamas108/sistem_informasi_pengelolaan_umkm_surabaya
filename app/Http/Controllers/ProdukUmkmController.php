<?php

namespace App\Http\Controllers;

use App\Models\ProdukUmkm;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProdukUmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'jenis_produk' => 'required|string|max:255',
                'tipe_produk' => 'required|string|max:255',
                'status' => 'required|string|in:AKTIF,TIDAK AKTIF',
            ]);

            $product = ProdukUmkm::create([
                'umkm_id' => $request->umkm_id,
                'jenis_produk' => $request->jenis_produk,
                'tipe_produk' => $request->tipe_produk,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving product: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage()
            ], 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_produk' => 'required|string|max:255',
                'tipe_produk' => 'required|string|max:255',
                'status' => 'required|string|in:AKTIF,TIDAK AKTIF',
            ]);

            $product = ProdukUmkm::findOrFail($id);
            $product->update([
                'jenis_produk' => $request->jenis_produk,
                'tipe_produk' => $request->tipe_produk,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage(), [
                'product_id' => $id,
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = ProdukUmkm::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), [
                'product_id' => $id,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductsByUmkm($umkmId)
    {
        try {
            $products = ProdukUmkm::where('umkm_id', $umkmId)->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products for UMKM: ' . $e->getMessage(), [
                'umkm_id' => $umkmId,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process temporary products for multiple UMKMs.
     */
    public function processTemp(Request $request)
    {
        try {
            $createdProducts = [];
            $errors = [];

            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $umkmId => $products) {
                    // Verify that UMKM exists
                    $umkm = Umkm::find($umkmId);
                    if (!$umkm) {
                        $errors[] = "UMKM dengan ID {$umkmId} tidak ditemukan";
                        continue;
                    }

                    foreach ($products as $productData) {
                        // Create product
                        $product = ProdukUmkm::create([
                            'umkm_id' => $umkmId,
                            'jenis_produk' => $productData['jenis_produk'],
                            'tipe_produk' => $productData['tipe_produk'],
                            'status' => $productData['status'] ?? 'AKTIF',
                        ]);

                        $createdProducts[] = $product;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($createdProducts) . ' produk berhasil diproses',
                'data' => [
                    'created' => $createdProducts,
                    'errors' => $errors
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing temp products: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple products at once.
     */
    public function storeMultiple(Request $request)
    {
        try {
            $request->validate([
                'products' => 'required|array',
                'products.*.umkm_id' => 'required|exists:umkm,id',
                'products.*.jenis_produk' => 'required|string|max:255',
                'products.*.tipe_produk' => 'required|string|max:255',
                'products.*.status' => 'required|string|in:AKTIF,TIDAK AKTIF',
            ]);

            $createdProducts = [];

            foreach ($request->products as $productData) {
                $product = ProdukUmkm::create([
                    'umkm_id' => $productData['umkm_id'],
                    'jenis_produk' => $productData['jenis_produk'],
                    'tipe_produk' => $productData['tipe_produk'],
                    'status' => $productData['status'],
                ]);

                $createdProducts[] = $product;
            }

            return response()->json([
                'success' => true,
                'message' => count($createdProducts) . ' produk berhasil ditambahkan',
                'data' => $createdProducts
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing multiple products: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created product via AJAX.
     */
    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'jenis_produk' => 'required|string|max:255',
                'tipe_produk' => 'required|string|max:255',
                'status' => 'required|string|in:AKTIF,TIDAK AKTIF',
            ]);

            $product = ProdukUmkm::create([
                'umkm_id' => $request->umkm_id,
                'jenis_produk' => $request->jenis_produk,
                'tipe_produk' => $request->tipe_produk,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving product via AJAX: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified product via AJAX.
     */
    public function updateAjax(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_produk' => 'required|string|max:255',
                'tipe_produk' => 'required|string|max:255',
                'status' => 'required|string|in:AKTIF,TIDAK AKTIF',
            ]);

            $product = ProdukUmkm::findOrFail($id);
            $product->update([
                'jenis_produk' => $request->jenis_produk,
                'tipe_produk' => $request->tipe_produk,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product via AJAX: ' . $e->getMessage(), [
                'product_id' => $id,
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product via AJAX.
     */
    public function destroyAjax($id)
    {
        try {
            $product = ProdukUmkm::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product via AJAX: ' . $e->getMessage(), [
                'product_id' => $id,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }
}
