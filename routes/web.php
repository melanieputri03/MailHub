<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

// DASHBOARD
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// KELOLA PENERIMA (CRUD)
Route::get('/penerima',              [PenerimaController::class, 'index'])  ->name('penerima.index');
Route::post('/penerima',             [PenerimaController::class, 'store'])  ->name('penerima.store');
Route::put('/penerima/{penerima}',   [PenerimaController::class, 'update']) ->name('penerima.update');
Route::delete('/penerima/{penerima}',[PenerimaController::class, 'destroy'])->name('penerima.destroy');

// KELOLA GRUP (CRUD)
Route::get('/grup',              [GrupController::class, 'index'])  ->name('grup.index');
Route::post('/grup',             [GrupController::class, 'store'])  ->name('grup.store');
Route::put('/grup/{grup}',       [GrupController::class, 'update']) ->name('grup.update');
Route::delete('/grup/{grup}',    [GrupController::class, 'destroy'])->name('grup.destroy');

// BUAT EMAIL
Route::get('/email',          [EmailController::class, 'index'])  ->name('email.index');
Route::post('/email/preview', [EmailController::class, 'preview'])->name('email.preview');
Route::post('/email/kirim',   [EmailController::class, 'kirim'])  ->name('email.kirim');

// UPLOAD GAMBAR UNTUK BODY EMAIL
Route::post('/email/upload-image', [EmailController::class, 'uploadImage'])->name('email.uploadImage');

// RIWAYAT
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

// Kelola Template (CRUD)
Route::get('/template', [TemplateController::class, 'index'])->name('template.index');
Route::post('/template', [TemplateController::class, 'store'])->name('template.store');
Route::get('/template/editor-draft', [TemplateController::class, 'editorDraft'])->name('template.editor.draft');
Route::post('/template/editor/save/{id?}', [TemplateController::class, 'saveEditor'])->name('template.editor.save');
Route::get('/template/{id}/editor', [TemplateController::class, 'editor'])->name('template.editor');
Route::put('/template/{id}', [TemplateController::class, 'update'])->name('template.update');
Route::delete('/template/{id}', [TemplateController::class, 'destroy'])->name('template.destroy');