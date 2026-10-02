<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

// ==========================================
// AUTENTIKASI
// ==========================================
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::view('/profile', 'profile.edit')->name('profile.edit')->middleware('auth');


// ==========================================
// ROUTE KHUSUS KASIR & ADMIN (POS / TRANSAKSI)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos');
});


// ==========================================
// KHUSUS ADMIN (DASHBOARD & MASTER DATA)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Produk
    Route::resource('products', ProductController::class);

    // Master Kategori
    Route::resource('categories', CategoryController::class);

    // Master Pengguna (Users)
    Route::resource('users', UserController::class);

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

});