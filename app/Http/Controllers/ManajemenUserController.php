<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PelakuUmkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManajemenUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Manajemen User';
        $datauser = User::whereIn('role', ['adminkantor', 'adminlapangan'])->get();
        return view('adminkantor.manajemen_user.index', compact('datauser', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $role = $request->query('role');
        if (!in_array($role, ['adminkantor', 'adminlapangan'])) {
            return redirect()->route('manajemenuser.index')->with('error', 'Role tidak valid');
        }

        $pageTitle = 'Tambah ' . ucfirst($role);
        return view('adminkantor.manajemen_user.create', compact('role', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:16', 'unique:pelaku_umkm,nik'],
            'no_kk' => ['required', 'string', 'max:16'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'status_hubungan_keluarga' => ['required', 'string', 'max:255'],
            'status_perkawinan' => ['required', 'string', 'max:255'],
            'alamat_sesuai_ktp' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'integer'],
            'rw' => ['required', 'integer'],
            'no_telp' => ['required', 'string', 'max:15'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:adminkantor,adminlapangan'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                // Simpan ke tabel users
                $user = User::create([
                    'username' => $request->username,
                    'nik' => $request->nik,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                // Simpan ke tabel pelaku_umkm dengan FK users_id
                PelakuUmkm::create([
                    'users_id' => $user->id,
                    'nama_lengkap' => $request->nama_lengkap,
                    'nik' => $request->nik,
                    'no_kk' => $request->no_kk,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'status_hubungan_keluarga' => $request->status_hubungan_keluarga,
                    'status_perkawinan' => $request->status_perkawinan,
                    'alamat_sesuai_ktp' => $request->alamat_sesuai_ktp,
                    'kelurahan' => $request->kelurahan,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'no_telp' => $request->no_telp,
                    'pendidikan_terakhir' => $request->pendidikan_terakhir,
                ]);
            });

            return redirect()
                ->route('manajemenuser.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Detail User';
        $user = User::findOrFail($id);
        $detailUser = PelakuUmkm::where('users_id', $id)->first();

        if (!$detailUser) {
            return redirect()->route('manajemenuser.index')->with('error', 'Data detail user tidak ditemukan');
        }

        return view('adminkantor.manajemen_user.show', compact('user', 'detailUser', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit User';
        $user = User::findOrFail($id);
        $detailUser = PelakuUmkm::where('users_id', $id)->first();

        if (!$detailUser) {
            return redirect()->route('manajemenuser.index')->with('error', 'Data detail user tidak ditemukan');
        }

        return view('adminkantor.manajemen_user.edit', compact('user', 'detailUser', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $detailUser = PelakuUmkm::where('users_id', $id)->first();

        if (!$detailUser) {
            return redirect()->route('manajemenuser.index')->with('error', 'Data detail user tidak ditemukan');
        }

        // Validasi data
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:16', Rule::unique('pelaku_umkm')->ignore($detailUser->id)],
            'no_kk' => ['required', 'string', 'max:16'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'status_hubungan_keluarga' => ['required', 'string', 'max:255'],
            'status_perkawinan' => ['required', 'string', 'max:255'],
            'alamat_sesuai_ktp' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'integer'],
            'rw' => ['required', 'integer'],
            'no_telp' => ['required', 'string', 'max:15'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:adminkantor,adminlapangan'],
        ]);

        if ($request->filled('password')) {
            $validator->addRules([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
        }

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $user, $detailUser) {
                // Update tabel users
                $user->username = $request->username;
                $user->NIK = $request->nik;
                $user->role = $request->role;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();

                // Update tabel pelaku_umkm
                $detailUser->nama_lengkap = $request->nama_lengkap;
                $detailUser->nik = $request->nik;
                $detailUser->no_kk = $request->no_kk;
                $detailUser->tempat_lahir = $request->tempat_lahir;
                $detailUser->tgl_lahir = $request->tgl_lahir;
                $detailUser->jenis_kelamin = $request->jenis_kelamin;
                $detailUser->status_hubungan_keluarga = $request->status_hubungan_keluarga;
                $detailUser->status_perkawinan = $request->status_perkawinan;
                $detailUser->kelurahan = $request->kelurahan;
                $detailUser->rt = $request->rt;
                $detailUser->rw = $request->rw;
                $detailUser->alamat_sesuai_ktp = $request->alamat_sesuai_ktp;
                $detailUser->no_telp = $request->no_telp;
                $detailUser->pendidikan_terakhir = $request->pendidikan_terakhir;
                $detailUser->save();
            });

            return redirect()
                ->route('manajemenuser.index')
                ->with('success', 'Data user berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $user = User::findOrFail($id);

                // Hapus data dari tabel pelaku_umkm
                PelakuUmkm::where('users_id', $id)->delete();

                // Hapus data dari tabel users
                $user->delete();
            });

            return redirect()
                ->route('manajemenuser.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->route('manajemenuser.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}