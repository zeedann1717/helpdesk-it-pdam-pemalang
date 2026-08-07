<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\TiketController;
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

    // ==== Admin only ====
    Route::middleware('admin')->group(function () {
        Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
        Route::get('/tiket-waiting', [TiketController::class, 'waiting'])->name('tiket.waiting');
        Route::put('/tiket/{tiket}/status', [TiketController::class, 'updateStatus'])->name('tiket.updateStatus');
        Route::delete('/tiket/{tiket}/foto', [TiketController::class, 'destroyFoto'])->name('tiket.destroyFoto');

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

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
    });
});
