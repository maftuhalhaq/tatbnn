<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('asesmen.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                </a>
                <div>
                    <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-800 text-[10px] font-extrabold rounded mb-1 uppercase tracking-wider">Pusat Data Klien</span>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">{{ $asesmen->nama_lengkap }}</h2>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('manage-data')
                <a href="{{ route('asesmen.edit', $asesmen->id) }}" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs rounded-lg transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data Utama
                </a>
                @endcan
                <a href="{{ route('asesmen.cetakPdf', $asesmen->id) }}" target="_blank" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-lg transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/40 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
                // Penghitungan Usia Klien
                $usiaKlien = '-';
                if (!empty($asesmen->tgl_lahir) && !empty($asesmen->created_at)) {
                    try {
                        $usiaKlien = \Carbon\Carbon::parse($asesmen->tgl_lahir)->diff(\Carbon\Carbon::parse($asesmen->created_at))->y . ' Tahun';
                    } catch (\Exception $e) { $usiaKlien = '-'; }
                }
            @endphp

            <!-- ========================================== -->
            <!-- CARD 1: PROFIL KLIEN                       -->
            <!-- ========================================== -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm relative w-full">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500 rounded-l-2xl"></div>
                <h3 class="text-[13px] font-extrabold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-100 pb-3 ml-2 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></span>
                    1. Profil Klien
                </h3>

                <div class="flex flex-col md:flex-row gap-6 ml-2">
                    <!-- FOTO KLIEN -->
                    <div class="flex flex-col items-center mb-2 md:mb-0 shrink-0">
                        <div class="w-24 h-32 bg-slate-100 rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-2">
                            @if(!empty($asesmen->foto_klien))
                                <img src="{{ asset('storage/' . $asesmen->foto_klien) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                            @endif
                        </div>
                        <h4 class="font-extrabold text-sm text-slate-900 text-center leading-tight max-w-[120px]">{{ $asesmen->nama_lengkap ?? '-' }}</h4>
                        <span class="text-[11px] font-bold text-blue-600 mt-1">Usia: {{ $usiaKlien }}</span>
                    </div>

                    <!-- LIST DATA PROFIL -->
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-6">
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">NIK KTP</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->nik ?? '-' }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Jenis Kelamin</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-Laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Tempat Lahir</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tempat_lahir ?? '-' }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Tanggal Lahir</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->format('d-m-Y') : '-' }}</p></div>
                        
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Agama</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->agama ?? '-' }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Kewarganegaraan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->kewarganegaraan ?? '-' }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Pekerjaan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->pekerjaan_input ?? $asesmen->pekerjaan->nama_pekerjaan ?? '-' }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Pendidikan Terakhir</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->pendidikan_input ?? $asesmen->pendidikan->nama_pendidikan ?? '-' }}</p></div>
                        
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">No. Handphone</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_hp ?? '-' }}</p></div>
                        <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Penghasilan Rata-rata</span><p class="text-[13px] font-extrabold text-emerald-600">{{ $asesmen->penghasilan_rata_rata ?? '-' }}</p></div>

                        <!-- Alamat Blok -->
                        <div class="sm:col-span-2 lg:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat KTP</span>
                                <p class="text-[12px] font-semibold text-slate-800 leading-relaxed">{{ $asesmen->alamat_ktp ?? '-' }}</p>
                            </div>
                            <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Domisili Saat Ini</span>
                                <p class="text-[12px] font-semibold text-slate-800 leading-relaxed">{{ $asesmen->alamat_domisili ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CARD 2: DATA ADMINISTRASI                  -->
            <!-- ========================================== -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm relative w-full">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 rounded-l-2xl"></div>
                <h3 class="text-[13px] font-extrabold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-3 ml-2 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                    2. Data Administrasi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-6 ml-2">
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">No / Bulan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_bln ?? '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">No Register</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_register ?? '-' }}</p></div>
                    <div class="col-span-1 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Asal Pengajuan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->asal_pengajuan ?? '-' }}</p></div>
                    
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Tanggal Surat</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->format('d-m-Y') : '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Tgl Berkas Diterima</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('d-m-Y') : '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Tgl Penangkapan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->format('d-m-Y') : '-' }}</p></div>
                    
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 inline-block w-max">
                        <span class="block text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-0.5">Tgl Pelaksanaan</span>
                        <p class="text-[13px] font-extrabold text-indigo-700">{{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('d-m-Y') : '-' }}</p>
                    </div>
                    
                    <div class="col-span-1 sm:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">No Surat Pengajuan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_surat_pengajuan ?? '-' }}</p></div>
                    <div class="col-span-1 sm:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">No LKN / LP / LI</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_lkn ?? '-' }}</p></div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CARD 3: CASE CONFERENCE                    -->
            <!-- ========================================== -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm relative w-full">
                <div class="absolute top-0 left-0 right-0 h-1 bg-amber-400 rounded-t-2xl"></div>
                <h3 class="text-[13px] font-extrabold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                    <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg"><svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg></span>
                    3. Case Conference
                </h3>

                <!-- Area Hukum & Bukti -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-6 mb-5">
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Hukum</span><p class="inline-flex px-2 py-1 rounded bg-amber-100 text-amber-800 font-extrabold text-[11px] uppercase">{{ $asesmen->status_hukum ?? '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jaringan</span><p class="text-[13px] font-extrabold text-rose-600 uppercase">{{ $asesmen->keterlibatan_jaringan ?? '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Zat / Narkotika</span><p class="text-[13px] font-bold text-slate-900">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Berat Bukti (BB)</span><p class="text-[13px] font-bold text-slate-900">{{ $asesmen->berat_bb ? $asesmen->berat_bb.' Gram' : '-' }}</p></div>
                    
                    <div class="col-span-2 lg:col-span-4 bg-slate-50 p-3.5 rounded-lg border border-slate-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Detail Barang Bukti</span>
                        <p class="text-[13px] font-medium text-slate-800 leading-relaxed">{{ $asesmen->deskripsi_bb ?? '-' }}</p>
                    </div>
                    
                    <div class="col-span-1 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cara Dapat</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->cara_mendapatkan ?? '-' }}</p></div>
                    <div class="col-span-1 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dapat Dari Siapa</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->dapat_dari_siapa ?? '-' }}</p></div>
                </div>

                <div class="border-t border-slate-100 my-5"></div>

                <!-- Area Analisis (Hukum, Medis, Psikologi) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 block">Analisis Aspek Hukum</span>
                        <div class="text-[13px] font-medium text-slate-800 whitespace-pre-line leading-relaxed">{{ $asesmen->aspek_hukum ?? '-' }}</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 block">Analisis Aspek Medis</span>
                        <div class="text-[13px] font-medium text-slate-800 whitespace-pre-line leading-relaxed">{{ $asesmen->aspek_medis ?? '-' }}</div>
                    </div>
                </div>

                <!-- Sub-grid Kesehatan Fisik & Psikologi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kesehatan Fisik</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->kesehatan_fisik ?? '-' }}</p></div>
                    <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Psikologi</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->psikologi ?? '-' }}</p></div>
                </div>

                <div class="border-t border-slate-100 my-5"></div>

                <!-- Area Pola, Urine & Rekomendasi -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div class="col-span-2 lg:col-span-1">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Hasil Tes Urine</span>
                        @php
                            $urineText = $asesmen->tes_urine ?? '';
                            $urineFirstWord = strtoupper(strtok($urineText, " :,-"));
                        @endphp
                        @if($urineFirstWord === 'POSITIF')
                            <span class="inline-block px-3 py-1.5 rounded text-[11px] font-extrabold bg-rose-100 text-rose-700 tracking-wide">{{ $asesmen->tes_urine }}</span>
                        @elseif($urineFirstWord === 'NEGATIF')
                            <span class="inline-block px-3 py-1.5 rounded text-[11px] font-extrabold bg-emerald-100 text-emerald-700 tracking-wide">{{ $asesmen->tes_urine }}</span>
                        @else
                            <span class="inline-block px-3 py-1.5 rounded text-[11px] font-bold bg-white border border-slate-200 text-slate-700 tracking-wide">{{ $asesmen->tes_urine ?? '-' }}</span>
                        @endif
                    </div>
                    <div class="col-span-2 lg:col-span-1"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alasan Pakai</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->alasan_penggunaan ?? '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pola Pakai</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->pola_pemakaian ?? '-' }}</p></div>
                    <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Ketergantungan</span><p class="text-[13px] font-extrabold text-rose-600">{{ $asesmen->tingkat_ketergantungan ?? '-' }}</p></div>
                    
                    <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kondisi Keluarga</span><p class="text-[12px] font-medium text-slate-800 bg-white p-3 rounded-lg border border-slate-200">{{ $asesmen->kondisi_keluarga ?? '-' }}</p></div>
                    <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kondisi Lingkungan</span><p class="text-[12px] font-medium text-slate-800 bg-white p-3 rounded-lg border border-slate-200">{{ $asesmen->kondisi_lingkungan ?? '-' }}</p></div>
                </div>

                <!-- Rekomendasi TAT dan Saran Sidang -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 pt-2">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1.5">Rekomendasi TAT (Instansi)</span>
                            <span class="inline-block bg-emerald-100 text-emerald-800 font-extrabold px-3 py-1.5 rounded-md text-[13px] border border-emerald-200 shadow-sm">{{ $asesmen->rekomendasi_input ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan Tambahan TAT</span>
                            <p class="text-[13px] font-medium text-slate-700 italic leading-relaxed">"{{ $asesmen->keterangan_tambahan ?? '-' }}"</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Saran Sidang Case Conference</span>
                        @php 
                            $saranList = array_filter(array_map('trim', explode(',', $asesmen->saran_case_conference ?? '')));
                        @endphp
                        @if(count($saranList) > 0)
                            <ul class="list-decimal list-inside text-[13px] font-semibold text-slate-800 space-y-1.5 leading-relaxed">
                                @foreach($saranList as $saran) <li>{{ $saran }}</li> @endforeach
                            </ul>
                        @else
                            <div class="text-[13px] font-medium text-slate-500 text-center py-2">- Belum ada saran -</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CARD 4: HASIL ASESMEN FINAL & PELAKSANAAN  -->
            <!-- ========================================== -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm relative w-full">
                <div class="absolute top-0 left-0 w-full h-1 bg-slate-500 rounded-t-2xl"></div>
                <h3 class="text-[13px] font-extrabold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                    <span class="bg-slate-100 text-slate-600 p-1.5 rounded-md"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></span>
                    4. Hasil Asesmen Final & Pelaksanaan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div class="md:col-span-2">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pasal Yang Disangkakan</span>
                        <p class="text-[13px] font-bold text-slate-900 bg-slate-50 p-3.5 rounded-lg border border-slate-200">{{ $asesmen->pasal_sangkaan ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Hasil Asesmen Hukum Final</span>
                        <div class="text-[13px] font-medium leading-relaxed text-slate-800 whitespace-pre-line">{{ $asesmen->hasil_asesmen_hukum ?? '-' }}</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Hasil Rujukan Medis Final</span>
                        <div class="text-[13px] font-medium leading-relaxed text-slate-800 whitespace-pre-line">{{ $asesmen->hasil_asesmen_medis ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2 pt-3 border-t border-slate-100 mt-1">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status Pelaksanaan Rekomendasi</span>
                        @if($asesmen->pelaksanaan === 'YA') 
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold text-emerald-700 bg-emerald-100 px-3 py-1.5 rounded-md border border-emerald-200"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> SUDAH DILAKSANAKAN (YA)</span>
                        @elseif($asesmen->pelaksanaan === 'TIDAK') 
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold text-rose-700 bg-rose-100 px-3 py-1.5 rounded-md border border-rose-200"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg> BELUM / BATAL DILAKSANAKAN (TIDAK)</span>
                        @else 
                            <span class="text-[13px] font-bold text-slate-800">-</span> 
                        @endif
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CARD 5: DATA SURAT BERITA ACARA            -->
            <!-- ========================================== -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-emerald-200 shadow-sm relative w-full">
                <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 rounded-t-2xl"></div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 border-b border-emerald-100 pb-3 gap-4 mt-1">
                    <h3 class="text-[13px] font-extrabold text-emerald-700 uppercase tracking-wider flex items-center gap-2">
                        <span class="bg-emerald-100 text-emerald-600 p-1.5 rounded-md"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                        5. Data Surat Berita Acara
                    </h3>
                    <div class="flex gap-2 w-full sm:w-auto">
                        @can('akses-dokumen')
                        <a href="{{ route('asesmen.berita-acara', $asesmen->id) }}" class="flex-1 sm:flex-none text-center px-3 py-1.5 border border-emerald-300 bg-white rounded-md font-bold text-[10px] text-emerald-700 uppercase tracking-widest hover:bg-emerald-50 transition shadow-sm">
                            Edit Form BA
                        </a>
                        @endcan
                        <form action="{{ route('asesmen.berita-acara.unduh', $asesmen->id) }}" method="POST" class="flex-1 sm:flex-none">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-emerald-600 rounded-md font-bold text-[10px] text-white uppercase tracking-widest hover:bg-emerald-700 transition shadow-sm">
                                Unduh Word
                            </button>
                        </form>
                    </div>
                </div>

                @if($asesmen->no_ba || $asesmen->ketua_tat_nama)
                    <div class="space-y-6">
                        <!-- Baris 1: Header BA -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-6">
                            <div class="col-span-2 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Ketua TAT</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->ketua_tat_nama ?? '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">NRP Ketua</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->ketua_tat_nrp ?? '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Rapat B.A</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_ba ? \Carbon\Carbon::parse($asesmen->tgl_ba)->format('d-m-Y') : '-' }}</p></div>
                            
                            <div class="col-span-2 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">No. SK Tim Asesmen</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_kep_tim ?? '-' }}</p></div>
                            <div class="col-span-2 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal SK Tim</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_kep_tim ? \Carbon\Carbon::parse($asesmen->tgl_kep_tim)->format('d-m-Y') : '-' }}</p></div>
                        </div>

                        <!-- Baris 2: Tim Terlibat -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-xl border border-emerald-200">
                                <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Tim Medis Terlibat</span>
                                @forelse($asesmen->anggotaTim->where('kategori', 'medis') as $m)
                                    <p class="text-[12px] font-semibold text-slate-800 mb-1 flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-emerald-500"></span> {{ $m->nama }}</p>
                                @empty <p class="text-[12px] text-slate-400 italic">-</p> @endforelse
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-emerald-200">
                                <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Tim Hukum Terlibat</span>
                                @forelse($asesmen->anggotaTim->where('kategori', 'hukum') as $h)
                                    <p class="text-[12px] font-semibold text-slate-800 mb-1 flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-emerald-500"></span> {{ $h->nama }}</p>
                                @empty <p class="text-[12px] text-slate-400 italic">-</p> @endforelse
                            </div>
                        </div>

                        <!-- Baris 3: Narasi (Gaya Callout Kiri) -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div class="bg-slate-50 border-l-4 border-emerald-400 p-4 rounded-r-lg">
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Narasi Tim Hukum</span>
                                <div class="text-[12px] font-medium leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $asesmen->narasi_hukum ?? '-' }}</div>
                            </div>
                            <div class="bg-slate-50 border-l-4 border-emerald-400 p-4 rounded-r-lg">
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Narasi Tim Medis</span>
                                <div class="text-[12px] font-medium leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $asesmen->narasi_medis ?? '-' }}</div>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-100"></div>

                        <!-- Baris 4: SK Narkotika & Hasil Zat -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-6">
                            <div class="col-span-2 lg:col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">No. SK Narkotika</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->alat_bukti_no_sk ?? '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tgl SK Narkotika</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->alat_bukti_tgl_sk ? \Carbon\Carbon::parse($asesmen->alat_bukti_tgl_sk)->format('d-m-Y') : '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Dokter Pemeriksa</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->alat_bukti_dokter ?? '-' }}</p></div>
                            
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Zat Yang Dipakai</span><p class="text-[13px] font-semibold text-slate-900 leading-relaxed">{{ $asesmen->kesimpulan_jenis_zat ?? '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Klien</span><p class="inline-flex px-2 py-0.5 rounded border border-slate-200 text-slate-700 font-bold text-[10px] uppercase">{{ $asesmen->status_klien ?? '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pola Pemakaian</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->kesimpulan_pola_pakai ?? '-' }}</p></div>
                            
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Hasil Zat Positif</span><p class="text-[13px] font-bold text-rose-600 leading-relaxed">{{ $asesmen->alat_bukti_hasil ?? '-' }}</p></div>
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori Ketergantungan</span><p class="text-[13px] font-bold text-rose-600">{{ $asesmen->kesimpulan_kategori ?? '-' }}</p></div>
                        </div>

                        <!-- Baris 5: Diagnosis Final Centered Box -->
                        <div class="pt-2">
                            <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 text-center">Diagnosis Medis Final</span>
                            <div class="text-[14px] font-bold text-indigo-700 bg-white p-4 rounded-xl border border-emerald-200 shadow-sm text-center leading-relaxed">{{ $asesmen->diagnosis_medis ?? '-' }}</div>
                        </div>
                        
                        <!-- Baris 6: Keterangan -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-3">
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan Hukum Rekomendasi</span>
                                <div class="text-[13px] font-medium leading-relaxed text-slate-800 p-4 rounded-xl border border-slate-200 bg-slate-50">"{{ $asesmen->rekomendasi_keterangan ?? '-' }}"</div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Lama (Durasi) Rawat</span>
                                <p class="text-[18px] font-extrabold text-rose-600 mt-2 uppercase">{{ $asesmen->lama_perawatan ?? $asesmen->rekomendasi_durasi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- EMPTY STATE B.A -->
                    <div class="text-center py-6 border-2 border-dashed border-emerald-200 rounded-xl bg-slate-50">
                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-[13px] font-bold text-emerald-800 mb-1">Belum Ada Berita Acara</h3>
                        <p class="text-emerald-600/70 text-[10px]">Silakan klik tombol Edit Form BA di atas untuk melengkapi data.</p>
                    </div>
                @endif
            </div>

            <!-- ========================================== -->
            <!-- CARD 6: DATA SURAT REKOMENDASI             -->
            <!-- ========================================== -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-purple-200 shadow-sm relative overflow-hidden w-full mb-10">
                <div class="absolute top-0 left-0 w-full h-1 bg-purple-500 rounded-t-2xl"></div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 border-b border-purple-100 pb-3 gap-4 mt-1">
                    <h3 class="text-[13px] font-extrabold text-purple-700 uppercase tracking-wider flex items-center gap-2">
                        <span class="bg-purple-100 text-purple-600 p-1.5 rounded-md"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></span>
                        6. Data Surat Rekomendasi
                    </h3>
                    <div class="flex gap-2 w-full sm:w-auto">
                        @can('akses-dokumen')
                        <a href="{{ route('asesmen.rekomendasi', $asesmen->id) }}" class="flex-1 sm:flex-none text-center px-3 py-1.5 border border-purple-300 bg-white rounded-md font-bold text-[10px] text-purple-700 uppercase tracking-widest hover:bg-purple-50 transition shadow-sm">
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

                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-purple-600 rounded-md font-bold text-[10px] text-white uppercase tracking-widest hover:bg-purple-700 transition shadow-sm">
                                Unduh Word
                            </button>
                        </form>
                    </div>
                </div>

                @if($asesmen->no_surat_rekomendasi || $asesmen->kepada_yth)
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-6">
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Surat BNN</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_surat_rekomendasi ?? '-' }}</p></div>
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tujuan (Kepada Yth)</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->kepada_yth ?? '-' }}</p></div>
                            
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Surat</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_rekomendasi ? \Carbon\Carbon::parse($asesmen->tgl_rekomendasi)->format('d-m-Y') : '-' }}</p></div>
                            <div><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kewarganegaraan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->kewarganegaraan ?? '-' }}</p></div>
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Golongan Narkotika</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->nama_narkotika_medis ?? '-' }}</p></div>
                            
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">No. Keputusan Tim TAT</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->no_keputusan ?? '-' }}</p></div>
                            <div class="col-span-2"><span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Keputusan</span><p class="text-[13px] font-semibold text-slate-900">{{ $asesmen->tgl_keputusan ? \Carbon\Carbon::parse($asesmen->tgl_keputusan)->format('d-m-Y') : '-' }}</p></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-slate-50 border-l-4 border-purple-300 p-4 rounded-r-lg">
                                <span class="block text-[10px] font-bold text-purple-600 uppercase tracking-wider mb-1.5">Tentang Permohonan (Isi Surat)</span>
                                <div class="text-[12px] font-medium leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $asesmen->tentang_permohonan ?? '-' }}</div>
                            </div>
                            <div class="bg-slate-50 border-l-4 border-purple-300 p-4 rounded-r-lg">
                                <span class="block text-[10px] font-bold text-purple-600 uppercase tracking-wider mb-1.5">Keterangan Diagnosis Panjang</span>
                                <div class="text-[12px] font-medium leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $asesmen->keterangan_diagnosis ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Lama Waktu Perawatan</span>
                            <p class="text-[18px] font-extrabold text-rose-600 uppercase">{{ $asesmen->lama_perawatan ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <!-- EMPTY STATE REKOMENDASI -->
                    <div class="text-center py-6 border-2 border-dashed border-purple-200 rounded-xl bg-slate-50">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-[13px] font-bold text-purple-800 mb-1">Belum Ada Surat Rekomendasi</h3>
                        <p class="text-purple-600/70 text-[10px]">Silakan klik tombol Edit Form Rekom di atas untuk melengkapi data.</p>
                    </div>
                @endif
            </div>

        </div> <!-- END MAX-W-7XL -->
    </div> <!-- END PY-8 -->
</x-app-layout>