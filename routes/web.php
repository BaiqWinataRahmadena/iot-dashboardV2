<?php

use Illuminate\Support\Facades\Route;

// Impor Controller
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengukuranController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Rute Halaman Depan
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rute Aplikasi (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Fitur Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::post('/pelanggan/store', [PelangganController::class, 'store'])->name('pelanggan.store');

    // Fitur Karyawan (Users)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');

    // Fitur Pengukuran
    Route::get('/pengukuran/baru', [PengukuranController::class, 'create'])->name('pengukuran.create');
    Route::post('/pengukuran/baru', [PengukuranController::class, 'store'])->name('pengukuran.store');
    Route::delete('/pengukuran/{id}', [PengukuranController::class, 'cancel'])->name('pengukuran.cancel');

    // Fitur Profile User (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rute Autentikasi Bawaan
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';