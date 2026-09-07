<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Beranda Pengenalan (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// =========================================================================
// RUTE PUBLIK (Bisa diakses tanpa login)
// =========================================================================
Route::get('/waiting-approval', function () {
    return view('auth.waiting-approval');
})->name('approval.waiting');


// =========================================================================
// RUTE APLIKASI (WAJIB LOGIN)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // ROUTE PROFIL USER
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // ROUTE ASESMEN & CASE CONFERENCE
    // ==========================================

    // A. ROUTE CUSTOM (Wajib ditaruh di atas route resource & wildcard {id}!)
    Route::get('/asesmen/download-template', [AsesmenController::class, 'downloadTemplate'])->name('asesmen.downloadTemplate');
    Route::post('/asesmen/import', [AsesmenController::class, 'import'])->name('asesmen.import');

    // --> INI DIA ROUTE EXCELNYA <--
    Route::get('/asesmen/export-excel', [AsesmenController::class, 'exportExcel'])->name('asesmen.export-excel');

    // B. ROUTE CRUD UTAMA (Otomatis membuat index, create, store, show, dll)
    Route::resource('asesmen', AsesmenController::class);

    // C. ROUTE DOKUMEN TAT (Berita Acara & Rekomendasi)
    // ----------------------------------------------------

    // Fitur Berita Acara
    Route::get('/asesmen/{id}/berita-acara', [AsesmenController::class, 'beritaAcara'])->name('asesmen.berita-acara');
    Route::post('/asesmen/{id}/berita-acara/generate', [AsesmenController::class, 'generateBeritaAcara'])->name('asesmen.berita-acara.generate');
    Route::post('/asesmen/{id}/berita-acara/unduh', [AsesmenController::class, 'unduhBeritaAcara'])->name('asesmen.berita-acara.unduh');

    // Fitur Rekomendasi
    Route::get('/asesmen/{id}/rekomendasi', [AsesmenController::class, 'rekomendasi'])->name('asesmen.rekomendasi');
    Route::post('/asesmen/{id}/rekomendasi/unduh', [AsesmenController::class, 'unduhRekomendasi'])->name('asesmen.rekomendasi.unduh');

    // Cetak PDF Detail
    Route::get('/asesmen/{id}/pdf', [AsesmenController::class, 'cetakPdf'])->name('asesmen.cetakPdf');


    // ==========================================
    // ROUTE MASTER DATA (AJAX)
    // ==========================================
    Route::post('/master-anggota/ajax', [App\Http\Controllers\MasterAnggotaController::class, 'storeAjax'])->name('master-anggota.storeAjax');
    Route::delete('/master-anggota/ajax/{id}', [App\Http\Controllers\MasterAnggotaController::class, 'destroyAjax'])->name('master-anggota.destroyAjax');

    Route::post('/master-opsi/ajax', [App\Http\Controllers\MasterOpsiController::class, 'storeAjax'])->name('master-opsi.storeAjax');
    Route::delete('/master-opsi/ajax/{id}', [App\Http\Controllers\MasterOpsiController::class, 'destroyAjax'])->name('master-opsi.destroyAjax');

    Route::patch('/asesmen/{id}/update-tanggal', [AsesmenController::class, 'updateTanggal'])->name('asesmen.update-tanggal');

    // Route untuk menghapus master data Pendidikan
    Route::delete('/pendidikan/{id}', [App\Http\Controllers\AsesmenController::class, 'destroyPendidikan'])->name('pendidikan.destroy');
    // Route untuk menghapus master data Rekomendasi TAT
    Route::delete('/rekomendasi/{id}', [App\Http\Controllers\AsesmenController::class, 'destroyRekomendasi'])->name('rekomendasi.destroy');


    // ==========================================
    // ROUTE MANAJEMEN PENGGUNA (Khusus Admin)
    // ==========================================
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});

// 3. Route bawaan Laravel Breeze untuk autentikasi (Login, Register, Logout)
require __DIR__ . '/auth.php';