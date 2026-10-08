<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Asesmen Terpadu (TAT) - BNN</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Lembut Mac UI */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -40px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-blob {
            animation: blob 15s infinite alternate;
        }
        .animation-delay-2000 {
            animation-delay: 4s;
        }
        .animation-delay-4000 {
            animation-delay: 8s;
        }

        /* Custom Scrollbar untuk App Frame */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media (prefers-color-scheme: dark) {
            ::-webkit-scrollbar-thumb { background: #334155; }
            ::-webkit-scrollbar-thumb:hover { background: #475569; }
        }
    </style>
</head>

<!-- Latar belakang transisi otomatis Light -> Dark -->
<body class="antialiased bg-[#F8FAFC] dark:bg-[#0B1120] font-sans text-slate-800 dark:text-slate-200 selection:bg-blue-500 selection:text-white h-screen w-screen overflow-hidden flex items-center justify-center p-4 sm:p-6 lg:p-8 relative transition-colors duration-500">

    <!-- Ambient Background Blobs -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- KONTINER UTAMA (APP FRAME) -->
    <div class="relative z-10 w-full max-w-[1200px] h-full max-h-[850px] bg-white dark:bg-[#0F172A] rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.5)] border border-white/80 dark:border-slate-800/80 flex flex-col overflow-hidden transition-colors duration-500">

        <!-- Header / Navbar -->
        <header class="flex-none w-full pt-6 pb-3 px-6 sm:px-8 lg:px-12 border-b border-slate-50/50 dark:border-slate-800/50 transition-colors duration-500">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Mac Window Controls -->
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56] shadow-sm"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E] shadow-sm"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F] shadow-sm"></div>
                    </div>

                    <!-- Logo & Brand -->
                    <div class="flex items-center gap-3 border-l border-slate-200 dark:border-slate-700 pl-4 sm:pl-6 transition-colors duration-500">
                        <img src="{{ asset('logo-bnn.jpg') }}" alt="Logo BNN" class="w-8 h-8 object-contain drop-shadow-sm rounded-full">
                        <div class="flex flex-col justify-center">
                            <h1 class="font-black text-[13px] tracking-tight text-slate-900 dark:text-white leading-none mb-1 transition-colors duration-500">SI-TAT BNN</h1>
                            <p class="text-[8px] text-slate-500 dark:text-slate-400 font-bold leading-tight uppercase tracking-widest transition-colors duration-500">
                                Badan Narkotika Nasional
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Actions -->
                <nav class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/asesmen') }}" class="px-5 py-2.5 rounded-full bg-[#3B82F6] text-white text-[10px] font-bold uppercase tracking-widest hover:bg-blue-600 hover:-translate-y-0.5 transition-all shadow-md shadow-blue-500/30">
                                Dashboard Utama
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:block text-slate-600 dark:text-slate-300 text-[10px] font-bold uppercase tracking-widest hover:text-[#3B82F6] dark:hover:text-blue-400 transition-colors mr-2">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-[#3B82F6] text-white text-[10px] font-bold uppercase tracking-widest hover:bg-blue-600 hover:-translate-y-0.5 transition-all shadow-md shadow-blue-500/30">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <!-- Main Hero Content -->
        <main class="flex-1 overflow-y-auto overflow-x-hidden relative w-full px-6 sm:px-8 lg:px-12 py-8 flex items-center">
            <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Kiri: Teks Informasi -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 text-[#3B82F6] dark:text-blue-400 text-[9px] font-extrabold tracking-widest uppercase shadow-sm transition-colors duration-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6] dark:bg-blue-400 animate-pulse"></span>
                        Platform Terpadu Asesmen Hukum & Medis
                    </div>

                    <!-- Judul Utama -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.1] transition-colors duration-500">
                        Manajemen Data <br>
                        <span class="text-[#3B82F6] dark:text-blue-400 transition-colors duration-500">Asesmen Terpadu</span> <br>
                        & Klien BNN.
                    </h1>

                    <!-- Deskripsi -->
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-lg transition-colors duration-500">
                        Sistem informasi modern untuk mengelola administrasi, rekam jejak perkara hukum, hasil asesmen medis, hingga rekomendasi Tim Asesmen Terpadu (TAT) secara akurat, cepat, dan terstruktur.
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        @auth
                            <a href="{{ url('/asesmen') }}" class="px-6 py-3 rounded-full bg-[#3B82F6] text-white font-bold text-[10px] tracking-widest uppercase shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40 hover:bg-blue-600 hover:-translate-y-1 transition-all flex items-center gap-2">
                                Buka Panel Aplikasi &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-3 rounded-full bg-[#3B82F6] text-white font-bold text-[10px] tracking-widest uppercase shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40 hover:bg-blue-600 hover:-translate-y-1 transition-all flex items-center gap-2">
                                Mulai Masuk Sistem &rarr;
                            </a>
                            <a href="{{ route('register') }}" class="px-6 py-3 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-200 border border-slate-200 dark:border-slate-700 font-bold text-[10px] tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-slate-700 hover:-translate-y-1 transition-all shadow-sm">
                                Pendaftaran Akun Baru
                            </a>
                        @endauth
                    </div>

                    <!-- Statistik / Fitur Bawah -->
                    <div class="grid grid-cols-3 gap-4 pt-6 mt-2 border-t border-slate-100 dark:border-slate-800 max-w-lg transition-colors duration-500">
                        <div>
                            <h4 class="text-2xl font-black text-slate-900 dark:text-white transition-colors duration-500 tracking-tight">100%</h4>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1 transition-colors duration-500">Data Terpusat & Aman</p>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-slate-900 dark:text-white transition-colors duration-500 tracking-tight">Ms.Excel</h4>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1 transition-colors duration-500">Impor & Ekspor Cepat</p>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-slate-900 dark:text-white transition-colors duration-500 tracking-tight">Ms.Word</h4>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1 transition-colors duration-500">Cetak Berita Acara</p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Card UI Interaktif -->
                <div class="lg:col-span-5 relative w-full max-w-[420px] mx-auto lg:ml-auto mt-10 lg:mt-0">
                    <!-- Glow halus di belakang card dalam -->
                    <div class="absolute -inset-2 bg-gradient-to-tr from-blue-50 to-slate-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-[2.5rem] blur-xl opacity-50 transition-colors duration-500"></div>

                    <div class="relative bg-white dark:bg-[#1E293B] rounded-[2rem] p-6 lg:p-8 border border-slate-100 dark:border-slate-700/50 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] animate-float transition-colors duration-500">

                        <!-- Mac Card Header Inner -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50 dark:border-slate-800 transition-colors duration-500">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#FF5F56]"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-[#FFBD2E]"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-[#27C93F]"></div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[8px] font-extrabold text-slate-300 dark:text-slate-500 uppercase tracking-widest transition-colors duration-500">Sistem Aktif</span>
                            </div>
                        </div>

                        <!-- Modul List -->
                        <div class="space-y-3">
                            <!-- Item 1 -->
                            <div class="p-3 lg:p-4 rounded-2xl bg-white dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 flex items-center justify-between group hover:shadow-md dark:hover:bg-[#0F172A] transition-all cursor-default">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-[#3B82F6] dark:text-blue-400 flex items-center justify-center shadow-sm border border-blue-100/50 dark:border-blue-800/50 transition-colors duration-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#3B82F6] dark:group-hover:text-blue-400 transition-colors duration-500">Modul Asesmen Terpadu</h5>
                                        <p class="text-[9px] text-slate-500 dark:text-slate-400 font-medium mt-0.5 transition-colors duration-500">Hukum, Medis & Rekomendasi</p>
                                    </div>
                                </div>
                                <span class="text-[8px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded border border-emerald-100/50 dark:border-emerald-800/50 transition-colors duration-500">Online</span>
                            </div>

                            <!-- Item 2 -->
                            <div class="p-3 lg:p-4 rounded-2xl bg-white dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 flex items-center justify-between group hover:shadow-md dark:hover:bg-[#0F172A] transition-all cursor-default">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/40 text-rose-500 dark:text-rose-400 flex items-center justify-center shadow-sm border border-rose-100/50 dark:border-rose-800/50 transition-colors duration-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors duration-500">Autentikasi Keamanan</h5>
                                        <p class="text-[9px] text-slate-500 dark:text-slate-400 font-medium mt-0.5 transition-colors duration-500">Proteksi Akses Petugas</p>
                                    </div>
                                </div>
                                <span class="text-[8px] font-bold text-[#3B82F6] dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded border border-blue-100/50 dark:border-blue-800/50 transition-colors duration-500">Secure</span>
                            </div>

                            <!-- Item 3 -->
                            <div class="p-3 lg:p-4 rounded-2xl bg-white dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 flex items-center justify-between group hover:shadow-md dark:hover:bg-[#0F172A] transition-all cursor-default">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shadow-sm border border-emerald-100/50 dark:border-emerald-800/50 transition-colors duration-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors duration-500">Basis Data BNNK Malang</h5>
                                        <p class="text-[9px] text-slate-500 dark:text-slate-400 font-medium mt-0.5 transition-colors duration-500">Enkripsi End-to-End Realtime</p>
                                    </div>
                                </div>
                                <span class="text-[8px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded border border-slate-200/50 dark:border-slate-700/50 transition-colors duration-500">Tersambung</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-2 text-center">
                            <p class="text-[8px] font-bold text-slate-300 dark:text-slate-600 transition-colors duration-500">Badan Narkotika Nasional Kota Malang © 2026</p>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer Bar -->
        <footer class="flex-none w-full px-6 sm:px-8 lg:px-12 py-5 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md transition-colors duration-500">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-t border-slate-100 dark:border-slate-800 pt-5 transition-colors duration-500">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Status Server: Berjalan Normal • Latensi 12ms
                </div>
                <div>
                    SI-TAT v2.4.0 (macOS Soft-UI Edition)
                </div>
            </div>
        </footer>

    </div>
</body>
</html>
