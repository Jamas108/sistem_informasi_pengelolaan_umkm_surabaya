<?php

use App\Http\Controllers\AdminLapDataUmkmController;
use App\Http\Controllers\AdminLapKegiatanController;
use App\Http\Controllers\AdminLapPesertaPendaftaranController;
use App\Http\Controllers\ApprovalUMKMController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataUmkmController;
use App\Http\Controllers\IntervensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\OmsetController;
use App\Http\Controllers\PelakuIntervensiController;
use App\Http\Controllers\PelakuKegiatanController;
use App\Http\Controllers\PelakuKelolaUmkmController;
use App\Http\Controllers\PesertaPendaftaranController;
use App\Http\Controllers\ProfilController;
use App\Models\Intervensi;
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


// ROUTE REDIRECT DASHBOARD BERDASARKAN ROLE
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin-kantor', [DashboardController::class, 'index'])->name('dashboard.admin_kantor');
    Route::get('/dashboard/admin-lapangan', [DashboardController::class, 'index'])->name('dashboard.admin_lapangan');
    Route::get('/dashboard/pelaku-umkm', [DashboardController::class, 'index'])->name('dashboard.pelaku_umkm');
});

// ----- ROUTE UNTUK MANAJEMEN UMKM ADMIN KANTOR DAN ADMIN LAPANGAN ------ //
// CRUD UMKM //
Route::resource('dataumkm', DataUmkmController::class);
Route::post('/check-nik', [App\Http\Controllers\DataUmkmController::class, 'checkNik'])->name('check.nik');

// EDIT UMKM > TAB OMSET //
Route::post('/dataumkm/omset/save/{id}', [OmsetController::class, 'saveOmset']);
Route::get('/dataumkm/omset/list/{id}', 'App\Http\Controllers\OmsetController@getOmsetList');
Route::get('/dataumkm/omset/{id}', 'App\Http\Controllers\OmsetController@getOmset');
Route::put('/dataumkm/omset/{id}', 'App\Http\Controllers\OmsetController@updateOmset');
Route::delete('/dataumkm/omset/{id}', 'App\Http\Controllers\OmsetController@deleteOmset');
// EDIT UMKM > TAB LEGALITAS //
Route::get('/dataumkm/legalitas/list/{id}', [App\Http\Controllers\LegalitasController::class, 'getLegalitasList']);
Route::get('/dataumkm/legalitas/{id}', [App\Http\Controllers\LegalitasController::class, 'getLegalitas']);
Route::post('/dataumkm/legalitas/save/{id}', [App\Http\Controllers\LegalitasController::class, 'saveLegalitas']);
Route::put('/dataumkm/legalitas/{id}', [App\Http\Controllers\LegalitasController::class, 'updateLegalitas']);
Route::delete('/dataumkm/legalitas/{id}', [App\Http\Controllers\LegalitasController::class, 'deleteLegalitas']);
// EDIT UMKM > TAB INTERVENSI //
Route::get('/dataumkm/intervensi/list/{id}', [App\Http\Controllers\IntervensiController::class, 'getIntervensiList']);
Route::get('/dataumkm/intervensi/{id}', [App\Http\Controllers\IntervensiController::class, 'getIntervensi']);
Route::post('/dataumkm/intervensi/save/{id}', [App\Http\Controllers\IntervensiController::class, 'saveIntervensi']);
Route::put('/dataumkm/intervensi/{id}', [App\Http\Controllers\IntervensiController::class, 'updateIntervensi']);
Route::delete('/dataumkm/intervensi/{id}', [App\Http\Controllers\IntervensiController::class, 'deleteIntervensi']);
Route::get('/dataumkm/get-umkm-options/{pelakuId}', [DataUmkmController::class, 'getUmkmOptions'])
    ->name('dataumkm.umkm-options');
Route::get('/dataumkm/intervensi/edit/{pelakuId}/{intervensiId}', [IntervensiController::class, 'getIntervensiForEdit'])
    ->name('intervensi.edit');
// Route untuk menyimpan perubahan intervensi
Route::post('/dataumkm/intervensi/update/{pelakuId}/{intervensiId}', [IntervensiController::class, 'updateIntervensi'])
    ->name('intervensi.update');
// Route untuk menampilkan daftar intervensi
Route::get('/dataumkm/intervensi/list/{pelakuId}', [IntervensiController::class, 'getIntervensiList'])
    ->name('intervensi.list');
// Route untuk menyimpan intervensi baru
Route::post('/dataumkm/intervensi/save/{pelakuId}', [IntervensiController::class, 'saveIntervensi'])
    ->name('intervensi.save');
// Route untuk menghapus intervensi
Route::post('/dataumkm/intervensi/{intervensiId}', [IntervensiController::class, 'deleteIntervensi'])
    ->name('intervensi.delete')
    ->where('intervensiId', '[0-9]+');

// CRUD HALAMAN KELOLA KEGIATAN //
Route::resource('datakegiatan', KegiatanController::class);
Route::post('/datakegiatan/{id}/generate-bukti', [KegiatanController::class, 'generateBuktiPendaftaran'])
    ->name('datakegiatan.generate-bukti');
Route::get('datakegiatan/{id}/pendaftar', [PesertaPendaftaranController::class, 'index'])
    ->name('pendaftar.index');
Route::patch('pendaftar/{id}/update-status', [PesertaPendaftaranController::class, 'updateStatus'])
    ->name('pendaftar.updateStatus');
Route::get('/datakegiatan/{kegiatanId}/print-attendance', [PesertaPendaftaranController::class, 'printAttendance'])->name('datakegiatan.print-attendance');
Route::delete('/peserta/{intervensiId}', [PesertaPendaftaranController::class, 'destroy'])->name('peserta.destroy');
Route::post('/datakegiatan/{id}/generate-bukti', [KegiatanController::class, 'generateBuktiPendaftaran'])->name('datakegiatan.generate-bukti');

// APPROVAL UMKM //
Route::resource('approvalumkm', ApprovalUMKMController::class);
Route::put('/approval/{id}/approve', [App\Http\Controllers\ApprovalUMKMController::class, 'approve'])->name('approval.approve');
Route::put('/approval/{id}/reject', [App\Http\Controllers\ApprovalUMKMController::class, 'reject'])->name('approval.reject');

// EXPORT //
Route::get('exportintervensi', [IntervensiController::class, 'index'])
    ->name('exportintervensi.index');
Route::get('exportomset', [OmsetController::class, 'index'])
    ->name('exportomset.index');
Route::get('exportumkm', [DataUmkmController::class, 'ExportUmkmIndex'])
    ->name('exportumkm.index');

// ROUTE HALAMAN KELOLA USER //
Route::resource('manajemenuser', ManajemenUserController::class);





// ----- ROUTE UNTUK PELAKU UMKM ------ //
//ROUTE UNTUK KELOLA UMKM OLEH ROLE PELAKU
Route::resource('pelakukelolaumkm', PelakuKelolaUmkmController::class);
Route::resource('pelakukelolaintervensi', PelakuIntervensiController::class);
Route::resource('pelakukegiatan', PelakuKegiatanController::class);
Route::resource('profil', ProfilController::class);
