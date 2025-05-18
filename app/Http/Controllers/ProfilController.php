<?php

namespace App\Http\Controllers;

use App\Models\PelakuUmkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the currently logged in user's ID
        $userId = Auth::id();

        // Get the pelaku UMKM data for this user
        $pelakuUmkm = PelakuUmkm::where('users_id', $userId)->firstOrFail();

        return view('pelakuumkm.profil', compact('pelakuUmkm'));
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
        //
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
    public function update(Request $request, string $id)
    { // Validate the request data
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'no_kk' => 'required|string|max:16',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'status_hubungan_keluarga' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'alamat_sesuai_ktp' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'pendidikan_terakhir' => 'required|string|max:255',
        ]);

        try {
            // Get the pelaku UMKM record
            $pelakuUmkm = PelakuUmkm::findOrFail($id);

            // Make sure the user can only edit their own profile
            if ($pelakuUmkm->users_id != Auth::id()) {
                return redirect()->route('profil.index')
                    ->with('error', 'Anda tidak memiliki izin untuk mengedit profil ini.');
            }

            // Update the record
            $pelakuUmkm->update($validated);

            return redirect()->route('profil.index')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('profil.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        // Validasi request
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Periksa password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai!');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
