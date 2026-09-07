<x-app-layout>
    <!-- CSS Custom untuk Animasi Autosave -->
    <style>
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .autosave-active {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <div class="py-8 sm:py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- ========================================== -->
            <!-- 1. HEADER (SEAMLESS HERO) -->
            <!-- ========================================== -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <!-- Tombol Kembali ke Detail -->
                    <a href="{{ route('asesmen.show', $asesmen->id) }}" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-emerald-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-wider mb-1">
                            Generator Dokumen (Word)
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight flex items-center gap-3">
                            Berita Acara: {{ $asesmen->nama_lengkap }}
                            <!-- INDIKATOR AUTOSAVE -->
                            <span id="autosaveIndicator" class="hidden text-[10px] font-bold text-emerald-600 bg-emerald-100 border border-emerald-200 px-2 py-1 rounded-md uppercase tracking-wider autosave-active">
                                Draft Tersimpan
                            </span>
                        </h1>
                    </div>
                </div>
                <div class="text-sm text-slate-500 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm hidden md:block">
                    Isi dan lengkapi data untuk mencetak <span class="font-bold text-slate-700">Berita Acara TAT</span>.
                </div>
            </div>

            <form action="{{ route('asesmen.berita-acara.generate', $asesmen->id) }}" method="POST" class="space-y-8" id="formBeritaAcara">
                @csrf
                <div id="hidden-inputs-container"></div>

                <!-- ========================================== -->
                <!-- SEKSI 1: HEADER SURAT & PIMPINAN RAPAT -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        1. Header Surat & Pimpinan Rapat
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nomor Berita Acara</label>
                            <input type="text" name="no_ba" value="{{ old('no_ba', $asesmen->no_ba) }}" placeholder="Cth: BA/277/VII/TAT/..." class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal Rapat (BA)</label>
                            <input type="text" name="tgl_ba" value="{{ old('tgl_ba', $asesmen->tgl_ba ? \Carbon\Carbon::parse($asesmen->tgl_ba)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nama Ketua TAT</label>
                            <input type="text" name="ketua_tat_nama" value="{{ old('ketua_tat_nama', $asesmen->ketua_tat_nama ?? 'LETKOL LAUT (PM) Hendratmo Budi Wibowo S Pd.') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">NRP Ketua TAT</label>
                            <input type="text" name="ketua_tat_nrp" value="{{ old('ketua_tat_nrp', $asesmen->ketua_tat_nrp ?? '16301/P') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex flex-col sm:flex-row gap-5">
                                <div class="flex-1">
                                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nomor SK Tim Asesmen</label>
                                    <input type="text" name="no_kep_tim" value="{{ old('no_kep_tim', $asesmen->no_kep_tim ?? 'KEP/10/IV/KA/KP/2026/BNN Kab. Malang') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                </div>
                                <div class="sm:w-1/3">
                                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal SK Tim</label>
                                    <input type="text" name="tgl_kep_tim" value="{{ old('tgl_kep_tim', $asesmen->tgl_kep_tim ? \Carbon\Carbon::parse($asesmen->tgl_kep_tim)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SEKSI 2: PEMILIHAN ANGGOTA TIM TAT -->
                <!-- ========================================== -->
                <div class="bg-indigo-50/50 p-6 md:p-8 rounded-2xl border border-indigo-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500"></div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-indigo-200/60 pb-3 mt-1 gap-2">
                        <h3 class="text-sm font-extrabold text-indigo-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            2. Susunan Anggota Tim TAT
                        </h3>
                        <p class="text-xs font-semibold text-indigo-600 bg-white px-3 py-1 rounded-lg border border-indigo-100 shadow-sm">Pilih & Kelola dari Master Data</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- KOLOM TIM MEDIS -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-indigo-100">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-2">Tim Medis <span class="text-slate-400 font-medium normal-case">(Maks. 2)</span></label>
                            <div class="flex gap-2 mb-4">
                                <select id="select-medis" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-slate-50 focus:bg-white transition-all">
                                    <option value="">-- Pilih Dokter --</option>
                                </select>
                                <button type="button" onclick="bukaModal('medis')" class="px-3 py-2 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl hover:bg-indigo-200 transition whitespace-nowrap shadow-sm">+ Baru</button>
                                <button type="button" onclick="bukaKelola('medis')" class="px-3 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-200 transition whitespace-nowrap shadow-sm">⚙️ Kelola</button>
                            </div>
                            <div id="container-medis" class="space-y-2"></div>
                        </div>

                        <!-- KOLOM TIM HUKUM -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-indigo-100">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-2">Tim Hukum <span class="text-slate-400 font-medium normal-case">(Maks. 3)</span></label>
                            <div class="flex gap-2 mb-4">
                                <select id="select-hukum" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-slate-50 focus:bg-white transition-all">
                                    <option value="">-- Pilih Penyidik/Jaksa --</option>
                                </select>
                                <button type="button" onclick="bukaModal('hukum')" class="px-3 py-2 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl hover:bg-indigo-200 transition whitespace-nowrap shadow-sm">+ Baru</button>
                                <button type="button" onclick="bukaKelola('hukum')" class="px-3 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-200 transition whitespace-nowrap shadow-sm">⚙️ Kelola</button>
                            </div>
                            <div id="container-hukum" class="space-y-2"></div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SEKSI 3: HASIL PEMERIKSAAN (NARASI) -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        3. Narasi Hasil Pemeriksaan
                    </h3>

                    <div class="space-y-8">
                        <!-- KOTAK NARASI MEDIS -->
                        <div class="p-5 md:p-6 bg-blue-50/50 border border-blue-100 rounded-2xl shadow-sm">
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-4">
                                <label class="block text-[13px] font-extrabold text-blue-900 uppercase tracking-wider">Narasi Tim Medis</label>
                                <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-blue-100 shadow-sm flex-wrap">
                                    <span class="text-slate-500 font-bold text-[11px] uppercase tracking-wider">Gunakan Alamat:</span>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-blue-800 font-bold text-xs">
                                        <input type="radio" name="alamat_medis" value="ktp" checked class="text-blue-600 focus:ring-blue-500"> KTP
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-blue-800 font-bold text-xs">
                                        <input type="radio" name="alamat_medis" value="domisili" class="text-blue-600 focus:ring-blue-500"> Domisili
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-blue-800 font-bold text-xs">
                                        <input type="radio" name="alamat_medis" value="keduanya" class="text-blue-600 focus:ring-blue-500"> Keduanya
                                    </label>
                                    <div class="w-px h-6 bg-blue-100 hidden sm:block mx-1"></div>
                                    <button type="button" onclick="applyDraftMedis()" class="px-4 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-[11px] font-bold uppercase tracking-wider transition shadow-sm">Terapkan Draf Pembuka</button>
                                </div>
                            </div>
                            <textarea id="narasi_medis" name="narasi_medis" rows="8" class="block w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all leading-relaxed">{{ old('narasi_medis', $asesmen->narasi_medis) }}</textarea>
                        </div>

                        <!-- KOTAK NARASI HUKUM -->
                        <div class="p-5 md:p-6 bg-slate-50 border border-slate-200 rounded-2xl shadow-sm">
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-4">
                                <label class="block text-[13px] font-extrabold text-slate-800 uppercase tracking-wider">Narasi Tim Hukum</label>
                                <div class="flex flex-wrap items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 border-r border-slate-200 pr-3">
                                        <span class="text-slate-500 font-bold text-[11px] uppercase tracking-wider">Pekerjaan:</span>
                                        <input type="text" id="input_pekerjaan_hukum" value="{{ $asesmen->pekerjaan->nama_pekerjaan ?? '' }}" class="h-8 text-xs font-bold rounded-lg border-slate-300 w-32 focus:ring-2 focus:ring-slate-200">
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-500 font-bold text-[11px] uppercase tracking-wider">Alamat:</span>
                                        <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 font-bold text-xs">
                                            <input type="radio" name="alamat_hukum" value="ktp" checked class="text-slate-600 focus:ring-slate-500"> KTP
                                        </label>
                                        <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 font-bold text-xs">
                                            <input type="radio" name="alamat_hukum" value="domisili" class="text-slate-600 focus:ring-slate-500"> Domisili
                                        </label>
                                        <label class="flex items-center gap-1.5 cursor-pointer text-slate-800 font-bold text-xs">
                                            <input type="radio" name="alamat_hukum" value="keduanya" class="text-slate-600 focus:ring-slate-500"> Keduanya
                                        </label>
                                    </div>
                                    <div class="w-px h-6 bg-slate-200 hidden sm:block mx-1"></div>
                                    <button type="button" onclick="applyDraftHukum()" class="px-4 py-1.5 bg-slate-700 text-white hover:bg-slate-800 rounded-lg text-[11px] font-bold uppercase tracking-wider transition shadow-sm">Terapkan Draf Pembuka</button>
                                </div>
                            </div>
                            <textarea id="narasi_hukum" name="narasi_hukum" rows="8" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-200 sm:text-sm transition-all leading-relaxed">{{ old('narasi_hukum', $asesmen->narasi_hukum) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SEKSI 4: ALAT BUKTI & KESIMPULAN -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        4. Alat Bukti & Kesimpulan
                    </h3>

                    <!-- Sub A: Alat Bukti -->
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 mb-8">
                        <h4 class="font-extrabold text-[12px] text-emerald-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">A</span> Data Alat Bukti (Surat Keterangan Narkoba)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">No. SK Narkoba</label>
                                <input type="text" name="alat_bukti_no_sk" value="{{ old('alat_bukti_no_sk', $asesmen->alat_bukti_no_sk ?? 'B/ND/336VII/KES.I./2026') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal SK Narkoba</label>
                                <input type="text" name="alat_bukti_tgl_sk" value="{{ old('alat_bukti_tgl_sk', $asesmen->alat_bukti_tgl_sk ? \Carbon\Carbon::parse($asesmen->alat_bukti_tgl_sk)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nama Dokter Pemeriksa</label>
                                <select id="select-dokter-pemeriksa" name="alat_bukti_dokter" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm bg-white cursor-pointer transition-all">
                                    <option value="{{ $asesmen->alat_bukti_dokter ?? '' }}">{{ $asesmen->alat_bukti_dokter ?? '-- Pilih dari Tim Medis --' }}</option>
                                </select>
                                <p class="text-[10px] text-slate-500 mt-1">Otomatis ditarik dari Susunan Tim Medis di atas.</p>
                            </div>
                            <div class="row-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Hasil Zat Positif</label>
                                <div class="p-4 border border-slate-200 rounded-xl bg-white shadow-sm">
                                    <div id="container-zat" class="space-y-1.5 mb-4 max-h-40 overflow-y-auto pr-2">
                                        <!-- Render Checkbox Zat Positif JS -->
                                    </div>
                                    <div class="flex gap-2 pt-3 border-t border-slate-100">
                                        <button type="button" onclick="bukaModalOpsi('zat')" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold uppercase tracking-wider rounded-lg hover:bg-emerald-100 transition shadow-sm">+ Tambah Zat</button>
                                        <button type="button" onclick="bukaKelolaOpsi('zat')" class="px-3 py-1.5 bg-slate-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider rounded-lg hover:bg-slate-200 transition shadow-sm">Kelola</button>
                                    </div>
                                </div>
                                <input type="hidden" name="alat_bukti_hasil" id="alat_bukti_hasil_input" value="{{ old('alat_bukti_hasil', $asesmen->alat_bukti_hasil) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Sub B: Kesimpulan TAT -->
                    <div class="bg-indigo-50/40 p-5 rounded-xl border border-indigo-100/50 mb-8">
                        <h4 class="font-extrabold text-[12px] text-indigo-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="bg-indigo-200 text-indigo-800 px-2 py-0.5 rounded">B</span> Kesimpulan Diagnostik TAT
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Jenis Zat yang Dipakai</label>
                                <textarea id="kesimpulan_jenis_zat_input" name="kesimpulan_jenis_zat" rows="2" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-slate-100 cursor-not-allowed text-slate-600 font-bold" readonly>{{ old('kesimpulan_jenis_zat', $asesmen->kesimpulan_jenis_zat) }}</textarea>
                                <p class="text-[10px] text-slate-500 mt-1">Otomatis terisi dari centangan Hasil Zat Positif di atas.</p>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Status Klien</label>
                                <select name="status_klien" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-white transition-all">
                                    <option value="penyalahguna" {{ old('status_klien', $asesmen->status_klien) == 'penyalahguna' ? 'selected' : '' }}>Penyalahguna</option>
                                    <option value="korban penyalahguna" {{ old('status_klien', $asesmen->status_klien) == 'korban penyalahguna' ? 'selected' : '' }}>Korban Penyalahguna</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pola Pemakaian</label>
                                <select name="kesimpulan_pola_pakai" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-white transition-all">
                                    <option value="">-- Pilih Pola --</option>
                                    <option value="Coba pakai" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Coba pakai' ? 'selected' : '' }}>Coba pakai</option>
                                    <option value="Situasional" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Situasional' ? 'selected' : '' }}>Situasional</option>
                                    <option value="Rekreasional" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Rekreasional' ? 'selected' : '' }}>Rekreasional</option>
                                    <option value="Teratur pakai" {{ old('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai) == 'Teratur pakai' ? 'selected' : '' }}>Teratur pakai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kategori Ketergantungan</label>
                                <select name="kesimpulan_kategori" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-white transition-all">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Ringan" {{ old('kesimpulan_kategori', $asesmen->kesimpulan_kategori) == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                    <option value="Sedang" {{ old('kesimpulan_kategori', $asesmen->kesimpulan_kategori) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Berat" {{ old('kesimpulan_kategori', $asesmen->kesimpulan_kategori) == 'Berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 pt-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Diagnosis Medis Final</label>
                                <div class="flex gap-2">
                                    <select id="select_diagnosis" name="diagnosis_medis" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm bg-white font-bold text-indigo-700 transition-all">
                                        <!-- Dirender oleh JS agar tersinkronisasi master data -->
                                    </select>
                                    <button type="button" onclick="bukaModalOpsi('diagnosis')" class="px-4 py-2 bg-indigo-100 text-indigo-700 text-[11px] font-bold uppercase tracking-wider rounded-xl hover:bg-indigo-200 whitespace-nowrap transition shadow-sm">+ Tambah</button>
                                    <button type="button" onclick="bukaKelolaOpsi('diagnosis')" class="px-4 py-2 bg-slate-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider rounded-xl hover:bg-slate-200 whitespace-nowrap transition shadow-sm">Kelola</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sub C: Rekomendasi (TERKUNCI - BERSUMBER DARI FORM REGISTRASI) -->
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-5 rounded-xl border border-emerald-100 mb-8">
                        <h4 class="font-extrabold text-[12px] text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="bg-emerald-200 text-emerald-800 px-2 py-0.5 rounded">C</span> Keputusan Rekomendasi & Penempatan
                        </h4>

                        @php
                            // Logika Tarik Data & Pembersihan Prefix AGRESIF
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tempat Rehabilitasi (Sesuai SK)</label>
                                <div class="relative">
                                    <input type="text" value="{{ $tempatBersih ?: 'Belum diset (Silakan edit di Data Klien)' }}" readonly class="block w-full rounded-xl border-emerald-200 shadow-sm sm:text-sm bg-emerald-50/50 cursor-not-allowed text-emerald-800 font-extrabold pr-10 pointer-events-none">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-emerald-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                </div>
                                <!-- Input tersembunyi ini yang dikirim ke controller, MENGGUNAKAN $tempatBersih murni -->
                                <input type="hidden" name="rekomendasi_tempat_rehab" value="{{ $tempatBersih }}">
                                <p class="text-[10px] text-slate-500 mt-1.5 italic">Terkunci. Data ini otomatis ditarik dari form pendaftaran awal (Edit Klien).</p>
                            </div>

                            <!-- DURASI REHABILITASI (SINKRON DENGAN REKOMENDASI VIA LOCAL STORAGE) -->
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Lama (Durasi) Rawat <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <select name="rekomendasi_durasi" id="rekomendasi_durasi" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm bg-white text-slate-700 font-medium transition-all">
                                        <option value="">-- Pilih Waktu Perawatan --</option>
                                        @php
                                            $valDurasi = old('rekomendasi_durasi', $asesmen->lama_perawatan ?? $asesmen->rekomendasi_durasi ?? '');
                                            $defaultsDurasi = ['1 bulan', '2 bulan', '3 bulan', '1-3 bulan'];
                                        @endphp
                                        @if($valDurasi !== '')
                                            <option value="{{ $valDurasi }}" selected>{{ $valDurasi }}</option>
                                        @endif
                                        @foreach($defaultsDurasi as $d)
                                            @if($d !== $valDurasi)
                                                <option value="{{ $d }}">{{ $d }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <!-- Key storage 'lama_perawatan' menyatukan opsi form ini dengan form Rekomendasi -->
                                    <button type="button" onclick="openTambahModal('rekomendasi_durasi', 'Lama (Durasi) Rawat', 'lama_perawatan')" class="px-3 py-2.5 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-bold hover:bg-emerald-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                    <button type="button" onclick="openKelolaModal('rekomendasi_durasi', 'Lama (Durasi) Rawat', 'lama_perawatan')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                                </div>
                            </div>

                            <!-- KETERANGAN HUKUM REKOMENDASI (DYNAMIC & SYNCED) -->
                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Keterangan Hukum Rekomendasi</label>
                                <div class="flex gap-2 items-center">
                                    <select name="rekomendasi_keterangan" id="rekomendasi_keterangan" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm bg-white h-auto py-3 leading-snug transition-all" onchange="if(typeof saveFormDraft === 'function') saveFormDraft();">
                                        <option value="">-- Pilih Keterangan Hukum --</option>
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
                                            <option value="{{ $savedKet }}" selected>{{ $savedKet }}</option>
                                        @endif
                                        @foreach($ketList as $ket)
                                            <option value="{{ $ket }}" {{ $savedKet == $ket ? 'selected' : '' }}>{{ $ket }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openTambahModal('rekomendasi_keterangan', 'Keterangan Hukum Rekomendasi')" class="px-3 py-2.5 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-bold hover:bg-emerald-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                    <button type="button" onclick="openKelolaModal('rekomendasi_keterangan', 'Keterangan Hukum Rekomendasi')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TOMBOL SUBMIT -->
                <!-- ========================================== -->
                <div class="flex justify-end gap-3 pt-4 bg-slate-50 p-4 border border-slate-200 rounded-lg">
                    <a href="{{ route('asesmen.show', $asesmen->id) }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-slate-300 rounded-lg font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">Batal</a>
                    <button type="submit" name="action" value="save_only" onclick="syncHiddenInputs()" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none transition shadow-sm">Simpan Perubahan</button>
                    <button type="submit" name="action" value="generate" onclick="syncHiddenInputs()" style="background-color: #10b981;" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg font-semibold text-white hover:opacity-90 focus:outline-none transition shadow-sm">Simpan & Unduh Berita Acara</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODALS (ANGGOTA TAT, OPSI, & MODAL DINAMIS)-->
    <!-- ========================================== -->

    <!-- Modal Tambah Opsi Dinamis -->
    <div id="modalTambahDurasi" class="fixed inset-0 z-[80] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeTambahModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
                    <div class="bg-white px-5 pt-6 pb-5 sm:p-6 sm:pb-5">
                        <h3 class="text-lg leading-6 font-extrabold text-slate-900 mb-4" id="modal-title">Tambah <span id="tambah_label" class="text-indigo-600"></span> Baru</h3>
                        <input type="hidden" id="tambah_target_id">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nama/Nilai Baru</label>
                        <input type="text" id="tambah_input" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition text-sm py-2.5" placeholder="Ketik di sini...">
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 gap-2">
                        <button type="button" onclick="simpanOpsiBaruKhusus()" class="w-full inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 sm:w-auto transition-colors">Simpan</button>
                        <button type="button" onclick="closeTambahModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Opsi Dinamis -->
    <div id="modalKelolaDurasi" class="fixed inset-0 z-[80] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeKelolaModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-lg leading-6 font-extrabold text-slate-900">Kelola Daftar <span id="kelola_label" class="text-indigo-600"></span></h3>
                            <button onclick="closeKelolaModal()" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        <input type="hidden" id="kelola_target_id">
                        <ul id="kelola_list" class="space-y-2 max-h-[40vh] overflow-y-auto pr-2"></ul>
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                        <button type="button" onclick="closeKelolaModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent bg-slate-200 px-6 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-300 sm:w-auto transition-colors">Tutup Kelola</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambahAnggota" class="fixed inset-0 z-[60] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 sm:p-8">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-5">
                <h3 class="text-lg font-extrabold text-slate-900" id="modalTitle">Tambah Anggota Baru</h3>
                <button type="button" onclick="tutupModal()" class="text-slate-400 hover:text-rose-500 transition-colors p-1">&times;</button>
            </div>
            <form id="formTambahAnggota" class="space-y-4">
                <input type="hidden" id="modalKategori" name="kategori">
                <div><label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap & Gelar</label><input type="text" id="modalNama" required class="block w-full rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-100 sm:text-sm"></div>
                <div><label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">NIP / NRP / SIP</label><input type="text" id="modalNip" class="block w-full rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-100 sm:text-sm"></div>
                <div id="wrapPangkat"><label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pangkat (Opsional)</label><input type="text" id="modalPangkat" class="block w-full rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-100 sm:text-sm"></div>
                <div><label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Jabatan</label><input type="text" id="modalJabatan" class="block w-full rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-100 sm:text-sm"></div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-2">
                    <button type="button" onclick="tutupModal()" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition">Batal</button>
                    <button type="button" onclick="simpanAnggotaBaru()" id="btnSimpanAnggota" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-md transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalKelolaAnggota" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 sm:p-8 flex flex-col max-h-[85vh]">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-lg font-extrabold text-slate-900" id="kelolaTitle">Kelola Anggota</h3>
                <button type="button" onclick="tutupKelola()" class="text-slate-400 hover:text-rose-500 p-1 transition-colors">&times;</button>
            </div>
            <div class="overflow-y-auto flex-1 pr-2 space-y-2" id="kelolaList"></div>
            <div class="mt-5 pt-4 border-t border-slate-100 text-right">
                <button type="button" onclick="tutupKelola()" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition">Tutup Kelola</button>
            </div>
        </div>
    </div>

    <div id="modalTambahOpsi" class="fixed inset-0 z-[70] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 sm:p-8">
            <h3 class="text-lg font-extrabold text-slate-900 mb-5 border-b border-slate-100 pb-3" id="modalOpsiTitle">Tambah Data Baru</h3>
            <input type="hidden" id="modalOpsiKategori">
            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nama/Nilai Baru</label>
            <input type="text" id="modalOpsiNilai" placeholder="Ketik di sini..." class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 mb-6">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTambahOpsi').classList.add('hidden')" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition">Batal</button>
                <button type="button" onclick="simpanOpsiBaru()" id="btnSimpanOpsi" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-md transition">Simpan</button>
            </div>
        </div>
    </div>

    <div id="modalKelolaOpsi" class="fixed inset-0 z-[65] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 sm:p-8 flex flex-col max-h-[80vh]">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-lg font-extrabold text-slate-900" id="kelolaOpsiTitle">Kelola Opsi</h3>
                <button type="button" onclick="document.getElementById('modalKelolaOpsi').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 p-1 transition-colors">&times;</button>
            </div>
            <div class="overflow-y-auto flex-1 space-y-2" id="kelolaOpsiList"></div>
            <div class="mt-5 pt-4 border-t border-slate-100 text-right">
                <button type="button" onclick="document.getElementById('modalKelolaOpsi').classList.add('hidden')" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition">Tutup Kelola</button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC (Fungsionalitas 100% Utuh) -->
    <!-- ========================================== -->
    <script>
        // === FUNGSI SINKRONISASI DROPDOWN & AUTOSAVE LENGKAP ===
        let currentStorageKey = null;

        function openTambahModal(selectId, labelName, storageKey = null) {
            document.getElementById('tambah_target_id').value = selectId;
            currentStorageKey = storageKey || selectId;
            document.getElementById('tambah_label').innerText = labelName;
            document.getElementById('tambah_input').value = '';
            document.getElementById('modalTambahDurasi').classList.remove('hidden');
            setTimeout(() => document.getElementById('tambah_input').focus(), 100);
        }

        function closeTambahModal() {
            document.getElementById('modalTambahDurasi').classList.add('hidden');
        }

        function simpanOpsiBaruKhusus() {
            const selectId = document.getElementById('tambah_target_id').value;
            const newValue = document.getElementById('tambah_input').value.trim();

            if(newValue !== '') {
                // Tambahkan opsi ke semua elemen durasi (jika yang ditambah adalah durasi)
                let els = [];
                if (selectId === 'lama_perawatan' || selectId === 'rekomendasi_durasi') {
                    if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
                    if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));
                } else {
                    els.push(document.getElementById(selectId));
                }

                els.forEach(el => {
                    if (!Array.from(el.options).some(opt => opt.value === newValue)) {
                        el.add(new Option(newValue, newValue));
                    }
                    el.value = newValue;
                });

                // Simpan opsi ke local storage
                let keyToSave = currentStorageKey || selectId;
                let savedOpts = JSON.parse(localStorage.getItem('opsi_' + keyToSave)) || [];
                if (!savedOpts.includes(newValue)) {
                    savedOpts.push(newValue);
                    localStorage.setItem('opsi_' + keyToSave, JSON.stringify(savedOpts));
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
                    li.className = "flex justify-between items-center p-3.5 bg-slate-50 border border-slate-100 rounded-xl hover:bg-white hover:shadow-sm transition-all";
                    li.innerHTML = `
                        <span class="text-sm font-medium text-slate-700 leading-snug w-3/4">${opt.text}</span>
                        <button type="button" onclick="hapusOpsiLokal('${selectId}', '${opt.value}', this)" class="text-xs font-bold text-rose-500 hover:text-white bg-rose-50 border border-rose-100 hover:bg-rose-500 px-4 py-1.5 rounded-lg transition shadow-sm">Hapus</button>
                    `;
                    listContainer.appendChild(li);
                }
            }

            if(!hasItems) {
                listContainer.innerHTML = '<li class="text-center text-slate-400 py-4 text-sm italic">Belum ada daftar riwayat tersimpan.</li>';
            }
            document.getElementById('modalKelolaDurasi').classList.remove('hidden');
        }

        function closeKelolaModal() {
            document.getElementById('modalKelolaDurasi').classList.add('hidden');
        }

        function hapusOpsiLokal(selectId, valueToRemove, btnEl) {
            // Hapus dari semua form durasi yang ada di halaman ini
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

            // Hapus dari local storage
            let keyToSave = currentStorageKey || selectId;
            let savedOpts = JSON.parse(localStorage.getItem('opsi_' + keyToSave)) || [];
            savedOpts = savedOpts.filter(item => item !== valueToRemove);
            localStorage.setItem('opsi_' + keyToSave, JSON.stringify(savedOpts));

            const li = btnEl.closest('li');
            li.style.opacity = '0';
            setTimeout(() => {
                li.remove();
                if(document.getElementById('kelola_list').children.length === 0) {
                    document.getElementById('kelola_list').innerHTML = '<li class="text-center text-slate-400 py-4 text-sm italic">Semua opsi telah dihapus dari daftar ini.</li>';
                }
            }, 200);

            if (typeof saveFormDraft === 'function') saveFormDraft();
        }

        function loadSemuaOpsiLokal() {
            // DITAMBAHKAN rekomendasi_keterangan UNTUK SINKRONISASI
            const selectIds = ['kepada_yth', 'no_keputusan', 'tentang_permohonan', 'nama_narkotika_medis', 'keterangan_diagnosis', 'rekomendasi_keterangan'];
            selectIds.forEach(id => {
                let savedOpts = JSON.parse(localStorage.getItem('opsi_' + id)) || [];
                const selectEl = document.getElementById(id);
                if(selectEl) {
                    savedOpts.forEach(val => {
                        if (!Array.from(selectEl.options).some(opt => opt.value === val)) selectEl.add(new Option(val, val));
                    });
                }
            });
        }

        function syncDurasiRawat() {
            let els = [];
            if(document.getElementById('lama_perawatan')) els.push(document.getElementById('lama_perawatan'));
            if(document.getElementById('rekomendasi_durasi')) els.push(document.getElementById('rekomendasi_durasi'));

            // Load opsi kustom ke semua dropdown durasi
            let savedDurasiOpts = JSON.parse(localStorage.getItem('opsi_lama_perawatan')) || [];
            els.forEach(select => {
                savedDurasiOpts.forEach(val => {
                    if (!Array.from(select.options).some(opt => opt.value === val)) {
                        select.add(new Option(val, val));
                    }
                });
            });

            // Ganti isian dengan nilai yang terakhir kali dipilih (SINKRON ANTAR HALAMAN)
            let sharedDurasi = localStorage.getItem('shared_durasi_rawat_{{ $asesmen->id }}');
            if (sharedDurasi) {
                els.forEach(select => {
                    if (!Array.from(select.options).some(opt => opt.value === sharedDurasi)) {
                        select.add(new Option(sharedDurasi, sharedDurasi));
                    }
                    select.value = sharedDurasi;
                });
            }

            // Event Listener agar setiap perubahan tersimpan ke shared value
            els.forEach(select => {
                select.addEventListener('change', function() {
                    localStorage.setItem('shared_durasi_rawat_{{ $asesmen->id }}', this.value);
                    els.forEach(other => {
                        if (other !== this) {
                            if (!Array.from(other.options).some(opt => opt.value === this.value)) {
                                other.add(new Option(this.value, this.value));
                            }
                            other.value = this.value;
                        }
                    });
                    if (typeof saveFormDraft === 'function') saveFormDraft();
                });
            });
        }


        // === 1. FITUR DRAF OTOMATIS NARASI ===
        let klien = @json($klienData);
        let hasSavedMedis = "{{ $asesmen->narasi_medis ? 'yes' : 'no' }}";
        let hasSavedHukum = "{{ $asesmen->narasi_hukum ? 'yes' : 'no' }}";

        function getDraftMedis() {
            let alamatType = document.querySelector('input[name="alamat_medis"]:checked').value;
            let alamatTeks = "";

            if (alamatType === 'ktp') {
                alamatTeks = klien.alamat_ktp;
            } else if (alamatType === 'domisili') {
                alamatTeks = klien.alamat_domisili;
            } else {
                alamatTeks = `Sesuai KTP di ${klien.alamat_ktp} dan Domisili saat ini di ${klien.alamat_domisili}`;
            }

            return `Bahwa klien bernama ${klien.nama} Usia ${klien.usia} (Lahir di ${klien.tempat_lahir}, ${klien.tgl_lahir}). Pendidikan Terakhir ${klien.pendidikan}. Alamat Tempat Tinggal ${alamatTeks}.\n\n(Lanjutkan mengetik kronologi medis klien di sini...)`;
        }
        function getDraftHukum() {
            let alamatType = document.querySelector('input[name="alamat_hukum"]:checked').value;
            let alamatTeks = "";

            if (alamatType === 'ktp') {
                alamatTeks = klien.alamat_ktp;
            } else if (alamatType === 'domisili') {
                alamatTeks = klien.alamat_domisili;
            } else {
                alamatTeks = `Sesuai KTP di ${klien.alamat_ktp} dan Domisili saat ini di ${klien.alamat_domisili}`;
            }

            let pekerjaan = document.getElementById('input_pekerjaan_hukum').value || '-';

            return `Bahwa Tersangka bernama lengkap ${klien.nama}, NIK ${klien.nik}, Tempat/Tanggal Lahir ${klien.tempat_lahir}, ${klien.tgl_lahir}, Jenis Kelamin ${klien.jk}, Agama ${klien.agama}, Pekerjaan ${pekerjaan}, Pendidikan Terakhir ${klien.pendidikan}, Alamat Tempat Tinggal ${alamatTeks}.\n\nTersangka diamankan oleh petugas pada... (Lanjutkan mengetik kronologi penangkapan di sini...)`;
        }
        function applyDraftMedis() {
            let box = document.getElementById('narasi_medis');
            if(box.value.trim() !== '' && !box.value.includes('Lanjutkan mengetik')) if(!confirm("Draf akan menimpa teks Anda. Lanjutkan?")) return;
            box.value = getDraftMedis();
            saveFormDraft();
        }
        function applyDraftHukum() {
            let box = document.getElementById('narasi_hukum');
            if(box.value.trim() !== '' && !box.value.includes('Lanjutkan mengetik')) if(!confirm("Draf akan menimpa teks Anda. Lanjutkan?")) return;
            box.value = getDraftHukum();
            saveFormDraft();
        }

        // === 2. FITUR ZAT & DIAGNOSIS DINAMIS ===
        let masterZat = @json($masterZat);
        let masterDiagnosis = @json($masterDiagnosis);
        let prevZatString = "{{ old('alat_bukti_hasil', $asesmen->alat_bukti_hasil) }}";

        let defaultDiagnosis = {!! json_encode($asesmen->diagnosis_medis ?? '') !!};

        function renderOpsiData() {
            let contZat = document.getElementById('container-zat');
            contZat.innerHTML = '';
            let currentStr = document.getElementById('alat_bukti_hasil_input').value;
            masterZat.forEach(z => {
                let isChecked = currentStr.includes(z.nilai) ? 'checked' : '';
                contZat.innerHTML += `
                    <label class="flex items-center gap-2 cursor-pointer mb-1.5 text-[13px] font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 p-1.5 rounded-lg transition-colors border border-transparent hover:border-emerald-100">
                        <input type="checkbox" value="${z.nilai}" ${isChecked} onchange="updateZatTerpilih()" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4 shadow-sm border-slate-300">
                        ${z.nilai}
                    </label>
                `;
            });

            let selDiag = document.getElementById('select_diagnosis');
            let currentDiag = selDiag.value || defaultDiagnosis;

            selDiag.innerHTML = `<option value="">-- Pilih Diagnosis --</option>`;

            let isDiagInMaster = false;
            masterDiagnosis.forEach(d => {
                let isSelected = (d.nilai === currentDiag) ? 'selected' : '';
                if(d.nilai === currentDiag) isDiagInMaster = true;
                selDiag.innerHTML += `<option value="${d.nilai}" ${isSelected}>${d.nilai}</option>`;
            });

            if (currentDiag && !isDiagInMaster) {
                selDiag.innerHTML += `<option value="${currentDiag}" selected>${currentDiag}</option>`;
            }
        }

        function updateZatTerpilih() {
            let checkboxes = document.querySelectorAll('#container-zat input[type="checkbox"]:checked');
            let vals = Array.from(checkboxes).map(cb => cb.value);
            let teksHasil = vals.length > 0 ? "POSITIF " + vals.join(' DAN ') : "";
            document.getElementById('alat_bukti_hasil_input').value = teksHasil;
            document.getElementById('kesimpulan_jenis_zat_input').value = vals.join(', ');
            saveFormDraft();
        }

        function bukaModalOpsi(kategori) {
            document.getElementById('modalOpsiKategori').value = kategori;

            let title = 'Tambah Data Baru';
            if(kategori === 'zat') title = 'Tambah Zat Baru';
            else if(kategori === 'diagnosis') title = 'Tambah Diagnosis Baru';

            document.getElementById('modalOpsiTitle').innerText = title;
            document.getElementById('modalOpsiNilai').value = '';
            document.getElementById('modalTambahOpsi').classList.remove('hidden');
        }

        function simpanOpsiBaru() {
            let btn = document.getElementById('btnSimpanOpsi');
            let data = {
                kategori: document.getElementById('modalOpsiKategori').value,
                nilai: document.getElementById('modalOpsiNilai').value,
                _token: '{{ csrf_token() }}'
            };
            if(!data.nilai) return alert('Data tidak boleh kosong!');

            btn.innerText = 'Tunggu...';
            fetch('{{ route("master-opsi.storeAjax") }}', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(data)
            }).then(r => r.json()).then(res => {
                if(res.success) {
                    if(data.kategori === 'zat') masterZat.push(res.data);
                    else masterDiagnosis.push(res.data);

                    renderOpsiData();
                    document.getElementById('modalTambahOpsi').classList.add('hidden');
                }
            }).finally(() => btn.innerText = 'Simpan');
        }

        function bukaKelolaOpsi(kategori) {
            let title = 'Kelola Opsi';
            if(kategori === 'zat') title = 'Kelola Daftar Zat';
            else if(kategori === 'diagnosis') title = 'Kelola Daftar Diagnosis';

            document.getElementById('kelolaOpsiTitle').innerText = title;
            renderKelolaOpsiList(kategori);
            document.getElementById('modalKelolaOpsi').classList.remove('hidden');
        }

        function renderKelolaOpsiList(kategori) {
            let container = document.getElementById('kelolaOpsiList');
            container.innerHTML = '';
            let data = kategori === 'zat' ? masterZat : masterDiagnosis;

            data.forEach(item => {
                container.innerHTML += `
                    <div class="flex justify-between items-center p-3 border border-slate-100 rounded-xl mb-2 hover:bg-slate-50 transition-colors shadow-sm">
                        <span class="text-sm font-bold text-slate-700">${item.nilai}</span>
                        <button type="button" onclick="hapusOpsiPermanen(${item.id}, '${kategori}')" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 text-[11px] font-bold px-3 py-1 rounded-lg transition-colors border border-rose-100">Hapus</button>
                    </div>
                `;
            });
        }

        function hapusOpsiPermanen(id, kategori) {
            if(!confirm('Hapus permanen dari database?')) return;
            fetch(`/master-opsi/ajax/${id}`, {
                method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(r => r.json()).then(res => {
                if(res.success) {
                    if(kategori === 'zat') masterZat = masterZat.filter(x => x.id !== id);
                    else masterDiagnosis = masterDiagnosis.filter(x => x.id !== id);

                    renderOpsiData();
                    renderKelolaOpsiList(kategori);
                }
            });
        }

        // === 3. FITUR TIM MEDIS & HUKUM ===
        let masterMedis = @json($masterMedis);
        let masterHukum = @json($masterHukum);
        let selectedMedis = @json($asesmen->anggotaTim->where('kategori', 'medis')->pluck('id')->toArray());
        let selectedHukum = @json($asesmen->anggotaTim->where('kategori', 'hukum')->pluck('id')->toArray());

        function renderMedis() {
            const container = document.getElementById('container-medis');
            const select = document.getElementById('select-medis');
            const selectDokter = document.getElementById('select-dokter-pemeriksa');

            container.innerHTML = ''; select.innerHTML = '<option value="">-- Pilih Dokter TAT --</option>';
            let currentDokterVal = selectDokter.value || "{{ old('alat_bukti_dokter', $asesmen->alat_bukti_dokter) }}";
            selectDokter.innerHTML = `<option value="${currentDokterVal}">${currentDokterVal || '-- Otomatis dari Tim Medis --'}</option>`;

            selectedMedis.forEach(id => {
                let p = masterMedis.find(x => x.id == id);
                if(p) {
                    container.innerHTML += `
                        <div class="relative p-3 bg-white border border-indigo-100 rounded-xl shadow-sm mb-2 group">
                            <button type="button" onclick="hapusMedis(${p.id})" class="absolute top-3 right-3 text-rose-500 font-bold bg-rose-50 border border-rose-100 rounded-lg px-2 py-1 text-[10px] hover:bg-rose-500 hover:text-white transition-colors opacity-0 group-hover:opacity-100">HAPUS</button>
                            <p class="font-extrabold text-sm text-indigo-900 pr-12">${p.nama}</p>
                            <p class="text-[11px] font-bold text-indigo-500 mt-1 uppercase tracking-wider">NIP/SIP: <span class="text-slate-600 normal-case">${p.nip_nrp_sip || '-'}</span></p>
                            <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider">Jabatan: <span class="text-slate-600 normal-case">${p.jabatan || '-'}</span></p>
                        </div>
                    `;
                    // Sinkronisasi ke dropdown Dokter Pemeriksa
                    if(p.nama !== currentDokterVal) {
                        selectDokter.innerHTML += `<option value="${p.nama}">${p.nama}</option>`;
                    }
                }
            });

            masterMedis.forEach(p => {
                if(!selectedMedis.includes(p.id)) select.innerHTML += `<option value="${p.id}">${p.nama}</option>`;
            });
            syncHiddenInputs();
        }

        function renderHukum() {
            const container = document.getElementById('container-hukum');
            const select = document.getElementById('select-hukum');
            container.innerHTML = ''; select.innerHTML = '<option value="">-- Pilih Penyidik/Jaksa --</option>';

            selectedHukum.forEach(id => {
                let p = masterHukum.find(x => x.id == id);
                if(p) {
                    container.innerHTML += `
                        <div class="relative p-3 bg-white border border-indigo-100 rounded-xl shadow-sm mb-2 group">
                            <button type="button" onclick="hapusHukum(${p.id})" class="absolute top-3 right-3 text-rose-500 font-bold bg-rose-50 border border-rose-100 rounded-lg px-2 py-1 text-[10px] hover:bg-rose-500 hover:text-white transition-colors opacity-0 group-hover:opacity-100">HAPUS</button>
                            <p class="font-extrabold text-sm text-slate-800 pr-12">${p.nama}</p>
                            ${p.pangkat ? `<p class="text-[11px] font-bold text-indigo-500 mt-1 uppercase tracking-wider">Pangkat: <span class="text-slate-600 normal-case">${p.pangkat}</span></p>` : ''}
                            <p class="text-[11px] font-bold text-indigo-500 ${p.pangkat ? '' : 'mt-1'} uppercase tracking-wider">NIP/NRP: <span class="text-slate-600 normal-case">${p.nip_nrp_sip || '-'}</span></p>
                            <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider">Jabatan: <span class="text-slate-600 normal-case">${p.jabatan || '-'}</span></p>
                        </div>
                    `;
                }
            });
            masterHukum.forEach(p => {
                if(!selectedHukum.includes(p.id)) select.innerHTML += `<option value="${p.id}">${p.nama}</option>`;
            });
            syncHiddenInputs();
        }

        function syncHiddenInputs() {
            const container = document.getElementById('hidden-inputs-container');
            container.innerHTML = '';
            selectedMedis.forEach(id => container.innerHTML += `<input type="hidden" name="tim_medis[]" value="${id}">`);
            selectedHukum.forEach(id => container.innerHTML += `<input type="hidden" name="tim_hukum[]" value="${id}">`);
            saveFormDraft();
        }

        document.getElementById('select-medis').addEventListener('change', function() {
            if(this.value && selectedMedis.length < 2) { selectedMedis.push(parseInt(this.value)); renderMedis(); }
            else if (selectedMedis.length >= 2) { alert('Maks. 2 Dokter untuk Tim Medis.'); this.value = ''; }
        });

        document.getElementById('select-hukum').addEventListener('change', function() {
            if(this.value && selectedHukum.length < 3) { selectedHukum.push(parseInt(this.value)); renderHukum(); }
            else if (selectedHukum.length >= 3) { alert('Maks. 3 Penyidik/Jaksa untuk Tim Hukum.'); this.value = ''; }
        });

        function hapusMedis(id) { selectedMedis = selectedMedis.filter(x => x !== id); renderMedis(); }
        function hapusHukum(id) { selectedHukum = selectedHukum.filter(x => x !== id); renderHukum(); }

        function bukaModal(k) {
            document.getElementById('modalKategori').value = k;
            document.getElementById('modalTitle').innerText = k === 'medis' ? 'Tambah Dokter' : 'Tambah Tim Hukum';
            document.getElementById('wrapPangkat').style.display = k === 'medis' ? 'none' : 'block';
            document.getElementById('modalTambahAnggota').classList.remove('hidden');
        }
        function tutupModal() {
            document.getElementById('formTambahAnggota').reset();
            document.getElementById('modalTambahAnggota').classList.add('hidden');
        }
        function simpanAnggotaBaru() {
            let data = {
                nama: document.getElementById('modalNama').value, nip_nrp_sip: document.getElementById('modalNip').value,
                pangkat: document.getElementById('modalPangkat').value, jabatan: document.getElementById('modalJabatan').value,
                kategori: document.getElementById('modalKategori').value, _token: '{{ csrf_token() }}'
            };
            fetch('{{ route("master-anggota.storeAjax") }}', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
            }).then(r => r.json()).then(res => {
                if(data.kategori === 'medis') { masterMedis.push(res.data); if(selectedMedis.length < 2) selectedMedis.push(res.data.id); renderMedis(); }
                else { masterHukum.push(res.data); if(selectedHukum.length < 3) selectedHukum.push(res.data.id); renderHukum(); }
                tutupModal();
            });
        }

        function bukaKelola(kategori) {
            document.getElementById('kelolaTitle').innerText = kategori === 'medis' ? 'Kelola Tim Medis' : 'Kelola Tim Hukum';
            renderKelolaList(kategori);
            document.getElementById('modalKelolaAnggota').classList.remove('hidden');
        }
        function tutupKelola() { document.getElementById('modalKelolaAnggota').classList.add('hidden'); }

        function renderKelolaList(kategori) {
            let container = document.getElementById('kelolaList'); container.innerHTML = '';
            let data = kategori === 'medis' ? masterMedis : masterHukum;
            data.forEach(p => {
                container.innerHTML += `
                    <div class="flex justify-between items-center p-3 border border-slate-100 rounded-xl mb-2 hover:bg-slate-50 shadow-sm transition-colors">
                        <div>
                            <p class="font-extrabold text-sm text-slate-800">${p.nama}</p>
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">${p.nip_nrp_sip || '-'} <span class="text-slate-300 mx-1">|</span> ${p.jabatan || '-'}</p>
                        </div>
                        <button type="button" onclick="hapusPermanen(${p.id}, '${kategori}')" class="text-rose-500 bg-rose-50 hover:bg-rose-100 border border-rose-100 px-3 py-1.5 rounded-lg font-bold text-[11px] transition-colors">Hapus</button>
                    </div>`;
            });
        }
        function hapusPermanen(id, kategori) {
            if(!confirm('Yakin ingin menghapus anggota ini secara permanen dari sistem?')) return;
            fetch(`/master-anggota/ajax/${id}`, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
            .then(r => r.json()).then(res => {
                if(kategori === 'medis') { masterMedis = masterMedis.filter(x => x.id !== id); selectedMedis = selectedMedis.filter(x => x !== id); renderMedis(); }
                else { masterHukum = masterHukum.filter(x => x.id !== id); selectedHukum = selectedHukum.filter(x => x !== id); renderHukum(); }
                renderKelolaList(kategori);
            });
        }

        // === 4. AUTOSAVE DRAFT FORM ===
        const formBeritaAcara = document.getElementById('formBeritaAcara');
        const autosaveIndicator = document.getElementById('autosaveIndicator');

        function saveFormDraft() {
            if(!formBeritaAcara) return;
            const formData = new FormData(formBeritaAcara);
            const data = {};
            formData.forEach((value, key) => {
                if(key !== '_token' && key !== 'action') {
                    data[key] = value;
                }
            });
            localStorage.setItem('formBeritaAcaraDraft_{{ $asesmen->id }}', JSON.stringify(data));

            if(autosaveIndicator) {
                autosaveIndicator.classList.remove('hidden');
                setTimeout(() => { autosaveIndicator.classList.add('hidden'); }, 3000);
            }
        }

        function loadFormDraft() {
            if(!formBeritaAcara) return;
            const draft = localStorage.getItem('formBeritaAcaraDraft_{{ $asesmen->id }}');
            if (draft) {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    const elements = formBeritaAcara.querySelectorAll(`[name="${key}"]`);
                    if (elements.length > 0) {
                        const el = elements[0];
                        if (el.type === 'radio' || el.type === 'checkbox') {
                            const target = formBeritaAcara.querySelector(`[name="${key}"][value="${data[key]}"]`);
                            if (target) target.checked = true;
                        } else {
                            el.value = data[key];
                        }
                    }
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // 1. Memuat semua custom options
            loadSemuaOpsiLokal();

            // 2. Render manual UI
            renderOpsiData();
            renderMedis();
            renderHukum();

            // 3. Muat draf lokal (halaman ini saja) terlebih dahulu
            loadFormDraft();

            if(hasSavedMedis === 'no' && document.getElementById('narasi_medis').value.trim() === '') document.getElementById('narasi_medis').value = getDraftMedis();
            if(hasSavedHukum === 'no' && document.getElementById('narasi_hukum').value.trim() === '') document.getElementById('narasi_hukum').value = getDraftHukum();

            // 4. SINKRONISASI MUTLAK: Override draf lokal dengan 'Shared Value' Durasi Rawat
            syncDurasiRawat();

            if(formBeritaAcara) {
                formBeritaAcara.addEventListener('input', saveFormDraft);
                formBeritaAcara.addEventListener('change', saveFormDraft);
            }
        });
    </script>
</x-app-layout>
