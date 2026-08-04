<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('asesmen.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Detail Asesmen: {{ $asesmen->nama_lengkap }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">NIK: {{ $asesmen->nik ?? '-' }} | No. Register: {{ $asesmen->no_register ?? '-' }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('asesmen.edit', $asesmen->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs rounded-lg uppercase tracking-wider transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data
                </a>
                <a href="{{ route('asesmen.cetakPdf', $asesmen->id) }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-lg uppercase tracking-wider transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card 1: Administrasi Surat -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4 border-b pb-2 flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    1. Administrasi Surat & Registrasi
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-slate-400 block text-xs">No. Register</span> <span class="font-semibold text-slate-800">{{ $asesmen->no_register ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">No / BLN</span> <span class="font-semibold text-slate-800">{{ $asesmen->no_bln ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Asal Pengajuan</span> <span class="font-semibold text-slate-800">{{ $asesmen->asal_pengajuan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">No. Surat Pengajuan</span> <span class="font-semibold text-slate-800">{{ $asesmen->no_surat_pengajuan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">No. LKN</span> <span class="font-semibold text-slate-800">{{ $asesmen->no_lkn ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Tanggal Surat</span> <span class="font-semibold text-slate-800">{{ $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->format('d-m-Y') : '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Tanggal Berkas Diterima</span> <span class="font-semibold text-slate-800">{{ $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('d-m-Y') : '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Tanggal Pelaksanaan</span> <span class="font-semibold text-slate-800">{{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('d-m-Y') : '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Tanggal Penangkapan</span> <span class="font-semibold text-slate-800">{{ $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->format('d-m-Y') : '-' }}</span></div>
                </div>
            </div>

            <!-- Card 2: Identitas Klien -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4 border-b pb-2 flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    2. Identitas Klien
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-slate-400 block text-xs">Nama Lengkap</span> <span class="font-semibold text-slate-800">{{ $asesmen->nama_lengkap }}</span></div>
                    <div><span class="text-slate-400 block text-xs">NIK</span> <span class="font-semibold text-slate-800">{{ $asesmen->nik }}</span></div>
                    <div><span class="text-slate-400 block text-xs">No. HP</span> <span class="font-semibold text-slate-800">{{ $asesmen->no_hp ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Tempat, Tanggal Lahir</span> <span class="font-semibold text-slate-800">{{ $asesmen->tempat_lahir ?? '-' }}, {{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->format('d-m-Y') : '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Jenis Kelamin</span> <span class="font-semibold text-slate-800">{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Kewarganegaraan</span> <span class="font-semibold text-slate-800">{{ $asesmen->kewarganegaraan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Agama</span> <span class="font-semibold text-slate-800">{{ $asesmen->agama ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Pendidikan</span> <span class="font-semibold text-slate-800">{{ $asesmen->pendidikan->nama_pendidikan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Pekerjaan</span> <span class="font-semibold text-slate-800">{{ $asesmen->pekerjaan->nama_pekerjaan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Penghasilan Rata-Rata</span> <span class="font-semibold text-slate-800">{{ $asesmen->penghasilan_rata_rata ?? '-' }}</span></div>
                    <div class="col-span-2"><span class="text-slate-400 block text-xs">Alamat KTP</span> <span class="font-semibold text-slate-800">{{ $asesmen->alamat_ktp ?? '-' }}</span></div>
                    <div class="col-span-2"><span class="text-slate-400 block text-xs">Alamat Domisili</span> <span class="font-semibold text-slate-800">{{ $asesmen->alamat_domisili ?? '-' }}</span></div>
                </div>
            </div>

            <!-- Card 3: Perkara Hukum & Barang Bukti -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4 border-b pb-2 flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    3. Perkara Hukum & Barang Bukti
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-slate-400 block text-xs">Jenis Narkotika</span> <span class="font-semibold text-slate-800">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Berat Barang Bukti</span> <span class="font-semibold text-slate-800">{{ $asesmen->berat_bb ? $asesmen->berat_bb . ' gram' : '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Status Hukum</span> <span class="font-semibold text-slate-800">{{ $asesmen->status_hukum ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Pasal Sangkaan</span> <span class="font-semibold text-slate-800">{{ $asesmen->pasal_sangkaan ?? '-' }}</span></div>
                    <div class="col-span-2"><span class="text-slate-400 block text-xs">Deskripsi Barang Bukti</span> <span class="font-semibold text-slate-800">{{ $asesmen->deskripsi_bb ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Keterlibatan Jaringan</span> <span class="font-semibold text-slate-800">{{ $asesmen->keterlibatan_jaringan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Cara Mendapatkan</span> <span class="font-semibold text-slate-800">{{ $asesmen->cara_mendapatkan ?? '-' }}</span></div>
                    <div><span class="text-slate-400 block text-xs">Dapat Dari Siapa</span> <span class="font-semibold text-slate-800">{{ $asesmen->dapat_dari_siapa ?? '-' }}</span></div>
                    <div>
                        <span class="text-slate-400 block text-xs">Hasil Tes Urine</span> 
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $asesmen->tes_urine == 'Positif' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $asesmen->tes_urine ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Hasil Asesmen Awal (TAT) -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4 border-b pb-2 flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    4. Hasil Asesmen Awal (Rekap TAT)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-400 block text-xs mb-1">Hasil Asesmen Hukum</span>
                        <div class="p-3 bg-slate-50 rounded-lg text-slate-700 whitespace-pre-line">{{ $asesmen->hasil_asesmen_hukum ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs mb-1">Hasil Asesmen Medis</span>
                        <div class="p-3 bg-slate-50 rounded-lg text-slate-700 whitespace-pre-line">{{ $asesmen->hasil_asesmen_medis ?? '-' }}</div>
                    </div>
                    <div><span class="text-slate-400 block text-xs">Rekomendasi TAT</span> <span class="font-bold text-blue-600">{{ $asesmen->rekomendasi->tempat_rehabilitasi ?? '-' }}</span></div>
                    <div>
                        <span class="text-slate-400 block text-xs">Status Pelaksanaan</span> 
                        <span class="font-bold {{ $asesmen->pelaksanaan == 'YA' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $asesmen->pelaksanaan == 'YA' ? 'Sudah Dilaksanakan (YA)' : 'Belum Dilaksanakan (TIDAK)' }}
                        </span>
                    </div>
                    <div class="md:col-span-2"><span class="text-slate-400 block text-xs">Keterangan Tambahan</span> <span class="font-semibold text-slate-800">{{ $asesmen->keterangan_tambahan ?? '-' }}</span></div>
                </div>
            </div>

            <!-- Card 5: Hasil Case Conference -->
            <div class="bg-blue-50/60 p-6 rounded-xl border border-blue-200 shadow-sm">
                <h3 class="text-base font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    5. Hasil Sidang Case Conference
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-500 block text-xs mb-1 font-semibold">Analisis Aspek Hukum</span>
                        <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->aspek_hukum ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs mb-1 font-semibold">Analisis Aspek Medis</span>
                        <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->aspek_medis ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs mb-1 font-semibold">Kesehatan Fisik</span>
                        <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->kesehatan_fisik ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs mb-1 font-semibold">Kondisi Psikologi</span>
                        <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->psikologi ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-500 block text-xs mb-1 font-semibold">Alasan Penggunaan</span>
                            <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->alasan_penggunaan ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-xs mb-1 font-semibold">Kondisi Keluarga</span>
                            <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->kondisi_keluarga ?? '-' }}</div>
                        </div>
                    </div>
                    <div><span class="text-slate-500 block text-xs">Tingkat Ketergantungan</span> <span class="font-semibold text-slate-800">{{ $asesmen->tingkat_ketergantungan ?? '-' }}</span></div>
                    <div><span class="text-slate-500 block text-xs">Pola Pemakaian</span> <span class="font-semibold text-slate-800">{{ $asesmen->pola_pemakaian ?? '-' }}</span></div>
                    <div class="md:col-span-2">
                        <span class="text-slate-500 block text-xs mb-1 font-semibold">Kondisi Lingkungan</span>
                        <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->kondisi_lingkungan ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-slate-500 block text-xs mb-1 font-semibold">Saran Case Conference</span>
                        <div class="p-3 bg-white rounded-lg text-slate-700 border border-blue-100 whitespace-pre-line">{{ $asesmen->saran_case_conference ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>