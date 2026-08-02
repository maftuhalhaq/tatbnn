<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Asesmen Terpadu (TAT) - BNN</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Scripts & Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 font-sans text-slate-900 selection:bg-blue-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xl shadow-md">
                    B
                </div>
                <div>
                    <h1 class="font-extrabold text-base tracking-tight text-slate-900">SI-TAT BNN</h1>
                    <p class="text-xs text-slate-500 font-medium">Badan Narkotika Nasional</p>
                </div>
            </div>

            <nav class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/asesmen') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition shadow-sm">
                            Dashboard Utama
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-slate-700 text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" style="background-color: #3890f5;" class="px-5 py-2.5 rounded-xl text-white text-xs font-bold uppercase tracking-wider hover:opacity-90 transition shadow-sm">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center min-h-[calc(100vh-12rem)]">
                
                <!-- Kiri: Teks Informasi -->
                <div class="lg:col-span-7 space-y-8">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-[#3890f5] text-xs font-bold tracking-wide uppercase">
                        <span class="w-2 h-2 rounded-full bg-[#3890f5] animate-pulse"></span>
                        Platform Terpadu Asesmen Hukum & Medis
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                        Manajemen Data <span style="color: #3890f5;">Asesmen Terpadu</span> & Klien BNN.
                    </h1>
                    
                    <p class="text-lg text-slate-600 font-normal leading-relaxed max-w-2xl">
                        Sistem informasi modern untuk mengelola administrasi, rekam jejak perkara hukum, hasil asesmen medis, hingga rekomendasi Tim Asesmen Terpadu (TAT) secara akurat, cepat, dan terstruktur.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        @auth
                            <a href="{{ url('/asesmen') }}" style="background-color: #3890f5;" class="px-8 py-4 rounded-xl text-white font-bold text-sm tracking-wider uppercase shadow-lg shadow-blue-500/25 hover:opacity-90 transition">
                                Buka Panel Aplikasi &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" style="background-color: #3890f5;" class="px-8 py-4 rounded-xl text-white font-bold text-sm tracking-wider uppercase shadow-lg shadow-blue-500/25 hover:opacity-90 transition">
                                Mulai Masuk Sistem &rarr;
                            </a>
                            <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-white text-slate-700 border border-slate-300 font-bold text-sm tracking-wider uppercase hover:bg-slate-50 transition shadow-sm">
                                Pendaftaran Akun Baru
                            </a>
                        @endauth
                    </div>

                    <!-- Fitur Unggulan Grid kecil -->
                    <div class="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200">
                        <div>
                            <h4 class="text-2xl font-black text-slate-900">100%</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1">Data Terpusat & Aman</p>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-slate-900">Excel</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1">Impor & Ekspor Cepat</p>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-slate-900">PDF</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1">Cetak Berita Acara</p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Ilustrasi / Card Modern -->
                <div class="lg:col-span-5">
                    <div class="relative">
                        <!-- Efekglow dekoratif -->
                        <div class="absolute -inset-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl blur-xl opacity-20 animate-tilt"></div>
                        
                        <div class="relative bg-white rounded-3xl p-8 border border-slate-200 shadow-2xl space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Aktif</span>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#3890f5] flex items-center justify-center font-bold">📋</div>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-900">Modul Asesmen Terpadu</h5>
                                            <p class="text-[11px] text-slate-500">Hukum, Medis & Rekomendasi</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Online</span>
                                </div>

                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">🔒</div>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-900">Autentikasi Keamanan</h5>
                                            <p class="text-[11px] text-slate-500">Proteksi Akses Petugas</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Secure</span>
                                </div>
                            </div>

                            <div class="pt-2 text-center">
                                <p class="text-xs text-slate-400">Badan Narkotika Nasional Kota Malang © 2026</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>