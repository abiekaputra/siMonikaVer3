<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AtributController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\AtributTambahanController;
use App\Http\Controllers\LinimasaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MagangController;

// Route untuk guest (belum login)
Route::middleware(['guest', 'throttle:6,1'])->group(function () {
    // Redirect root URL ke halaman login
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Route untuk login dan register
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/aplikasi/export', [AplikasiController::class, 'export'])->name('aplikasi.export');
    // Route::resource('aplikasi', AplikasiController::class);  // Comment atau hapus ini untuk sementara
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Definisikan route secara manual dan terorganisir
    Route::prefix('aplikasi')->group(function () {
        // Route dasar aplikasi
        Route::get('/', [AplikasiController::class, 'index'])->name('aplikasi.index');
        Route::get('/create', [AplikasiController::class, 'create'])->name('aplikasi.create');
        Route::post('/', [AplikasiController::class, 'store'])->name('aplikasi.store');
        Route::get('/export', [AplikasiController::class, 'export'])->name('aplikasi.export');

        // Route dengan parameter id
        Route::get('/{id}/detail', [AplikasiController::class, 'detail'])->name('aplikasi.detail');
        Route::get('/{id}/edit', [AplikasiController::class, 'edit'])->name('aplikasi.edit');
        Route::put('/{id}', [AplikasiController::class, 'update'])->name('aplikasi.update');
        Route::delete('/{id}', [AplikasiController::class, 'destroy'])->name('aplikasi.destroy');

        // Route untuk atribut
        Route::get('/{id}/atribut', [AplikasiController::class, 'getAtribut']);
        Route::put('/{id}/atribut', [AplikasiController::class, 'updateAtribut'])->name('aplikasi.updateAtribut');

        // Perbaikan route untuk update atribut
        Route::post('/{id}/atribut', [AtributTambahanController::class, 'updateAtributValues'])
            ->name('aplikasi.atribut.update');
    });

    // Prefix untuk Linimasa
    Route::prefix('linimasa')->group(function () {
        Route::get('/', [LinimasaController::class, 'index'])->name('linimasa.index');
        Route::post('/store', [LinimasaController::class, 'store'])->name('linimasa.store');
        Route::get('/{id}/edit', [LinimasaController::class, 'edit'])->name('linimasa.edit');
        Route::put('/{id}', [LinimasaController::class, 'update'])->name('linimasa.update');
        Route::delete('/{id}', [LinimasaController::class, 'destroy'])->name('linimasa.destroy');
    });

    // Prefix untuk Pegawai
    Route::prefix('pegawai')->middleware(['auth'])->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    });

    // Prefix untuk Proyek
    Route::prefix('proyek')->group(function () {
        Route::get('/', [ProyekController::class, 'index'])->name('proyek.index');
        Route::get('/create', [ProyekController::class, 'create'])->name('proyek.create');
        Route::post('/', [ProyekController::class, 'store'])->name('proyek.store');
        Route::get('/{proyek}/edit', [ProyekController::class, 'edit'])->name('proyek.edit');
        Route::put('/{id}', [ProyekController::class, 'update'])->name('proyek.update');
        Route::delete('/{proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');
    });
    // Prefix untuk Kategori
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        Route::delete('/kategori/{id}', [ProyekController::class, 'destroyKategori'])->name('kategori.destroy');
    });

    // Prefix untuk Magang
    Route::prefix('magang')->group(function () {
        Route::get('/', [MagangController::class, 'index'])->name('magang.index');
        Route::get('/create', [MagangController::class, 'create'])->name('magang.create');
        Route::post('/store', [MagangController::class, 'store'])->name('magang.store');
        Route::get('/{id}/edit', [MagangController::class, 'edit'])->name('magang.edit');
        Route::put('/{id}', [MagangController::class, 'update'])->name('magang.update');
        Route::delete('/{id}', [MagangController::class, 'destroy'])->name('magang.destroy');
    });

    Route::get('/chart-data', [AplikasiController::class, 'getChartData']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    Route::get('/last-update', [DashboardController::class, 'getLastUpdate'])->name('last.update');

    // Route untuk Super Admin (tanpa middleware di route)
    Route::get('/super-admin/dashboard', [SuperAdminDashboard::class, 'index'])
        ->name('super-admin.dashboard');

    // Tambahkan route untuk mendapatkan dan mengupdate atribut tambahan
    Route::get('/aplikasi/{id}/atribut-tambahan', [AplikasiController::class, 'getAtributTambahan'])
        ->name('aplikasi.atribut-tambahan');
    Route::put('/aplikasi/{id}/atribut-tambahan', [AplikasiController::class, 'updateAtributTambahan'])
        ->name('aplikasi.update-atribut-tambahan');
});

// Route untuk admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// Route untuk login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::resource('atribut', AtributController::class);

Route::get('/super-admin/log/export', [App\Http\Controllers\SuperAdmin\LogAktivitasController::class, 'export'])
    ->name('super-admin.log.export')
    ->middleware('auth')
    ->middleware(\App\Http\Middleware\CheckRole::class . ':super_admin');

// Tambahkan route forgot password dari branch baru
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

Route::post('/atribut/check-duplicate', [AtributController::class, 'checkDuplicate'])->name('atribut.check-duplicate');

Route::get('/atribut/{id}/edit', [AtributController::class, 'edit'])->name('atribut.edit');

Route::resource('atribut', AtributTambahanController::class);
// Route untuk aplikasi
Route::get('/aplikasi/{nama}/edit', [AplikasiController::class, 'edit'])->name('aplikasi.edit');
Route::put('/aplikasi/{nama}', [AplikasiController::class, 'update'])->name('aplikasi.update');

Route::get('/atribut/{id}/detail', [AtributController::class, 'detail'])->name('atribut.detail');
Route::put('/atribut/{id_aplikasi}/nilai', [AtributController::class, 'updateNilai'])
    ->name('atribut.updateNilai')
    ->middleware('auth');
Route::delete('/atribut/{id_aplikasi}/{id_atribut}', [AtributController::class, 'removeFromApp'])->name('atribut.removeFromApp');

// Tambahkan route untuk detail aplikasi
Route::get('/aplikasi/{id}/detail', [AplikasiController::class, 'detail'])->name('aplikasi.detail');
Route::get('/aplikasi/{id}/atribut', [AplikasiController::class, 'getAtribut']);
// Route::put('/aplikasi/{id}/atribut', [AplikasiController::class, 'updateAtribut']);

// Routes untuk aplikasi
Route::get('/aplikasi/{id}', [AplikasiController::class, 'show'])->name('aplikasi.show');
Route::get('/aplikasi/{id}/atribut', [AplikasiController::class, 'getAtribut']);
Route::put('/aplikasi/{id}/atribut', [AplikasiController::class, 'updateAtribut']);

Route::put('/aplikasi/{id}', [AplikasiController::class, 'update'])->name('aplikasi.update');
Route::delete('/aplikasi/{id}', [AplikasiController::class, 'destroy'])->name('aplikasi.destroy');

Route::get('/aplikasi/detail/{id}', [AplikasiController::class, 'getDetail'])->name('aplikasi.detail');

// Hapus atau comment route yang duplikat
Route::middleware(['auth'])->group(function () {
    Route::prefix('aplikasi')->group(function () {
        Route::get('/', [AplikasiController::class, 'index'])->name('aplikasi.index');
        Route::get('/create', [AplikasiController::class, 'create'])->name('aplikasi.create');
        Route::post('/', [AplikasiController::class, 'store'])->name('aplikasi.store');
        Route::get('/{id}/edit', [AplikasiController::class, 'edit'])->name('aplikasi.edit');
        Route::put('/{id}', [AplikasiController::class, 'update'])->name('aplikasi.update');
        Route::delete('/{id}', [AplikasiController::class, 'destroy'])->name('aplikasi.destroy');
        Route::get('/{id}/detail', [AplikasiController::class, 'detail'])->name('aplikasi.detail');
        Route::get('/export', [AplikasiController::class, 'export'])->name('aplikasi.export');

        // Route untuk atribut
        Route::get('/{id}/atribut', [AplikasiController::class, 'getAtribut']);
        Route::put('/{id}/atribut', [AplikasiController::class, 'updateAtribut'])->name('aplikasi.updateAtribut');
    });
});

// Pastikan route untuk update atribut sudah terdaftar
Route::post('/aplikasi/{id}/atribut', [AtributTambahanController::class, 'update'])
    ->name('aplikasi.atribut.update');

