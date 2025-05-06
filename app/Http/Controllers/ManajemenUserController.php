<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PelakuUmkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $user = User::whereIn('role', ['adminkantor', 'adminlapangan'])->get();
        return view('adminkantor.manajemen_user.index', compact('user', 'pageTitle'));
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
        ]);

        if ($validator->fails()) {
            // Log kegagalan validasi
            Log::warning('Validasi gagal saat menambah user: ', [
                'username' => $request->username,
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);

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

                // Log berhasil tambah user
                Log::info('User berhasil ditambahkan', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role
                ]);
            });

            return redirect()
                ->route('manajemenuser.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            // Log kegagalan dengan detil error
            Log::error('Gagal menambahkan user: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);

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

        return view('adminkantor.manajemen_user.show', compact('user', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit User';
        $user = User::findOrFail($id);



        return view('adminkantor.manajemen_user.edit', compact('user', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validasi data
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
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
            DB::transaction(function () use ($request, $user) {
                // Update tabel users
                $user->username = $request->username;
                $user->NIK = $request->nik;
                $user->role = $request->role;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
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
