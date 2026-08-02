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

    // A. ROUTE CUSTOM (Sangat penting ditaruh di atas route resource!)
    Route::get('/asesmen/download-template', [AsesmenController::class, 'downloadTemplate'])->name('asesmen.downloadTemplate');
    Route::post('/asesmen/import', [AsesmenController::class, 'import'])->name('asesmen.import');
    Route::get('/asesmen/{asesmen}/pdf', [AsesmenController::class, 'cetakPdf'])->name('asesmen.cetakPdf');

    // B. ROUTE CRUD UTAMA (Otomatis membuat index, create, store, show, edit, update, destroy)
    Route::resource('asesmen', AsesmenController::class);

    Route::get('/asesmen/{id}/berita-acara', [AsesmenController::class, 'beritaAcara'])->name('asesmen.berita_acara');
    Route::get('/asesmen/{id}/rekomendasi', [AsesmenController::class, 'rekomendasi'])->name('asesmen.rekomendasi');
    Route::get('/asesmen/{id}/pdf', [AsesmenController::class, 'pdf'])->name('asesmen.pdf');
    Route::post('/asesmen/{id}/rekomendasi/unduh', [AsesmenController::class, 'unduhRekomendasi'])->name('asesmen.rekomendasi.unduh');


});

// 3. Route bawaan Laravel Breeze untuk autentikasi (Login, Register, Logout)
require __DIR__ . '/auth.php';
