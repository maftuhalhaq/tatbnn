<x-app-layout>
    <!-- Tambahan CSS untuk Animasi Masuk & Background Blobs -->
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

        /* Animasi Blobs Latar Belakang */
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
        .animate-blob { animation: blob 15s infinite alternate; }
        .animation-delay-2000 { animation-delay: 4s; }
        .animation-delay-4000 { animation-delay: 8s; }
    </style>

    <!-- BACKGROUND BASE & BLOBS (Lapisan paling belakang) -->
    <div class="fixed inset-0 z-0 bg-[#F8FAFC] dark:bg-[#0B1120] transition-colors duration-500 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- HEADER (Dikeluarkan dari kotak putih, menyatu dengan background) -->
    <x-slot name="header">
        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 max-w-[1200px] mx-auto w-full">
            <div class="flex items-center gap-4">
                <!-- Ikon Kotak Biru di Kiri -->
                <div class="w-12 h-12 rounded-[1rem] bg-indigo-50 dark:bg-[#1E293B] text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-slate-700/50 shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-900 dark:text-white tracking-tight">
                        Edit Petugas: {{ $user->name }}
                    </h2>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Perbarui identitas atau reset kata sandi petugas jika lupa.</p>
                </div>
            </div>

            <a href="{{ route('users.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-[#1E293B] border border-slate-200 dark:border-slate-700/50 rounded-full font-black text-[10px] text-slate-600 dark:text-slate-300 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white shadow-sm transition-all group">
                <svg class="w-4 h-4 mr-1.5 text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="relative z-10 py-8 min-h-screen">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- KONTAINER FORM MAC SOFT UI -->
            <div class="bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-white/60 dark:border-slate-700/50 overflow-hidden transition-colors duration-300">
                <div class="p-8 sm:px-10 py-10">

                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-12">
                        @csrf
                        @method('PATCH')

                        <!-- ===================================== -->
                        <!-- BAGIAN 1: IDENTITAS -->
                        <!-- ===================================== -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-in-up delay-100">
                            <!-- Kolom Kiri: Deskripsi -->
                            <div class="md:col-span-1 pt-2">
                                <h3 class="text-[17px] font-black text-slate-900 dark:text-white tracking-tight">Identitas Petugas</h3>
                                <p class="mt-2 text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed pr-4">
                                    Perbarui nama lengkap dan alamat email yang digunakan oleh petugas ini untuk masuk ke dalam sistem.
                                </p>
                            </div>

                            <!-- Kolom Kanan: Card Inner -->
                            <div class="md:col-span-2">
                                <div class="bg-white dark:bg-[#0F172A] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] dark:shadow-none rounded-[2rem] border border-slate-100 dark:border-slate-700/50 transition-colors duration-300 overflow-hidden relative">
                                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6]"></div>
                                    <div class="p-6 sm:p-8">
                                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="block w-full py-3 px-4 rounded-[1.25rem] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm">
                                                @error('name') <span class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Login</label>
                                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="block w-full py-3 px-4 rounded-[1.25rem] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm">
                                                @error('email') <span class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DIVIDER -->
                        <div class="hidden md:block border-t border-slate-100 dark:border-slate-800/60 w-full fade-in-up delay-100"></div>

                        <!-- ===================================== -->
                        <!-- BAGIAN 2: RESET PASSWORD -->
                        <!-- ===================================== -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-in-up delay-200">
                            <!-- Kolom Kiri: Deskripsi -->
                            <div class="md:col-span-1 pt-2">
                                <h3 class="text-[17px] font-black text-slate-900 dark:text-white tracking-tight">Reset Kata Sandi</h3>
                                <p class="mt-2 text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed pr-4">
                                    Jika petugas lupa kata sandinya, Anda dapat membuatkan kata sandi baru di sini.
                                </p>
                                <div class="inline-flex items-center gap-2 mt-4 px-3 py-2 bg-amber-50 dark:bg-amber-500/10 border border-amber-200/50 dark:border-amber-500/20 rounded-xl shadow-sm">
                                    <svg class="w-4 h-4 text-amber-500 dark:text-[#FFBD2E]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-amber-600 dark:text-[#FFBD2E]">Biarkan kosong jika tidak diubah.</span>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Card Inner -->
                            <div class="md:col-span-2">
                                <div class="bg-white dark:bg-[#0F172A] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] dark:shadow-none rounded-[2rem] border border-slate-100 dark:border-slate-700/50 transition-colors duration-300 overflow-hidden relative">
                                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#FFBD2E]"></div>
                                    <div class="p-6 sm:p-8">
                                        <div class="max-w-md">
                                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Kata Sandi Baru</label>

                                            <!-- STRUKTUR INPUT PASSWORD -->
                                            <div class="relative rounded-[1.25rem] shadow-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </div>
                                                <input type="password" id="password_input" name="password" placeholder="Minimal 8 karakter..." class="block w-full py-3 pl-11 pr-11 rounded-[1.25rem] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 dark:text-slate-500 hover:text-amber-500 dark:hover:text-[#FFBD2E] focus:outline-none transition-colors" title="Lihat/Sembunyikan Kata Sandi">
                                                    <svg id="eye_open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg id="eye_closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0l1.414 1.414M15 15l1.414 1.414" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('password') <span class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL SIMPAN -->
                        <div class="border-t border-slate-100 dark:border-slate-800/60 pt-8 fade-in-up delay-200 flex justify-end">
                            <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#4F46E5] dark:bg-[#3B82F6] text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-indigo-600 dark:hover:bg-blue-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-500/30 dark:shadow-blue-900/40">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

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
