<?php

namespace App\Http\Controllers;

use App\Models\ProdukUmkm;
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
}
