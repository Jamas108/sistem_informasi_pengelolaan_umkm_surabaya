<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PelakuUmkm;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected function redirectTo()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'adminkantor':
                return '/dashboard/admin-kantor';
            case 'adminlapangan':
                return '/dashboard/admin-lapangan';
            case 'pelakuumkm':
                return '/dashboard/pelaku-umkm';
            default:
                return '/home'; // Default jika role tidak dikenali
        }
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validasi input sebelum menyimpan ke database.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Data Users
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Data Pelaku UMKM
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:16', 'unique:pelaku_umkm,nik'],
            'no_kk' => ['required', 'string', 'max:16'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'status_hubungan_keluarga' => ['required', 'string', 'max:255'],
            'status_perkawinan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'integer'],
            'rw' => ['required', 'integer'],
            'alamat_sesuai_ktp' => ['required', 'string', 'in:Ya,Tidak'],
            'no_telp' => ['required', 'string', 'max:15'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Menyimpan data User & Pelaku UMKM dalam satu transaksi.
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Simpan ke tabel users
            $user = User::create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => 'pelakuumkm', // Role default sebagai user
            ]);

            // Simpan ke tabel pelaku_umkm dengan FK users_id
            PelakuUmkm::create([
                'users_id' => $user->id,
                'nama_lengkap' => $data['nama_lengkap'],
                'nik' => $data['nik'],
                'no_kk' => $data['no_kk'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tgl_lahir' => $data['tgl_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'status_hubungan_keluarga' => $data['status_hubungan_keluarga'],
                'status_perkawinan' => $data['status_perkawinan'],
                'alamat' => $data['alamat'],
                'kelurahan' => $data['kelurahan'],
                'rt' => $data['rt'],
                'rw' => $data['rw'],
                'alamat_sesuai_ktp' => $data['alamat_sesuai_ktp'],
                'no_telp' => $data['no_telp'],
                'pendidikan_terakhir' => $data['pendidikan_terakhir'],
            ]);

            return $user;
        });
    }
}
