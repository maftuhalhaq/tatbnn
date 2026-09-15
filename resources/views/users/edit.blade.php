<x-app-layout>
    <!-- Tambahan CSS untuk Animasi Masuk -->
    <style>
        .fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl shadow-sm border border-indigo-100 hidden sm:flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                        Edit Petugas: {{ $user->name }}
                    </h2>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Perbarui identitas atau reset kata sandi petugas jika lupa.</p>
                </div>
            </div>
            
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-bold text-xs text-slate-700 uppercase tracking-wider hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition-all group">
                <svg class="w-4 h-4 mr-1.5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-10">
                @csrf
                @method('PATCH')

                <!-- ===================================== -->
                <!-- BAGIAN 1: IDENTITAS (DESAIN GRID UI/UX) -->
                <!-- ===================================== -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-in-up delay-100">
                    <div class="md:col-span-1">
                        <div class="px-2 sm:px-0">
                            <h3 class="text-lg font-extrabold text-slate-900">Identitas Petugas</h3>
                            <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                                Perbarui nama lengkap dan alamat email yang digunakan oleh petugas ini untuk masuk ke dalam sistem.
                            </p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="bg-white shadow-sm hover:shadow-md rounded-2xl border border-slate-200/80 transition-shadow duration-300 overflow-hidden relative">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                            <div class="p-6 sm:p-8">
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm transition-all bg-slate-50 focus:bg-white py-2.5">
                                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Login</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm transition-all bg-slate-50 focus:bg-white py-2.5">
                                        @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block border-t border-slate-200/60"></div>

                <!-- ===================================== -->
                <!-- BAGIAN 2: RESET PASSWORD -->
                <!-- ===================================== -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-in-up delay-200">
                    <div class="md:col-span-1">
                        <div class="px-2 sm:px-0">
                            <h3 class="text-lg font-extrabold text-slate-900">Reset Kata Sandi</h3>
                            <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                                Jika petugas lupa kata sandinya, Anda dapat membuatkan kata sandi baru di sini.<br><br>
                                <span class="font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100 shadow-sm inline-block">Biarkan kosong jika tidak diubah.</span>
                            </p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="bg-white shadow-sm hover:shadow-md rounded-2xl border border-slate-200/80 transition-shadow duration-300 overflow-hidden relative">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                            <div class="p-6 sm:p-8">
                                
                                <div class="max-w-md">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                                    
                                    <!-- STRUKTUR INPUT PASSWORD DIPERBAIKI SECARA PIXEL-PERFECT -->
                                    <div class="relative rounded-xl shadow-sm">
                                        <!-- Ikon Gembok Kiri (Flex Center mutlak) -->
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        
                                        <!-- Form Input (Telah diberikan pl-10 dan pr-10 agar teks di tengah aman) -->
                                        <input type="password" id="password_input" name="password" placeholder="Minimal 8 karakter..." class="block w-full py-2.5 pl-10 pr-10 rounded-xl border-slate-300 text-slate-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                        
                                        <!-- Ikon Mata Kanan (Tombol Interaktif) -->
                                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-amber-600 focus:outline-none transition-colors" title="Lihat/Sembunyikan Kata Sandi">
                                            <svg id="eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0l1.414 1.414M15 15l1.414 1.414" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================================== -->
                <!-- DIVIDER & SUBMIT BUTTON -->
                <!-- ===================================== -->
                <div class="border-t border-slate-200 pt-6 fade-in-up delay-200 flex justify-end">
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-200 focus:ring-4 focus:ring-indigo-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Script Interaktif untuk Fitur Toggle Mata -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password_input');
            const eyeOpen = document.getElementById('eye_open');
            const eyeClosed = document.getElementById('eye_closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>