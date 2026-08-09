<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AsesmenController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Beranda Pengenalan (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. Route aplikasi yang dikunci dengan Middleware (Wajib Login)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard diarahkan ke halaman utama Asesmen
    Route::get('/dashboard', [AsesmenController::class, 'index'])->name('dashboard');

    // ==========================================
    // ROUTE PROFIL USER
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // ROUTE ASESMEN & CASE CONFERENCE
    // ==========================================

    // A. ROUTE CUSTOM (Sangat penting ditaruh di atas route resource & wildcard {id}!)
    Route::get('/asesmen/download-template', [AsesmenController::class, 'downloadTemplate'])->name('asesmen.downloadTemplate');
    Route::post('/asesmen/import', [AsesmenController::class, 'import'])->name('asesmen.import');

    // Rute Export Excel diletakkan di sini (SEBELUM ada {id} atau resource)
    Route::get('/asesmen/export-excel', [AsesmenController::class, 'exportExcel'])->name('asesmen.export-excel');

    // Route PDF ini menggunakan {asesmen}, pastikan rute tanpa {id} ada di atasnya
    Route::get('/asesmen/{asesmen}/pdf', [AsesmenController::class, 'cetakPdf'])->name('asesmen.cetakPdf');

    // B. ROUTE CRUD UTAMA (Otomatis membuat index, create, store, show, edit, update, destroy)
    Route::resource('asesmen', AsesmenController::class);

    // C. ROUTE DOKUMEN TAT (Berita Acara & Rekomendasi)
    // ----------------------------------------------------

    // Fitur Berita Acara
    Route::get('/asesmen/{id}/berita-acara', [AsesmenController::class, 'beritaAcara'])
        ->name('asesmen.berita-acara');
    Route::post('/asesmen/{id}/berita-acara/generate', [AsesmenController::class, 'generateBeritaAcara'])
        ->name('asesmen.berita-acara.generate');

    // Fitur Rekomendasi
    Route::get('/asesmen/{id}/rekomendasi', [AsesmenController::class, 'rekomendasi'])
        ->name('asesmen.rekomendasi');
    Route::post('/asesmen/{id}/rekomendasi/unduh', [AsesmenController::class, 'unduhRekomendasi'])
        ->name('asesmen.rekomendasi.unduh');

    // Cetak PDF Detail (opsional)
    Route::get('/asesmen/{id}/pdf', [AsesmenController::class, 'pdf'])
        ->name('asesmen.pdf');

    Route::get('/asesmen/{id}/rekomendasi/download-word', [AsesmenController::class, 'downloadWordTerpakai'])->name('asesmen.rekomendasi.downloadWord');

});

// 3. Route bawaan Laravel Breeze untuk autentikasi (Login, Register, Logout)
require __DIR__ . '/auth.php';
