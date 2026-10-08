<x-app-layout>
    <!-- CSS Custom untuk Scrollbar & Animasi -->
    <style>
        .custom-select-scroll::-webkit-scrollbar { width: 6px; }
        .custom-select-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-select-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media (prefers-color-scheme: dark) {
            .custom-select-scroll::-webkit-scrollbar-thumb { background: #334155; }
            .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
        }

        /* Animasi Blobs Latar Belakang */
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

    <!-- BACKGROUND BASE & BLOBS (Berada di lapisan paling belakang) -->
    <div class="fixed inset-0 z-0 bg-[#F8FAFC] dark:bg-[#0B1120] transition-colors duration-500 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    @php
        // 1. Penghitungan Usia Klien
        $usiaKlien = '-';
        if (!empty($asesmen->tgl_lahir) && !empty($asesmen->created_at)) {
            try {
                $usiaKlien = \Carbon\Carbon::parse($asesmen->tgl_lahir)->diff(\Carbon\Carbon::parse($asesmen->created_at))->y . ' Tahun';
            } catch (\Exception $e) { $usiaKlien = '-'; }
        }

        // 2. LOGIKA KELENGKAPAN DATA (REVISI MUTLAK 100% FIELD)
        $kolomWajib = [
            'no_register', 'no_bln', 'asal_pengajuan', 'no_surat_pengajuan', 'no_lkn',
            'tgl_surat', 'tgl_berkas', 'tgl_pelaksanaan', 'tgl_tangkap',
            'nama_lengkap', 'nik', 'kewarganegaraan', 'agama', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'no_hp',
            'pendidikan_id', 'pekerjaan_id', 'penghasilan_rata_rata', 'alamat_ktp', 'alamat_domisili',
            'narkotika_id', 'berat_bb', 'deskripsi_bb', 'pasal_sangkaan',
            'status_hukum', 'keterlibatan_jaringan', 'cara_mendapatkan', 'dapat_dari_siapa',
            'tes_urine', 'hasil_asesmen_hukum', 'hasil_asesmen_medis',
            'aspek_hukum', 'aspek_medis', 'kesehatan_fisik', 'psikologi',
            'alasan_penggunaan', 'kondisi_keluarga', 'tingkat_ketergantungan', 'pola_pemakaian', 'kondisi_lingkungan', 'saran_case_conference'
        ];

        $isDataUtamaLengkap = true;
        foreach($kolomWajib as $kol) {
            if ($asesmen->$kol === null || trim((string)$asesmen->$kol) === '') {
                $isDataUtamaLengkap = false;
                break;
            }
        }

        // Berita Acara Terisi (Minimal Nomor BA & Ketua TAT)
        $isBaLengkap = !empty($asesmen->no_ba) && !empty($asesmen->ketua_tat_nama);

        // Rekomendasi Terisi (Minimal No Surat & Kepada Yth)
        $isRekomLengkap = !empty($asesmen->no_surat_rekomendasi) && !empty($asesmen->kepada_yth);

        // Final Lengkap Status
        $isLengkapTotal = $isDataUtamaLengkap && $isBaLengkap && $isRekomLengkap;
    @endphp

    <!-- HEADER (Dikeluarkan dari kotak putih, menyatu dengan background) -->
    <x-slot name="header">
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 max-w-[1400px] mx-auto w-full">
            <div class="flex items-center gap-4">
                <a href="{{ route('asesmen.index') }}" class="w-12 h-12 rounded-[1rem] bg-white dark:bg-[#1E293B] text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-slate-200 dark:border-slate-700/50 shadow-sm shrink-0 hover:scale-105 transition-transform group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-1 border border-blue-200 dark:border-blue-800/50 shadow-sm">
                        Pusat Data Klien
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                        {{ $asesmen->nama_lengkap }}
                        @if($isLengkapTotal)
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">DATA LENGKAP</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-500 border border-amber-200 dark:border-amber-800/50 shadow-sm">BELUM LENGKAP</span>
                        @endif
                    </h1>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                @can('manage-data')
                <a href="{{ route('asesmen.edit', $asesmen->id) }}" class="inline-flex items-center px-6 py-3 bg-[#FFBD2E] hover:bg-amber-500 text-amber-900 font-black text-[11px] uppercase tracking-widest rounded-[1.25rem] transition shadow-lg shadow-amber-500/30 transform-gpu hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data Utama
                </a>
                @endcan
                <a href="{{ route('asesmen.cetakPdf', $asesmen->id) }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-[#FF5F56] hover:bg-rose-600 text-white font-black text-[11px] uppercase tracking-widest rounded-[1.25rem] transition shadow-lg shadow-rose-500/30 transform-gpu hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="relative z-10 py-8 min-h-screen">
        <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- KONTAINER RAKSASA MAC SOFT UI -->
            <div class="bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-white/60 dark:border-slate-700/50 overflow-hidden transition-colors duration-300 p-6 sm:p-10 space-y-8">

                <!-- ========================================== -->
                <!-- CARD 1: PROFIL KLIEN                       -->
                <!-- ========================================== -->
                <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden z-30 transition-colors">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6]"></div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-[#3B82F6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        1. Profil Klien
                    </h3>

                    <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
                        <!-- FOTO KLIEN -->
                        <div class="flex flex-col items-center shrink-0 w-full md:w-auto">
                            <div class="w-32 h-44 bg-slate-50 dark:bg-[#1E293B] rounded-[1.5rem] border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 relative flex items-center justify-center">
                                @if(!empty($asesmen->foto_klien))
                                    <img src="{{ asset('storage/' . $asesmen->foto_klien) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                @endif
                            </div>
                            <h4 class="font-black text-[15px] text-slate-900 dark:text-white text-center leading-tight max-w-[150px]">{{ $asesmen->nama_lengkap ?? '-' }}</h4>
                            <span class="inline-flex mt-2 px-3 py-1 bg-blue-50 dark:bg-[#1E293B] text-[#3B82F6] dark:text-blue-400 text-[10px] font-black rounded-lg border border-blue-100 dark:border-blue-900/50 shadow-inner">Usia: {{ $usiaKlien }}</span>
                        </div>

                        <!-- LIST DATA PROFIL -->
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8">
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NIK KTP</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->nik ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Kelamin</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-Laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tempat Lahir</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tempat_lahir ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Lahir</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->format('d-m-Y') : '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Agama</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->agama ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kewarganegaraan</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kewarganegaraan ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pekerjaan</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->pekerjaan_input ?? $asesmen->pekerjaan->nama_pekerjaan ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pendidikan Terakhir</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->pendidikan_input ?? $asesmen->pendidikan->nama_pendidikan ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. Handphone</span>
                                <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_hp ?? '-' }}</div>
                            </div>
                            <div class="sm:col-span-2 lg:col-span-3">
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Penghasilan Rata-rata</span>
                                <div class="block w-full rounded-[1.25rem] border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/10 px-4 py-3 text-[13px] font-black text-emerald-600 dark:text-emerald-400 shadow-sm">{{ $asesmen->penghasilan_rata_rata ?? '-' }}</div>
                            </div>

                            <!-- Alamat Blok -->
                            <div class="sm:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 border-t border-slate-100 dark:border-slate-800 pt-6">
                                <div>
                                    <span class="block text-[10px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest mb-1.5 ml-1">Alamat Sesuai KTP</span>
                                    <div class="block w-full rounded-[1.25rem] border border-blue-100 dark:border-blue-900/50 bg-blue-50/50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-semibold leading-relaxed text-slate-800 dark:text-slate-200 shadow-inner h-full min-h-[80px]">
                                        {{ $asesmen->alamat_ktp ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest mb-1.5 ml-1">Alamat Domisili Saat Ini</span>
                                    <div class="block w-full rounded-[1.25rem] border border-blue-100 dark:border-blue-900/50 bg-blue-50/50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-semibold leading-relaxed text-slate-800 dark:text-slate-200 shadow-inner h-full min-h-[80px]">
                                        {{ $asesmen->alamat_domisili ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 2: DATA ADMINISTRASI                  -->
                <!-- ========================================== -->
                <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden z-20 transition-colors mt-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500"></div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        2. Data Administrasi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No / Bulan</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_bln ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No Register</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_register ?? '-' }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Asal Pengajuan</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->asal_pengajuan ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Surat</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->format('d-m-Y') : '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tgl Berkas Diterima</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('d-m-Y') : '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tgl Penangkapan</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->format('d-m-Y') : '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-1.5 ml-1">Tgl Pelaksanaan</span>
                            <div class="block w-full rounded-[1.25rem] border border-indigo-200 dark:border-indigo-800/50 bg-indigo-50 dark:bg-indigo-900/10 px-4 py-3 text-[13px] font-black text-indigo-700 dark:text-indigo-400 shadow-sm">{{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('d-m-Y') : '-' }}</div>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No Surat Pengajuan</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_surat_pengajuan ?? '-' }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No LKN / LP / LI</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_lkn ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 3: CASE CONFERENCE                    -->
                <!-- ========================================== -->
                <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden z-20 transition-colors mt-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#FFBD2E]"></div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-8 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-[#FFBD2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        3. Perkara Hukum, Medis & Rekomendasi TAT
                    </h3>

                    <!-- A. SIDANG HUKUM & BARANG BUKTI -->
                    <div class="flex items-center gap-3 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 p-3 rounded-xl mb-6">
                        <span class="bg-amber-200 dark:bg-amber-600 text-amber-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">A</span>
                        <span class="text-[11px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest">Sidang Hukum & Barang Bukti</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status Hukum</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->status_hukum ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterlibatan Jaringan</span>
                            <div class="block w-full rounded-[1.25rem] border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-900/10 px-4 py-3 text-[13px] font-black uppercase text-rose-600 dark:text-rose-400 shadow-sm">{{ $asesmen->keterlibatan_jaringan ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Zat / Narkotika</span>
                            <!-- SINKRONISASI UPDATE DROPDOWN -->
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-900 dark:text-white shadow-sm">{{ $asesmen->narkotika_input ?? $asesmen->narkotika->jenis_narkotika ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Berat Bukti (BB)</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-900 dark:text-white shadow-sm">{{ $asesmen->berat_bb ? $asesmen->berat_bb.' Gram' : '-' }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Detail Barang Bukti</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-medium leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->deskripsi_bb ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Cara Mendapatkan</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->cara_mendapatkan ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dapat Dari Siapa</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->dapat_dari_siapa ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- B. SIDANG MEDIS -->
                    <div class="flex items-center gap-3 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 p-3 rounded-xl mb-6 mt-8">
                        <span class="bg-blue-200 dark:bg-blue-600 text-blue-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">B</span>
                        <span class="text-[11px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest">Sidang Medis</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kesehatan (Fisik)</span>
                            <div class="block w-full min-h-[60px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium whitespace-pre-line leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kesehatan_fisik ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Psikologi</span>
                            <div class="block w-full min-h-[60px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium whitespace-pre-line leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->psikologi ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Tes Urine</span>
                            @php
                                $urineText = $asesmen->tes_urine ?? '';
                                $urineFirstWord = strtoupper(strtok($urineText, " :,-"));
                            @endphp
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 shadow-sm">
                                @if($urineFirstWord === 'POSITIF')
                                    <span class="inline-block px-3 py-1 rounded-md text-[11px] font-black bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 tracking-widest uppercase border border-rose-200/50 dark:border-rose-800/50">{{ $asesmen->tes_urine }}</span>
                                @elseif($urineFirstWord === 'NEGATIF')
                                    <span class="inline-block px-3 py-1 rounded-md text-[11px] font-black bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 tracking-widest uppercase border border-emerald-200/50 dark:border-emerald-800/50">{{ $asesmen->tes_urine }}</span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-md text-[11px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 tracking-widest uppercase">{{ $asesmen->tes_urine ?? '-' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- C. KONDISI SOSIO-PSIKOLOGIS & POLA PEMAKAIAN -->
                    <div class="flex items-center gap-3 bg-purple-50/50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-800/30 p-3 rounded-xl mb-6 mt-8">
                        <span class="bg-purple-200 dark:bg-purple-600 text-purple-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">C</span>
                        <span class="text-[11px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">Kondisi Sosio-Psikologis & Pola Pemakaian</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Alasan Penggunaan</span>
                            <div class="block w-full min-h-[60px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium whitespace-pre-line leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->alasan_penggunaan ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kondisi Keluarga</span>
                            <div class="block w-full min-h-[60px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium whitespace-pre-line leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kondisi_keluarga ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kondisi Lingkungan</span>
                        <div class="block w-full min-h-[60px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium whitespace-pre-line leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kondisi_lingkungan ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tingkat Ketergantungan</span>
                            <div class="block w-full rounded-[1.25rem] border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-900/10 px-4 py-3 text-[13px] font-black text-rose-600 dark:text-rose-400 shadow-sm">{{ $asesmen->tingkat_ketergantungan ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pola Pemakaian</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->pola_pemakaian ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- D. KEPUTUSAN REKOMENDASI & SARAN -->
                    <div class="flex items-center gap-3 bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 p-3 rounded-xl mb-6 mt-8">
                        <span class="bg-emerald-200 dark:bg-emerald-600 text-emerald-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">D</span>
                        <span class="text-[11px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Keputusan Rekomendasi & Saran</span>
                    </div>

                    <div class="mb-5">
                        <span class="block text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1.5 ml-1">Rekomendasi TAT (Instansi)</span>
                        <div class="inline-block bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-400 font-black px-4 py-2.5 rounded-[1rem] text-[13px] border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                            {{ $asesmen->rekomendasi_input ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Saran Sidang Case Conference</span>
                        <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] p-5 shadow-sm">
                            @php
                                $saranList = array_filter(array_map('trim', explode(',', $asesmen->saran_case_conference ?? '')));
                            @endphp
                            @if(count($saranList) > 0)
                                <ul class="list-decimal list-inside text-[13px] font-bold text-slate-800 dark:text-slate-200 space-y-2 leading-relaxed">
                                    @foreach($saranList as $saran) <li>{{ $saran }}</li> @endforeach
                                </ul>
                            @else
                                <div class="text-[13px] font-bold text-slate-400 dark:text-slate-500 text-center py-2 uppercase tracking-widest">- Belum ada saran -</div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- CARD 4: HASIL ASESMEN FINAL & PELAKSANAAN -->
                <!-- ========================================== -->
                <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden z-20 transition-colors mt-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#27C93F]"></div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-[#27C93F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        4. Hasil Asesmen Final & Pelaksanaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pasal Yang Disangkakan</span>
                            <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-900 dark:text-white shadow-sm">{{ $asesmen->pasal_sangkaan ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Asesmen Hukum Final</span>
                            <div class="block w-full min-h-[80px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium leading-relaxed whitespace-pre-line text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->hasil_asesmen_hukum ?? '-' }}</div>
                        </div>
                        <div>
                            <!-- SINKRONISASI UPDATE LABEL -->
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Asesmen Medis Final</span>
                            <div class="block w-full min-h-[80px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium leading-relaxed whitespace-pre-line text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->hasil_asesmen_medis ?? '-' }}</div>
                        </div>
                        <div class="md:col-span-2 pt-2 border-t border-slate-100 dark:border-slate-800 mt-2">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Status Pelaksanaan Rekomendasi</span>
                            @if($asesmen->pelaksanaan === 'YA')
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-4 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> SUDAH DILAKSANAKAN (YA)
                                </span>
                            @elseif($asesmen->pelaksanaan === 'TIDAK')
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-widest text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/40 px-4 py-2.5 rounded-xl border border-rose-200 dark:border-rose-800/50 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg> BELUM / BATAL DILAKSANAKAN (TIDAK)
                                </span>
                            @else
                                <span class="text-[13px] font-bold text-slate-400 dark:text-slate-500">-</span>
                            @endif
                        </div>
                        
                        <!-- SINKRONISASI KETERANGAN TAMBAHAN -->
                        <div class="md:col-span-2 pt-2 border-t border-slate-100 dark:border-slate-800 mt-4">
                            <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan Tambahan</span>
                            <div class="block w-full min-h-[60px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-4 text-[13px] font-medium italic leading-relaxed whitespace-pre-line text-slate-700 dark:text-slate-300 shadow-sm">{{ $asesmen->keterangan_tambahan ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 5: DATA SURAT BERITA ACARA            -->
                <!-- ========================================== -->
                <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden transition-colors w-full mt-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#10B981]"></div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 gap-4 mt-1">
                        <h3 class="text-sm font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            5. Data Surat Berita Acara
                        </h3>
                        <div class="flex gap-2 w-full sm:w-auto">
                            @can('akses-dokumen')
                            <a href="{{ route('asesmen.berita-acara', $asesmen->id) }}" class="flex-1 sm:flex-none text-center px-4 py-2 border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl font-black text-[10px] text-emerald-700 dark:text-emerald-400 uppercase tracking-widest hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors shadow-sm transform-gpu hover:-translate-y-0.5">
                                Edit Form BA
                            </a>
                            @endcan
                            <form action="{{ route('asesmen.berita-acara.unduh', $asesmen->id) }}" method="POST" class="flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 rounded-xl font-black text-[10px] text-white uppercase tracking-widest hover:bg-emerald-700 transition-colors shadow-sm transform-gpu hover:-translate-y-0.5">
                                    Unduh Word
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($asesmen->no_ba || $asesmen->ketua_tat_nama)
                        <div class="space-y-8">
                            <!-- Baris 1: Header BA -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-6">
                                <div class="col-span-2 lg:col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Ketua TAT</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->ketua_tat_nama ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NRP Ketua</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->ketua_tat_nrp ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Rapat B.A</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_ba ? \Carbon\Carbon::parse($asesmen->tgl_ba)->format('d-m-Y') : '-' }}</div>
                                </div>

                                <div class="col-span-2 lg:col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. SK Tim Asesmen</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_kep_tim ?? '-' }}</div>
                                </div>
                                <div class="col-span-2 lg:col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal SK Tim</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_kep_tim ? \Carbon\Carbon::parse($asesmen->tgl_kep_tim)->format('d-m-Y') : '-' }}</div>
                                </div>
                            </div>

                            <!-- Baris 2: Tim Terlibat -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-slate-50 dark:bg-[#1E293B] p-5 rounded-[1.25rem] border border-emerald-100 dark:border-emerald-900/30">
                                    <span class="block text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-3 border-b border-emerald-100 dark:border-emerald-800/50 pb-2">Tim Medis Terlibat</span>
                                    @forelse($asesmen->anggotaTim->where('kategori', 'medis') as $m)
                                        <p class="text-[13px] font-bold text-slate-800 dark:text-slate-200 mb-1.5 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $m->nama }}</p>
                                    @empty <p class="text-[12px] font-bold text-slate-400 italic tracking-widest">-</p> @endforelse
                                </div>
                                <div class="bg-slate-50 dark:bg-[#1E293B] p-5 rounded-[1.25rem] border border-emerald-100 dark:border-emerald-900/30">
                                    <span class="block text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-3 border-b border-emerald-100 dark:border-emerald-800/50 pb-2">Tim Hukum Terlibat</span>
                                    @forelse($asesmen->anggotaTim->where('kategori', 'hukum') as $h)
                                        <p class="text-[13px] font-bold text-slate-800 dark:text-slate-200 mb-1.5 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $h->nama }}</p>
                                    @empty <p class="text-[12px] font-bold text-slate-400 italic tracking-widest">-</p> @endforelse
                                </div>
                            </div>

                            <!-- Baris 3: Narasi -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Narasi Tim Hukum</span>
                                    <div class="block w-full min-h-[80px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-5 py-4 text-[13px] font-medium leading-relaxed whitespace-pre-wrap text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->narasi_hukum ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Narasi Tim Medis</span>
                                    <div class="block w-full min-h-[80px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-5 py-4 text-[13px] font-medium leading-relaxed whitespace-pre-wrap text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->narasi_medis ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-800"></div>

                            <!-- Baris 4: SK Narkotika & Hasil Zat -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-6">
                                <div class="col-span-2 lg:col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. SK Narkotika</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->alat_bukti_no_sk ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tgl SK Narkotika</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->alat_bukti_tgl_sk ? \Carbon\Carbon::parse($asesmen->alat_bukti_tgl_sk)->format('d-m-Y') : '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Dokter Pemeriksa</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->alat_bukti_dokter ?? '-' }}</div>
                                </div>

                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Zat Yang Dipakai</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kesimpulan_jenis_zat ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status Klien</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-black uppercase text-slate-700 dark:text-slate-300 shadow-sm">{{ $asesmen->status_klien ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pola Pemakaian</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kesimpulan_pola_pakai ?? '-' }}</div>
                                </div>

                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Zat Positif</span>
                                    <div class="block w-full rounded-[1.25rem] border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-900/10 px-4 py-3 text-[13px] font-black uppercase leading-relaxed text-rose-600 dark:text-rose-400 shadow-sm">{{ $asesmen->alat_bukti_hasil ?? '-' }}</div>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kategori Ketergantungan</span>
                                    <div class="block w-full rounded-[1.25rem] border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-900/10 px-4 py-3 text-[13px] font-black uppercase text-rose-600 dark:text-rose-400 shadow-sm">{{ $asesmen->kesimpulan_kategori ?? '-' }}</div>
                                </div>
                            </div>

                            <!-- Baris 5: Diagnosis Final Centered Box -->
                            <div class="pt-2">
                                <span class="block text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-2 text-center">Diagnosis Medis Final</span>
                                <div class="w-full bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 p-5 rounded-2xl text-[14px] font-black text-emerald-800 dark:text-emerald-400 shadow-sm text-center leading-relaxed">
                                    {{ $asesmen->diagnosis_medis ?? '-' }}
                                </div>
                            </div>

                            <!-- Baris 6: Keterangan -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-3">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan Hukum Rekomendasi</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-5 py-4 text-[13px] font-medium italic leading-relaxed text-slate-800 dark:text-slate-200 shadow-sm">"{{ $asesmen->rekomendasi_keterangan ?? '-' }}"</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Lama (Durasi) Rawat</span>
                                    <div class="block w-full rounded-[1.25rem] border border-transparent bg-rose-50 dark:bg-rose-900/20 px-4 py-4 text-[16px] font-black uppercase text-rose-600 dark:text-rose-400 shadow-inner text-center">{{ $asesmen->lama_perawatan ?? $asesmen->rekomendasi_durasi ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- EMPTY STATE B.A -->
                        <div class="text-center py-12 border-2 border-dashed border-emerald-200 dark:border-emerald-800/50 rounded-[2rem] bg-emerald-50/50 dark:bg-emerald-900/10">
                            <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-base font-black text-emerald-800 dark:text-emerald-400 mb-2">Belum Ada Berita Acara</h3>
                            <p class="text-emerald-600/70 dark:text-emerald-500/70 text-xs font-bold uppercase tracking-widest">Silakan klik tombol Edit Form BA di atas untuk melengkapi data.</p>
                        </div>
                    @endif
                </div>

                <!-- ========================================== -->
                <!-- CARD 6: DATA SURAT REKOMENDASI             -->
                <!-- ========================================== -->
                <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden transition-colors w-full mb-10 mt-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#8B5CF6]"></div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 gap-4 mt-1">
                        <h3 class="text-sm font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            6. Data Surat Rekomendasi
                        </h3>
                        <div class="flex gap-2 w-full sm:w-auto">
                            @can('akses-dokumen')
                            <a href="{{ route('asesmen.rekomendasi', $asesmen->id) }}" class="flex-1 sm:flex-none text-center px-4 py-2 border border-purple-200 dark:border-purple-800/50 bg-purple-50 dark:bg-purple-500/10 rounded-xl font-black text-[10px] text-purple-700 dark:text-purple-400 uppercase tracking-widest hover:bg-purple-100 dark:hover:bg-purple-500/20 transition-colors shadow-sm transform-gpu hover:-translate-y-0.5">
                                Edit Form Rekom
                            </a>
                            @endcan
                            <form action="{{ route('asesmen.rekomendasi.unduh', $asesmen->id) }}" method="POST" class="flex-1 sm:flex-none">
                                @csrf
                                <input type="hidden" name="action" value="download">
                                <!-- Hidden Data Transfer... -->
                                <input type="hidden" name="no_surat_rekomendasi" value="{{ $asesmen->no_surat_rekomendasi }}">
                                <input type="hidden" name="tgl_rekomendasi" value="{{ $asesmen->tgl_rekomendasi }}">
                                <input type="hidden" name="kepada_yth" value="{{ $asesmen->kepada_yth }}">
                                <input type="hidden" name="no_keputusan" value="{{ $asesmen->no_keputusan }}">
                                <input type="hidden" name="tgl_keputusan" value="{{ $asesmen->tgl_keputusan }}">
                                <input type="hidden" name="tentang_permohonan" value="{{ $asesmen->tentang_permohonan }}">
                                <input type="hidden" name="kewarganegaraan" value="{{ $asesmen->kewarganegaraan }}">
                                <input type="hidden" name="nama_narkotika_medis" value="{{ $asesmen->nama_narkotika_medis }}">
                                <input type="hidden" name="lama_perawatan" value="{{ $asesmen->lama_perawatan }}">
                                <input type="hidden" name="keterangan_diagnosis" value="{{ $asesmen->keterangan_diagnosis }}">

                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 rounded-xl font-black text-[10px] text-white uppercase tracking-widest hover:bg-purple-700 transition-colors shadow-sm transform-gpu hover:-translate-y-0.5">
                                    Unduh Word
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($asesmen->no_surat_rekomendasi || $asesmen->kepada_yth)
                        <div class="space-y-8">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-6">
                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nomor Surat BNN</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_surat_rekomendasi ?? '-' }}</div>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tujuan (Kepada Yth)</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kepada_yth ?? '-' }}</div>
                                </div>

                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Surat</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_rekomendasi ? \Carbon\Carbon::parse($asesmen->tgl_rekomendasi)->format('d-m-Y') : '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kewarganegaraan</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->kewarganegaraan ?? '-' }}</div>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Golongan Narkotika</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->nama_narkotika_medis ?? '-' }}</div>
                                </div>

                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. Keputusan Tim TAT</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->no_keputusan ?? '-' }}</div>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Keputusan</span>
                                    <div class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-4 py-3 text-[13px] font-bold text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tgl_keputusan ? \Carbon\Carbon::parse($asesmen->tgl_keputusan)->format('d-m-Y') : '-' }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tentang Permohonan (Isi Surat)</span>
                                    <div class="block w-full min-h-[80px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-5 py-4 text-[13px] font-medium leading-relaxed whitespace-pre-wrap text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->tentang_permohonan ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan Diagnosis Panjang</span>
                                    <div class="block w-full min-h-[80px] rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#1E293B] px-5 py-4 text-[13px] font-medium leading-relaxed whitespace-pre-wrap text-slate-800 dark:text-slate-200 shadow-sm">{{ $asesmen->keterangan_diagnosis ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Lama Waktu Perawatan</span>
                                <div class="inline-block rounded-[1.25rem] border border-transparent bg-rose-50 dark:bg-rose-900/20 px-6 py-4 text-[16px] font-black uppercase text-rose-600 dark:text-rose-400 shadow-inner text-center">{{ $asesmen->lama_perawatan ?? '-' }}</div>
                            </div>
                        </div>
                    @else
                        <!-- EMPTY STATE REKOMENDASI -->
                        <div class="text-center py-12 border-2 border-dashed border-purple-200 dark:border-purple-800/50 rounded-[2rem] bg-purple-50/50 dark:bg-purple-900/10">
                            <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-purple-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <h3 class="text-base font-black text-purple-800 dark:text-purple-400 mb-2">Belum Ada Surat Rekomendasi</h3>
                            <p class="text-purple-600/70 dark:text-purple-500/70 text-xs font-bold uppercase tracking-widest">Silakan klik tombol Edit Form Rekom di atas untuk melengkapi data.</p>
                        </div>
                    @endif
                </div>

            </div> <!-- END CONTAINER RAKSASA -->

        </div>
    </div>
</x-app-layout>