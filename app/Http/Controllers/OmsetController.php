<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OmsetController extends Controller
{

    public function index()
    {
        $dataomsets = Omset::with('dataUmkm')->get();
        return view('adminkantor.export.omset', compact('dataomsets'));
    }

    /**
     * Get list of omset data for a specific Pelaku UMKM.
     *
     * @param string $id The ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOmsetList(string $id)
    {
        Log::info('Getting omset list', ['pelaku_id' => $id]);
        try {
            // Skip the Pelaku UMKM check and directly get UMKMs by pelaku_umkm_id
            // This avoids the error when ID isn't found in PelakuUmkm model

            // Get all UMKMs associated with this Pelaku directly
            $umkmIds = Umkm::where('pelaku_umkm_id', $id)->pluck('id')->toArray();
            Log::info('Found associated UMKMs', ['pelaku_id' => $id, 'umkm_count' => count($umkmIds)]);

            // Get all omset data for these UMKMs with UMKM name
            $omsetData = Omset::whereIn('umkm_id', $umkmIds)
                ->with('dataUmkm:id,nama_usaha')
                ->orderBy('jangka_waktu', 'desc')
                ->get();

            Log::info('Retrieved omset data', ['pelaku_id' => $id, 'omset_count' => $omsetData->count()]);

            $mappedData = $omsetData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'umkm_id' => $item->umkm_id,
                    'nama_usaha' => $item->dataUmkm->nama_usaha,
                    'jangka_waktu' => $item->jangka_waktu,
                    'total_omset' => $item->total_omset,
                    'keterangan' => $item->keterangan,
                ];
            });

            Log::info('Returning omset list response', ['pelaku_id' => $id, 'data_count' => $mappedData->count()]);
            return response()->json([
                'success' => true,
                'data' => $mappedData
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting omset list', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pelaku_id' => $id,
                'exception_class' => get_class($e)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // The rest of the controller stays the same
    public function getOmset(string $id)
    {
        Log::info('Getting omset details', ['omset_id' => $id]);
        try {
            // Find the omset data
            $omset = Omset::findOrFail($id);
            Log::info('Found omset data', ['omset_id' => $id, 'umkm_id' => $omset->umkm_id]);

            Log::info('Returning omset data response', ['omset_id' => $id]);
            return response()->json([
                'success' => true,
                'data' => $omset
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting omset data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'omset_id' => $id,
                'exception_class' => get_class($e)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Save new omset data.
     *
     * @param Request $request
     * @param string $id ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOmset(Request $request, string $id)
    {
        Log::info('Omset save request received', [
            'pelaku_id' => $id,
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

    /**
     * Update the specified omset in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOmset(Request $request, string $id)
    {
        Log::info('Omset update request received', [
            'omset_id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            // Find the omset
            $omset = Omset::findOrFail($id);

            // Validate the request
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'jangka_waktu' => 'required|date',
                'total_omset' => 'required',
                'keterangan' => 'required|string|max:50',
            ]);

            Log::info('Omset update validation passed', ['validated_data' => $validated]);

            // Format the omset value (remove thousands separators)
            $formattedOmset = str_replace(['.', ','], ['', '.'], $request->total_omset);

            // Update the omset
            $omset->umkm_id = $request->umkm_id;
            $omset->jangka_waktu = $request->jangka_waktu;
            $omset->total_omset = $formattedOmset;
            $omset->keterangan = $request->keterangan;
            $omset->save();

            Log::info('Omset data updated successfully', ['omset_id' => $omset->id]);

            return response()->json([
                'success' => true,
                'message' => 'Data omset berhasil diperbarui',
                'data' => $omset
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Omset not found', [
                'omset_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data omset tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for omset update', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating omset data', [
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

    /**
     * Remove the specified omset from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOmset(string $id)
    {
        Log::info('Omset delete request received', [
            'omset_id' => $id
        ]);

        try {
            // Find the omset
            $omset = Omset::findOrFail($id);

            // Delete the omset
            $omset->delete();

            Log::info('Omset data deleted successfully', ['omset_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Data omset berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Omset not found for deletion', [
                'omset_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data omset tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting omset data', [
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
}
