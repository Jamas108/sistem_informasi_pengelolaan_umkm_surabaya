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
        $messages = [
            // Custom messages for User data
            'username.required' => 'Username wajib diisi',
            'username.string' => 'Username harus berupa teks',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',

            // Custom messages for Pelaku UMKM data
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter',
            'nik.required' => 'NIK wajib diisi',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits' => 'NIK harus berisi 16 angka',
            'nik.unique' => 'NIK sudah terdaftar',
            'no_kk.required' => 'Nomor KK wajib diisi',
            'no_kk.numeric' => 'Nomor KK harus berupa teks',
            'no_kk.digits' => 'Nomor KK harus 16 digit',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'jenis_kelamin.string' => 'Jenis kelamin harus berupa teks',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'kelurahan.required' => 'Kelurahan wajib diisi',
            'kelurahan.string' => 'Kelurahan harus berupa teks',
            'kelurahan.max' => 'Kelurahan maksimal 255 karakter',
            'rt.required' => 'RT wajib diisi',
            'rt.integer' => 'RT harus berupa angka',
            'rw.required' => 'RW wajib diisi',
            'rw.integer' => 'RW harus berupa angka',
            'alamat_sesuai_ktp.required' => 'Status alamat wajib diisi',
            'alamat_sesuai_ktp.string' => 'Status alamat harus berupa teks',
            'alamat_sesuai_ktp.in' => 'Status alamat harus Ya atau Tidak',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'no_telp.string' => 'Nomor telepon harus berupa teks',
            'no_telp.max' => 'Nomor telepon maksimal 15 digit',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi',
            'pendidikan_terakhir.string' => 'Pendidikan terakhir harus berupa teks',
            'pendidikan_terakhir.max' => 'Pendidikan terakhir maksimal 255 karakter',
        ];

        return Validator::make($data, [
            // Data Users
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Data Pelaku UMKM
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'numeric', 'digits:16', 'unique:pelaku_umkm,nik'],
            'no_kk' => ['required', 'numeric', 'digits:16'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'integer'],
            'rw' => ['required', 'integer'],
            'alamat_sesuai_ktp' => ['required', 'string', 'in:Ya,Tidak'],
            'no_telp' => ['required', 'string', 'max:15'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
        ], $messages);
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
                'NIK' => $data['nik'],
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
