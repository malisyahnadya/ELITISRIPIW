<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Semua yang butuh LOGIN masuk sini
Route::middleware('auth')->group(function () {

    // Kelola Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // KHUSUS ADMIN (Satpam Middleware Aktif)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', function () {
            return view('admin.index'); // Manggil file resources/views/admin/index.blade.php
        })->name('admin.dashboard');
    });
});

require __DIR__ . '/auth.php';
