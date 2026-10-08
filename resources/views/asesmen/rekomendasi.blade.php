<x-app-layout>
    <!-- CSS Custom untuk Animasi Autosave & Scrollbar -->
    <style>
        /* Animasi Indikator Autosave */
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .autosave-active {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Custom scrollbar untuk dropdown */
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
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-purple-200/60 dark:bg-purple-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- HEADER (Dikeluarkan dari kotak putih, menyatu dengan background) -->
    <x-slot name="header">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 max-w-[1400px] mx-auto w-full">
            <div class="flex items-center gap-4">
                <a href="{{ route('asesmen.show', $asesmen->id) }}" class="w-12 h-12 rounded-[1rem] bg-white dark:bg-[#1E293B] text-purple-600 dark:text-purple-400 flex items-center justify-center border border-slate-200 dark:border-slate-700/50 shadow-sm shrink-0 hover:scale-105 transition-transform group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-black bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-1 border border-purple-200 dark:border-purple-800/50 shadow-sm">
                        Generator Dokumen (Word)
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                        Surat Rekomendasi: {{ $asesmen->nama_lengkap }}
                        <span id="autosaveIndicator" class="hidden text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800/50 px-2 py-1 rounded-md uppercase tracking-wider autosave-active">
                            Draft Tersimpan
                        </span>
                    </h1>
                </div>
            </div>
            <p class="text-xs font-bold text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] px-4 py-2 rounded-xl border border-rose-100 dark:border-rose-900/50 shadow-sm hidden md:block uppercase tracking-widest">
                Data akan digenerate menjadi Microsoft Word
            </p>
        </div>
    </x-slot>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="relative z-10 py-8 min-h-screen">
        <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- KONTAINER RAKSASA MAC SOFT UI -->
            <div class="bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-white/60 dark:border-slate-700/50 transition-colors duration-300 p-6 sm:p-10">

                <!-- ========================================== -->
                <!-- SEKSI 0: PREVIEW DATA KLIEN (AUTOFILL) -->
                <!-- ========================================== -->
                <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-6 md:p-8 rounded-[2rem] border border-indigo-100 dark:border-indigo-800/30 shadow-sm relative z-[50] transition-colors mb-10">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500 rounded-t-[2rem]"></div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-indigo-200/60 dark:border-indigo-800/50 pb-4 mt-1 gap-2">
                        <h3 class="text-sm font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Preview Data Klien (Autofill ke Word)
                        </h3>
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-white dark:bg-[#0F172A] px-3 py-1.5 rounded-lg border border-indigo-100 dark:border-indigo-800/50 shadow-sm">Hanya Baca</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-5">
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-[1.25rem] border border-indigo-50 dark:border-slate-700/50 shadow-sm">
                            <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $asesmen->nama_lengkap }}</span>
                        </div>
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-[1.25rem] border border-indigo-50 dark:border-slate-700/50 shadow-sm">
                            <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">NIK</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $asesmen->nik }}</span>
                        </div>
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-[1.25rem] border border-indigo-50 dark:border-slate-700/50 shadow-sm">
                            <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">No. Surat Pengajuan</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $asesmen->no_surat_pengajuan ?? '-' }}</span>
                        </div>
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-[1.25rem] border border-indigo-50 dark:border-slate-700/50 shadow-sm">
                            <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Jenis Narkotika</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</span>
                        </div>
                    </div>

                    @php
                        $rawTempat = $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? 'Belum ada data tempat rehabilitasi';
                        $tempatBersih = $rawTempat;

                        if (str_starts_with($rawTempat, 'Rawat Jalan')) {
                            $tempatBersih = trim(str_replace('Rawat Jalan', '', $rawTempat));
                        } elseif (str_starts_with($rawTempat, 'Rawat Inap')) {
                            $tempatBersih = trim(str_replace('Rawat Inap', '', $rawTempat));
                        } elseif (str_starts_with($rawTempat, 'Rehab di Lapas / Rutan')) {
                            $tempatBersih = trim(str_replace('Rehab di Lapas / Rutan', '', $rawTempat));
                        } elseif (str_starts_with($rawTempat, 'Tidak Rehab (Proses Hukum)')) {
                            $tempatBersih = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $rawTempat));
                        }

                        $tempatBersih = trim(ltrim($tempatBersih, ' -'));

                        if (empty($tempatBersih) && $rawTempat !== 'Belum ada data tempat rehabilitasi') {
                            $tempatBersih = 'Tanpa Instansi';
                        } elseif ($rawTempat === 'Belum ada data tempat rehabilitasi') {
                            $tempatBersih = $rawTempat;
                        }
                    @endphp

                    <div class="bg-gradient-to-r from-[#8B5CF6] to-[#6366F1] dark:from-[#6D28D9] dark:to-[#4338CA] p-5 rounded-[1.25rem] shadow-md text-white flex items-center justify-between transition-colors">
                        <div>
                            <span class="block text-[10px] font-black text-purple-100 uppercase tracking-widest mb-1.5">Keputusan Tempat Rehabilitasi</span>
                            <span class="font-extrabold text-[16px]">{{ $tempatBersih }}</span>
                        </div>
                    </div>
                </div>

                <!-- FORM UTAMA REKOMENDASI -->
                <form id="formRekomendasi" action="{{ route('asesmen.rekomendasi.unduh', $asesmen->id) }}" method="POST" class="space-y-10">
                    @csrf
                    <input type="hidden" name="tempat_rehabilitasi" value="{{ $tempatBersih }}">
                    <input type="hidden" name="rekomendasi_input" value="{{ $tempatBersih }}">

                    <!-- ========================================== -->
                    <!-- SEKSI 1: ADMINISTRASI SURAT -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[40] transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#8B5CF6] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#8B5CF6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            1. Administrasi Surat
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nomor Surat Rekomendasi BNN <span class="text-rose-500">*</span></label>
                                <input type="text" name="no_surat_rekomendasi" value="{{ old('no_surat_rekomendasi', $asesmen->no_surat_rekomendasi) }}" placeholder="Misal: R/397/VII/Ka/PB.06.00/2026/BNNK" required class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Pembuatan Surat <span class="text-rose-500">*</span></label>
                                <input type="text" name="tgl_rekomendasi" value="{{ old('tgl_rekomendasi', $asesmen->tgl_rekomendasi ?? date('Y-m-d')) }}" required class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tujuan Surat (Kepada Yth) <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="kepada_yth" id="kepada_yth" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Tujuan Surat --</option>
                                        @if(isset($asesmen->kepada_yth) && $asesmen->kepada_yth !== '')
                                            <option value="{{ $asesmen->kepada_yth }}" selected class="dark:bg-slate-800">{{ $asesmen->kepada_yth }}</option>
                                        @endif
                                        @foreach($riwayat_kepada as $item)
                                            @if($item->kepada_yth !== $asesmen->kepada_yth && $item->kepada_yth !== '')
                                                <option value="{{ $item->kepada_yth }}" class="dark:bg-slate-800">{{ $item->kepada_yth }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('kepada_yth', 'Tujuan Surat')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('kepada_yth', 'Tujuan Surat')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                            </div>

                            <!-- Kewarganegaraan dikunci (Read-Only) -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kewarganegaraan Klien</label>
                                <div class="relative">
                                    <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $asesmen->kewarganegaraan ?? 'Indonesia (WNI)') }}" readonly class="block w-full rounded-[1.25rem] border-transparent shadow-inner text-sm font-bold bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 px-4 py-3 pointer-events-none cursor-not-allowed">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mt-2 ml-1">Terkunci. Ubah data ini melalui menu "Edit Data Utama".</p>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SEKSI 2: DASAR HUKUM & PENGAJUAN -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[30] transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-purple-500 rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            2. Dasar Hukum & Pengajuan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. Keputusan Penunjukan Tim TAT <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="no_keputusan" id="no_keputusan" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih No. Keputusan --</option>
                                        @if(isset($asesmen->no_keputusan) && $asesmen->no_keputusan !== '')
                                            <option value="{{ $asesmen->no_keputusan }}" selected class="dark:bg-slate-800">{{ $asesmen->no_keputusan }}</option>
                                        @endif
                                        @foreach($riwayat_no_keputusan as $item)
                                            @if($item->no_keputusan !== $asesmen->no_keputusan && $item->no_keputusan !== '')
                                                <option value="{{ $item->no_keputusan }}" class="dark:bg-slate-800">{{ $item->no_keputusan }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('no_keputusan', 'No. Keputusan Tim TAT')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('no_keputusan', 'No. Keputusan Tim TAT')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Keputusan Penunjukan <span class="text-rose-500">*</span></label>
                                <input type="text" name="tgl_keputusan" value="{{ old('tgl_keputusan', $asesmen->tgl_keputusan) }}" required class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tentang Permohonan (Isi Surat) <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="tentang_permohonan" id="tentang_permohonan" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Tentang Permohonan --</option>
                                        @if(isset($asesmen->tentang_permohonan) && $asesmen->tentang_permohonan !== '')
                                            <option value="{{ $asesmen->tentang_permohonan }}" selected class="dark:bg-slate-800">{{ $asesmen->tentang_permohonan }}</option>
                                        @endif
                                        @foreach($riwayat_tentang as $item)
                                            @if($item->tentang_permohonan !== $asesmen->tentang_permohonan && $item->tentang_permohonan !== '')
                                                <option value="{{ $item->tentang_permohonan }}" class="dark:bg-slate-800">{{ $item->tentang_permohonan }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('tentang_permohonan', 'Tentang Permohonan')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('tentang_permohonan', 'Tentang Permohonan')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SEKSI 3: DIAGNOSIS & REKOMENDASI MEDIS -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[20] transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#8B5CF6] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#8B5CF6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            3. Diagnosis & Rekomendasi Medis
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- SINKRONISASI MASTER ZAT KESIMPULAN BERITA ACARA -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Golongan Narkotika (Medis) <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="nama_narkotika_medis" id="nama_narkotika_medis" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Golongan Narkotika --</option>
                                        <!-- Options will be populated by JS from shared_master_zat_narkotika -->
                                    </select>
                                    <button type="button" onclick="openTambahModal('nama_narkotika_medis', 'Golongan Narkotika (Medis)', 'shared_master_zat_narkotika')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('nama_narkotika_medis', 'Golongan Narkotika (Medis)', 'shared_master_zat_narkotika')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mt-2 ml-1">Tersinkronisasi dengan Master Jenis Zat pada Berita Acara.</p>
                            </div>

                            <!-- LAMA WAKTU PERAWATAN (SINKRONISASI 100%) -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Lama Waktu Perawatan <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    @php
                                        $valDurasi = old('lama_perawatan', $asesmen->lama_perawatan ?? $asesmen->rekomendasi_durasi ?? '');
                                        $defaultsDurasi = ['1 bulan', '2 bulan', '3 bulan', '1-3 bulan'];

                                        $dbRiwayat = [];
                                        if(isset($riwayat_perawatan)) {
                                            foreach($riwayat_perawatan as $item) {
                                                if($item->lama_perawatan !== '') $dbRiwayat[] = $item->lama_perawatan;
                                            }
                                        }
                                        $allDurasi = array_unique(array_merge($defaultsDurasi, $dbRiwayat));
                                    @endphp
                                    <select name="lama_perawatan" id="lama_perawatan" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Waktu Perawatan --</option>
                                        @if($valDurasi !== '')
                                            <option value="{{ $valDurasi }}" selected class="dark:bg-slate-800">{{ $valDurasi }}</option>
                                        @endif
                                        @foreach($allDurasi as $d)
                                            @if($d !== $valDurasi)
                                                <option value="{{ $d }}" class="dark:bg-slate-800">{{ $d }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('lama_perawatan', 'Lama Waktu Perawatan', 'lama_perawatan')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('lama_perawatan', 'Lama Waktu Perawatan', 'lama_perawatan')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan Diagnosis Panjang <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="keterangan_diagnosis" id="keterangan_diagnosis" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Keterangan Diagnosis --</option>
                                        @if(isset($asesmen->keterangan_diagnosis) && $asesmen->keterangan_diagnosis !== '')
                                            <option value="{{ $asesmen->keterangan_diagnosis }}" selected class="dark:bg-slate-800">{{ $asesmen->keterangan_diagnosis }}</option>
                                        @endif
                                        @foreach($riwayat_diagnosis as $item)
                                            @if($item->keterangan_diagnosis !== $asesmen->keterangan_diagnosis && $item->keterangan_diagnosis !== '')
                                                <option value="{{ $item->keterangan_diagnosis }}" class="dark:bg-slate-800">{{ $item->keterangan_diagnosis }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('keterangan_diagnosis', 'Keterangan Diagnosis Panjang')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('keterangan_diagnosis', 'Keterangan Diagnosis Panjang')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                            </div>

                            <!-- FITUR BARU: KETERANGAN HUKUM REKOMENDASI (POIN 4.b) -->
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan Hukum Rekomendasi (Poin 4.b) <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="rekomendasi_keterangan" id="rekomendasi_keterangan" required class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Keterangan Hukum --</option>
                                        @php
                                            $ketList = [
                                                "hasil pemeriksaan urine/rambut positif narkotika tanpa barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut negatif narkotika tanpa barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut positif narkotika dengan barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut negatif narkotika dengan barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut positif narkotika tanpa barang bukti narkotika, namun terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut negatif narkotika tanpa barang bukti narkotika, namun terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut positif narkotika dengan barang bukti narkotika dan terdapat keterlibatan dalam jaringan peredaran gelap narkotika",
                                                "hasil pemeriksaan urine/rambut negatif narkotika dengan barang bukti narkotika dan terdapat keterlibatan dalam jaringan peredaran gelap narkotika"
                                            ];
                                            $savedKet = old('rekomendasi_keterangan', $asesmen->rekomendasi_keterangan);
                                        @endphp
                                        @if($savedKet !== '' && $savedKet !== null && !in_array($savedKet, $ketList))
                                            <option value="{{ $savedKet }}" selected class="dark:bg-slate-800">{{ $savedKet }}</option>
                                        @endif
                                        @foreach($ketList as $ket)
                                            <option value="{{ $ket }}" {{ $savedKet == $ket ? 'selected' : '' }} class="dark:bg-slate-800">{{ $ket }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('rekomendasi_keterangan', 'Keterangan Hukum')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="openKelolaModal('rekomendasi_keterangan', 'Keterangan Hukum')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TOMBOL SUBMIT -->
                    <!-- ========================================== -->
                    <div class="border-t border-slate-200 dark:border-slate-800/60 pt-8 pb-10 flex flex-col sm:flex-row justify-end gap-4 fade-in-up delay-200">
                        <a href="{{ route('asesmen.show', $asesmen->id) }}" class="inline-flex justify-center items-center px-8 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Batal</a>
                        <button type="submit" name="action" value="save" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#3B82F6] text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-blue-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40">Simpan Perubahan</button>
                        <button type="submit" name="action" value="download" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#8B5CF6] text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-purple-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-purple-500/30 dark:shadow-purple-900/40">Simpan & Unduh Dokumen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH & KELOLA OPSI DINAMIS -->
    <div id="modalTambahDurasi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeTambahModal()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight" id="modal-title">Tambah <span id="tambah_label" class="text-[#3B82F6] dark:text-blue-400"></span></h3>
            <input type="hidden" id="tambah_target_id">
            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama/Nilai Baru</label>
            <input type="text" id="tambah_input" placeholder="Ketik di sini..." class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none mb-6 transition-all">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="button" onclick="simpanOpsiBaruKhusus()" class="px-5 py-2.5 bg-[#3B82F6] text-white font-bold text-xs rounded-xl hover:bg-blue-600 transition-colors shadow-md">Simpan</button>
            </div>
        </div>
    </div>

    <div id="modalKelolaDurasi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeKelolaModal()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Kelola <span id="kelola_label" class="text-[#3B82F6] dark:text-blue-400"></span></h3>
                <button type="button" onclick="closeKelolaModal()" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <input type="hidden" id="kelola_target_id">
            <ul id="kelola_list" class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-select-scroll mb-6"></ul>
            <button type="button" onclick="closeKelolaModal()" class="w-full px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup Kelola</button>
        </div>
    </div>


    <script>
        // MESIN CUSTOM SELECT MAC SOFT UI
        function makeCustomSelect(select) {
            if (!select || select.classList.contains('no-custom') || select.classList.contains('hidden')) return;

            let isRendering = false;
            let renderTimeout;

            function render() {
                if (isRendering) return;
                isRendering = true;

                if (select.nextElementSibling && select.nextElementSibling.classList.contains('mac-select-wrapper')) {
                    select.nextElementSibling.remove();
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'mac-select-wrapper relative w-full';
                wrapper.style.zIndex = '1';

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'flex justify-between items-center w-full px-4 py-3 rounded-[1.25rem] border transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-sm text-sm font-medium text-left ';

                if (select.disabled) {
                    btn.className += 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:text-slate-500';
                    btn.disabled = true;
                } else {
                    btn.className += 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 dark:bg-[#0F172A] dark:border-slate-700 dark:text-slate-200';
                }

                const span = document.createElement('span');
                span.className = 'truncate pointer-events-none flex-1';

                let selectedText = '-- Pilih --';
                if (select.selectedIndex >= 0 && select.options[select.selectedIndex]) {
                    selectedText = select.options[select.selectedIndex].text;
                } else if (select.options.length > 0) {
                    selectedText = select.options[0].text;
                }
                span.innerText = selectedText;

                const icon = document.createElement('div');
                icon.className = 'shrink-0 ml-3 transition-transform duration-200 pointer-events-none flex items-center justify-center';
                icon.innerHTML = `<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`;

                btn.appendChild(span);
                btn.appendChild(icon);

                const listWrapper = document.createElement('div');
                listWrapper.className = 'mac-select-list absolute z-[90] top-full left-0 mt-2 w-full bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] rounded-2xl py-2 max-h-60 overflow-y-auto hidden custom-select-scroll transition-all opacity-0 translate-y-2';

                let hasVisibleOptions = false;
                Array.from(select.options).forEach((opt, index) => {
                    if(opt.style.display === 'none') return;
                    hasVisibleOptions = true;

                    const item = document.createElement('div');
                    item.className = 'px-4 py-2.5 mx-1.5 mt-0.5 rounded-xl cursor-pointer text-sm transition-colors ';

                    if (opt.disabled && opt.value === '') {
                        item.className += 'text-slate-400 font-bold opacity-70 cursor-default pointer-events-none';
                    } else if (opt.disabled) {
                        item.className += 'text-slate-400 cursor-not-allowed bg-slate-50 pointer-events-none';
                    } else if (opt.selected && opt.value !== '') {
                        item.className += 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400';
                    } else {
                        item.className += 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800';
                    }

                    item.innerText = opt.text;

                    if (!opt.disabled) {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            select.selectedIndex = index;
                            span.innerText = opt.text;
                            closeAllMacSelects();
                            select.dispatchEvent(new Event('change'));
                        });
                    }
                    listWrapper.appendChild(item);
                });

                if(!hasVisibleOptions) {
                    const empty = document.createElement('div');
                    empty.className = 'px-4 py-2 text-sm text-slate-400 italic text-center';
                    empty.innerText = 'Tidak ada pilihan';
                    listWrapper.appendChild(empty);
                }

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if(select.disabled) return;

                    const isHidden = listWrapper.classList.contains('hidden');
                    closeAllMacSelects();

                    if (isHidden) {
                        wrapper.style.zIndex = '90'; // Mengangkat elemen ini di atas elemen form lain
                        if (listWrapper.closeTimeout) clearTimeout(listWrapper.closeTimeout);
                        listWrapper.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            listWrapper.classList.remove('opacity-0', 'translate-y-2');
                            icon.classList.add('rotate-180');
                        });
                    }
                });

                wrapper.appendChild(btn);
                wrapper.appendChild(listWrapper);

                select.style.display = 'none';
                select.parentNode.insertBefore(wrapper, select.nextSibling);
                isRendering = false;
            }

            function triggerRender() {
                clearTimeout(renderTimeout);
                renderTimeout = setTimeout(render, 50); // Timeout mencegah bentrok saat append opt dinamis
            }

            triggerRender();

            const observer = new MutationObserver(() => triggerRender());
            observer.observe(select, { childList: true, attributes: true, attributeFilter: ['disabled'] });
            select.addEventListener('change', () => triggerRender());
        }

        function closeAllMacSelects() {
            document.querySelectorAll('.mac-select-wrapper').forEach(wrapper => {
                wrapper.style.zIndex = '1'; // Kembalikan ke posisi semula
                const list = wrapper.querySelector('.mac-select-list');
                const icon = wrapper.querySelector('button > div');
                if(list && !list.classList.contains('hidden')) {
                    list.classList.add('opacity-0', 'translate-y-2');
                    if(icon) icon.classList.remove('rotate-180');
                    if (list.closeTimeout) clearTimeout(list.closeTimeout);
                    list.closeTimeout = setTimeout(() => {
                        list.classList.add('hidden');
                    }, 200);
                }
            });
        }
        document.addEventListener('click', closeAllMacSelects);

        function initAllCustomSelects() {
            document.querySelectorAll('select').forEach(select => {
                makeCustomSelect(select);
            });
        }

        // === FUNGSI SINKRONISASI DROPDOWN & AUTOSAVE LENGKAP ===
        let currentStorageKey = null;

        function openTambahModal(selectId, labelName, storageKey = null) {
            document.getElementById('tambah_target_id').value = selectId;
            currentStorageKey = storageKey || selectId;
            document.getElementById('tambah_label').innerText = labelName;
            document.getElementById('tambah_input').value = '';

            const m = document.getElementById('modalTambahDurasi');
            m.classList.remove('hidden'); m.classList.add('flex');
            setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); document.getElementById('tambah_input').focus(); }, 10);
        }

        function closeTambahModal() {
            const m = document.getElementById('modalTambahDurasi');
            m.children[1].classList.add('opacity-0', 'scale-95');
            setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
        }

        function simpanOpsiBaruKhusus() {
            const selectId = document.getElementById('tambah_target_id').value;
            const newValue = document.getElementById('tambah_input').value.trim();

            if(newValue !== '') {
                let els = [];
                if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi') {
                    if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
                    if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));
                } else {
                    els.push(document.getElementById(selectId));
                }

                els.forEach(el => {
                    if (!Array.from(el.options).some(opt => opt.value === newValue)) {
                        let opt = new Option(newValue, newValue);
                        opt.className = 'dark:bg-slate-800';
                        el.add(opt);
                    }
                    el.value = newValue;
                });

                // Tentukan key penyimpanannya, jika Narkotika Medis, set paksa ke shared_master_zat_narkotika
                let storageString = (currentStorageKey === 'shared_master_zat_narkotika') ? currentStorageKey : 'opsi_' + (currentStorageKey || selectId);
                let savedOpts = JSON.parse(localStorage.getItem(storageString)) || [];

                if (!savedOpts.includes(newValue)) {
                    savedOpts.push(newValue);
                    localStorage.setItem(storageString, JSON.stringify(savedOpts));
                }

                // SINKRONISASI VALUE TERPILIH ANTAR HALAMAN
                if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi' || currentStorageKey === 'lama_perawatan') {
                    localStorage.setItem('shared_durasi_rawat_{{ $asesmen->id }}', newValue);
                }

                closeTambahModal();
                if (typeof saveFormDraft === 'function') saveFormDraft();
            }
        }

        function openKelolaModal(selectId, labelName, storageKey = null) {
            document.getElementById('kelola_target_id').value = selectId;
            currentStorageKey = storageKey || selectId;
            document.getElementById('kelola_label').innerText = labelName;

            const selectEl = document.getElementById(selectId);
            const listContainer = document.getElementById('kelola_list');
            listContainer.innerHTML = '';

            let hasItems = false;
            for(let i = 0; i < selectEl.options.length; i++) {
                const opt = selectEl.options[i];
                if(opt.value !== '') {
                    hasItems = true;
                    const li = document.createElement('li');
                    li.className = "flex justify-between items-center p-4 bg-slate-50/50 dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors";
                    li.innerHTML = `
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-3/4">${opt.text}</span>
                        <button type="button" onclick="hapusOpsiLokal('${selectId}', '${opt.value}', this)" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                    `;
                    listContainer.appendChild(li);
                }
            }

            if(!hasItems) {
                listContainer.innerHTML = '<li class="text-center text-slate-400 dark:text-slate-500 py-4 text-sm italic">Belum ada daftar riwayat tersimpan.</li>';
            }

            const m = document.getElementById('modalKelolaDurasi');
            m.classList.remove('hidden'); m.classList.add('flex');
            setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); }, 10);
        }

        function closeKelolaModal() {
            const m = document.getElementById('modalKelolaDurasi');
            m.children[1].classList.add('opacity-0', 'scale-95');
            setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
        }

        function hapusOpsiLokal(selectId, valueToRemove, btnEl) {
            let els = [];
            if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi') {
                if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
                if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));
            } else {
                els.push(document.getElementById(selectId));
            }

            els.forEach(selectEl => {
                for(let i = 0; i < selectEl.options.length; i++) {
                    if(selectEl.options[i].value === valueToRemove) {
                        selectEl.remove(i);
                        break;
                    }
                }
            });

            // Tentukan key hapusnya
            let storageString = (currentStorageKey === 'shared_master_zat_narkotika') ? currentStorageKey : 'opsi_' + (currentStorageKey || selectId);
            let savedOpts = JSON.parse(localStorage.getItem(storageString)) || [];

            savedOpts = savedOpts.filter(item => item !== valueToRemove);
            localStorage.setItem(storageString, JSON.stringify(savedOpts));

            const li = btnEl.closest('li');
            li.style.opacity = '0';
            setTimeout(() => {
                li.remove();
                if(document.getElementById('kelola_list').children.length === 0) {
                    document.getElementById('kelola_list').innerHTML = '<li class="text-center text-slate-400 dark:text-slate-500 py-4 text-sm italic">Semua opsi telah dihapus dari daftar ini.</li>';
                }
            }, 200);

            if (typeof saveFormDraft === 'function') saveFormDraft();
        }

        function loadSemuaOpsiLokal() {
            // DITAMBAHKAN rekomendasi_keterangan KE DALAM DAFTAR AUTO-LOAD
            const selectIds = ['kepada_yth', 'no_keputusan', 'tentang_permohonan', 'keterangan_diagnosis', 'rekomendasi_keterangan'];
            selectIds.forEach(id => {
                let savedOpts = JSON.parse(localStorage.getItem('opsi_' + id)) || [];
                const selectEl = document.getElementById(id);
                if(selectEl) {
                    savedOpts.forEach(val => {
                        if (!Array.from(selectEl.options).some(opt => opt.value === val)) {
                            let opt = new Option(val, val);
                            opt.className = 'dark:bg-slate-800';
                            selectEl.add(opt);
                        }
                    });
                }
            });

            // KHUSUS UNTUK SINKRONISASI NAMA NARKOTIKA MEDIS
            let savedZat = JSON.parse(localStorage.getItem('shared_master_zat_narkotika'));
            if (!savedZat) {
                savedZat = [
                    "Amphetamine (Amfetamin)",
                    "Methamphetamine (Metamfetamin)",
                    "Tetrahydrocannabinol",
                    "Benzodiazepines (Benzodiazepin)"
                ];
                localStorage.setItem('shared_master_zat_narkotika', JSON.stringify(savedZat));
            }
            const selectNarkotika = document.getElementById('nama_narkotika_medis');
            if(selectNarkotika) {
                savedZat.forEach(val => {
                    if (!Array.from(selectNarkotika.options).some(opt => opt.value === val)) {
                        let opt = new Option(val, val);
                        opt.className = 'dark:bg-slate-800';
                        selectNarkotika.add(opt);
                    }
                });
                let currentNarkotika = "{{ old('nama_narkotika_medis', $asesmen->nama_narkotika_medis) }}";
                if (currentNarkotika && !Array.from(selectNarkotika.options).some(opt => opt.value === currentNarkotika)) {
                    let opt = new Option(currentNarkotika, currentNarkotika);
                    opt.className = 'dark:bg-slate-800';
                    selectNarkotika.add(opt);
                }
                if(currentNarkotika) selectNarkotika.value = currentNarkotika;
            }
        }

        function syncDurasiRawat() {
            let els = [];
            if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
            if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));

            let savedDurasiOpts = JSON.parse(localStorage.getItem('opsi_lama_perawatan')) || [];
            els.forEach(select => {
                savedDurasiOpts.forEach(val => {
                    if (!Array.from(select.options).some(opt => opt.value === val)) {
                        let opt = new Option(val, val);
                        opt.className = 'dark:bg-slate-800';
                        select.add(opt);
                    }
                });
            });

            let sharedDurasi = localStorage.getItem('shared_durasi_rawat_{{ $asesmen->id }}');
            if (sharedDurasi) {
                els.forEach(select => {
                    if (!Array.from(select.options).some(opt => opt.value === sharedDurasi)) {
                        let opt = new Option(sharedDurasi, sharedDurasi);
                        opt.className = 'dark:bg-slate-800';
                        select.add(opt);
                    }
                    select.value = sharedDurasi;
                });
            }

            let isSyncingDurasi = false;
            els.forEach(select => {
                select.addEventListener('change', function() {
                    if (isSyncingDurasi) return;
                    isSyncingDurasi = true;

                    localStorage.setItem('shared_durasi_rawat_{{ $asesmen->id }}', this.value);
                    els.forEach(other => {
                        if (other !== this) {
                            if (!Array.from(other.options).some(opt => opt.value === this.value)) {
                                let opt = new Option(this.value, this.value);
                                opt.className = 'dark:bg-slate-800';
                                other.add(opt);
                            }
                            other.value = this.value;
                            other.dispatchEvent(new Event('change'));
                        }
                    });
                    if (typeof saveFormDraft === 'function') saveFormDraft();

                    setTimeout(() => { isSyncingDurasi = false; }, 50);
                });
            });
        }

        // === AUTOSAVE DRAFT FORM ===
        const formRekomendasi = document.getElementById('formRekomendasi');
        const autosaveIndicator = document.getElementById('autosaveIndicator');

        function saveFormDraft() {
            if(!formRekomendasi) return;
            const formData = new FormData(formRekomendasi);
            const data = {};
            formData.forEach((value, key) => {
                if(key !== '_token' && key !== 'action') data[key] = value;
            });
            localStorage.setItem('formRekomendasiDraft_{{ $asesmen->id }}', JSON.stringify(data));

            if(autosaveIndicator) {
                autosaveIndicator.classList.remove('hidden');
                setTimeout(() => { autosaveIndicator.classList.add('hidden'); }, 3000);
            }
        }

        function loadFormDraft() {
            if(!formRekomendasi) return;
            const draft = localStorage.getItem('formRekomendasiDraft_{{ $asesmen->id }}');
            if (draft) {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    const elements = formRekomendasi.querySelectorAll(`[name="${key}"]`);
                    if (elements.length > 0) {
                        const el = elements[0];
                        if (el.type === 'radio' || el.type === 'checkbox') {
                            const target = formRekomendasi.querySelector(`[name="${key}"][value="${data[key]}"]`);
                            if (target) target.checked = true;
                        } else {
                            el.value = data[key];
                        }
                    }
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            initAllCustomSelects();
            loadSemuaOpsiLokal();
            loadFormDraft();
            syncDurasiRawat();

            if(formRekomendasi) {
                formRekomendasi.addEventListener('input', saveFormDraft);
                formRekomendasi.addEventListener('change', saveFormDraft);
            }
        });
    </script>
</x-app-layout>
