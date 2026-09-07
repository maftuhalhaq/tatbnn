<x-app-layout>
    <style>
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .autosave-active { animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>

    <div class="py-8 sm:py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('asesmen.show', $asesmen->id) }}" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-purple-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 uppercase tracking-wider mb-1">
                            Generator Dokumen (Word)
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight flex items-center gap-3">
                            Surat Rekomendasi: {{ $asesmen->nama_lengkap }}
                            <span id="autosaveIndicator" class="hidden text-[10px] font-bold text-emerald-600 bg-emerald-100 border border-emerald-200 px-2 py-1 rounded-md uppercase tracking-wider autosave-active">
                                Draft Tersimpan
                            </span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50/50 p-6 md:p-8 rounded-2xl border border-indigo-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500"></div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 border-b border-indigo-200/60 pb-3 mt-1 gap-2">
                    <h3 class="text-sm font-extrabold text-indigo-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Preview Data Klien (Autofill ke Word)
                    </h3>
                    <p class="text-[11px] font-bold text-indigo-600 bg-white px-3 py-1 rounded-lg border border-indigo-100 shadow-sm uppercase tracking-wider">Hanya Baca</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    <div class="bg-white p-3.5 rounded-xl border border-indigo-50 shadow-sm">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nama Lengkap</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $asesmen->nama_lengkap }}</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-indigo-50 shadow-sm">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">NIK</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $asesmen->nik }}</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-indigo-50 shadow-sm">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">No. Surat Pengajuan</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $asesmen->no_surat_pengajuan ?? '-' }}</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-indigo-50 shadow-sm">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jenis Narkotika</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</span>
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

                    <div class="md:col-span-4 bg-gradient-to-r from-purple-500 to-indigo-600 p-4 rounded-xl shadow-md text-white flex items-center justify-between">
                        <div>
                            <span class="block text-[11px] font-medium text-purple-100 uppercase tracking-wider mb-0.5">Keputusan Tempat Rehabilitasi</span>
                            <span class="font-extrabold text-lg">{{ $tempatBersih }}</span>
                        </div>
                        <form action="{{ route('asesmen.rekomendasi.unduh', $asesmen->id) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <input type="hidden" name="action" value="download">
                            <input type="hidden" name="tempat_rehabilitasi" value="{{ $tempatBersih }}">
                            <input type="hidden" name="rekomendasi_input" value="{{ $tempatBersih }}">
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

                            <button type="submit" style="background-color: #9333ea;" class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:opacity-90 transition shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <form id="formRekomendasi" action="{{ route('asesmen.rekomendasi.unduh', $asesmen->id) }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="tempat_rehabilitasi" value="{{ $tempatBersih }}">
                <input type="hidden" name="rekomendasi_input" value="{{ $tempatBersih }}">

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-purple-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        1. Administrasi Surat
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nomor Surat Rekomendasi BNN <span class="text-rose-500">*</span></label>
                            <input type="text" name="no_surat_rekomendasi" value="{{ old('no_surat_rekomendasi', $asesmen->no_surat_rekomendasi) }}" placeholder="Misal: R/397/VII/Ka/PB.06.00/2026/BNNK" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal Pembuatan Surat <span class="text-rose-500">*</span></label>
                            <input type="text" name="tgl_rekomendasi" value="{{ old('tgl_rekomendasi', $asesmen->tgl_rekomendasi ?? date('Y-m-d')) }}" required class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tujuan Surat (Kepada Yth) <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2 items-center">
                                <select name="kepada_yth" id="kepada_yth" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                                    <option value="">-- Pilih Tujuan Surat --</option>
                                    @if(isset($asesmen->kepada_yth) && $asesmen->kepada_yth !== '')
                                        <option value="{{ $asesmen->kepada_yth }}" selected>{{ $asesmen->kepada_yth }}</option>
                                    @endif
                                    @foreach($riwayat_kepada as $item)
                                        @if($item->kepada_yth !== $asesmen->kepada_yth && $item->kepada_yth !== '')
                                            <option value="{{ $item->kepada_yth }}">{{ $item->kepada_yth }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTambahModal('kepada_yth', 'Tujuan Surat')" class="px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                <button type="button" onclick="openKelolaModal('kepada_yth', 'Tujuan Surat')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kewarganegaraan Klien</label>
                            <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $asesmen->kewarganegaraan ?? 'Indonesia (WNI)') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-purple-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        2. Dasar Hukum & Pengajuan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">No. Keputusan Penunjukan Tim TAT <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2 items-center">
                                <select name="no_keputusan" id="no_keputusan" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                                    <option value="">-- Pilih No. Keputusan --</option>
                                    @if(isset($asesmen->no_keputusan) && $asesmen->no_keputusan !== '')
                                        <option value="{{ $asesmen->no_keputusan }}" selected>{{ $asesmen->no_keputusan }}</option>
                                    @endif
                                    @foreach($riwayat_no_keputusan as $item)
                                        @if($item->no_keputusan !== $asesmen->no_keputusan && $item->no_keputusan !== '')
                                            <option value="{{ $item->no_keputusan }}">{{ $item->no_keputusan }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTambahModal('no_keputusan', 'No. Keputusan Tim TAT')" class="px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                <button type="button" onclick="openKelolaModal('no_keputusan', 'No. Keputusan Tim TAT')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal Keputusan Penunjukan <span class="text-rose-500">*</span></label>
                            <input type="text" name="tgl_keputusan" value="{{ old('tgl_keputusan', $asesmen->tgl_keputusan) }}" required class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tentang Permohonan (Isi Surat) <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2 items-center">
                                <select name="tentang_permohonan" id="tentang_permohonan" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                                    <option value="">-- Pilih Tentang Permohonan --</option>
                                    @if(isset($asesmen->tentang_permohonan) && $asesmen->tentang_permohonan !== '')
                                        <option value="{{ $asesmen->tentang_permohonan }}" selected>{{ $asesmen->tentang_permohonan }}</option>
                                    @endif
                                    @foreach($riwayat_tentang as $item)
                                        @if($item->tentang_permohonan !== $asesmen->tentang_permohonan && $item->tentang_permohonan !== '')
                                            <option value="{{ $item->tentang_permohonan }}">{{ $item->tentang_permohonan }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTambahModal('tentang_permohonan', 'Tentang Permohonan')" class="px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                <button type="button" onclick="openKelolaModal('tentang_permohonan', 'Tentang Permohonan')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-purple-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        3. Diagnosis & Rekomendasi Medis
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nama Golongan Narkotika <span class="text-slate-400 font-medium normal-case">(Medis)</span> <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2 items-center">
                                <select name="nama_narkotika_medis" id="nama_narkotika_medis" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                                    <option value="">-- Pilih Golongan Narkotika --</option>
                                    @if(isset($asesmen->nama_narkotika_medis) && $asesmen->nama_narkotika_medis !== '')
                                        <option value="{{ $asesmen->nama_narkotika_medis }}" selected>{{ $asesmen->nama_narkotika_medis }}</option>
                                    @endif
                                    @foreach($riwayat_narkotika as $item)
                                        @if($item->nama_narkotika_medis !== $asesmen->nama_narkotika_medis && $item->nama_narkotika_medis !== '')
                                            <option value="{{ $item->nama_narkotika_medis }}">{{ $item->nama_narkotika_medis }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTambahModal('nama_narkotika_medis', 'Golongan Narkotika (Medis)')" class="px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                <button type="button" onclick="openKelolaModal('nama_narkotika_medis', 'Golongan Narkotika (Medis)')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                            </div>
                        </div>

                        <!-- LAMA WAKTU PERAWATAN (SINKRONISASI 100%) -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Lama Waktu Perawatan <span class="text-rose-500">*</span></label>
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
                                <select name="lama_perawatan" id="lama_perawatan" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                                    <option value="">-- Pilih Waktu Perawatan --</option>
                                    @if($valDurasi !== '')
                                        <option value="{{ $valDurasi }}" selected>{{ $valDurasi }}</option>
                                    @endif
                                    @foreach($allDurasi as $d)
                                        @if($d !== $valDurasi)
                                            <option value="{{ $d }}">{{ $d }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTambahModal('lama_perawatan', 'Lama Waktu Perawatan', 'lama_perawatan')" class="px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                <button type="button" onclick="openKelolaModal('lama_perawatan', 'Lama Waktu Perawatan', 'lama_perawatan')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Keterangan Diagnosis Panjang <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2 items-center">
                                <select name="keterangan_diagnosis" id="keterangan_diagnosis" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                                    <option value="">-- Pilih Keterangan Diagnosis --</option>
                                    @if(isset($asesmen->keterangan_diagnosis) && $asesmen->keterangan_diagnosis !== '')
                                        <option value="{{ $asesmen->keterangan_diagnosis }}" selected>{{ $asesmen->keterangan_diagnosis }}</option>
                                    @endif
                                    @foreach($riwayat_diagnosis as $item)
                                        @if($item->keterangan_diagnosis !== $asesmen->keterangan_diagnosis && $item->keterangan_diagnosis !== '')
                                            <option value="{{ $item->keterangan_diagnosis }}">{{ $item->keterangan_diagnosis }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTambahModal('keterangan_diagnosis', 'Keterangan Diagnosis Panjang')" class="px-3 py-2.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition shadow-sm whitespace-nowrap">+ TAMBAH</button>
                                <button type="button" onclick="openKelolaModal('keterangan_diagnosis', 'Keterangan Diagnosis Panjang')" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-200 transition shadow-sm whitespace-nowrap">KELOLA</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 bg-slate-50 p-4 border border-slate-200 rounded-lg">
                    <a href="{{ route('asesmen.show', $asesmen->id) }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-slate-300 rounded-lg font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">Batal</a>
                    <button type="submit" name="action" value="save" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none transition shadow-sm">Simpan Perubahan</button>
                    <button type="submit" name="action" value="download" style="background-color: #9333ea;" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg font-semibold text-white hover:opacity-90 focus:outline-none transition shadow-sm">Simpan & Unduh Dokumen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH & KELOLA OPSI DINAMIS -->
    <div id="modalTambah" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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

    <div id="modalKelola" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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


    <script>
        // === FUNGSI SINKRONISASI DROPDOWN & AUTOSAVE LENGKAP ===
        let currentStorageKey = null;

        function openTambahModal(selectId, labelName, storageKey = null) {
            document.getElementById('tambah_target_id').value = selectId;
            currentStorageKey = storageKey || selectId;
            document.getElementById('tambah_label').innerText = labelName;
            document.getElementById('tambah_input').value = '';
            document.getElementById('modalTambah').classList.remove('hidden');
            setTimeout(() => document.getElementById('tambah_input').focus(), 100);
        }

        function closeTambahModal() {
            document.getElementById('modalTambah').classList.add('hidden');
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
            document.getElementById('modalKelola').classList.remove('hidden');
        }

        function closeKelolaModal() {
            document.getElementById('modalKelola').classList.add('hidden');
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
            const selectIds = ['kepada_yth', 'no_keputusan', 'tentang_permohonan', 'nama_narkotika_medis', 'keterangan_diagnosis'];
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
            // 1. Memuat semua custom options
            loadSemuaOpsiLokal();

            // 2. Muat draf terlebih dahulu
            loadFormDraft();

            // 3. SINKRONISASI MUTLAK
            // Override nilai draf dengan shared value durasi (prioritas tertinggi)
            syncDurasiRawat();

            if(formRekomendasi) {
                formRekomendasi.addEventListener('input', saveFormDraft);
                formRekomendasi.addEventListener('change', saveFormDraft);
            }
        });
    </script>
</x-app-layout>
