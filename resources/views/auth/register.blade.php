<x-guest-layout>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-white">
        
        <!-- SISI KIRI: Branding / Informasi -->
        <div class="hidden lg:flex lg:col-span-5 bg-slate-900 text-white p-12 flex-col justify-between relative overflow-hidden">
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
                    Pendaftaran Akun Baru
                </span>
                <h2 class="text-3xl font-extrabold tracking-tight leading-snug">
                    Bergabung dengan Tim Asesmen Terpadu.
                </h2>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Buat akun petugas untuk mulai mengelola, mengimpor, dan mengawasi rekam jejak asesmen klien secara aman.
                </p>
            </div>

            <div class="relative z-10 text-xs text-slate-500 font-medium">
                &copy; 2026 BNN Kota Malang. All rights reserved.
            </div>
        </div>

        <!-- SISI KANAN: Form Register -->
        <div class="col-span-1 lg:col-span-7 flex items-center justify-center p-8 sm:p-12 lg:p-16">
            <div class="w-full max-w-md space-y-6">
                
                <!-- Logo versi Mobile -->
                <div class="lg:hidden text-center space-y-2">
                    <div class="inline-flex w-12 h-12 rounded-2xl bg-slate-900 text-white items-center justify-center font-black text-2xl shadow-md">
                        B
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">SI-TAT BNN</h2>
                </div>

                <div class="space-y-1 text-center lg:text-left">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Buat Akun Baru</h2>
                    <p class="text-sm text-slate-500">Lengkapi formulir di bawah ini untuk pendaftaran akun.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Petugas</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-2.5 px-4">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-2.5 px-4">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                        <input type="password" name="password" required autocomplete="new-password" 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-2.5 px-4">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-2.5 px-4">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-2">
                        <button type="submit" style="background-color: #3890f5;" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-500/25 hover:opacity-95 transition flex items-center justify-center gap-2">
                            Daftarkan Akun &rarr;
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-xs text-slate-500">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="font-bold text-[#3890f5] hover:underline">Masuk di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>