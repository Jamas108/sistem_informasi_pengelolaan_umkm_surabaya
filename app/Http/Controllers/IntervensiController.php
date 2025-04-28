<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Umkm;
use App\Models\PelakuUmkm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class IntervensiController extends Controller
{
    /**
     * Get list of intervensi data for a specific Pelaku UMKM.
     *
     * @param string $id The ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $user = Auth::user();

        // Find the PelakuUmkm record associated with the logged-in user
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // If no PelakuUmkm record found, return with empty data
        if (!$pelakuUmkm) {
            return view('pelakuumkm.kelolaintervensi.index', [
                'dataintervensi' => collect(), // Empty collection
                'pageTitle' => 'Kelola Intervensi'
            ]);
        }

        // Get the UMKM IDs for this PelakuUmkm
        $umkmIds = $pelakuUmkm->dataUmkm->pluck('id');

        // Retrieve Intervensi data for the specific UMKM
        $dataintervensi = Intervensi::whereIn('umkm_id', $umkmIds)
            ->with('dataUmkm') // Eager load the related UMKM
            ->get();

        return view('pelakuumkm.kelolaintervensi.index', [
            'dataintervensi' => $dataintervensi,
            'pageTitle' => 'Kelola Intervensi'
        ]);
    }
    public function getIntervensiList(string $id)
    {
        Log::info('Getting intervensi list', ['pelaku_id' => $id]);
        try {
            // Get all UMKMs associated with this Pelaku directly
            $umkmIds = Umkm::where('pelaku_umkm_id', $id)->pluck('id')->toArray();
            Log::info('Found associated UMKMs', ['pelaku_id' => $id, 'umkm_count' => count($umkmIds)]);

            // Get all intervensi data for these UMKMs with UMKM name
            $intervensiData = Intervensi::whereIn('umkm_id', $umkmIds)
                ->with('dataUmkm:id,nama_usaha')
                ->get();

            Log::info('Retrieved intervensi data', ['pelaku_id' => $id, 'intervensi_count' => $intervensiData->count()]);

            $mappedData = $intervensiData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'umkm_id' => $item->umkm_id,
                    'nama_usaha' => $item->dataUmkm->nama_usaha ?? 'Tidak ada',
                    'tgl_intervensi' => $item->tgl_intervensi,
                    'jenis_intervensi' => $item->jenis_intervensi,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'omset' => $item->omset,
                ];
            });

            Log::info('Returning intervensi list response', ['pelaku_id' => $id, 'data_count' => $mappedData->count()]);
            return response()->json([
                'success' => true,
                'data' => $mappedData
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting intervensi list', [
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

    /**
     * Get specific intervensi data
     *
     * @param string $id The ID of the Intervensi
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIntervensi(string $id)
    {
        Log::info('Getting intervensi details', ['intervensi_id' => $id]);
        try {
            // Find the intervensi data
            $intervensi = Intervensi::findOrFail($id);
            Log::info('Found intervensi data', ['intervensi_id' => $id, 'umkm_id' => $intervensi->umkm_id]);

            Log::info('Returning intervensi data response', ['intervensi_id' => $id]);
            return response()->json([
                'success' => true,
                'data' => $intervensi
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting intervensi data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'intervensi_id' => $id,
                'exception_class' => get_class($e)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save new intervensi data.
     *
     * @param Request $request
     * @param string $id ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveIntervensi(Request $request, string $id)
    {
        Log::info('Intervensi save request received', [
            'pelaku_id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            // Validate the request
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'tgl_intervensi' => 'required|date',
                'jenis_intervensi' => 'required|string|max:100',
                'nama_kegiatan' => 'nullable|string|max:255',
                'omset' => 'nullable|numeric',
            ]);

            Log::info('Intervensi validation passed', ['validated_data' => $validated]);

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

            // Create new intervensi data
            $intervensi = new Intervensi([
                'umkm_id' => $request->umkm_id,
                'tgl_intervensi' => $request->tgl_intervensi,
                'jenis_intervensi' => $request->jenis_intervensi,
                'nama_kegiatan' => $request->nama_kegiatan,
                'omset' => $request->omset ?? 0,
                'status_intervensi' => 'PROSES',
            ]);

            // Save the intervensi
            $intervensi->save();
            Log::info('New intervensi data saved successfully', ['intervensi_id' => $intervensi->id]);

            return response()->json([
                'success' => true,
                'message' => 'Data intervensi berhasil disimpan',
                'data' => $intervensi
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for intervensi data', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving intervensi data', [
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
     * Update the specified intervensi in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateIntervensi(Request $request, string $id)
    {
        Log::info('Intervensi update request received', [
            'intervensi_id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            // Find the intervensi
            $intervensi = Intervensi::findOrFail($id);

            // Validate the request
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'tgl_intervensi' => 'required|date',
                'jenis_intervensi' => 'required|string|max:100',
                'nama_kegiatan' => 'nullable|string|max:255',
                'omset' => 'nullable|numeric',
            ]);

            Log::info('Intervensi update validation passed', ['validated_data' => $validated]);

            // Update the intervensi
            $intervensi->umkm_id = $request->umkm_id;
            $intervensi->tgl_intervensi = $request->tgl_intervensi;
            $intervensi->jenis_intervensi = $request->jenis_intervensi;
            $intervensi->nama_kegiatan = $request->nama_kegiatan;
            $intervensi->omset = $request->omset ?? 0;
            $intervensi->save();

            Log::info('Intervensi data updated successfully', ['intervensi_id' => $intervensi->id]);

            return response()->json([
                'success' => true,
                'message' => 'Data intervensi berhasil diperbarui',
                'data' => $intervensi
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Intervensi not found', [
                'intervensi_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data intervensi tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for intervensi update', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating intervensi data', [
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
     * Remove the specified intervensi from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteIntervensi(string $id)
    {
        Log::info('Intervensi delete request received', [
            'intervensi_id' => $id
        ]);

        try {
            // Find the intervensi
            $intervensi = Intervensi::findOrFail($id);

            // Delete the intervensi
            $intervensi->delete();

            Log::info('Intervensi data deleted successfully', ['intervensi_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Data intervensi berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Intervensi not found for deletion', [
                'intervensi_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data intervensi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting intervensi data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'intervensi_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
