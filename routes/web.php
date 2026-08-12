<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\TiketChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// ==== Guest only ====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/lupa-password', [PasswordResetRequestController::class, 'create'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetRequestController::class, 'store'])->name('password.request.store');
});

// ==== Authenticated (semua role) ====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tiket - user
    Route::get('/tiket/buat', [TiketController::class, 'create'])->name('tiket.create');
    Route::post('/tiket', [TiketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/saya', [TiketController::class, 'my'])->name('tiket.my');
    Route::get('/tiket/{tiket}', [TiketController::class, 'show'])->name('tiket.show');

    // Endpoint AJAX Lokasi by Divisi
    Route::get('/lokasi/by-divisi/{divisi}', [LokasiController::class, 'byDivisi'])->name('lokasi.byDivisi');

    // Live Chat per tiket (user & admin)
    Route::get('/tiket/{tiket}/chat', [TiketChatController::class, 'index'])->name('tiket.chat.index');
    Route::post('/tiket/{tiket}/chat', [TiketChatController::class, 'store'])->name('tiket.chat.store');

    // Profile Saya (semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // ==== Admin (Super Admin & Admin Divisi) - kelola tiket ====
    Route::middleware('admin')->group(function () {
        Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
        Route::get('/tiket-waiting', [TiketController::class, 'waiting'])->name('tiket.waiting');
        Route::put('/tiket/{tiket}/status', [TiketController::class, 'updateStatus'])->name('tiket.updateStatus');
        Route::delete('/tiket/{tiket}/foto', [TiketController::class, 'destroyFoto'])->name('tiket.destroyFoto');

        // Permintaan Reset Password
        Route::get('/reset-password-requests', [PasswordResetRequestController::class, 'index'])->name('passwordRequests.index');
        Route::put('/reset-password-requests/{passwordResetRequest}/proses', [PasswordResetRequestController::class, 'approve'])->name('passwordRequests.approve');

        // ==== TAMBAHAN BARU: Pemeriksaan Berkala Perangkat (PDE) ====
        Route::get('/perangkat', [PerangkatController::class, 'index'])->name('perangkat.index');
        Route::post('/perangkat', [PerangkatController::class, 'store'])->name('perangkat.store');
        Route::put('/perangkat/{perangkat}', [PerangkatController::class, 'update'])->name('perangkat.update');
        Route::delete('/perangkat/{perangkat}', [PerangkatController::class, 'destroy'])->name('perangkat.destroy');

        Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
        Route::get('/pemeriksaan/buat', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
        Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
        Route::get('/pemeriksaan/{pemeriksaan}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show');
        Route::get('/pemeriksaan/{pemeriksaan}/export-pdf', [PemeriksaanController::class, 'exportPdf'])->name('pemeriksaan.exportPdf');
        Route::delete('/pemeriksaan/{pemeriksaan}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');
        // ==== AKHIR TAMBAHAN ====
    });

    // ==== Super Admin only - kelola data master ====
    Route::middleware('superadmin')->group(function () {
        Route::get('/divisi', [DivisiController::class, 'index'])->name('divisi.index');
        Route::post('/divisi', [DivisiController::class, 'store'])->name('divisi.store');
        Route::put('/divisi/{divisi}', [DivisiController::class, 'update'])->name('divisi.update');
        Route::delete('/divisi/{divisi}', [DivisiController::class, 'destroy'])->name('divisi.destroy');

        Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
        Route::post('/lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
        Route::put('/lokasi/{lokasi}', [LokasiController::class, 'update'])->name('lokasi.update');
        Route::delete('/lokasi/{lokasi}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');

        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::put('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');

        Route::get('/stok-barang', [StokBarangController::class, 'index'])->name('stokBarang.index');
        Route::post('/stok-barang', [StokBarangController::class, 'store'])->name('stokBarang.store');
        Route::put('/stok-barang/{stokBarang}', [StokBarangController::class, 'update'])->name('stokBarang.update');
        Route::delete('/stok-barang/{stokBarang}', [StokBarangController::class, 'destroy'])->name('stokBarang.destroy');
        Route::get('/stok-barang/export-pdf', [StokBarangController::class, 'exportPdf'])->name('stokBarang.exportPdf');
    });
});
