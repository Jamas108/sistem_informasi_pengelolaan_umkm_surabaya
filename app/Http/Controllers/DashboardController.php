<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang sedang login

        // Cek peran pengguna dan arahkan ke dashboard yang sesuai
        switch ($user->role) {
            case 'adminkantor':
                return view('adminkantor.dashboard');
            case 'adminlapangan':
                return view('adminkantor.dashboard');
            case 'pelakuumkm':
                return view('pelaku.dashboard');
            default:
                return abort(403, 'Akses ditolak'); // Jika peran tidak dikenal, tolak akses
        }
    }
}
