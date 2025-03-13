<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::redirect('/', '/login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route 3 Role Untuk Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin-kantor', [DashboardController::class, 'index'])->name('dashboard.admin_kantor');
    Route::get('/dashboard/admin-lapangan', [DashboardController::class, 'index'])->name('dashboard.admin_lapangan');
    Route::get('/dashboard/pelaku-umkm', [DashboardController::class, 'index'])->name('dashboard.pelaku_umkm');
});
