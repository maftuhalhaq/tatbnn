<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Berita Acara TAT</h2>
                <!-- Menampilkan nama klien langsung di header -->
                <p class="text-sm text-slate-500 mt-1">Data Klien: <span class="font-semibold text-slate-700">{{ $asesmen->nama_lengkap }}</span></p>
            </div>

            <!-- Tombol Kembali -->
            <div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-wider hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Form / Detail Berita Acara</h3>
                <hr class="mb-4">

                <!-- Contoh memanggil data dari database -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-slate-500">Nama Lengkap</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">NIK</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Perkara</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</p>
                    </div>
                </div>

                <!-- Silakan lanjutkan desain form atau dokumen berita acara di sini -->
                <div class="p-4 bg-blue-50 text-blue-700 rounded-lg border border-blue-200">
                    Silakan tambahkan elemen form, tabel cetak, atau desain dokumen Berita Acara Anda di bagian ini.
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
