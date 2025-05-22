<?php

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
use App\Http\Controllers\ProdukUmkmController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// -------------------------- RUTE UNTUK PUBLIC ATAU GUEST -------------------------- //
Auth::routes();
Route::redirect('/', '/login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/adminlogin', [App\Http\Controllers\Auth\LoginController::class, 'loginadmin'])->name('adminlogin');
Route::post('/adminlogin', [App\Http\Controllers\Auth\LoginController::class, 'adminprocesslogin'])->name('loginadmin');


// -------------------------- RUTE UNTUK ROLE ADMIN KANTOR -------------------------- //
Route::middleware(['auth', 'role:adminkantor'])->group(function () {

    // ---- RUTE UNTUK MENAMPILKAN DASHBOARD ADMIN KANTOR ---- //
    Route::get('/dashboard/admin-kantor', [DashboardController::class, 'index'])->name('dashboard.admin_kantor')->middleware('role:adminkantor');

    // ---- RUTE UNTUK EXPORT INTERVENSI ---- //
    Route::get('exportintervensi', [IntervensiController::class, 'index'])->name('exportintervensi.index');
    Route::get('exportomset', [OmsetController::class, 'index'])->name('exportomset.index');
    Route::get('exportumkm', [DataUmkmController::class, 'ExportUmkmIndex'])->name('exportumkm.index');
    Route::get('exportexcelumkm', [DataUmkmController::class, 'exportUmkm'])->name('dataumkm.export');
    Route::get('exportexcelintervensi', [IntervensiController::class, 'ExportIntervensi'])->name('intervensi.exportexcel');

    // ---- RUTE UNTUK MANAJEMEN USER ---- //
    Route::resource('manajemenuser', ManajemenUserController::class);
});



// -------------------------- RUTE UNTUK ROLE ADMIN LAPANGAN -------------------------- //
Route::middleware(['auth', 'role:adminlapangan'])->group(function () {
    // Dashboard
    Route::get('/dashboard/admin-lapangan', [DashboardController::class, 'index'])->name('dashboard.admin_lapangan');
});


// -------------------------- RUTE UNTUK ROLE PELAKU UMKM -------------------------- //
Route::middleware(['auth', 'role:pelakuumkm'])->group(function () {
    // Dashboard
    Route::get('/dashboard/pelaku-umkm', [DashboardController::class, 'index'])->name('dashboard.pelaku_umkm');

    // UMKM Management
    Route::resource('pelakukelolaumkm', PelakuKelolaUmkmController::class);
    Route::resource('pelakukelolaintervensi', PelakuIntervensiController::class);
    Route::resource('pelakukegiatan', PelakuKegiatanController::class);
    Route::resource('profil', ProfilController::class);

    Route::post('/profil/update-password', [ProfilController::class, 'updatePassword'])->name('profil.updatePassword');
});

// Additional UMKM-related routes that might be shared
Route::middleware(['auth'])->group(function () {
    Route::resource('produkumkm', ProdukUmkmController::class);
    Route::post('/store-product', [ProdukUmkmController::class, 'store']);
    Route::put('/update-product/{id}', [ProdukUmkmController::class, 'update']);
    Route::delete('/delete-product/{id}', [ProdukUmkmController::class, 'destroy']);
    Route::get('/get-products/{umkmId}', [ProdukUmkmController::class, 'getProductsByUmkm']);
    Route::post('/process-temp-products', [ProdukUmkmController::class, 'processTemp'])->name('produk.process.temp');
    Route::post('/store-multiple-products', [ProdukUmkmController::class, 'storeMultiple'])->name('produk.store.multiple');
    Route::post('/process-edit-form-temp', [ProdukUmkmController::class, 'processEditFormTemp'])->name('produk.process.edit.form.temp');

    Route::resource('datakegiatan', KegiatanController::class);

    Route::get('/dataumkm/get-umkm-options/{pelakuId}', [DataUmkmController::class, 'getUmkmOptions'])
        ->name('dataumkm.umkm-options');

    Route::get('/dataumkm/intervensi/edit/{pelakuId}/{intervensiId}', [IntervensiController::class, 'getIntervensiForEdit'])
        ->name('intervensi.edit');

    Route::post('/dataumkm/intervensi/update/{pelakuId}/{intervensiId}', [IntervensiController::class, 'updateIntervensi'])
        ->name('intervensi.update');

    Route::get('/dataumkm/intervensi/list/{pelakuId}', [IntervensiController::class, 'getIntervensiList'])
        ->name('intervensi.list');

    Route::post('/dataumkm/intervensi/save/{pelakuId}', [IntervensiController::class, 'saveIntervensi'])
        ->name('intervensi.save');

    Route::post('/dataumkm/intervensi/{intervensiId}', [IntervensiController::class, 'deleteIntervensi'])
        ->name('intervensi.delete')
        ->where('intervensiId', '[0-9]+');
});


// -------------------------- RUTE UNTUK ROLE ADMIN KANTOR DAN ADMIN LAPANGAN -------------------------- //

Route::middleware(['auth', 'role:adminkantor,adminlapangan'])->group(function () {

    // ---- RUTE UNTUK KELOLA DATA UMKM ----//
    Route::resource('dataumkm', DataUmkmController::class);
    Route::post('/check-nik', [DataUmkmController::class, 'checkNik'])->name('check.nik');

    // ---- RUTE UNTUK KELOLA OMSET DI UMKM ----//
    // Route::post('/dataumkm/omset/save/{id}', [OmsetController::class, 'saveOmset']);
    // Route::get('/dataumkm/omset/list/{id}', [OmsetController::class, 'getOmsetList']);
    // Route::get('/dataumkm/omset/{id}', [OmsetController::class, 'getOmset']);
    // Route::put('/dataumkm/omset/{id}', [OmsetController::class, 'updateOmset']);
    // Route::delete('/dataumkm/omset/{id}', [OmsetController::class, 'deleteOmset']);

    // ---- RUTE UNTUK KELOLA LEGALITAS DI UMKM ----//
    Route::get('/dataumkm/legalitas/list/{id}', [App\Http\Controllers\LegalitasController::class, 'getLegalitasList']);
    Route::get('/dataumkm/legalitas/{id}', [App\Http\Controllers\LegalitasController::class, 'getLegalitas']);
    Route::post('/dataumkm/legalitas/save/{id}', [App\Http\Controllers\LegalitasController::class, 'saveLegalitas']);
    Route::put('/dataumkm/legalitas/{id}', [App\Http\Controllers\LegalitasController::class, 'updateLegalitas']);
    Route::delete('/dataumkm/legalitas/{id}', [App\Http\Controllers\LegalitasController::class, 'deleteLegalitas']);

    // ---- RUTE UNTUK KELOLA INTERVENSI DI UMKM ----//
    Route::get('/dataumkm/intervensi/list/{id}', [IntervensiController::class, 'getIntervensiList']);
    Route::get('/dataumkm/intervensi/{id}', [IntervensiController::class, 'getIntervensi']);
    Route::post('/dataumkm/intervensi/save/{id}', [IntervensiController::class, 'saveIntervensi']);
    Route::put('/dataumkm/intervensi/{id}', [IntervensiController::class, 'updateIntervensi']);
    Route::delete('/dataumkm/intervensi/{id}', [IntervensiController::class, 'deleteIntervensi']);

    // ---- RUTE UNTUK KELOLA KEGIATAN ----//
    Route::post('/datakegiatan/{id}/generate-bukti', [KegiatanController::class, 'generateBuktiPendaftaran'])->name('datakegiatan.generate-bukti');
    Route::post('datakegiatan/{id}/generate-sertifikat', [KegiatanController::class, 'generateSertifikat'])->name('datakegiatan.generate-sertifikat');
    Route::get('datakegiatan/{id}/pendaftar', [PesertaPendaftaranController::class, 'index'])->name('pendaftar.index');
    Route::patch('pendaftar/{id}/update-status', [PesertaPendaftaranController::class, 'updateStatus'])->name('pendaftar.updateStatus');
    Route::get('/datakegiatan/{kegiatanId}/print-attendance', [PesertaPendaftaranController::class, 'printAttendance'])->name('datakegiatan.print-attendance');
    Route::delete('/peserta/{intervensiId}', [PesertaPendaftaranController::class, 'destroy'])->name('peserta.destroy');

    // ---- RUTE UNTUK KELOLA APPROVAL UMKM ----//
    Route::resource('approvalumkm', ApprovalUMKMController::class);
    Route::put('/approval/{id}/approve', [ApprovalUMKMController::class, 'approve'])->name('approval.approve');
    Route::put('/approval/{id}/reject', [ApprovalUMKMController::class, 'reject'])->name('approval.reject');
});
