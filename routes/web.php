<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Dashboard — semua bisa lihat
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------------
    // Persediaan
    // PENTING: route spesifik (string segment) harus di atas route
    // wildcard {id} agar tidak tertangkap duluan.
    // ----------------------------------------------------------------
    Route::get('/persediaan/masuk',           [StockTransactionController::class, 'masuk'])->name('persediaan.masuk');
    Route::get('/persediaan/keluar',          [StockTransactionController::class, 'keluar'])->name('persediaan.keluar');

    // Riwayat & export — semua di atas {id}
    Route::get('/persediaan/riwayat/export',     [StockTransactionController::class, 'export'])->name('persediaan.riwayat.export');
    Route::get('/persediaan/riwayat/export-pdf', [StockTransactionController::class, 'exportPdf'])->name('persediaan.riwayat.export-pdf');
    Route::get('/persediaan/riwayat',            [StockTransactionController::class, 'riwayat'])->name('persediaan.riwayat');

    // Wildcard — harus paling bawah dalam grup persediaan
    Route::get('/persediaan/{id}',            [StockTransactionController::class, 'show'])->name('persediaan.show');

    Route::middleware('admin')->group(function () {
        Route::get('/persediaan/create/{type}', [StockTransactionController::class, 'create'])->name('persediaan.create');
        Route::post('/persediaan',              [StockTransactionController::class, 'store'])->name('persediaan.store');
    });

    // ----------------------------------------------------------------
    // Laporan — semua bisa lihat
    // PENTING: export-pdf & export harus di atas /laporan agar tidak
    // ada konflik (meski di sini tidak ada wildcard, tetap rapi).
    // ----------------------------------------------------------------
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('/laporan/export',     [ReportController::class, 'export'])->name('laporan.export');
    Route::get('/laporan',            [ReportController::class, 'index'])->name('laporan');

    // ----------------------------------------------------------------
    // Profil — semua bisa edit profil sendiri
    // ----------------------------------------------------------------
    Route::get('/profile/edit',     [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ----------------------------------------------------------------
    // Master Data & Pengguna — hanya admin
    // ----------------------------------------------------------------
    Route::middleware('admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products',   ProductController::class);
        Route::resource('users',      UserController::class);
    });
});