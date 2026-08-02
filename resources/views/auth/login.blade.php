<x-guest-layout>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-white">
        
        <!-- SISI KIRI: Branding / Informasi (Hanya tampil di layar besar/lg) -->
        <div class="hidden lg:flex lg:col-span-5 bg-slate-900 text-white p-12 flex-col justify-between relative overflow-hidden">
            <!-- Efek dekoratif latar belakang -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-white text-slate-900 flex items-center justify-center font-black text-xl shadow-md group-hover:scale-105 transition">
                        B
                    </div>
                    <div>
                        <h1 class="font-extrabold text-base tracking-tight text-white">SI-TAT BNN</h1>
                        <p class="text-xs text-slate-400 font-medium">Badan Narkotika Nasional</p>
                    </div>
                </a>
            </div>

            <div class="relative z-10 space-y-4 my-auto">
                <span class="px-3 py-1 rounded-full bg-blue-500/10 text-[#3890f5] border border-blue-500/20 text-xs font-bold uppercase tracking-wider">
                    Portal Petugas Resmi
                </span>
                <h2 class="text-3xl font-extrabold tracking-tight leading-snug">
                    Masuk ke Sistem Asesmen Terpadu.
                </h2>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Kelola data klien, rekam jejak perkara hukum, asesmen medis, dan laporan rekapitulasi dengan aman dan terpusat.
                </p>
            </div>

            <div class="relative z-10 text-xs text-slate-500 font-medium">
                &copy; 2026 BNN Kota Malang. All rights reserved.
            </div>
        </div>

        <!-- SISI KANAN: Form Login -->
        <div class="col-span-1 lg:col-span-7 flex items-center justify-center p-8 sm:p-12 lg:p-16">
            <div class="w-full max-w-md space-y-8">
                
                <!-- Logo versi Mobile -->
                <div class="lg:hidden text-center space-y-2">
                    <div class="inline-flex w-12 h-12 rounded-2xl bg-slate-900 text-white items-center justify-center font-black text-2xl shadow-md">
                        B
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">SI-TAT BNN</h2>
                </div>

                <div class="space-y-2 text-center lg:text-left">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                    <p class="text-sm text-slate-500">Silakan masukkan kredensial akun Anda untuk mengakses sistem.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Petugas</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-3 px-4">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#3890f5] hover:underline">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <input type="password" name="password" required autocomplete="current-password" 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-3 px-4">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-[#3890f5] focus:ring-[#3890f5]">
                            <span class="ms-2 text-xs font-medium text-slate-600">Ingat perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-2">
                        <button type="submit" style="background-color: #3890f5;" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-500/25 hover:opacity-95 transition flex items-center justify-center gap-2">
                            Masuk ke Sistem &rarr;
                        </button>
                    </div>

                    @if (Route::has('register'))
                        <div class="text-center pt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-500">
                                Belum memiliki akun petugas? 
                                <a href="{{ route('register') }}" class="font-bold text-[#3890f5] hover:underline">Daftar sekarang</a>
                            </p>
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>