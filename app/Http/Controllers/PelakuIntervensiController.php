<?php

namespace App\Http\Controllers;

use App\Models\Intervensi;
use App\Models\Kegiatan;
use App\Models\Umkm;
use App\Models\PelakuUmkm;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PelakuIntervensiController extends Controller
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
            return view('pelakuumkm.kelolaintervensi.index', [
                'dataintervensi' => collect(), // Empty collection
                'pageTitle' => 'Kelola Intervensi'
            ]);
        }

        // Get the UMKM IDs for this PelakuUmkm
        $umkmIds = $pelakuUmkm->dataUmkm->pluck('id');

        // Retrieve Intervensi data for the specific UMKM
        $dataintervensi = Intervensi::whereIn('umkm_id', $umkmIds)
            ->with(['dataUmkm', 'kegiatan']) // Eager load the related UMKM and Kegiatan
            ->get();

        return view('pelakuumkm.kelolaintervensi.index', [
            'dataintervensi' => $dataintervensi,
            'pageTitle' => 'Kelola Intervensi'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // Get the UMKM for this PelakuUmkm
        $umkms = $pelakuUmkm->dataUmkm;

        // Fetch all available kegiatan with status info
        $kegiatans = Kegiatan::all();

        return view('pelakuumkm.kelolaintervensi.create', [
            'umkms' => $umkms,
            'kegiatans' => $kegiatans,
            'pageTitle' => 'Tambah Intervensi'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'umkm_id' => 'required|exists:umkm,id',
            'kegiatan_id' => 'required|exists:kegiatan,id',
        ]);

        try {
            // Check kuota (quota) for the selected kegiatan
            $kegiatan = Kegiatan::findOrFail($validatedData['kegiatan_id']);

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

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }

            // Count existing interventions for this kegiatan
            $existingInterventions = Intervensi::where('kegiatan_id', $kegiatan->id)->count();

            // Check if kuota has been reached
            if ($existingInterventions >= $kegiatan->kuota_pendaftaran) {
                // Redirect back with error message about quota being full
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maaf, kuota kegiatan sudah penuh. Tidak dapat mendaftar.');
            }

            // Generate unique registration number
            $registrationNumber = $this->generateUniqueRegistrationNumber(
                $validatedData['kegiatan_id'],
                $validatedData['umkm_id']
            );

            // Create a new Intervensi record
            $intervensi = new Intervensi();
            $intervensi->umkm_id = $validatedData['umkm_id'];
            $intervensi->kegiatan_id = $validatedData['kegiatan_id'];
            $intervensi->no_pendaftaran_kegiatan = $registrationNumber;
            $intervensi->save();

            // Redirect with success message
            return redirect()->route('pelakukelolaintervensi.index')
                ->with('success', 'Intervensi berhasil ditambahkan dengan nomor pendaftaran: ' . $registrationNumber);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating intervention: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan intervensi. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // Get the UMKM for this PelakuUmkm
        $umkms = $pelakuUmkm->dataUmkm;
        $kegiatans = Kegiatan::all();

        // Find the specific intervention with kegiatan relation
        $intervensi = Intervensi::with(['kegiatan', 'dataUmkm'])->findOrFail($id);

        // Ensure the user can only view their own UMKM's interventions
        if (!$umkms->contains('id', $intervensi->umkm_id)) {
            abort(403, 'Unauthorized access');
        }

        // Debugging: Cek semua informasi terkait bukti pendaftaran
        \Log::info('Debug Bukti Pendaftaran', [
            'kegiatan_id' => $intervensi->kegiatan->id,
            'bukti_pendaftaran_path' => $intervensi->kegiatan->bukti_pendaftaran_path,
            'storage_path' => storage_path('app/public/' . $intervensi->kegiatan->bukti_pendaftaran_path)
        ]);

        // Ambil bukti pendaftaran untuk UMKM ini
        $buktiPendaftaranPath = null;
        if ($intervensi->kegiatan->bukti_pendaftaran_path) {
            // Coba beberapa variasi nama file
            $fileNameVariations = [
                Str::slug($intervensi->dataUmkm->nama_usaha . '-bukti-pendaftaran') . '.pdf',
                'bukti_pendaftaran_' . Str::slug($intervensi->dataUmkm->nama_usaha) . '.pdf'
            ];

            foreach ($fileNameVariations as $fileName) {
                $fullPath = $intervensi->kegiatan->bukti_pendaftaran_path . '/' . $fileName;

                // Cek menggunakan Storage
                if (Storage::disk('public')->exists($fullPath)) {
                    $buktiPendaftaranPath = $fullPath;
                    break;
                }

                // Cek menggunakan file_exists untuk debugging
                $absolutePath = storage_path('app/public/' . $fullPath);
                if (file_exists($absolutePath)) {
                    $buktiPendaftaranPath = $fullPath;
                    Log::info('File ditemukan dengan file_exists', [
                        'path' => $absolutePath
                    ]);
                    break;
                }
            }

            // Log hasil pencarian
            if ($buktiPendaftaranPath) {
                Log::info('Bukti Pendaftaran Ditemukan', [
                    'path' => $buktiPendaftaranPath
                ]);
            } else {
                Log::warning('Bukti Pendaftaran Tidak Ditemukan', [
                    'kegiatan_id' => $intervensi->kegiatan->id,
                    'umkm_nama' => $intervensi->dataUmkm->nama_usaha,
                    'bukti_pendaftaran_path' => $intervensi->kegiatan->bukti_pendaftaran_path
                ]);
            }
        }

        return view('pelakuumkm.kelolaintervensi.show', [
            'umkms' => $umkms,
            'kegiatans' => $kegiatans,
            'intervensi' => $intervensi,
            'buktiPendaftaranPath' => $buktiPendaftaranPath,
            'pageTitle' => 'Detail Intervensi'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        // Get the UMKM for this PelakuUmkm
        $umkms = $pelakuUmkm->dataUmkm;

        // Fetch all available kegiatan
        $kegiatans = Kegiatan::all();

        // Find the specific intervention with kegiatan relation
        $intervensi = Intervensi::with('kegiatan')->findOrFail($id);

        // Ensure the user can only edit their own UMKM's interventions
        if (!$umkms->contains('id', $intervensi->umkm_id)) {
            abort(403, 'Unauthorized access');
        }

        return view('pelakuumkm.kelolaintervensi.edit', [
            'umkms' => $umkms,
            'kegiatans' => $kegiatans,
            'intervensi' => $intervensi,
            'pageTitle' => 'Edit Intervensi'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the specific intervention with kegiatan relation
        $intervensi = Intervensi::with('kegiatan')->findOrFail($id);

        // Get status from related kegiatan
        $status = $intervensi->kegiatan->status_kegiatan;

        // Set up validation rules based on status
        $validationRules = [];

        if ($status === 'Pendaftaran') {
            // During Pendaftaran, can change UMKM and kegiatan
            $validationRules['umkm_id'] = 'required|exists:umkm,id';
            $validationRules['kegiatan_id'] = 'required|exists:kegiatan,id';
        } elseif ($status === 'Sedang Berlangsung') {
            // During Sedang Berlangsung, can only upload dokumentasi
            // No need to validate umkm_id as it will be preserved from existing data
            $validationRules['dokumentasi_kegiatan.*'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120'; // 5MB max
        } elseif ($status === 'Selesai') {
            // During Selesai, can set omset and upload dokumentasi
            // No need to validate umkm_id as it will be preserved from existing data
            $validationRules['omset'] = 'required|numeric|min:0';
            $validationRules['dokumentasi_kegiatan.*'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120'; // 5MB max
        }

        // Validate the request with only the relevant rules
        $validatedData = $request->validate($validationRules);

        try {
            // Prepare update data - start with an empty array
            $updateData = [];

            // Handle different status updates
            if ($status === 'Pendaftaran') {
                // During Pendaftaran, update UMKM and kegiatan
                $updateData['umkm_id'] = $validatedData['umkm_id'];
                $updateData['kegiatan_id'] = $validatedData['kegiatan_id'];
            } elseif ($status === 'Selesai') {
                // During Selesai, update omset
                $updateData['omset'] = $validatedData['omset'];
            }

            // Handle dokumentasi upload for both Sedang Berlangsung and Selesai status
            if (($status === 'Sedang Berlangsung' || $status === 'Selesai') && $request->hasFile('dokumentasi_kegiatan')) {
                // Handle existing dokumentasi - safely get as array no matter the storage format
                if (!empty($intervensi->dokumentasi_kegiatan)) {
                    // Safe handling of dokumentasi_kegiatan, checking if it's already an array
                    $existingDokumentasis = is_array($intervensi->dokumentasi_kegiatan)
                        ? $intervensi->dokumentasi_kegiatan
                        : (is_string($intervensi->dokumentasi_kegiatan)
                            ? json_decode($intervensi->dokumentasi_kegiatan, true)
                            : []);

                    // Make sure $existingDokumentasis is an array
                    if (!is_array($existingDokumentasis)) {
                        $existingDokumentasis = [$existingDokumentasis];
                    }

                    // Delete existing dokumentasi files
                    foreach ($existingDokumentasis as $existingDok) {
                        if (!empty($existingDok) && is_string($existingDok)) {
                            Storage::disk('public')->delete($existingDok);
                        }
                    }
                }

                // Store the new file
                $file = $request->file('dokumentasi_kegiatan')[0];
                $path = $file->store('dokumentasi_intervensi', 'public');

                // Add to update data - store as an array with a single path
                $updateData['dokumentasi_kegiatan'] = [$path];
            }

            // Update the intervention only if there's data to update
            if (!empty($updateData)) {
                $intervensi->update($updateData);
            }

            // Redirect with success message
            return redirect()->route('pelakukelolaintervensi.index')
                ->with('success', 'Intervensi berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating intervention: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui intervensi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
