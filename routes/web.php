<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Instruktur\DashboardController as InstrukturDashboardController;
use App\Http\Controllers\Instruktur\MateriController as InstrukturMateriController;
use App\Http\Controllers\Instruktur\SoalController as InstrukturSoalController;
use App\Http\Controllers\Instruktur\PesertaController as InstrukturPesertaController;
use App\Http\Controllers\Instruktur\SertifikatController as InstrukturSertifikatController;

// ================== HALAMAN DEPAN ==================
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ================== DASHBOARD PUBLIK (BISA DIAKSES TANPA LOGIN) ==================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ================== AUTH USER BIASA (Daftar / Masuk) ==================
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::get('/login', [RegisteredUserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegisteredUserController::class, 'login'])->name('login.submit');

Route::post('/logout', [RegisteredUserController::class, 'logout'])->name('logout');

// ================== PROFIL PRIBADI (WAJIB LOGIN) ==================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ================== AUTH SUPER ADMIN ==================
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ================== AREA SUPER ADMIN (dilindungi middleware) ==================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_super_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Resource route untuk tiap modul.
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('instruktur', \App\Http\Controllers\Admin\InstrukturController::class);
    Route::resource('materi', \App\Http\Controllers\Admin\MateriController::class);

    // Yang di bawah ini masih DINONAKTIFKAN, controllernya belum dibuat.
    // Route::resource('soal', \App\Http\Controllers\Admin\SoalController::class);
    // Route::resource('penilaian', \App\Http\Controllers\Admin\PenilaianController::class);
    // Route::resource('progress', \App\Http\Controllers\Admin\ProgressController::class);
    // Route::resource('video', \App\Http\Controllers\Admin\VideoController::class);
    // Route::resource('pdf', \App\Http\Controllers\Admin\PdfController::class);
    // Route::resource('gambar', \App\Http\Controllers\Admin\GambarController::class);
});

// ================== AREA INSTRUKTUR (dilindungi middleware) ==================
Route::prefix('instruktur')->name('instruktur.')->middleware(['auth', 'is_instruktur'])->group(function () {

    Route::get('/dashboard', [InstrukturDashboardController::class, 'index'])->name('dashboard');

    // --- MATERI SAYA + TAMBAH MATERI ---
    // Preview ditaruh duluan biar nggak ketutup pola {materi} milik resource.
    Route::get('materi/{materi}/preview', [InstrukturMateriController::class, 'show'])
        ->name('materi.show');

    Route::resource('materi', InstrukturMateriController::class)->except(['show']);

    // --- BANK SOAL ---
    Route::resource('soal', InstrukturSoalController::class)->except(['show']);

    // --- PESERTA (cuma lihat, nggak bisa diubah) ---
    Route::get('peserta', [InstrukturPesertaController::class, 'index'])
        ->name('peserta.index');

    // --- SERTIFIKAT ---
    Route::get('sertifikat', [InstrukturSertifikatController::class, 'index'])
        ->name('sertifikat.index');

    Route::get('sertifikat/{progress}', [InstrukturSertifikatController::class, 'show'])
        ->name('sertifikat.show');
});