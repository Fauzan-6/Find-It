<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/* ---------- Publik ---------- */
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/barang-hilang', [ItemController::class, 'lost'])->name('items.lost');
Route::get('/barang-ditemukan', [ItemController::class, 'found'])->name('items.found');

/* ---------- Tamu (belum login) ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

/* ---------- Pengguna login (user) ---------- */
// Catatan: rute statis /barang/lapor harus didefinisikan SEBELUM /barang/{item}
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/barang/lapor', [ItemController::class, 'create'])->name('items.create');
    Route::post('/barang/lapor', [ItemController::class, 'store'])->name('items.store');

    Route::get('/dashboard', [ItemController::class, 'dashboard'])->name('dashboard');
    Route::patch('/barang/{item}/selesai', [ItemController::class, 'resolve'])->name('items.resolve');

    Route::post('/barang/{item}/klaim', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/klaim/{claim}/setujui', [ClaimController::class, 'approve'])->name('claims.approve');
    Route::patch('/klaim/{claim}/tolak', [ClaimController::class, 'reject'])->name('claims.reject');
});

/* ---------- Detail barang (publik; pending hanya untuk pemilik/admin) ---------- */
Route::get('/barang/{item}', [ItemController::class, 'show'])->name('items.show');

/* ---------- Admin ---------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::patch('/barang/{item}/setujui', [AdminController::class, 'approve'])->name('approve');
    Route::patch('/barang/{item}/tolak', [AdminController::class, 'reject'])->name('reject');
    Route::get('/arsip', [AdminController::class, 'archive'])->name('archive');
});

/* ---------- Logout ---------- */
Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
