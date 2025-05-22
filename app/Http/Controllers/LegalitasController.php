<?php

namespace App\Http\Controllers;

use App\Models\Legalitas;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LegalitasController extends Controller
{
    /**
     * Get list of legalitas data for a specific Pelaku UMKM.
     *
     * @param string $id The ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLegalitasList(string $id)
    {
        Log::info('Getting legalitas list', ['pelaku_id' => $id]);
        try {
            // Get all UMKMs associated with this Pelaku directly
            $umkmIds = Umkm::where('pelaku_umkm_id', $id)->pluck('id')->toArray();
            Log::info('Found associated UMKMs', ['pelaku_id' => $id, 'umkm_count' => count($umkmIds)]);

            // Get all legalitas data for these UMKMs with UMKM name
            $legalitasData = Legalitas::whereIn('umkm_id', $umkmIds)
                ->with('dataUmkm:id,nama_usaha')
                ->get();

            Log::info('Retrieved legalitas data', ['pelaku_id' => $id, 'legalitas_count' => $legalitasData->count()]);

            $mappedData = $legalitasData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'umkm_id' => $item->umkm_id,
                    'nama_usaha' => $item->dataUmkm->nama_usaha ?? 'Tidak ada',
                    'no_sk_nib' => $item->no_sk_nib,
                    'no_sk_siup' => $item->no_sk_siup,
                    'no_sk_tdp' => $item->no_sk_tdp,
                    'no_sk_pirt' => $item->no_sk_pirt,
                    'no_sk_bpom' => $item->no_sk_bpom,
                    'no_sk_halal' => $item->no_sk_halal,
                    'no_sk_merek' => $item->no_sk_merek,
                    'no_sk_haki' => $item->no_sk_haki,
                    'no_surat_keterangan' => $item->no_surat_keterangan,
                ];
            });

            Log::info('Returning legalitas list response', ['pelaku_id' => $id, 'data_count' => $mappedData->count()]);
            return response()->json([
                'success' => true,
                'data' => $mappedData
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting legalitas list', [
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
     * Get specific legalitas data
     *
     * @param string $id The ID of the Legalitas
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLegalitas(string $id)
    {
        Log::info('Getting legalitas details', ['legalitas_id' => $id]);
        try {
            // Find the legalitas data
            $legalitas = Legalitas::findOrFail($id);
            Log::info('Found legalitas data', ['legalitas_id' => $id, 'umkm_id' => $legalitas->umkm_id]);

            Log::info('Returning legalitas data response', ['legalitas_id' => $id]);
            return response()->json([
                'success' => true,
                'data' => $legalitas
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting legalitas data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'legalitas_id' => $id,
                'exception_class' => get_class($e)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save new legalitas data.
     *
     * @param Request $request
     * @param string $id ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveLegalitas(Request $request, string $id)
    {
        Log::info('Legalitas save request received', [
            'pelaku_id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            // Validasi dasar
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
            ]);

            // Validasi urutan pengisian field legalitas
            $errorMessage = $this->validateSequentialFields($request->all());
            if ($errorMessage) {
                Log::warning('Sequential validation failed', ['message' => $errorMessage]);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }

            // Proses simpan data seperti sebelumnya...

            // Cek UMKM
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

            // Cek legalitas sudah ada
            $existingLegalitas = Legalitas::where('umkm_id', $request->umkm_id)->first();

            if ($existingLegalitas) {
                // Update data legalitas
                $existingLegalitas->no_sk_nib = $request->no_sk_nib;
                $existingLegalitas->no_sk_siup = $request->no_sk_siup;
                $existingLegalitas->no_sk_tdp = $request->no_sk_tdp;
                $existingLegalitas->no_sk_pirt = $request->no_sk_pirt;
                $existingLegalitas->no_sk_bpom = $request->no_sk_bpom;
                $existingLegalitas->no_sk_halal = $request->no_sk_halal;
                $existingLegalitas->no_sk_merek = $request->no_sk_merek;
                $existingLegalitas->no_sk_haki = $request->no_sk_haki;
                $existingLegalitas->no_surat_keterangan = $request->no_surat_keterangan;
                $existingLegalitas->save();

                Log::info('Existing legalitas data updated successfully', ['legalitas_id' => $existingLegalitas->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data legalitas berhasil diperbarui',
                    'data' => $existingLegalitas
                ]);
            } else {
                // Create new legalitas
                $legalitas = new Legalitas([
                    'umkm_id' => $request->umkm_id,
                    'no_sk_nib' => $request->no_sk_nib,
                    'no_sk_siup' => $request->no_sk_siup,
                    'no_sk_tdp' => $request->no_sk_tdp,
                    'no_sk_pirt' => $request->no_sk_pirt,
                    'no_sk_bpom' => $request->no_sk_bpom,
                    'no_sk_halal' => $request->no_sk_halal,
                    'no_sk_merek' => $request->no_sk_merek,
                    'no_sk_haki' => $request->no_sk_haki,
                    'no_surat_keterangan' => $request->no_surat_keterangan,
                ]);

                $legalitas->save();
                Log::info('New legalitas data saved successfully', ['legalitas_id' => $legalitas->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data legalitas berhasil disimpan',
                    'data' => $legalitas
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for legalitas data', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving legalitas data', [
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
     * Update the specified legalitas in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLegalitas(Request $request, string $id)
{
    Log::info('Legalitas update request received', [
        'legalitas_id' => $id,
        'request_data' => $request->all()
    ]);

    try {
        // Cari data legalitas dulu
        $legalitas = Legalitas::findOrFail($id);

        // Validasi dasar
        $validated = $request->validate([
            'umkm_id' => 'required|exists:umkm,id',
        ]);

        // Validasi urutan pengisian field legalitas
        $errorMessage = $this->validateSequentialFields($request->all());
        if ($errorMessage) {
            Log::warning('Sequential validation failed', ['message' => $errorMessage]);
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 422);
        }

        // Update data legalitas
        $legalitas->umkm_id = $request->umkm_id;
        $legalitas->no_sk_nib = $request->no_sk_nib;
        $legalitas->no_sk_siup = $request->no_sk_siup;
        $legalitas->no_sk_tdp = $request->no_sk_tdp;
        $legalitas->no_sk_pirt = $request->no_sk_pirt;
        $legalitas->no_sk_bpom = $request->no_sk_bpom;
        $legalitas->no_sk_halal = $request->no_sk_halal;
        $legalitas->no_sk_merek = $request->no_sk_merek;
        $legalitas->no_sk_haki = $request->no_sk_haki;
        $legalitas->no_surat_keterangan = $request->no_surat_keterangan;
        $legalitas->save();

        Log::info('Legalitas data updated successfully', ['legalitas_id' => $legalitas->id]);

        return response()->json([
            'success' => true,
            'message' => 'Data legalitas berhasil diperbarui',
            'data' => $legalitas
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Legalitas not found', [
            'legalitas_id' => $id
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Data legalitas tidak ditemukan'
        ], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed for legalitas update', [
            'errors' => $e->errors(),
            'request' => $request->all()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error updating legalitas data', [
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
     * Remove the specified legalitas from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLegalitas(string $id)
    {
        Log::info('Legalitas delete request received', [
            'legalitas_id' => $id
        ]);

        try {
            // Find the legalitas
            $legalitas = Legalitas::findOrFail($id);

            // Delete the legalitas
            $legalitas->delete();

            Log::info('Legalitas data deleted successfully', ['legalitas_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Data legalitas berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Legalitas not found for deletion', [
                'legalitas_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data legalitas tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting legalitas data', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'legalitas_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    private function validateSequentialFields(array $data): ?string
    {
        $fields = [
            'no_surat_keterangan',
            'no_sk_nib',
            'no_sk_siup',
            'no_sk_tdp',
            'no_sk_pirt',
            'no_sk_bpom',
            'no_sk_halal',
            'no_sk_merek',
            'no_sk_haki',
        ];

        foreach ($fields as $index => $field) {
            if (!empty($data[$field])) {
                // Cek semua field sebelumnya sudah terisi
                for ($i = 0; $i < $index; $i++) {
                    if (empty($data[$fields[$i]])) {
                        return "Data {$field} tidak boleh diisi sebelum data {$fields[$i]} diisi terlebih dahulu.";
                    }
                }
            }
        }
        return null; // Validasi berhasil
    }
}
