<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SI-TAT BNN</title>

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
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-blob { animation: blob 15s infinite alternate; }
        .animation-delay-2000 { animation-delay: 4s; }
        .animation-delay-4000 { animation-delay: 8s; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @media (prefers-color-scheme: dark) {
            ::-webkit-scrollbar-thumb { background: #334155; }
            ::-webkit-scrollbar-thumb:hover { background: #475569; }
        }

        /* Menghilangkan icon mata bawaan dari browser (Microsoft Edge, dll) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>
<body class="antialiased bg-[#F8FAFC] dark:bg-slate-900 font-sans text-slate-800 dark:text-slate-200 selection:bg-blue-500 selection:text-white h-screen w-screen overflow-hidden flex items-center justify-center p-4 sm:p-6 lg:p-8 relative transition-colors duration-500">

    <!-- Ambient Background Blobs -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-900/30 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-900/30 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-900/30 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- KONTINER UTAMA (APP FRAME) -->
    <div class="relative z-10 w-full max-w-[1300px] h-full max-h-[900px] bg-white dark:bg-slate-900 rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.5)] border border-white/80 dark:border-slate-800 flex flex-col overflow-hidden transition-colors duration-500">

        <!-- Header / Navbar -->
        <header class="flex-none w-full pt-6 pb-3 px-6 lg:px-10 border-b border-slate-100 dark:border-slate-800/80 transition-colors duration-500">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Mac Window Controls -->
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500 shadow-sm"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400 shadow-sm"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm"></div>
                    </div>

                    <!-- Logo & Brand -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 border-l border-slate-200 dark:border-slate-700 pl-4 sm:pl-6 group">
                        <img src="{{ asset('logo-bnn.jpg') }}" alt="Logo BNN" class="object-contain drop-shadow-sm rounded-full group-hover:scale-105 transition-transform" style="width: 32px; height: 32px;">
                        <div class="flex flex-col justify-center">
                            <h1 class="font-black text-[13px] tracking-tight text-slate-900 dark:text-white leading-none mb-1">SI-TAT BNN</h1>
                            <p class="text-[8px] text-slate-500 font-bold leading-tight uppercase tracking-widest">
                                Badan Narkotika Nasional Kota Malang
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Actions (Mode Login: Tombol Masuk yang nyala) -->
                <nav class="flex items-center gap-3">
                    <div class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest shadow-sm flex items-center gap-2 cursor-default">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                        Masuk
                    </div>
                    <a href="{{ route('register') }}" class="hidden sm:block text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase tracking-widest hover:text-blue-600 dark:hover:text-blue-400 transition-colors ml-2">
                        Daftar Akun
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 w-full h-full flex flex-col-reverse lg:flex-row overflow-hidden relative">

            <!-- Sisi Kiri: Form Login -->
            <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-6 sm:p-8 lg:p-12 overflow-y-auto">
                <div class="w-full max-w-[400px] mx-auto space-y-8 pb-10 lg:pb-0">

                    <!-- Header Form -->
                    <div class="text-center space-y-3">
                        <div class="inline-flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-md border border-slate-100 dark:border-slate-700/50 mb-2" style="width: 48px; height: 48px;">
                            <img src="{{ asset('logo-bnn.jpg') }}" alt="Logo BNN" class="object-contain rounded-full" style="width: 32px; height: 32px;">
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Selamat Datang Kembali</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors">Silakan masukkan kredensial akun Anda untuk mengakses sistem.</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 text-center text-sm font-bold text-emerald-600 bg-emerald-50 py-2 rounded-xl" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest transition-colors ml-1">Email Petugas</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="petugas@bnn.go.id"
                                class="block w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs px-4 py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm placeholder:text-slate-400">
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] font-bold text-rose-500" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between mb-1.5 ml-1">
                                <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest transition-colors">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 hover:underline transition-colors">
                                        Lupa sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                                    class="block w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs pl-4 pr-10 py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm placeholder:text-slate-400">
                                <button type="button" onclick="togglePassword('password', 'eye-icon-pass')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-500 transition-colors focus:outline-none">
                                    <svg id="eye-icon-pass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] font-bold text-rose-500" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pl-1">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 dark:border-slate-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-slate-900 dark:checked:bg-blue-500">
                                <span class="ms-2 text-[11px] font-medium text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">Ingat perangkat ini</span>
                            </label>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-4">
                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-[11px] uppercase tracking-widest shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                Masuk Ke Sistem &rarr;
                            </button>
                        </div>

                        @if (Route::has('register'))
                            <div class="text-center pt-2">
                                <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                    Belum memiliki akun petugas?
                                    <a href="{{ route('register') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 hover:underline">Daftar sekarang</a>
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Sisi Kanan: Biru Gelap (Branding Card) -->
            <div class="hidden lg:flex lg:w-1/2 h-full p-6">
                <div class="w-full h-full bg-slate-900 rounded-[2rem] p-8 xl:p-10 flex flex-col justify-between relative overflow-hidden shadow-2xl border border-slate-800 animate-float">

                    <!-- Inner Card Blobs -->
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-600/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Inner Logo -->
                    <div class="relative z-10 flex items-center gap-3">
                        <img src="{{ asset('logo-bnn.jpg') }}" alt="Logo BNN" class="object-contain rounded-full shadow-md ring-2 ring-white/10" style="width: 40px; height: 40px;">
                        <div>
                            <h1 class="font-bold text-sm tracking-tight text-white">SI-TAT BNN</h1>
                            <p class="text-[9px] text-slate-400 font-medium tracking-wide">Badan Narkotika Nasional Kota Malang</p>
                        </div>
                    </div>

                    <!-- Inner Text Content -->
                    <div class="relative z-10 flex flex-col gap-6 my-auto max-w-md">
                        <div class="w-fit inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/20 border border-blue-500/30 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse shrink-0"></span>
                            <span class="text-blue-300 text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Portal Petugas Resmi</span>
                        </div>
                        <h2 class="text-3xl xl:text-4xl 2xl:text-[2.5rem] font-black tracking-tight text-white leading-tight">
                            Masuk ke Sistem Asesmen Terpadu.
                        </h2>
                        <p class="text-sm text-slate-400 font-medium leading-relaxed">
                            Kelola data klien, rekam jejak perkara hukum, asesmen medis, dan laporan rekapitulasi dengan aman dan terpusat dalam ekosistem terintegrasi.
                        </p>
                    </div>

                    <!-- Inner Footer -->
                    <div class="relative z-10 flex items-center justify-between text-[10px] text-slate-500 font-medium w-full">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="whitespace-nowrap">&copy; 2026 BNN Kota Malang.</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-emerald-500/20 px-2.5 py-1 rounded-full border border-emerald-500/30 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            <span class="text-emerald-400 font-bold uppercase tracking-widest whitespace-nowrap">Server Aman</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- Footer Bar -->
        <footer class="flex-none w-full px-6 sm:px-8 lg:px-10 py-5 border-t border-slate-100 dark:border-slate-800/50 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Keamanan Aktif • Enkripsi Akses Petugas Terpadu
                </div>
                <div>
                    SI-TAT v2.4.0 (macOS Soft-UI Edition)
                </div>
            </div>
        </footer>

    </div>

    <!-- Script Show/Hide Password -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
</body>
</html>
