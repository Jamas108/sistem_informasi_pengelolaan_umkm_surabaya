<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Override default redirect setelah login.
     */
    protected function redirectPath()
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

    /**
     * Buat instance controller baru.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override default Laravel login untuk menggunakan username alih-alih email.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Kustomisasi fungsi login agar menggunakan username dan password.
     */
    public function login(Request $request)
    {
        // Validasi input dari form login
        $request->validate([
            'nik' => 'required|string|exists:users,nik', // Pastikan username ada di tabel users
            'password' => 'required|string',
        ], [
            'username.exists' => 'Username tidak ditemukan, silakan periksa kembali.',
        ]);

        // Data login
        $credentials = [
            'nik' => $request->nik,
            'password' => $request->password,
        ];

        // Coba login dengan kredensial yang diberikan
        if (Auth::attempt($credentials, $request->remember)) {
            return redirect()->intended($this->redirectPath()); // Arahkan sesuai peran
        }

        // Jika login gagal, kembali ke halaman login dengan error
        return back()->withErrors([
            'password' => 'Password salah, silakan coba lagi.',
        ])->withInput($request->only('username', 'remember'));
    }

    public function loginadmin(Request $request)
    {
        return view('auth.admin.login');
    }

    public function adminprocesslogin (Request $request)
    {
        // Validasi input dari form login
        $request->validate([
            'username' => 'required|string|exists:users,username', // Pastikan username ada di tabel users
            'password' => 'required|string',
        ], [
            'username.exists' => 'Username tidak ditemukan, silakan periksa kembali.',
        ]);

        // Data login
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Coba login dengan kredensial yang diberikan
        if (Auth::attempt($credentials, $request->remember)) {
            return redirect()->intended($this->redirectPath()); // Arahkan sesuai peran
        }

        // Jika login gagal, kembali ke halaman login dengan error
        return back()->withErrors([
            'password' => 'Password salah, silakan coba lagi.',
        ])->withInput($request->only('username', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah berhasil logout.');
    }
}
