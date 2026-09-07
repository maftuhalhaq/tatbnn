<x-guest-layout>
    <!-- Kontainer utama untuk memusatkan posisi di tengah layar -->
    <div class="min-h-[80vh] flex items-center justify-center p-4">
        
        <!-- Card Putih dengan bayangan halus dan lebar dibatasi -->
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-10 text-center relative overflow-hidden">

            <!-- Garis dekorasi di bagian atas -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-blue-600"></div>

            <!-- Ikon Jam Pasir Animasi -->
            <div class="mx-auto w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 relative border-4 border-white outline outline-4 outline-indigo-50 shadow-sm">
                <svg class="w-10 h-10 text-indigo-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <!-- Titik notifikasi kecil di sudut ikon -->
                <div class="absolute top-1 right-1 w-3.5 h-3.5 bg-amber-400 rounded-full border-2 border-white animate-bounce"></div>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-900 mb-3 tracking-tight">Akun Menunggu Persetujuan</h2>
            
            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                Pendaftaran Anda berhasil. Demi menjaga keamanan Sistem TAT BNN, akun baru <span class="font-bold text-slate-700">memerlukan verifikasi Admin</span> sebelum dapat digunakan.
            </p>

            <!-- Kotak Peringatan/Instruksi -->
            <div class="bg-amber-50 border border-amber-200/60 rounded-2xl p-4 mb-8 text-left flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-[12px] font-medium text-amber-800 leading-relaxed">
                    Silakan hubungi Administrator untuk meminta aktivasi akun. Jika sudah disetujui, Anda dapat masuk melalui halaman login.
                </p>
            </div>

            <!-- Tombol Kembali -->
            <a href="{{ route('login') }}" class="inline-flex justify-center items-center w-full px-6 py-3.5 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Halaman Login
            </a>
            
        </div>
    </div>
</x-guest-layout>