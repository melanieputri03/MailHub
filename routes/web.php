<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

// Web Routes — BIC MailHub

// ============ DASHBOARD ============
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ============ KELOLA PENERIMA (CRUD) ============
Route::get('/penerima',              [PenerimaController::class, 'index'])  ->name('penerima.index');
Route::post('/penerima',             [PenerimaController::class, 'store'])  ->name('penerima.store');
Route::put('/penerima/{penerima}',   [PenerimaController::class, 'update']) ->name('penerima.update');
Route::delete('/penerima/{penerima}',[PenerimaController::class, 'destroy'])->name('penerima.destroy');

// ============ KELOLA GRUP (CRUD) ============
Route::get('/grup',              [GrupController::class, 'index'])  ->name('grup.index');
Route::post('/grup',             [GrupController::class, 'store'])  ->name('grup.store');
Route::put('/grup/{grup}',       [GrupController::class, 'update']) ->name('grup.update');
Route::delete('/grup/{grup}',    [GrupController::class, 'destroy'])->name('grup.destroy');

// ============ BUAT EMAIL ============
Route::get('/email',          [EmailController::class, 'index'])  ->name('email.index');
Route::post('/email/preview', [EmailController::class, 'preview'])->name('email.preview');
Route::post('/email/kirim',   [EmailController::class, 'kirim'])  ->name('email.kirim');

// ============ RIWAYAT ============
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');