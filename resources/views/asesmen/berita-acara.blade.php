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
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- HEADER (Dikeluarkan dari kotak putih, menyatu dengan background) -->
    <x-slot name="header">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 max-w-[1400px] mx-auto w-full">
            <div class="flex items-center gap-4">
                <a href="{{ route('asesmen.show', $asesmen->id) }}" class="w-12 h-12 rounded-[1rem] bg-white dark:bg-[#1E293B] text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-slate-200 dark:border-slate-700/50 shadow-sm shrink-0 hover:scale-105 transition-transform group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-1 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                        Generator Dokumen (Word)
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                        Berita Acara: {{ $asesmen->nama_lengkap }}
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

            <!-- KONTAINER RAKSASA MAC SOFT UI (overflow-visible) -->
            <div class="bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-white/60 dark:border-slate-700/50 transition-colors duration-300 p-6 sm:p-10 overflow-visible pb-32">

                <form action="{{ route('asesmen.berita-acara.generate', $asesmen->id) }}" method="POST" class="space-y-10" id="formBeritaAcara">
                    @csrf
                    <div id="hidden-inputs-container"></div>

                    <!-- ========================================== -->
                    <!-- SEKSI 1: HEADER SURAT & PIMPINAN RAPAT -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[50] overflow-visible transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-500 rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            1. Header Surat & Pimpinan Rapat
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nomor Berita Acara</label>
                                <input type="text" name="no_ba" value="{{ old('no_ba', $asesmen->no_ba) }}" placeholder="Cth: BA/277/VII/TAT/..." class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Rapat (BA)</label>
                                <input type="text" name="tgl_ba" value="{{ old('tgl_ba', $asesmen->tgl_ba ? \Carbon\Carbon::parse($asesmen->tgl_ba)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Pelaksanaan Asesmen</label>
                                <input type="text" name="tgl_asesmen" value="{{ old('tgl_asesmen', $asesmen->tgl_asesmen ? \Carbon\Carbon::parse($asesmen->tgl_asesmen)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Ketua TAT</label>
                                <input type="text" name="ketua_tat_nama" value="{{ old('ketua_tat_nama', $asesmen->ketua_tat_nama ?? 'LETKOL LAUT (PM) Hendratmo Budi Wibowo S Pd.') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NRP Ketua TAT</label>
                                <input type="text" name="ketua_tat_nrp" value="{{ old('ketua_tat_nrp', $asesmen->ketua_tat_nrp ?? '16301/P') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nomor SK Tim Asesmen</label>
                                        <input type="text" name="no_kep_tim" value="{{ old('no_kep_tim', $asesmen->no_kep_tim ?? 'KEP/10/IV/KA/KP/2026/BNN Kab. Malang') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                                    </div>
                                    <div class="sm:w-1/3">
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal SK Tim</label>
                                        <input type="text" name="tgl_kep_tim" value="{{ old('tgl_kep_tim', $asesmen->tgl_kep_tim ? \Carbon\Carbon::parse($asesmen->tgl_kep_tim)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SEKSI 2: PEMILIHAN ANGGOTA TIM TAT -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[40] overflow-visible transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500 rounded-t-[2rem]"></div>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 gap-2 mt-1">
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                2. Susunan Anggota Tim TAT
                            </h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-lg border border-indigo-100 dark:border-indigo-800/50 shadow-sm">Pilih & Kelola dari Master Data</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- KOLOM TIM MEDIS -->
                            <div class="bg-slate-50 dark:bg-[#1E293B] p-6 rounded-[2rem] shadow-inner border border-slate-100 dark:border-slate-700/50 transition-colors">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Tim Medis <span class="font-medium text-slate-400 normal-case tracking-normal">(Maks. 2)</span></label>
                                <div class="flex gap-2 mb-5">
                                    <div class="flex-1 relative z-[40]">
                                        <select id="select-medis" class="block w-full text-sm">
                                            <option value="" class="dark:bg-slate-800">-- Pilih Dokter TAT --</option>
                                        </select>
                                    </div>
                                    <button type="button" onclick="bukaModal('medis')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Baru</button>
                                    <button type="button" onclick="bukaKelola('medis')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                                <div id="container-medis" class="space-y-3"></div>
                            </div>

                            <!-- KOLOM TIM HUKUM -->
                            <div class="bg-slate-50 dark:bg-[#1E293B] p-6 rounded-[2rem] shadow-inner border border-slate-100 dark:border-slate-700/50 transition-colors">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Tim Hukum <span class="font-medium text-slate-400 normal-case tracking-normal">(Maks. 3)</span></label>
                                <div class="flex gap-2 mb-5">
                                    <div class="flex-1 relative z-[40]">
                                        <select id="select-hukum" class="block w-full text-sm">
                                            <option value="" class="dark:bg-slate-800">-- Pilih Penyidik/Jaksa --</option>
                                        </select>
                                    </div>
                                    <button type="button" onclick="bukaModal('hukum')" class="shrink-0 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Baru</button>
                                    <button type="button" onclick="bukaKelola('hukum')" class="shrink-0 px-4 py-2 bg-white dark:bg-[#0F172A] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                </div>
                                <div id="container-hukum" class="space-y-3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SEKSI 3: HASIL PEMERIKSAAN (NARASI) -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[30] overflow-visible transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#3B82F6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            3. Narasi Hasil Pemeriksaan
                        </h3>

                        <div class="space-y-8">
                            <!-- KOTAK NARASI MEDIS -->
                            <div class="p-6 md:p-8 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-[2rem] shadow-sm transition-colors">
                                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-6">
                                    <label class="block text-[13px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest">Narasi Tim Medis</label>

                                    <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-[#1E293B] p-3 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm">
                                        <span class="text-slate-500 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest pl-2">Gunakan Alamat:</span>
                                        <div class="flex gap-4 px-2 border-r border-slate-200 dark:border-slate-700 pr-4">
                                            <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 dark:text-slate-200 font-bold text-xs group">
                                                <input type="radio" name="alamat_medis" value="ktp" checked class="text-[#3B82F6] focus:ring-[#3B82F6] bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4">
                                                <span class="group-hover:text-[#3B82F6] transition-colors">KTP</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 dark:text-slate-200 font-bold text-xs group">
                                                <input type="radio" name="alamat_medis" value="domisili" class="text-[#3B82F6] focus:ring-[#3B82F6] bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4">
                                                <span class="group-hover:text-[#3B82F6] transition-colors">Domisili</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 dark:text-slate-200 font-bold text-xs group">
                                                <input type="radio" name="alamat_medis" value="keduanya" class="text-[#3B82F6] focus:ring-[#3B82F6] bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4">
                                                <span class="group-hover:text-[#3B82F6] transition-colors">Keduanya</span>
                                            </label>
                                        </div>
                                        <button type="button" onclick="applyDraftMedis()" class="px-5 py-2.5 bg-[#3B82F6] text-white hover:bg-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5">Terapkan Draf Pembuka</button>
                                    </div>
                                </div>
                                <textarea id="narasi_medis" name="narasi_medis" rows="8" class="block w-full rounded-[1.25rem] border border-blue-200 dark:border-slate-700 bg-white dark:bg-[#0F172A] text-slate-900 dark:text-white px-5 py-4 text-[13px] font-medium leading-relaxed shadow-inner focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500">{{ old('narasi_medis', $asesmen->narasi_medis) }}</textarea>
                            </div>

                            <!-- KOTAK NARASI HUKUM -->
                            <div class="p-6 md:p-8 bg-slate-50 dark:bg-[#1E293B] border border-slate-200 dark:border-slate-700/50 rounded-[2rem] shadow-sm transition-colors">
                                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-6">
                                    <label class="block text-[13px] font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest">Narasi Tim Hukum</label>

                                    <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-[#0F172A] p-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                        <div class="flex items-center gap-3 border-r border-slate-200 dark:border-slate-700 pr-4 pl-2">
                                            <span class="text-slate-500 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest">Pekerjaan:</span>
                                            <input type="text" id="input_pekerjaan_hukum" value="{{ $asesmen->pekerjaan->nama_pekerjaan ?? '' }}" class="h-9 text-xs font-bold rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-[#1E293B] text-slate-800 dark:text-slate-200 w-36 px-3 focus:ring-2 focus:ring-slate-500/20 outline-none transition-all">
                                        </div>
                                        <div class="flex items-center gap-4 border-r border-slate-200 dark:border-slate-700 pr-4">
                                            <span class="text-slate-500 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest">Alamat:</span>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 dark:text-slate-200 font-bold text-xs group">
                                                <input type="radio" name="alamat_hukum" value="ktp" checked class="text-slate-600 focus:ring-slate-500 bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4">
                                                <span class="group-hover:text-slate-500 transition-colors">KTP</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 dark:text-slate-200 font-bold text-xs group">
                                                <input type="radio" name="alamat_hukum" value="domisili" class="text-slate-600 focus:ring-slate-500 bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4">
                                                <span class="group-hover:text-slate-500 transition-colors">Domisili</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 dark:text-slate-200 font-bold text-xs group">
                                                <input type="radio" name="alamat_hukum" value="keduanya" class="text-slate-600 focus:ring-slate-500 bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4">
                                                <span class="group-hover:text-slate-500 transition-colors">Keduanya</span>
                                            </label>
                                        </div>
                                        <button type="button" onclick="applyDraftHukum()" class="px-5 py-2.5 bg-slate-800 dark:bg-slate-700 text-white hover:bg-slate-900 dark:hover:bg-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5">Terapkan Draf Pembuka</button>
                                    </div>
                                </div>
                                <textarea id="narasi_hukum" name="narasi_hukum" rows="8" class="block w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#0F172A] text-slate-900 dark:text-white px-5 py-4 text-[13px] font-medium leading-relaxed shadow-inner focus:border-slate-400 focus:ring-2 focus:ring-slate-500/20 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500">{{ old('narasi_hukum', $asesmen->narasi_hukum) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SEKSI 4: ALAT BUKTI & KESIMPULAN -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative z-[20] overflow-visible transition-colors mt-8">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#10B981] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-8 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#10B981]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            4. Alat Bukti & Kesimpulan
                        </h3>

                        <!-- Sub A: Alat Bukti -->
                        <div class="mb-10">
                            <div class="flex items-center gap-3 bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 p-3 rounded-xl mb-6">
                                <span class="bg-emerald-200 dark:bg-emerald-600 text-emerald-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">A</span>
                                <span class="text-[11px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Data Alat Bukti (Surat Keterangan Narkoba)</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. SK Narkoba</label>
                                    <input type="text" name="alat_bukti_no_sk" value="{{ old('alat_bukti_no_sk', $asesmen->alat_bukti_no_sk ?? 'B/ND/336VII/KES.I./2026') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal SK Narkoba</label>
                                    <input type="text" name="alat_bukti_tgl_sk" value="{{ old('alat_bukti_tgl_sk', $asesmen->alat_bukti_tgl_sk ? \Carbon\Carbon::parse($asesmen->alat_bukti_tgl_sk)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Dokter Pemeriksa</label>
                                    <select id="select-dokter-pemeriksa" name="alat_bukti_dokter" class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Dokter Pemeriksa --</option>
                                    </select>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mt-2 ml-1 tracking-widest uppercase">Pilih dari Master Data Dokter</p>
                                </div>

                                <!-- LOGIKA CERDAS: DETEKSI KATA POSITIF/NEGATIF -->
                                @php
                                    $savedUrine = old('tes_urine', $asesmen->tes_urine ?? '');
                                    $isPositif = stripos($savedUrine, 'positif') !== false;
                                    $isNegatif = stripos($savedUrine, 'negatif') !== false;
                                @endphp

                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Tes Urine</label>
                                    <select id="tes_urine_ba" name="tes_urine" class="block w-full text-sm" onchange="toggleZatBukti()">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Hasil Urine --</option>
                                        <option value="POSITIF" {{ $isPositif ? 'selected' : '' }} class="dark:bg-slate-800 font-bold text-rose-500">POSITIF</option>
                                        <option value="NEGATIF" {{ $isNegatif ? 'selected' : '' }} class="dark:bg-slate-800 font-bold text-emerald-500">NEGATIF</option>
                                    </select>
                                </div>

                                <div class="row-span-2 md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pilih Zat Narkotika <span class="font-medium text-slate-400 normal-case tracking-normal">(Tampil sbg: POSITIF Zat A DAN Zat B)</span></label>
                                    <div class="p-5 border border-slate-200 dark:border-slate-700 rounded-[1.25rem] bg-slate-50 dark:bg-[#1E293B] shadow-sm transition-colors" id="box-zat-bukti">
                                        <div id="container-zat-alat-bukti" class="mb-4 max-h-48 overflow-y-auto pr-2 custom-select-scroll grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <!-- Render Checkbox Alat Bukti JS -->
                                        </div>
                                        <div class="flex gap-2 pt-4 border-t border-slate-200 dark:border-slate-700/50">
                                            <button type="button" id="btnTambahZatBukti" onclick="bukaModalOpsi('zat_bukti')" class="px-4 py-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors shadow-sm">+ Tambah Zat</button>
                                            <button type="button" onclick="bukaKelolaOpsi('zat_bukti')" class="px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="alat_bukti_hasil" id="alat_bukti_hasil_input" value="{{ old('alat_bukti_hasil', $asesmen->alat_bukti_hasil) }}">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-3 ml-1">Preview Output Word: <span id="preview_alat_bukti_hasil" class="text-rose-500 dark:text-rose-400 font-bold normal-case tracking-normal">{{ old('alat_bukti_hasil', $asesmen->alat_bukti_hasil) }}</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Sub B: Kesimpulan TAT -->
                        <div class="mb-10">
                            <div class="flex items-center gap-3 bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800/30 p-3 rounded-xl mb-6">
                                <span class="bg-indigo-200 dark:bg-indigo-600 text-indigo-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">B</span>
                                <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Kesimpulan Diagnostik TAT</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- CHECKBOX KESIMPULAN JENIS ZAT -->
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Zat yang Dipakai <span class="font-medium text-slate-400 normal-case tracking-normal">(Tampil murni: Zat A, Zat B)</span></label>
                                    <div class="p-5 border border-slate-200 dark:border-slate-700 rounded-[1.25rem] bg-slate-50 dark:bg-[#1E293B] shadow-sm transition-colors">
                                        <div id="container-zat-kesimpulan" class="mb-4 max-h-48 overflow-y-auto pr-2 grid grid-cols-1 sm:grid-cols-2 gap-2 custom-select-scroll">
                                            <!-- Render Checkbox Kesimpulan JS -->
                                        </div>
                                        <div class="flex gap-2 pt-4 border-t border-slate-200 dark:border-slate-700/50">
                                            <button type="button" onclick="bukaModalOpsi('zat_kesimpulan')" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm">+ Tambah Zat</button>
                                            <button type="button" onclick="bukaKelolaOpsi('zat_kesimpulan')" class="px-4 py-2 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">Kelola</button>
                                        </div>
                                    </div>
                                    <!-- Input tersembunyi yang ditarik oleh Laravel -->
                                    <input type="hidden" id="kesimpulan_jenis_zat_input" name="kesimpulan_jenis_zat" value="{{ old('kesimpulan_jenis_zat', $asesmen->kesimpulan_jenis_zat) }}">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status Klien</label>
                                    <select name="status_klien" class="block w-full text-sm">
                                        <option value="penyalahguna" {{ old('status_klien', $asesmen->status_klien) == 'penyalahguna' ? 'selected' : '' }} class="dark:bg-slate-800">Penyalahguna</option>
                                        <option value="korban penyalahguna" {{ old('status_klien', $asesmen->status_klien) == 'korban penyalahguna' ? 'selected' : '' }} class="dark:bg-slate-800">Korban Penyalahguna</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pola Pemakaian</label>
                                    <select name="kesimpulan_pola_pakai" class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Pola --</option>
                                        <option value="Coba pakai" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Coba pakai' ? 'selected' : '' }} class="dark:bg-slate-800">Coba pakai</option>
                                        <option value="Situasional" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Situasional' ? 'selected' : '' }} class="dark:bg-slate-800">Situasional</option>
                                        <option value="Rekreasional" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Rekreasional' ? 'selected' : '' }} class="dark:bg-slate-800">Rekreasional</option>
                                        <option value="Teratur pakai" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Teratur pakai' ? 'selected' : '' }} class="dark:bg-slate-800">Teratur pakai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kategori Ketergantungan</label>
                                    <select name="kesimpulan_kategori" class="block w-full text-sm">
                                        <option value="" class="dark:bg-slate-800">-- Pilih Kategori --</option>
                                        <option value="Ringan" {{ old('kesimpulan_kategori', $asesmen->kesimpulan_kategori) == 'Ringan' ? 'selected' : '' }} class="dark:bg-slate-800">Ringan</option>
                                        <option value="Sedang" {{ old('kesimpulan_kategori', $asesmen->kesimpulan_kategori) == 'Sedang' ? 'selected' : '' }} class="dark:bg-slate-800">Sedang</option>
                                        <option value="Berat" {{ old('kesimpulan_kategori', $asesmen->kesimpulan_kategori) == 'Berat' ? 'selected' : '' }} class="dark:bg-slate-800">Berat</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 pt-4">
                                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1 text-center">Diagnosis Medis Final</label>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <!-- Custom Wrapper untuk menyelaraskan custom select dengan tombol sebelahnya -->
                                        <div class="flex-1">
                                            <select id="select_diagnosis" name="diagnosis_medis" class="block w-full text-sm text-center">
                                                <!-- Dirender oleh JS agar tersinkronisasi master data -->
                                            </select>
                                        </div>
                                        <div class="flex gap-2 justify-center sm:justify-start shrink-0">
                                            <button type="button" onclick="bukaModalOpsi('diagnosis')" class="px-5 py-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-widest rounded-[1.25rem] hover:bg-indigo-100 dark:hover:bg-indigo-800/50 transition-colors shadow-sm whitespace-nowrap">+ Tambah</button>
                                            <button type="button" onclick="bukaKelolaOpsi('diagnosis')" class="px-5 py-3 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-[1.25rem] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm whitespace-nowrap">Kelola</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sub C: Rekomendasi (TERKUNCI - BERSUMBER DARI FORM REGISTRASI) -->
                        <div class="bg-emerald-50/30 dark:bg-emerald-900/10 p-6 rounded-[2rem] border border-emerald-100 dark:border-emerald-800/30 mt-8 transition-colors">
                            <h4 class="font-extrabold text-[12px] text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="bg-emerald-200 dark:bg-emerald-700 text-emerald-800 dark:text-emerald-50 px-2 py-0.5 rounded shadow-sm">C</span> Keputusan Rekomendasi & Penempatan
                            </h4>

                            @php
                                $rawTempat = old('rekomendasi_tempat_rehab', $asesmen->rekomendasi_input ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_tempat_rehab ?? '');
                                $tempatBersih = $rawTempat;

                                if (str_starts_with($rawTempat, 'Rawat Jalan - ')) {
                                    $tempatBersih = trim(str_replace('Rawat Jalan - ', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Rawat Inap - ')) {
                                    $tempatBersih = trim(str_replace('Rawat Inap - ', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Rehab di Lapas / Rutan - ')) {
                                    $tempatBersih = trim(str_replace('Rehab di Lapas / Rutan - ', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Tidak Rehab (Proses Hukum) - ')) {
                                    $tempatBersih = trim(str_replace('Tidak Rehab (Proses Hukum) - ', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Rawat Jalan')) {
                                    $tempatBersih = trim(str_replace('Rawat Jalan', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Rawat Inap')) {
                                    $tempatBersih = trim(str_replace('Rawat Inap', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Rehab di Lapas / Rutan')) {
                                    $tempatBersih = trim(str_replace('Rehab di Lapas / Rutan', '', $rawTempat));
                                } elseif (str_starts_with($rawTempat, 'Tidak Rehab (Proses Hukum)')) {
                                    $tempatBersih = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $rawTempat));
                                }

                                $tempatBersih = trim(ltrim($tempatBersih, ' -'));

                                if (empty(trim($tempatBersih))) {
                                    $tempatBersih = '';
                                }
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-[12px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1">Tempat Rehabilitasi (Sesuai SK)</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $tempatBersih ?: 'Belum diset (Silakan edit di Data Klien)' }}" readonly class="block w-full rounded-xl border-emerald-200 dark:border-emerald-700 shadow-sm sm:text-sm bg-emerald-50/50 dark:bg-emerald-900/20 cursor-not-allowed text-emerald-800 dark:text-emerald-400 font-extrabold pr-10 pointer-events-none transition-colors">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-emerald-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                    </div>
                                    <input type="hidden" name="rekomendasi_tempat_rehab" value="{{ $tempatBersih }}">
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1.5 italic">Terkunci. Data ini otomatis ditarik dari form pendaftaran awal (Edit Klien).</p>
                                </div>

                                <!-- DURASI REHABILITASI (SINKRON DENGAN REKOMENDASI VIA LOCAL STORAGE) -->
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1">Lama (Durasi) Rawat <span class="text-rose-500">*</span></label>
                                    <div class="flex gap-2 items-center">
                                        <div class="flex-1">
                                            <select name="rekomendasi_durasi" id="rekomendasi_durasi" required class="block w-full text-sm">
                                                <option value="" class="dark:bg-slate-800">-- Pilih Waktu Perawatan --</option>
                                                @php
                                                    $valDurasi = old('rekomendasi_durasi', $asesmen->lama_perawatan ?? $asesmen->rekomendasi_durasi ?? '');
                                                    $defaultsDurasi = ['1 bulan', '2 bulan', '3 bulan', '1-3 bulan'];
                                                @endphp
                                                @if($valDurasi !== '')
                                                    <option value="{{ $valDurasi }}" selected class="dark:bg-slate-800">{{ $valDurasi }}</option>
                                                @endif
                                                @foreach($defaultsDurasi as $d)
                                                    @if($d !== $valDurasi)
                                                        <option value="{{ $d }}" class="dark:bg-slate-800">{{ $d }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" onclick="openTambahModal('rekomendasi_durasi', 'Lama (Durasi) Rawat', 'lama_perawatan')" class="px-4 py-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-xl text-[11px] font-bold hover:bg-emerald-100 dark:hover:bg-emerald-800/50 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                        <button type="button" onclick="openKelolaModal('rekomendasi_durasi', 'Lama (Durasi) Rawat', 'lama_perawatan')" class="px-4 py-3 bg-white dark:bg-[#0F172A] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-xl text-[11px] font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm whitespace-nowrap">KELOLA</button>
                                    </div>
                                </div>

                                <!-- KETERANGAN HUKUM REKOMENDASI -->
                                <div class="md:col-span-2 mt-2">
                                    <label class="block text-[12px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1">Keterangan Hukum Rekomendasi</label>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <div class="flex-1">
                                            <select name="rekomendasi_keterangan" id="rekomendasi_keterangan" required class="block w-full text-sm" onchange="if(typeof saveFormDraft === 'function') saveFormDraft();">
                                                <option value="" class="dark:bg-slate-800">-- Pilih Keterangan Hukum --</option>
                                                @php
                                                    $ketList = [
                                                        "Hasil pemeriksaan urine positif narkotika, tanpa barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine negatif narkotika, tanpa barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine positif narkotika, dengan barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine negatif narkotika, dengan barang bukti narkotika dan tidak terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine positif narkotika, tanpa barang bukti narkotika, namun terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine negatif narkotika, tanpa barang bukti narkotika, namun terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine positif narkotika, dengan barang bukti narkotika dan terdapat keterlibatan dalam jaringan peredaran gelap narkotika.",
                                                        "Hasil pemeriksaan urine negatif narkotika, dengan barang bukti narkotika dan terdapat keterlibatan dalam jaringan peredaran gelap narkotika."
                                                    ];
                                                    $savedKet = old('rekomendasi_keterangan', $asesmen->rekomendasi_keterangan);
                                                @endphp
                                                @if($savedKet !== '' && !in_array($savedKet, $ketList))
                                                    <option value="{{ $savedKet }}" selected class="dark:bg-slate-800">{{ $savedKet }}</option>
                                                @endif
                                                @foreach($ketList as $ket)
                                                    <option value="{{ $ket }}" {{ $savedKet == $ket ? 'selected' : '' }} class="dark:bg-slate-800">{{ $ket }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex gap-2 shrink-0 justify-center sm:justify-start">
                                            <button type="button" onclick="openTambahModal('rekomendasi_keterangan', 'Keterangan Hukum Rekomendasi')" class="px-5 py-3 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-black text-[10px] uppercase tracking-widest rounded-[1.25rem] hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors shadow-sm whitespace-nowrap">+ Tambah</button>
                                            <button type="button" onclick="openKelolaModal('rekomendasi_keterangan', 'Keterangan Hukum Rekomendasi')" class="px-5 py-3 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-[1.25rem] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm whitespace-nowrap">Kelola</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TOMBOL SUBMIT -->
                    <!-- ========================================== -->
                    <div class="border-t border-slate-200 dark:border-slate-800/60 pt-8 pb-10 flex flex-col sm:flex-row justify-end gap-4 fade-in-up delay-200">
                        <a href="{{ route('asesmen.show', $asesmen->id) }}" class="inline-flex justify-center items-center px-8 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Batal</a>
                        <button type="submit" name="action" value="save_only" onclick="syncHiddenInputs()" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#3B82F6] text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-blue-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40">Simpan Perubahan</button>
                        <button type="submit" name="action" value="generate" onclick="syncHiddenInputs()" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#10B981] text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-emerald-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-emerald-500/30 dark:shadow-emerald-900/40">Simpan & Unduh Berita Acara</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- KUMPULAN MODAL POPUP (MAC SOFT UI)                             -->
    <!-- ============================================================== -->

    <!-- Modal Tambah Durasi/Keterangan -->
    <div id="modalTambahDurasi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeTambahModal()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight" id="modal-title">Tambah <span id="tambah_label" class="text-[#3B82F6] dark:text-blue-400"></span></h3>
            <input type="hidden" id="tambah_target_id">
            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama/Nilai Baru</label>
            <input type="text" id="tambah_input" placeholder="Ketik di sini..." class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none mb-6 transition-all">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="button" onclick="simpanOpsiBaruKhusus()" class="px-5 py-2.5 bg-[#3B82F6] text-white font-bold text-xs rounded-xl hover:bg-blue-600 transition-colors shadow-md">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Durasi/Keterangan -->
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

    <!-- Modal Tambah Anggota -->
    <div id="modalTambahAnggota" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="tutupModal()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <button type="button" onclick="tutupModal()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight" id="modalTitle">Tambah Anggota Baru</h3>
            <form id="formTambahAnggota" class="space-y-4">
                <input type="hidden" id="modalKategori" name="kategori">
                <div><label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap & Gelar</label><input type="text" id="modalNama" required class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all"></div>
                <div><label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NIP / NRP / SIP</label><input type="text" id="modalNip" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all"></div>
                <div id="wrapPangkat"><label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pangkat (Opsional)</label><input type="text" id="modalPangkat" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all"></div>
                <div><label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jabatan</label><input type="text" id="modalJabatan" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all"></div>
                <div class="flex justify-end gap-2 pt-4 mt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="tutupModal()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                    <button type="button" onclick="simpanAnggotaBaru()" id="btnSimpanAnggota" class="px-5 py-2.5 bg-[#3B82F6] text-white font-bold text-xs rounded-xl hover:bg-blue-600 transition-colors shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kelola Anggota -->
    <div id="modalKelolaAnggota" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="tutupKelola()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight" id="kelolaTitle">Kelola Anggota</h3>
                <button type="button" onclick="tutupKelola()" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-60 overflow-y-auto space-y-2 pr-2 custom-select-scroll mb-6" id="kelolaList"></div>
            <button type="button" onclick="tutupKelola()" class="w-full px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup Kelola</button>
        </div>
    </div>

    <!-- Modal Tambah Opsi Master (Zat & Diagnosis) -->
    <div id="modalTambahOpsi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="document.getElementById('modalTambahOpsi').classList.add('hidden'); document.getElementById('modalTambahOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalTambahOpsi').classList.remove('flex'),300);"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight" id="modalOpsiTitle">Tambah Data Baru</h3>
            <input type="hidden" id="modalOpsiKategori">
            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama/Nilai Baru</label>
            <input type="text" id="modalOpsiNilai" placeholder="Ketik di sini..." class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none mb-6 transition-all">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahOpsi').classList.add('hidden'); document.getElementById('modalTambahOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalTambahOpsi').classList.remove('flex'),300);" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="button" onclick="simpanOpsiBaru()" id="btnSimpanOpsi" class="px-5 py-2.5 bg-[#3B82F6] text-white font-bold text-xs rounded-xl hover:bg-blue-600 transition-colors shadow-md">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Opsi Master (Zat & Diagnosis) -->
    <div id="modalKelolaOpsi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="document.getElementById('modalKelolaOpsi').classList.add('hidden'); document.getElementById('modalKelolaOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalKelolaOpsi').classList.remove('flex'),300);"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight" id="kelolaOpsiTitle">Kelola Opsi</h3>
                <button type="button" onclick="document.getElementById('modalKelolaOpsi').classList.add('hidden'); document.getElementById('modalKelolaOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalKelolaOpsi').classList.remove('flex'),300);" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-60 overflow-y-auto space-y-2 pr-2 custom-select-scroll mb-6" id="kelolaOpsiList"></div>
            <button type="button" onclick="document.getElementById('modalKelolaOpsi').classList.add('hidden'); document.getElementById('modalKelolaOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalKelolaOpsi').classList.remove('flex'),300);" class="w-full px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup Kelola</button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC -->
    <!-- ========================================== -->
    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC (BERITA ACARA) -->
    <!-- ========================================== -->
    <script>
        // === MESIN SINKRONISASI HASIL TES URINE ===
        function syncTesUrine() {
            // Target spesifik id="tes_urine_ba" untuk halaman Berita Acara
            const selectUrine = document.getElementById('tes_urine_ba');
            if (!selectUrine) return;

            const storageKey = 'shared_tes_urine_{{ $asesmen->id }}';
            let isSyncingUrine = false;
            let sharedUrine = localStorage.getItem(storageKey);

            // Jika ada data di memori lokal, terapkan dan render UI
            if (sharedUrine) {
                if (sharedUrine !== selectUrine.value) {
                    let exists = Array.from(selectUrine.options).some(opt => opt.value === sharedUrine);
                    if (!exists) {
                        let opt = new Option(sharedUrine, sharedUrine);
                        opt.className = 'dark:bg-slate-800';
                        selectUrine.add(opt);
                    }
                    selectUrine.value = sharedUrine;

                    // Trigger UI Custom Select & Buka/Kunci Zat Narkotika
                    setTimeout(() => { selectUrine.dispatchEvent(new Event('change', { bubbles: true })); }, 100);
                }
            }

            // Simpan ke memori lokal jika terjadi perubahan dari user
            selectUrine.addEventListener('change', function() {
                if(isSyncingUrine) return;
                isSyncingUrine = true;

                localStorage.setItem(storageKey, this.value);

                if (typeof toggleZatBukti === 'function') {
                    toggleZatBukti();
                }

                setTimeout(() => { isSyncingUrine = false; }, 50);
            });

            const formEl = selectUrine.closest('form');
            if(formEl) {
                formEl.addEventListener('submit', () => localStorage.removeItem(storageKey));
            }
        }

        // MESIN CUSTOM SELECT MAC SOFT UI
        function makeCustomSelect(select) {
            let skipCondition1 = !select;
            let skipCondition2 = select ? select.classList.contains('no-custom') : false;
            let skipCondition3 = select ? select.classList.contains('hidden') : false;
            let skipCondition4 = select ? (select.id === 'narkotika-dropdown') : false;

            if (skipCondition1) return;
            if (skipCondition2) return;
            if (skipCondition3) return;
            if (skipCondition4) return;

            let isRendering = false; let renderTimeout;

            function render() {
                if (isRendering) return; isRendering = true;

                let checkExisting = select.nextElementSibling;
                if (checkExisting) {
                    if (checkExisting.classList.contains('mac-select-wrapper')) {
                        checkExisting.remove();
                    }
                }

                const wrapper = document.createElement('div'); wrapper.className = 'mac-select-wrapper relative w-full'; wrapper.style.zIndex = '1';
                const btn = document.createElement('button'); btn.type = 'button';

                let isEmerald = select.classList.contains('bg-emerald-50') ? true : select.classList.contains('text-emerald-700');
                let isRose = select.classList.contains('bg-rose-50') ? true : select.classList.contains('text-rose-700');

                if (select.name === 'tes_urine') {
                    if (select.value === 'POSITIF') { isRose = true; isEmerald = false; }
                    else if (select.value === 'NEGATIF') { isEmerald = true; isRose = false; }
                }

                let btnBaseClass = 'flex justify-between items-center w-full px-4 py-3 rounded-[1.25rem] border transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-sm text-sm font-medium text-left ';
                if (select.disabled) { btn.className = btnBaseClass + 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:text-slate-500'; btn.disabled = true; }
                else if (isEmerald) { btn.className = btnBaseClass + 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400'; }
                else if (isRose) { btn.className = btnBaseClass + 'bg-rose-50 border-rose-100 text-rose-700 hover:border-rose-300 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400'; }
                else { btn.className = btnBaseClass + 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 dark:bg-[#0F172A] dark:border-slate-700 dark:text-slate-200'; }

                const span = document.createElement('span'); span.className = 'truncate pointer-events-none flex-1';
                let selectedText = '-- Pilih --';

                let isIndexValid = select.selectedIndex >= 0;
                let isOptionValid = isIndexValid ? select.options[select.selectedIndex] : false;

                if (isOptionValid) {
                    selectedText = select.options[select.selectedIndex].text;
                }
                else if (select.options.length > 0) {
                    selectedText = select.options[0].text;
                }
                span.innerText = selectedText;

                const icon = document.createElement('div'); icon.className = 'shrink-0 ml-3 transition-transform duration-200 pointer-events-none flex items-center justify-center';
                let iconColor = isEmerald ? 'text-emerald-500 dark:text-emerald-400' : (isRose ? 'text-rose-500 dark:text-rose-400' : 'text-slate-400');
                icon.innerHTML = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`;

                btn.appendChild(span); btn.appendChild(icon);
                const listWrapper = document.createElement('div'); listWrapper.className = 'mac-select-list absolute z-[90] top-full left-0 mt-2 w-full bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] rounded-2xl py-2 max-h-60 overflow-y-auto hidden custom-select-scroll transition-all opacity-0 translate-y-2';

                let hasVisibleOptions = false;
                Array.from(select.options).forEach((opt, index) => {
                    if(opt.style.display === 'none') return;
                    hasVisibleOptions = true;
                    const item = document.createElement('div'); item.className = 'px-4 py-2.5 mx-1.5 mt-0.5 rounded-xl cursor-pointer text-sm transition-colors ';

                    let conditionDisableEmpty = opt.disabled ? (opt.value === '') : false;

                    if (conditionDisableEmpty) item.className += 'text-slate-400 font-bold opacity-70 cursor-default pointer-events-none';
                    else if (opt.disabled) item.className += 'text-slate-400 cursor-not-allowed bg-slate-50 pointer-events-none';
                    else if (opt.selected ? (opt.value !== '') : false) item.className += 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400';
                    else item.className += 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800';

                    item.innerText = opt.text;
                    if (!opt.disabled) {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation(); select.selectedIndex = index; span.innerText = opt.text; closeAllMacSelects();

                            if (select.name === 'tes_urine') {
                                if (opt.value === "POSITIF") { btn.className = btnBaseClass + 'bg-rose-50 border-rose-100 text-rose-700 hover:border-rose-300 focus:ring-rose-500/20 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400'; icon.innerHTML = `<svg class="w-4 h-4 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`; }
                                else if (opt.value === "NEGATIF") { btn.className = btnBaseClass + 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300 focus:ring-emerald-500/20 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400'; icon.innerHTML = `<svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`; }
                                else { btn.className = btnBaseClass + 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 dark:bg-[#0F172A] dark:border-slate-700 dark:text-slate-200'; icon.innerHTML = `<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`; }
                            }
                            select.dispatchEvent(new Event('change', {bubbles: true}));
                        });
                    }
                    listWrapper.appendChild(item);
                });

                if(!hasVisibleOptions) {
                    const empty = document.createElement('div'); empty.className = 'px-4 py-2 text-sm text-slate-400 italic text-center'; empty.innerText = 'Tidak ada pilihan'; listWrapper.appendChild(empty);
                }

                btn.addEventListener('click', (e) => {
                    e.preventDefault(); e.stopPropagation(); if(select.disabled) return;
                    const isHidden = listWrapper.classList.contains('hidden'); closeAllMacSelects();
                    if (isHidden) {
                        wrapper.style.zIndex = '999';
                        const pCard = wrapper.closest('.bg-white.dark\\:bg-\\[\\#0F172A\\]'); if(pCard) pCard.style.zIndex = '999';
                        if (listWrapper.closeTimeout) clearTimeout(listWrapper.closeTimeout);
                        listWrapper.classList.remove('hidden'); requestAnimationFrame(() => { listWrapper.classList.remove('opacity-0', 'translate-y-2'); icon.classList.add('rotate-180'); });
                    }
                });

                wrapper.appendChild(btn); wrapper.appendChild(listWrapper); select.style.display = 'none';
                if (select.classList.contains('w-[110px]')) { const o = document.createElement('div'); o.className = 'w-[110px] shrink-0'; select.parentNode.insertBefore(o, select); o.appendChild(select); o.appendChild(wrapper); }
                else { select.parentNode.insertBefore(wrapper, select.nextSibling); }
                isRendering = false;
            }
            function triggerRender() { clearTimeout(renderTimeout); renderTimeout = setTimeout(render, 50); }
            triggerRender();
            const observer = new MutationObserver(() => triggerRender()); observer.observe(select, { childList: true, attributes: true, attributeFilter: ['disabled'] });
            select.addEventListener('change', () => triggerRender());
        }

        function closeAllMacSelects() {
            document.querySelectorAll('.mac-select-wrapper').forEach(wrapper => {
                wrapper.style.zIndex = '1'; const pCard = wrapper.closest('.bg-white.dark\\:bg-\\[\\#0F172A\\]'); if(pCard) pCard.style.zIndex = '';
                const list = wrapper.querySelector('.mac-select-list'); const icon = wrapper.querySelector('button > div');

                let isListVisible = false;
                if (list) {
                    if (!list.classList.contains('hidden')) {
                        isListVisible = true;
                    }
                }

                if(isListVisible) {
                    list.classList.add('opacity-0', 'translate-y-2'); if(icon) icon.classList.remove('rotate-180');
                    if (list.closeTimeout) clearTimeout(list.closeTimeout);
                    list.closeTimeout = setTimeout(() => { list.classList.add('hidden'); }, 200);
                }
            });
        }
        document.addEventListener('click', closeAllMacSelects);

        function initAllCustomSelects() { document.querySelectorAll('select').forEach(select => { makeCustomSelect(select); }); }

        // === SINKRONISASI DROPDOWN UMUM ===
        let currentStorageKey = null;

        function openTambahModal(selectId, labelName, storageKey = null) {
            document.getElementById('tambah_target_id').value = selectId; currentStorageKey = storageKey || selectId;
            document.getElementById('tambah_label').innerText = labelName; document.getElementById('tambah_input').value = '';
            const m = document.getElementById('modalTambahDurasi'); m.classList.remove('hidden'); m.classList.add('flex');
            setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); document.getElementById('tambah_input').focus(); }, 10);
        }
        function closeTambahModal() { const m = document.getElementById('modalTambahDurasi'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300); }

        function simpanOpsiBaruKhusus() {
            const selectId = document.getElementById('tambah_target_id').value; const newValue = document.getElementById('tambah_input').value.trim();
            if(newValue !== '') {
                let els = [];
                if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi') {
                    if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
                    if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));
                } else { els.push(document.getElementById(selectId)); }
                els.forEach(el => { if (!Array.from(el.options).some(opt => opt.value === newValue)) { let opt = new Option(newValue, newValue); el.add(opt); } el.value = newValue; });
                let keyToSave = currentStorageKey || selectId; let savedOpts = JSON.parse(localStorage.getItem('opsi_' + keyToSave)) || [];
                if (!savedOpts.includes(newValue)) { savedOpts.push(newValue); localStorage.setItem('opsi_' + keyToSave, JSON.stringify(savedOpts)); }
                if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi' || currentStorageKey === 'lama_perawatan') { localStorage.setItem('shared_durasi_rawat_{{ $asesmen->id }}', newValue); }
                closeTambahModal(); if (typeof saveFormDraft === 'function') saveFormDraft();
            }
        }

        function openKelolaModal(selectId, labelName, storageKey = null) {
            document.getElementById('kelola_target_id').value = selectId; currentStorageKey = storageKey || selectId; document.getElementById('kelola_label').innerText = labelName;
            const selectEl = document.getElementById(selectId); const listContainer = document.getElementById('kelola_list'); listContainer.innerHTML = '';
            let hasItems = false;
            for(let i = 0; i < selectEl.options.length; i++) {
                const opt = selectEl.options[i];
                if(opt.value !== '') {
                    hasItems = true; const li = document.createElement('li'); li.className = "flex justify-between items-center p-4 bg-slate-50/50 dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors";
                    li.innerHTML = `<span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-3/4">${opt.text}</span><button type="button" onclick="hapusOpsiLokal('${selectId}', '${opt.value}', this)" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>`;
                    listContainer.appendChild(li);
                }
            }
            if(!hasItems) listContainer.innerHTML = '<li class="text-center text-slate-400 dark:text-slate-500 py-4 text-sm italic">Belum ada daftar riwayat tersimpan.</li>';
            const m = document.getElementById('modalKelolaDurasi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); }, 10);
        }
        function closeKelolaModal() { const m = document.getElementById('modalKelolaDurasi'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300); }

        function hapusOpsiLokal(selectId, valueToRemove, btnEl) {
            let els = [];
            if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi') {
                if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
                if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));
            } else { els.push(document.getElementById(selectId)); }
            els.forEach(selectEl => { for(let i = 0; i < selectEl.options.length; i++) { if(selectEl.options[i].value === valueToRemove) { selectEl.remove(i); break; } } });
            let keyToSave = currentStorageKey || selectId; let savedOpts = JSON.parse(localStorage.getItem('opsi_' + keyToSave)) || []; savedOpts = savedOpts.filter(item => item !== valueToRemove); localStorage.setItem('opsi_' + keyToSave, JSON.stringify(savedOpts));
            const li = btnEl.closest('li'); li.style.opacity = '0'; setTimeout(() => { li.remove(); if(document.getElementById('kelola_list').children.length === 0) document.getElementById('kelola_list').innerHTML = '<li class="text-center text-slate-400 dark:text-slate-500 py-4 text-sm italic">Semua opsi telah dihapus dari daftar ini.</li>'; }, 200);
            if (typeof saveFormDraft === 'function') saveFormDraft();
        }

        function loadSemuaOpsiLokal() {
            const selectIds = ['kepada_yth', 'no_keputusan', 'tentang_permohonan', 'keterangan_diagnosis'];
            selectIds.forEach(id => {
                let savedOpts = JSON.parse(localStorage.getItem('opsi_' + id)) || []; const selectEl = document.getElementById(id);
                if(selectEl) { savedOpts.forEach(val => { if (!Array.from(selectEl.options).some(opt => opt.value === val)) { let opt = new Option(val, val); opt.className = 'dark:bg-slate-800'; selectEl.add(opt); } }); }
            });

            let savedZat = JSON.parse(localStorage.getItem('shared_master_zat_narkotika'));
            if (!savedZat) { savedZat = [ "Amphetamine (Amfetamin)", "Methamphetamine (Metamfetamin)", "Tetrahydrocannabinol", "Benzodiazepines (Benzodiazepin)" ]; localStorage.setItem('shared_master_zat_narkotika', JSON.stringify(savedZat)); }
            const selectNarkotika = document.getElementById('nama_narkotika_medis');
            if(selectNarkotika) {
                savedZat.forEach(val => { if (!Array.from(selectNarkotika.options).some(opt => opt.value === val)) { let opt = new Option(val, val); opt.className = 'dark:bg-slate-800'; selectNarkotika.add(opt); } });
                let currentNarkotika = "{{ old('nama_narkotika_medis', $asesmen->nama_narkotika_medis) }}";
                if (currentNarkotika && !Array.from(selectNarkotika.options).some(opt => opt.value === currentNarkotika)) { let opt = new Option(currentNarkotika, currentNarkotika); opt.className = 'dark:bg-slate-800'; selectNarkotika.add(opt); }
                if(currentNarkotika) selectNarkotika.value = currentNarkotika;
            }
        }

        function syncDurasiRawat() {
            let els = [];
            if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
            if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));

            let savedDurasiOpts = JSON.parse(localStorage.getItem('opsi_lama_perawatan')) || [];
            els.forEach(select => { savedDurasiOpts.forEach(val => { if (!Array.from(select.options).some(opt => opt.value === val)) { let opt = new Option(val, val); opt.className = 'dark:bg-slate-800'; select.add(opt); } }); });

            let sharedDurasi = localStorage.getItem('shared_durasi_rawat_{{ $asesmen->id }}');
            if (sharedDurasi) { els.forEach(select => { if (!Array.from(select.options).some(opt => opt.value === sharedDurasi)) { let opt = new Option(sharedDurasi, sharedDurasi); opt.className = 'dark:bg-slate-800'; select.add(opt); } select.value = sharedDurasi; }); }

            let isSyncingDurasi = false;
            els.forEach(select => {
                select.addEventListener('change', function() {
                    if (isSyncingDurasi) return; isSyncingDurasi = true; localStorage.setItem('shared_durasi_rawat_{{ $asesmen->id }}', this.value);
                    els.forEach(other => { if (other !== this) { if (!Array.from(other.options).some(opt => opt.value === this.value)) { let opt = new Option(this.value, this.value); opt.className = 'dark:bg-slate-800'; other.add(opt); } other.value = this.value; other.dispatchEvent(new Event('change')); } });
                    if (typeof saveFormDraft === 'function') saveFormDraft(); setTimeout(() => { isSyncingDurasi = false; }, 50);
                });
            });
        }

        // === FITUR DRAF OTOMATIS NARASI ===
        let klien = @json($klienData);
        let hasSavedMedis = "{{ $asesmen->narasi_medis ? 'yes' : 'no' }}";
        let hasSavedHukum = "{{ $asesmen->narasi_hukum ? 'yes' : 'no' }}";

        function getDraftMedis() {
            let alamatType = document.querySelector('input[name="alamat_medis"]:checked').value; let alamatTeks = "";
            if (alamatType === 'ktp') { alamatTeks = klien.alamat_ktp; } else if (alamatType === 'domisili') { alamatTeks = klien.alamat_domisili; } else { alamatTeks = `Sesuai KTP di ${klien.alamat_ktp} dan Domisili saat ini di ${klien.alamat_domisili}`; }
            return `Bahwa klien bernama ${klien.nama} Usia ${klien.usia} (Lahir di ${klien.tempat_lahir}, ${klien.tgl_lahir}). Pendidikan Terakhir ${klien.pendidikan}. Alamat Tempat Tinggal ${alamatTeks}.\n\n(Lanjutkan mengetik kronologi medis klien di sini...)`;
        }
        function getDraftHukum() {
            let alamatType = document.querySelector('input[name="alamat_hukum"]:checked').value; let alamatTeks = "";
            if (alamatType === 'ktp') { alamatTeks = klien.alamat_ktp; } else if (alamatType === 'domisili') { alamatTeks = klien.alamat_domisili; } else { alamatTeks = `Sesuai KTP di ${klien.alamat_ktp} dan Domisili saat ini di ${klien.alamat_domisili}`; }
            let pekerjaan = document.getElementById('input_pekerjaan_hukum').value || '-';
            return `Bahwa Tersangka bernama lengkap ${klien.nama}, NIK ${klien.nik}, Tempat/Tanggal Lahir ${klien.tempat_lahir}, ${klien.tgl_lahir}, Jenis Kelamin ${klien.jk}, Agama ${klien.agama}, Pekerjaan ${pekerjaan}, Pendidikan Terakhir ${klien.pendidikan}, Alamat Tempat Tinggal ${alamatTeks}.\n\nTersangka diamankan oleh petugas pada... (Lanjutkan mengetik kronologi penangkapan di sini...)`;
        }
        function applyDraftMedis() { let box = document.getElementById('narasi_medis'); if(box.value.trim() !== '' && !box.value.includes('Lanjutkan mengetik')) if(!confirm("Draf akan menimpa teks Anda. Lanjutkan?")) return; box.value = getDraftMedis(); saveFormDraft(); }
        function applyDraftHukum() { let box = document.getElementById('narasi_hukum'); if(box.value.trim() !== '' && !box.value.includes('Lanjutkan mengetik')) if(!confirm("Draf akan menimpa teks Anda. Lanjutkan?")) return; box.value = getDraftHukum(); saveFormDraft(); }

        // === PISAHAN DATA ZAT BUKTI & ZAT KESIMPULAN MENGGUNAKAN LOCALSTORAGE ===
        let defaultZat = [ "Amphetamine (Amfetamin)", "Methamphetamine (Metamfetamin)", "Tetrahydrocannabinol", "Benzodiazepines (Benzodiazepin)" ];
        let backendZat = @json($masterZat).map(z => z.nilai);
        let initialZat = [...new Set([...defaultZat, ...backendZat])];
        let zatBuktiList = JSON.parse(localStorage.getItem('custom_zat_bukti')) || [...initialZat];
        localStorage.setItem('custom_zat_bukti', JSON.stringify(zatBuktiList));
        let zatKesimpulanList = JSON.parse(localStorage.getItem('shared_master_zat_narkotika')) || [...initialZat];
        localStorage.setItem('shared_master_zat_narkotika', JSON.stringify(zatKesimpulanList));
        let masterDiagnosis = @json($masterDiagnosis);
        let defaultDiagnosis = {!! json_encode($asesmen->diagnosis_medis ?? '') !!};

        function renderOpsiData() {
            let contZatBukti = document.getElementById('container-zat-alat-bukti');
            if(contZatBukti) {
                contZatBukti.innerHTML = ''; let currentStrBukti = document.getElementById('alat_bukti_hasil_input').value;
                const tesUrineVal = document.getElementById('tes_urine_ba').value; const isUrineNegatif = (tesUrineVal === 'NEGATIF');
                zatBuktiList.forEach(val => {
                    let isCheckedBukti = currentStrBukti.includes(val) && !isUrineNegatif ? 'checked' : '';
                    let disabledProp = isUrineNegatif ? 'disabled' : ''; let opacityClass = isUrineNegatif ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20';
                    contZatBukti.innerHTML += `<label class="flex items-center gap-3 mb-2 text-[13px] font-bold text-slate-700 dark:text-slate-300 p-2 rounded-xl transition-colors border border-transparent ${opacityClass}"><input type="checkbox" value="${val}" ${isCheckedBukti} ${disabledProp} onchange="updateZatBukti()" class="rounded text-emerald-500 focus:ring-emerald-500 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4 shadow-sm">${val}</label>`;
                });
            }

            let contZatKesim = document.getElementById('container-zat-kesimpulan');
            if(contZatKesim) {
                contZatKesim.innerHTML = ''; let currentStrKesim = document.getElementById('kesimpulan_jenis_zat_input').value;
                zatKesimpulanList.forEach(val => {
                    let isCheckedKesim = currentStrKesim.includes(val) ? 'checked' : '';
                    contZatKesim.innerHTML += `<label class="flex items-center gap-3 cursor-pointer mb-2 text-[13px] font-bold text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 p-2 rounded-xl transition-colors border border-transparent"><input type="checkbox" value="${val}" ${isCheckedKesim} onchange="updateZatKesimpulan()" class="rounded text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600 w-4 h-4 shadow-sm">${val}</label>`;
                });
            }

            let selDiag = document.getElementById('select_diagnosis');
            if(selDiag) {
                let currentDiag = selDiag.value || defaultDiagnosis;
                selDiag.innerHTML = `<option value="" class="dark:bg-slate-800 text-slate-500">-- Pilih Diagnosis --</option>`;
                let isDiagInMaster = false;
                masterDiagnosis.forEach(d => { let isSelected = (d.nilai === currentDiag) ? 'selected' : ''; if(d.nilai === currentDiag) isDiagInMaster = true; selDiag.innerHTML += `<option value="${d.nilai}" ${isSelected} class="dark:bg-slate-800 text-slate-800 dark:text-slate-200">${d.nilai}</option>`; });
                if (currentDiag && !isDiagInMaster) { selDiag.innerHTML += `<option value="${currentDiag}" selected class="dark:bg-slate-800 text-slate-800 dark:text-slate-200">${currentDiag}</option>`; }
                selDiag.dispatchEvent(new Event('change'));
            }
        }

        function toggleZatBukti() {
            const urineVal = document.getElementById('tes_urine_ba').value;
            const btnTambahZatBukti = document.getElementById('btnTambahZatBukti');
            if (urineVal === 'NEGATIF') { if(btnTambahZatBukti) { btnTambahZatBukti.disabled = true; btnTambahZatBukti.classList.add('opacity-50', 'cursor-not-allowed'); } }
            else { if(btnTambahZatBukti) { btnTambahZatBukti.disabled = false; btnTambahZatBukti.classList.remove('opacity-50', 'cursor-not-allowed'); } }
            renderOpsiData(); updateZatBukti();
        }

        function updateZatBukti() {
            let urineVal = document.getElementById('tes_urine_ba').value;
            let checkboxes = document.querySelectorAll('#container-zat-alat-bukti input[type="checkbox"]:checked');
            let vals = Array.from(checkboxes).map(cb => cb.value);
            let teksHasil = "";
            if (urineVal === 'NEGATIF') { teksHasil = "NEGATIF"; } else if (urineVal === 'POSITIF') { teksHasil = vals.length > 0 ? "POSITIF " + vals.join(' DAN ') : "POSITIF"; }
            document.getElementById('alat_bukti_hasil_input').value = teksHasil; document.getElementById('preview_alat_bukti_hasil').innerText = teksHasil || '-'; saveFormDraft();
        }

        function updateZatKesimpulan() { let checkboxes = document.querySelectorAll('#container-zat-kesimpulan input[type="checkbox"]:checked'); let vals = Array.from(checkboxes).map(cb => cb.value); document.getElementById('kesimpulan_jenis_zat_input').value = vals.join(', '); saveFormDraft(); }

        function bukaModalOpsi(kategori) { document.getElementById('modalOpsiKategori').value = kategori; let title = 'Tambah Data Baru'; if(kategori === 'zat_bukti') title = 'Tambah Zat Positif Baru'; else if(kategori === 'zat_kesimpulan') title = 'Tambah Zat Pemakaian Baru'; else if(kategori === 'diagnosis') title = 'Tambah Diagnosis Baru'; document.getElementById('modalOpsiTitle').innerText = title; document.getElementById('modalOpsiNilai').value = ''; const m = document.getElementById('modalTambahOpsi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); document.getElementById('modalOpsiNilai').focus(); }, 10); }

        function simpanOpsiBaru() {
            let kategori = document.getElementById('modalOpsiKategori').value; let nilai = document.getElementById('modalOpsiNilai').value.trim();
            if(!nilai) return alert('Data tidak boleh kosong!');
            if (kategori === 'zat_bukti') { if(!zatBuktiList.includes(nilai)) { zatBuktiList.push(nilai); localStorage.setItem('custom_zat_bukti', JSON.stringify(zatBuktiList)); } renderOpsiData(); document.getElementById('modalTambahOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalTambahOpsi').classList.add('hidden'),300); }
            else if (kategori === 'zat_kesimpulan') { if(!zatKesimpulanList.includes(nilai)) { zatKesimpulanList.push(nilai); localStorage.setItem('shared_master_zat_narkotika', JSON.stringify(zatKesimpulanList)); } renderOpsiData(); document.getElementById('modalTambahOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalTambahOpsi').classList.add('hidden'),300); }
            else if (kategori === 'diagnosis') { let btn = document.getElementById('btnSimpanOpsi'); let data = { kategori: kategori, nilai: nilai, _token: '{{ csrf_token() }}' }; btn.innerText = 'Tunggu...'; fetch('{{ route("master-opsi.storeAjax") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(data) }).then(r => r.json()).then(res => { if(res.success) { masterDiagnosis.push(res.data); renderOpsiData(); document.getElementById('modalTambahOpsi').children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>document.getElementById('modalTambahOpsi').classList.add('hidden'),300); } }).finally(() => btn.innerText = 'Simpan'); }
        }

        function bukaKelolaOpsi(kategori) { let title = 'Kelola Opsi'; if(kategori === 'zat_bukti') title = 'Kelola Daftar Zat Positif'; else if(kategori === 'zat_kesimpulan') title = 'Kelola Daftar Zat Pemakaian'; else if(kategori === 'diagnosis') title = 'Kelola Daftar Diagnosis'; document.getElementById('kelolaOpsiTitle').innerText = title; renderKelolaOpsiList(kategori); const m = document.getElementById('modalKelolaOpsi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); }, 10); }

        function renderKelolaOpsiList(kategori) {
            let container = document.getElementById('kelolaOpsiList'); container.innerHTML = '';
            if (kategori === 'zat_bukti') { zatBuktiList.forEach(val => { container.innerHTML += `<div class="flex justify-between items-center p-4 bg-slate-50/50 dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors"><span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-3/4">${val}</span><button type="button" onclick="hapusOpsiLokalZat('${val}', '${kategori}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`; }); }
            else if (kategori === 'zat_kesimpulan') { zatKesimpulanList.forEach(val => { container.innerHTML += `<div class="flex justify-between items-center p-4 bg-slate-50/50 dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors"><span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-3/4">${val}</span><button type="button" onclick="hapusOpsiLokalZat('${val}', '${kategori}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`; }); }
            else if (kategori === 'diagnosis') { masterDiagnosis.forEach(item => { container.innerHTML += `<div class="flex justify-between items-center p-4 bg-slate-50/50 dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors"><span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-3/4">${item.nilai}</span><button type="button" onclick="hapusOpsiPermanen(${item.id}, '${kategori}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`; }); }
        }

        function hapusOpsiLokalZat(nilai, kategori) { if(!confirm('Hapus opsi ini dari daftar?')) return; if(kategori === 'zat_bukti') { zatBuktiList = zatBuktiList.filter(x => x !== nilai); localStorage.setItem('custom_zat_bukti', JSON.stringify(zatBuktiList)); } else if (kategori === 'zat_kesimpulan') { zatKesimpulanList = zatKesimpulanList.filter(x => x !== nilai); localStorage.setItem('shared_master_zat_narkotika', JSON.stringify(zatKesimpulanList)); } renderOpsiData(); renderKelolaOpsiList(kategori); }
        function hapusOpsiPermanen(id, kategori) { if(!confirm('Hapus permanen dari database?')) return; fetch(`/master-opsi/ajax/${id}`, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(res => { if(res.success) { if(kategori === 'diagnosis') masterDiagnosis = masterDiagnosis.filter(x => x.id !== id); renderOpsiData(); renderKelolaOpsiList(kategori); } }); }

        // === 3. FITUR TIM MEDIS & HUKUM ===
        let masterMedis = @json($masterMedis); let masterHukum = @json($masterHukum); let selectedMedis = @json($asesmen->anggotaTim->where('kategori', 'medis')->pluck('id')->toArray()); let selectedHukum = @json($asesmen->anggotaTim->where('kategori', 'hukum')->pluck('id')->toArray());

        function renderMedis() {
            const container = document.getElementById('container-medis'); const select = document.getElementById('select-medis'); const selectDokter = document.getElementById('select-dokter-pemeriksa');
            container.innerHTML = ''; select.innerHTML = '<option value="" class="dark:bg-slate-800">-- Pilih Dokter TAT --</option>';
            let currentDokterVal = selectDokter.value || "{!! old('alat_bukti_dokter', $asesmen->alat_bukti_dokter) !!}"; selectDokter.innerHTML = '<option value="" class="dark:bg-slate-800">-- Pilih Dokter Pemeriksa --</option>';

            selectedMedis.forEach(id => { let p = masterMedis.find(x => x.id == id); if(p) { container.innerHTML += `<div class="relative p-4 bg-slate-50 dark:bg-[#1E293B] border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm mb-3 group transition-colors"><button type="button" onclick="hapusMedis(${p.id})" class="absolute top-4 right-4 text-rose-500 dark:text-rose-400 font-black bg-white dark:bg-[#0F172A] border border-rose-100 dark:border-rose-900/50 rounded-xl px-3 py-1.5 text-[10px] uppercase tracking-widest hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors opacity-0 group-hover:opacity-100 shadow-sm">HAPUS</button><p class="font-extrabold text-sm text-slate-800 dark:text-slate-200 pr-16">${p.nama}</p><p class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 mt-2 uppercase tracking-widest">NIP/SIP: <span class="text-slate-600 dark:text-slate-400 normal-case">${p.nip_nrp_sip || '-'}</span></p><p class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest">Jabatan: <span class="text-slate-600 dark:text-slate-400 normal-case">${p.jabatan || '-'}</span></p></div>`; } });
            masterMedis.forEach(p => { if(!selectedMedis.includes(p.id)) { select.innerHTML += `<option value="${p.id}" class="dark:bg-slate-800">${p.nama}</option>`; } let isSelected = (p.nama === currentDokterVal) ? 'selected' : ''; selectDokter.innerHTML += `<option value="${p.nama}" ${isSelected} class="dark:bg-slate-800">${p.nama}</option>`; });
            if (currentDokterVal && !masterMedis.some(m => m.nama === currentDokterVal)) { selectDokter.innerHTML += `<option value="${currentDokterVal}" selected class="dark:bg-slate-800">${currentDokterVal}</option>`; }
            select.dispatchEvent(new Event('change')); selectDokter.dispatchEvent(new Event('change')); syncHiddenInputs();
        }

        function renderHukum() {
            const container = document.getElementById('container-hukum'); const select = document.getElementById('select-hukum'); container.innerHTML = ''; select.innerHTML = '<option value="" class="dark:bg-slate-800">-- Pilih Penyidik/Jaksa --</option>';
            selectedHukum.forEach(id => { let p = masterHukum.find(x => x.id == id); if(p) { container.innerHTML += `<div class="relative p-4 bg-slate-50 dark:bg-[#1E293B] border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm mb-3 group transition-colors"><button type="button" onclick="hapusHukum(${p.id})" class="absolute top-4 right-4 text-rose-500 dark:text-rose-400 font-black bg-white dark:bg-[#0F172A] border border-rose-100 dark:border-rose-900/50 rounded-xl px-3 py-1.5 text-[10px] uppercase tracking-widest hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors opacity-0 group-hover:opacity-100 shadow-sm">HAPUS</button><p class="font-extrabold text-sm text-slate-800 dark:text-slate-200 pr-16">${p.nama}</p>${p.pangkat ? `<p class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 mt-2 uppercase tracking-widest">Pangkat: <span class="text-slate-600 dark:text-slate-400 normal-case">${p.pangkat}</span></p>` : ''}<p class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 ${p.pangkat ? '' : 'mt-2'} uppercase tracking-widest">NIP/NRP: <span class="text-slate-600 dark:text-slate-400 normal-case">${p.nip_nrp_sip || '-'}</span></p><p class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest">Jabatan: <span class="text-slate-600 dark:text-slate-400 normal-case">${p.jabatan || '-'}</span></p></div>`; } });
            masterHukum.forEach(p => { if(!selectedHukum.includes(p.id)) select.innerHTML += `<option value="${p.id}" class="dark:bg-slate-800">${p.nama}</option>`; });
            select.dispatchEvent(new Event('change')); syncHiddenInputs();
        }

        function syncHiddenInputs() { const container = document.getElementById('hidden-inputs-container'); container.innerHTML = ''; selectedMedis.forEach(id => container.innerHTML += `<input type="hidden" name="tim_medis[]" value="${id}">`); selectedHukum.forEach(id => container.innerHTML += `<input type="hidden" name="tim_hukum[]" value="${id}">`); saveFormDraft(); }

        document.getElementById('select-medis').addEventListener('change', function(e) { const val = this.value; if(!val) return; if(selectedMedis.length >= 2) { alert('Maks. 2 Dokter untuk Tim Medis.'); this.value = ''; this.dispatchEvent(new Event('change')); return; } selectedMedis.push(parseInt(val)); renderMedis(); });
        document.getElementById('select-hukum').addEventListener('change', function(e) { const val = this.value; if(!val) return; if(selectedHukum.length >= 3) { alert('Maks. 3 Penyidik/Jaksa untuk Tim Hukum.'); this.value = ''; this.dispatchEvent(new Event('change')); return; } selectedHukum.push(parseInt(val)); renderHukum(); });

        function hapusMedis(id) { selectedMedis = selectedMedis.filter(x => x !== id); renderMedis(); }
        function hapusHukum(id) { selectedHukum = selectedHukum.filter(x => x !== id); renderHukum(); }

        function bukaModal(k) { document.getElementById('modalKategori').value = k; document.getElementById('modalTitle').innerText = k === 'medis' ? 'Tambah Dokter' : 'Tambah Tim Hukum'; document.getElementById('wrapPangkat').style.display = k === 'medis' ? 'none' : 'block'; const m = document.getElementById('modalTambahAnggota'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); document.getElementById('modalNama').focus(); }, 10); }
        function tutupModal() { document.getElementById('formTambahAnggota').reset(); const m = document.getElementById('modalTambahAnggota'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300); }
        function simpanAnggotaBaru() { let data = { nama: document.getElementById('modalNama').value, nip_nrp_sip: document.getElementById('modalNip').value, pangkat: document.getElementById('modalPangkat').value, jabatan: document.getElementById('modalJabatan').value, kategori: document.getElementById('modalKategori').value, _token: '{{ csrf_token() }}' }; let btn = document.getElementById('btnSimpanAnggota'); btn.innerText = 'Menyimpan...'; fetch('{{ route("master-anggota.storeAjax") }}', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) }).then(r => r.json()).then(res => { if(data.kategori === 'medis') { masterMedis.push(res.data); if(selectedMedis.length < 2) selectedMedis.push(res.data.id); renderMedis(); } else { masterHukum.push(res.data); if(selectedHukum.length < 3) selectedHukum.push(res.data.id); renderHukum(); } tutupModal(); }).finally(() => btn.innerText = 'Simpan Data'); }

        function bukaKelola(kategori) { document.getElementById('kelolaTitle').innerText = kategori === 'medis' ? 'Kelola Tim Medis' : 'Kelola Tim Hukum'; renderKelolaList(kategori); const m = document.getElementById('modalKelolaAnggota'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(() => { m.children[1].classList.remove('opacity-0', 'scale-95'); }, 10); }
        function tutupKelola() { const m = document.getElementById('modalKelolaAnggota'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300); }

        function renderKelolaList(kategori) { let container = document.getElementById('kelolaList'); container.innerHTML = ''; let data = kategori === 'medis' ? masterMedis : masterHukum; data.forEach(p => { container.innerHTML += `<div class="flex justify-between items-center p-4 bg-slate-50/50 dark:bg-[#0F172A]/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors"><div class="w-3/4"><p class="font-extrabold text-sm text-slate-800 dark:text-slate-200">${p.nama}</p><p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">${p.nip_nrp_sip || '-'} <span class="text-slate-300 dark:text-slate-600 mx-1">|</span> ${p.jabatan || '-'}</p></div><button type="button" onclick="hapusPermanen(${p.id}, '${kategori}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`; }); if(data.length === 0) container.innerHTML = '<div class="text-sm text-slate-500 dark:text-slate-400 text-center py-6 italic empty-msg">Belum ada data anggota.</div>'; }
        function hapusPermanen(id, kategori) { if(!confirm('Yakin ingin menghapus anggota ini secara permanen dari sistem?')) return; fetch(`/master-anggota/ajax/${id}`, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }}).then(r => r.json()).then(res => { if(kategori === 'medis') { masterMedis = masterMedis.filter(x => x.id !== id); selectedMedis = selectedMedis.filter(x => x !== id); renderMedis(); } else { masterHukum = masterHukum.filter(x => x.id !== id); selectedHukum = selectedHukum.filter(x => x !== id); renderHukum(); } renderKelolaList(kategori); }); }

        // === 4. AUTOSAVE DRAFT FORM ===
        const formBeritaAcara = document.getElementById('formBeritaAcara'); const autosaveIndicator = document.getElementById('autosaveIndicator');
        function saveFormDraft() { if(!formBeritaAcara) return; const formData = new FormData(formBeritaAcara); const data = {}; formData.forEach((value, key) => { if(key !== '_token' && key !== 'action') { data[key] = value; } }); localStorage.setItem('formBeritaAcaraDraft_{{ $asesmen->id }}', JSON.stringify(data)); if(autosaveIndicator) { autosaveIndicator.classList.remove('hidden'); setTimeout(() => { autosaveIndicator.classList.add('hidden'); }, 3000); } }
        function loadFormDraft() { if(!formBeritaAcara) return; const draft = localStorage.getItem('formBeritaAcaraDraft_{{ $asesmen->id }}'); if (draft) { const data = JSON.parse(draft); Object.keys(data).forEach(key => { const elements = formBeritaAcara.querySelectorAll(`[name="${key}"]`); if (elements.length > 0) { const el = elements[0]; if (el.type === 'radio' || el.type === 'checkbox') { const target = formBeritaAcara.querySelector(`[name="${key}"][value="${data[key]}"]`); if (target) target.checked = true; } else { el.value = data[key]; } } }); } }

        document.addEventListener("DOMContentLoaded", function() {
            syncTesUrine();
            initAllCustomSelects();
            loadSemuaOpsiLokal();
            renderOpsiData(); renderMedis(); renderHukum();
            loadFormDraft();
            toggleZatBukti();
            if(hasSavedMedis === 'no' && document.getElementById('narasi_medis').value.trim() === '') document.getElementById('narasi_medis').value = getDraftMedis();
            if(hasSavedHukum === 'no' && document.getElementById('narasi_hukum').value.trim() === '') document.getElementById('narasi_hukum').value = getDraftHukum();
            syncDurasiRawat();
            if(formBeritaAcara) { formBeritaAcara.addEventListener('input', saveFormDraft); formBeritaAcara.addEventListener('change', saveFormDraft); }
        });
    </script>
</x-app-layout>
