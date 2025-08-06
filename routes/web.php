<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\JuruArah\JuruArahController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuruArah\SesiAbsensiController;
use App\Http\Controllers\Anggota\AnggotaController;
use App\Http\Controllers\JuruArah\DaftarAnggotaController;
use App\Http\Controllers\Anggota\AbsensiController;
use App\Http\Controllers\Anggota\ScanController;
use App\Http\Controllers\Auth\ActivationController;

// ✅ Gunakan alias untuk mencegah tabrakan antara dua controller yang sama-sama bernama "ProfileController"
use App\Http\Controllers\Anggota\ProfileController as AnggotaProfileController;
use App\Http\Controllers\JuruArah\ProfileController as JuruArahProfileController;

Route::get('/', function () {
    return view('welcome');
});


require __DIR__.'/auth.php';

Route::get('/activate/{token}', [ActivationController::class, 'activate'])->name('auth.activate');

Route::middleware(['auth','adminMiddleware'])->group(function(){

    Route::get('admin/dashboard',[AdminController::class,'index'])->name('admin.board');
    Route::get('admin/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
    Route::get('admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('admin/board', [AdminController::class, 'boards'])->name('admin.board');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');

    Route::get('/admin/manage/juruarah', [AdminController::class, 'manageJuruArah'])->name('admin.manage-juruarah');
    Route::get('/admin/manage/anggota', [AdminController::class, 'manageAnggota'])->name('admin.manage-anggota');
    Route::get('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.create-user');
    Route::post('/admin/store-user', [AdminController::class, 'storeUser'])->name('admin.store-user');
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');

    Route::get('/admin/manage/juruarah/{id}/edit', [AdminController::class, 'editJuruArah'])->name('admin.manage-juruarah.edit');
    Route::put('/admin/manage/juruarah/{id}', [AdminController::class, 'updateJuruArah'])->name('admin.manage-juruarah.update');


    Route::get('/admin/rekap/{id}/anggota/export', [AdminController::class, 'exportRekapAnggota'])->name('admin.rekap.anggota.export');
    Route::get('/admin/rekap/anggota/{id}/export', [AdminController::class, 'exportAbsensiAnggota'])->name('admin.rekap.absensi.export');


    Route::get('/admin/rekap', [AdminController::class, 'rekapJuruarah'])->name('admin.rekap.index');
    Route::get('/admin/rekap/{id}/anggota', [AdminController::class, 'rekapAnggotaByJuruarah'])->name('admin.rekap.anggota');
    Route::get('/admin/rekap/anggota/{id}/detail', [AdminController::class, 'rekapDetailAbsensi'])->name('admin.rekap.absensi.detail');

});
Route::middleware(['auth', 'juruArahMiddleware'])->prefix('juruarah')->group(function () {
    Route::get('/dashboard', [JuruArahController::class, 'index'])->name('juruarah.board');
    Route::get('/monitoring', [JuruArahController::class, 'monitoring'])->name('juruarah.monitoring');

    Route::get('/rekap', [JuruArahController::class, 'rekapTabel'])->name('juruarah.rekap.index');
    Route::get('/rekap/anggota/{id}/detail', [JuruArahController::class, 'rekapDetailAbsensi'])->name('juruarah.rekap.absensi.detail');
    Route::get('/rekap/{id}/anggota/export', [JuruArahController::class, 'exportRekapAnggota'])->name('juruarah.rekap.anggota.export');
    Route::get('/rekap/anggota/{id}/export', [JuruArahController::class, 'exportAbsensiAnggota'])->name('juruarah.rekap.absensi.export');

    Route::get('/anggota', [DaftarAnggotaController::class, 'index'])->name('juruarah.anggota.index');
    Route::get('/anggota/create', [DaftarAnggotaController::class, 'create'])->name('juruarah.anggota.create');
    Route::post('/anggota', [DaftarAnggotaController::class, 'store'])->name('juruarah.anggota.store');
    Route::delete('/anggota/{id}', [DaftarAnggotaController::class, 'delete'])->name('juruarah.anggota.delete');

    Route::prefix('/sesi')->group(function () {
        Route::get('/buka', [SesiAbsensiController::class, 'showBukaSesi'])->name('juruarah.sesi.buka');
        Route::post('/buka', [SesiAbsensiController::class, 'bukaSesi'])->name('juruarah.sesi.bukaSesi');
        Route::get('/tutup', [SesiAbsensiController::class, 'showTutupSesi'])->name('juruarah.sesi.tutupSesi');
        Route::post('/tutup', [SesiAbsensiController::class, 'tutupSesi'])->name('juruarah.sesi.buka.submit');
    });

    Route::get('/profile', [JuruArahProfileController::class, 'show'])->name('juruarah.profile.show');
    Route::post('/profile/reset-password', [JuruArahProfileController::class, 'resetPassword'])->name('juruarah.profile.reset-password');

    Route::post('/juruarah/absensi/izin', [JuruArahController::class, 'storeIzin'])->name('juruarah.absensi.izin.store');

});

// Route Anggota
Route::middleware(['auth', 'anggotaMiddleware'])->prefix('anggota')->group(function () {
    // Form pengajuan izin sidebar
    Route::get('/izin/form', function() { return view('anggota.izin_form'); })->name('anggota.izin.form');
    Route::get('/layout', [AnggotaController::class, 'index'])->name('anggota.layout');
    Route::get('/dashboard', [AnggotaController::class, 'showDashboard'])->name('anggota.dashboard');
    
    Route::get('/scan', [ScanController::class, 'index'])->name('anggota.scan');
    // Route::post('/scan', [ScanController::class, 'submit'])->name('anggota.scan.submit');
    Route::get('/daftar-wajah', [AnggotaController::class, 'showFormWajah'])->name('anggota.formWajah');
    Route::post('/daftar-wajah', [AnggotaController::class, 'prosesDaftarWajah'])->name('anggota.daftarWajah');

    Route::post('/scan', [ScanController::class, 'scanViaWebcam'])->name('anggota.scan.submit');

    Route::get('/profile', [AnggotaProfileController::class, 'show'])->name('anggota.profile.show');
    Route::post('/profile/reset-password', [AnggotaProfileController::class, 'resetPassword'])->name('anggota.profile.reset-password');
    
});



