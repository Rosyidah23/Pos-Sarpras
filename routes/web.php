<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentAccountController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Area siswa
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/riwayat', [ReportController::class, 'history'])->name('reports.history');
    Route::get('/denah', [ReportController::class, 'map'])->name('map');
});

// Area petugas
Route::middleware(['auth', 'petugas'])->prefix('petugas')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/laporan', [VerificationController::class, 'index'])->name('admin.reports');
    Route::post('/laporan/{report}', [VerificationController::class, 'update'])->name('admin.reports.update');

    Route::get('/rekap', [RecapController::class, 'index'])->name('admin.recap');
    Route::get('/export', [RecapController::class, 'export'])->name('admin.export');

    Route::get('/kategori', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/lokasi', [LocationController::class, 'index'])->name('admin.locations');
    Route::post('/lokasi', [LocationController::class, 'store'])->name('admin.locations.store');
    Route::put('/lokasi/{location}', [LocationController::class, 'update'])->name('admin.locations.update');
    Route::delete('/lokasi/{location}', [LocationController::class, 'destroy'])->name('admin.locations.destroy');

    // Siswa tidak bisa daftar sendiri, akunnya dibuat petugas
    Route::get('/akun', [StudentAccountController::class, 'index'])->name('admin.accounts');
    Route::post('/akun', [StudentAccountController::class, 'store'])->name('admin.accounts.store');
    Route::put('/akun/{user}/password', [StudentAccountController::class, 'resetPassword'])->name('admin.accounts.reset-password');
    Route::delete('/akun/{user}', [StudentAccountController::class, 'destroy'])->name('admin.accounts.destroy');
});
