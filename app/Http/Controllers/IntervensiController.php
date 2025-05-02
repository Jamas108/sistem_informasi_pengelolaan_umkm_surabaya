<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use App\Models\Umkm;
use App\Models\PelakuUmkm;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class IntervensiController extends Controller
{
    public function index()
    {
        $dataintervensis = Intervensi::with('kegiatan','dataUmkm')->get();
        return view('adminkantor.export.intervensi', compact('dataintervensis'));
    }

    /**
     * Get list of intervensi data for the specific Pelaku UMKM
     *
     * @param string $id The ID of the PelakuUmkm
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIntervensiList(string $id)
    {
        Log::info('Getting intervensi list', ['pelaku_id' => $id]);
        try {
            // Get all UMKMs associated with this Pelaku directly
            $umkmIds = Umkm::where('pelaku_umkm_id', $id)->pluck('id')->toArray();
            Log::info('Found associated UMKMs', ['pelaku_id' => $id, 'umkm_count' => count($umkmIds)]);

            // Get all intervensi data for these UMKMs with UMKM name and kegiatan details
            $intervensiData = Intervensi::whereIn('umkm_id', $umkmIds)
                ->with([
                    'dataUmkm:id,nama_usaha',
                    'kegiatan:id,nama_kegiatan,status_kegiatan,tanggal_mulai' // Include kegiatan details
                ])
                ->get();

            Log::info('Retrieved intervensi data', ['pelaku_id' => $id, 'intervensi_count' => $intervensiData->count()]);

            $mappedData = $intervensiData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'umkm_id' => $item->umkm_id,
                    'nama_usaha' => $item->dataUmkm->nama_usaha ?? 'Tidak ada',
                    'tgl_intervensi' => $item->tgl_intervensi,
                    'jenis_intervensi' => $item->jenis_intervensi,
                    'nama_kegiatan' => $item->kegiatan->nama_kegiatan ?? $item->nama_kegiatan ?? '-',
                    'no_pendaftaran_kegiatan' => $item->no_pendaftaran_kegiatan ?? '-',
                    'status_kegiatan' => $item->kegiatan->status_kegiatan ?? 'PROSES',
                    'tanggal_kegiatan' => $item->kegiatan->tanggal_mulai ?? $item->tgl_intervensi,
                    'omset' => $item->omset ?? 0,
                    'kegiatan_id' => $item->kegiatan_id ?? null
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
            // Find the intervensi data with eager loading of related models
            $intervensi = Intervensi::with([
                'dataUmkm:id,nama_usaha,sektor_usaha',
                'kegiatan' => function ($query) {
                    $query->select([
                        'id',
                        'nama_kegiatan',
                        'jenis_kegiatan',
                        'lokasi_kegiatan',
                        'tanggal_mulai',
                        'tanggal_selesai',
                        'jam_mulai',
                        'jam_selesai',
                        'status_kegiatan',
                        'kuota_pendaftaran'
                    ]);
                }
            ])->findOrFail($id);

            Log::info('Found intervensi data', ['intervensi_id' => $id, 'umkm_id' => $intervensi->umkm_id]);

            // Prepare a comprehensive response with all related data
            $responseData = [
                'id' => $intervensi->id,
                'umkm_id' => $intervensi->umkm_id,
                'kegiatan_id' => $intervensi->kegiatan_id,
                'no_pendaftaran_kegiatan' => $intervensi->no_pendaftaran_kegiatan,
                'omset' => $intervensi->omset,
                'dokumentasi_kegiatan' => $intervensi->dokumentasi_kegiatan,

                // UMKM details
                'umkm_nama_usaha' => $intervensi->dataUmkm->nama_usaha,
                'umkm_sektor_usaha' => $intervensi->dataUmkm->sektor_usaha,

                // Kegiatan details
                'kegiatan_nama' => $intervensi->kegiatan->nama_kegiatan,
                'kegiatan_jenis' => $intervensi->kegiatan->jenis_kegiatan,
                'kegiatan_lokasi' => $intervensi->kegiatan->lokasi_kegiatan,
                'kegiatan_tanggal_mulai' => $intervensi->kegiatan->tanggal_mulai,
                'kegiatan_tanggal_selesai' => $intervensi->kegiatan->tanggal_selesai,
                'kegiatan_jam_mulai' => $intervensi->kegiatan->jam_mulai,
                'kegiatan_jam_selesai' => $intervensi->kegiatan->jam_selesai,
                'kegiatan_status' => $intervensi->kegiatan->status_kegiatan,
                'kegiatan_kuota' => $intervensi->kegiatan->kuota_pendaftaran
            ];

            Log::info('Returning intervensi data response', ['intervensi_id' => $id]);
            return response()->json([
                'success' => true,
                'data' => $responseData
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
     * Get intervensi data for editing
     *
     * @param string $pelakuId The ID of the PelakuUmkm
     * @param string $intervensiId The ID of the intervensi to edit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIntervensiForEdit(string $pelakuId, string $intervensiId)
    {
        Log::info('Getting intervensi data for edit', [
            'pelaku_id' => $pelakuId,
            'intervensi_id' => $intervensiId
        ]);

        try {
            // Find the PelakuUmkm
            $pelakuUmkm = PelakuUmkm::findOrFail($pelakuId);

            // Get all UMKMs associated with this PelakuUmkm
            $umkms = Umkm::where('pelaku_umkm_id', $pelakuId)
                ->select('id', 'nama_usaha', 'sektor_usaha', 'jenis_produk')
                ->get();

            Log::info('Found UMKMs for pelaku', [
                'pelaku_id' => $pelakuId,
                'umkm_count' => $umkms->count(),
                'umkms' => $umkms->toArray()
            ]);

            // Get all kegiatans
            $kegiatans = Kegiatan::select([
                'id',
                'nama_kegiatan',
                'jenis_kegiatan',
                'lokasi_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'jam_mulai',
                'jam_selesai',
                'status_kegiatan'
            ])->get();

            Log::info('Found kegiatan data', [
                'kegiatan_count' => $kegiatans->count(),
                'kegiatan_names' => $kegiatans->pluck('nama_kegiatan', 'id')
            ]);

            // Find the intervensi with related data
            $intervensi = Intervensi::with([
                'dataUmkm',
                'kegiatan'
            ])->findOrFail($intervensiId);

            Log::info('Found intervensi data', [
                'intervensi_id' => $intervensiId,
                'umkm_id' => $intervensi->umkm_id,
                'kegiatan_id' => $intervensi->kegiatan_id,
                'no_pendaftaran' => $intervensi->no_pendaftaran_kegiatan
            ]);

            // Make sure this intervensi belongs to one of this PelakuUmkm's UMKMs
            $umkmIds = $umkms->pluck('id')->toArray();

            if (!in_array($intervensi->umkm_id, $umkmIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data intervensi tidak terkait dengan Pelaku UMKM ini'
                ], 403);
            }

            // Prepare response data
            $responseData = [
                'id' => $intervensi->id,
                'umkm_id' => $intervensi->umkm_id,
                'kegiatan_id' => $intervensi->kegiatan_id,
                'no_pendaftaran_kegiatan' => $intervensi->no_pendaftaran_kegiatan,
                'omset' => $intervensi->omset,

                // Additional data for convenience
                'nama_usaha' => $intervensi->dataUmkm->nama_usaha ?? null,
                'nama_kegiatan' => $intervensi->kegiatan->nama_kegiatan ?? null
            ];

            Log::info('Sending response for edit intervensi', [
                'umkm_count' => $umkms->count(),
                'kegiatan_count' => $kegiatans->count(),
                'response_data' => $responseData
            ]);

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'umkms' => $umkms,
                'kegiatans' => $kegiatans
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Model not found', [
                'exception' => $e->getMessage(),
                'pelaku_id' => $pelakuId,
                'intervensi_id' => $intervensiId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting intervensi data for edit', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pelaku_id' => $pelakuId,
                'intervensi_id' => $intervensiId
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
            // Validate the request - match fields from original saveIntervensi
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'kegiatan_id' => 'required|exists:kegiatan,id',
                'tgl_intervensi' => 'nullable|date',
                'jenis_intervensi' => 'nullable|string|max:100',
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

            // Get kegiatan data
            $kegiatan = Kegiatan::findOrFail($validated['kegiatan_id']);

            // Check if kegiatan status allows registration (only Pendaftaran status)
            if ($kegiatan->status_kegiatan !== 'Pendaftaran') {
                $errorMessage = "";

                if ($kegiatan->status_kegiatan === 'Belum Dimulai') {
                    $errorMessage = 'Maaf, pendaftaran untuk kegiatan ini belum dibuka.';
                } else if ($kegiatan->status_kegiatan === 'Sedang Berlangsung') {
                    $errorMessage = 'Maaf, kegiatan ini sedang berlangsung dan tidak menerima pendaftaran baru.';
                } else if ($kegiatan->status_kegiatan === 'Selesai') {
                    $errorMessage = 'Maaf, kegiatan ini telah selesai dan tidak menerima pendaftaran.';
                } else {
                    $errorMessage = 'Maaf, pendaftaran untuk kegiatan ini tidak tersedia.';
                }

                Log::warning('Kegiatan status invalid', [
                    'kegiatan_id' => $validated['kegiatan_id'],
                    'status' => $kegiatan->status_kegiatan
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }

            // Count existing interventions for this kegiatan
            $existingInterventions = Intervensi::where('kegiatan_id', $kegiatan->id)->count();

            // Check if kuota has been reached
            if ($existingInterventions >= $kegiatan->kuota_pendaftaran) {
                // Return error message about quota being full
                Log::warning('Kegiatan quota reached', [
                    'kegiatan_id' => $validated['kegiatan_id'],
                    'kuota' => $kegiatan->kuota_pendaftaran,
                    'existing' => $existingInterventions
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, kuota kegiatan sudah penuh. Tidak dapat mendaftar.'
                ], 422);
            }

            // Generate unique registration number using the same format as PelakuIntervensiController
            $registrationNumber = $this->generateUniqueRegistrationNumber(
                $validated['kegiatan_id'],
                $validated['umkm_id']
            );

            // Create a new Intervensi record
            $intervensi = new Intervensi();
            $intervensi->umkm_id = $validated['umkm_id'];
            $intervensi->kegiatan_id = $validated['kegiatan_id'];
            $intervensi->no_pendaftaran_kegiatan = $registrationNumber;

            // Save the intervensi
            $intervensi->save();
            Log::info('New intervensi data saved successfully', [
                'intervensi_id' => $intervensi->id,
                'registration_number' => $registrationNumber
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data intervensi berhasil disimpan',
                'data' => $intervensi,
                'registration_number' => $registrationNumber
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

    private function generateUniqueRegistrationNumber($kegiatanId, $umkmId)
    {
        // Get current year and month
        $now = Carbon::now();
        $year = $now->format('Y');
        $month = $now->format('m');

        // Get kegiatan code (first 3 letters of kegiatan name, uppercase)
        $kegiatan = Kegiatan::findOrFail($kegiatanId);
        $kegiatanCode = Str::upper(Str::substr(Str::slug($kegiatan->nama_kegiatan), 0, 3));

        // Get UMKM code (first 3 letters of UMKM name, uppercase)
        $umkm = Umkm::findOrFail($umkmId);
        $umkmCode = Str::upper(Str::substr(Str::slug($umkm->nama_usaha), 0, 3));

        // Find existing registrations count for this kegiatan
        $registrationCount = Intervensi::where('kegiatan_id', $kegiatanId)->count();
        $sequenceNumber = str_pad($registrationCount + 1, 3, '0', STR_PAD_LEFT);

        // Generate a random 2-digit number to ensure uniqueness
        $randomNum = str_pad(mt_rand(10, 99), 2, '0', STR_PAD_LEFT);

        // Format: REG/YEAR/MONTH/KEGIATAN_CODE/UMKM_CODE/SEQUENCE/RANDOM
        $registrationNumber = "REG/{$year}/{$month}/{$kegiatanCode}/{$umkmCode}/{$sequenceNumber}/{$randomNum}";

        // Ensure uniqueness by checking if this number already exists
        while (Intervensi::where('no_pendaftaran_kegiatan', $registrationNumber)->exists()) {
            // If exists, generate a new random number and try again
            $randomNum = str_pad(mt_rand(10, 99), 2, '0', STR_PAD_LEFT);
            $registrationNumber = "REG/{$year}/{$month}/{$kegiatanCode}/{$umkmCode}/{$sequenceNumber}/{$randomNum}";
        }

        return $registrationNumber;
    }

    /**
     * Update the specified intervensi in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $pelakuId
     * @param  string  $intervensiId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateIntervensi(Request $request, string $pelakuId, string $intervensiId)
    {
        Log::info('Intervensi update request received', [
            'pelaku_id' => $pelakuId,
            'intervensi_id' => $intervensiId,
            'request_data' => $request->all()
        ]);

        try {
            // Find the PelakuUmkm
            $pelakuUmkm = PelakuUmkm::findOrFail($pelakuId);

            // Find the intervensi
            $intervensi = Intervensi::findOrFail($intervensiId);

            // Validate the request
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'kegiatan_id' => 'required|exists:kegiatan,id',
                'omset' => 'nullable',
            ]);

            // Make sure the UMKM belongs to this PelakuUmkm
            $umkmBelongsToPelaku = Umkm::where('id', $request->umkm_id)
                ->where('pelaku_umkm_id', $pelakuId)
                ->exists();

            if (!$umkmBelongsToPelaku) {
                return response()->json([
                    'success' => false,
                    'message' => 'UMKM tidak terkait dengan Pelaku UMKM ini'
                ], 403);
            }

            Log::info('Intervensi update validation passed', ['validated_data' => $validated]);

            // Update the intervensi
            $intervensi->umkm_id = $request->umkm_id;
            $intervensi->kegiatan_id = $request->kegiatan_id;

            // Handle omset value (might be formatted with currency symbols)
            if ($request->has('omset')) {
                $omsetValue = $request->omset;
                if (is_string($omsetValue)) {
                    // Remove any non-numeric characters (currency formatting)
                    $omsetValue = preg_replace('/[^0-9]/', '', $omsetValue);
                }
                $intervensi->omset = empty($omsetValue) ? null : (float)$omsetValue;
            }

            $intervensi->save();

            Log::info('Intervensi data updated successfully', ['intervensi_id' => $intervensi->id]);

            return response()->json([
                'success' => true,
                'message' => 'Data intervensi berhasil diperbarui',
                'data' => $intervensi
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Model not found in update', [
                'exception' => $e->getMessage(),
                'pelaku_id' => $pelakuId,
                'intervensi_id' => $intervensiId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
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
