<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-900 tracking-tight">
                Detail Asesmen: {{ $asesmen->nama_lengkap }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('asesmen.edit', $asesmen->id) }}" class="inline-flex justify-center rounded-md border border-transparent bg-amber-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:opacity-90">
                    Edit Data
                </a>
                <a href="{{ route('asesmen.index') }}" class="inline-flex justify-center rounded-md border border-slate-300 bg-white py-2 px-4 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Bagian 1: Identitas Klien -->
            <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-900">1. Identitas Klien</h3>
                </div>
                <div class="p-6 border-t border-slate-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $asesmen->nama_lengkap ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">NIK</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->nik ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Tempat, Tgl Lahir</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->tempat_lahir ?? '-' }}, {{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">No. Handphone</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->no_hp ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Pendidikan Terakhir</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->pendidikan->nama_pendidikan ?? $asesmen->pendidikan->nama ?? $asesmen->pendidikan->jenis_pendidikan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Pekerjaan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->pekerjaan->nama_pekerjaan ?? $asesmen->pekerjaan->nama ?? $asesmen->pekerjaan->jenis_pekerjaan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Penghasilan Rata-rata</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                            @if(is_numeric($asesmen->penghasilan_rata_rata))
                                Rp. {{ number_format($asesmen->penghasilan_rata_rata, 0, ',', '.') }}
                            @else
                                {{ $asesmen->penghasilan_rata_rata ?? '-' }}
                            @endif
                        </dd>
                        </div>
                        <div class="sm:col-span-3">
                            <dt class="text-sm font-medium text-slate-500">Alamat KTP</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->alamat_ktp ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-3">
                            <dt class="text-sm font-medium text-slate-500">Alamat Domisili</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->alamat_domisili ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Bagian 2: Data Administrasi Dokumen -->
            <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-900">2. Data Administrasi Dokumen</h3>
                </div>
                <div class="p-6 border-t border-slate-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">No. Registrasi</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->no_register ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">No / Bulan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->no_bln ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">No. Surat Pengajuan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->no_surat_pengajuan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">No. LKN</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->no_lkn ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Tanggal Surat</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Tgl Berkas Masuk</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Tgl Pelaksanaan TAT</dt>
                            <dd class="mt-1 text-sm text-slate-900 font-semibold">{{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Bagian 3: Data Perkara & Aspek Hukum -->
            <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-900">3. Data Perkara & Aspek Hukum</h3>
                </div>
                <div class="p-6 border-t border-slate-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Tanggal Tangkap</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Jenis Narkotika</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Berat Barang Bukti</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->berat_bb ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Pasal Disangkakan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->pasal_sangkaan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1 border-t border-slate-100 pt-4 mt-2">
                            <dt class="text-sm font-medium text-slate-500">Status Hukum</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->status_hukum ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1 border-t border-slate-100 pt-4 mt-2">
                            <dt class="text-sm font-medium text-slate-500">Keterlibatan Jaringan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->keterlibatan_jaringan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1 border-t border-slate-100 pt-4 mt-2">
                            <dt class="text-sm font-medium text-slate-500">Cara Mendapatkan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->cara_mendapatkan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1 border-t border-slate-100 pt-4 mt-2">
                            <dt class="text-sm font-medium text-slate-500">Dapat Dari Siapa</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->dapat_dari ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Bagian 4: Data Medis & Psikososial -->
            <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-900">4. Data Medis & Psikososial</h3>
                </div>
                <div class="p-6 border-t border-slate-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Hasil Tes Urine</dt>
                            <dd class="mt-1 text-sm font-bold {{ $asesmen->tes_urine == 'Positif' ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $asesmen->tes_urine ?? '-' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Tingkat Ketergantungan</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->tingkat_ketergantungan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-slate-500">Pola Pemakaian</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $asesmen->pola_pemakaian ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-3">
                            <dt class="text-sm font-medium text-slate-500">Kesehatan</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->kesehatan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-3">
                            <dt class="text-sm font-medium text-slate-500">Psikologi</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->psikologi ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-3 border-t border-slate-100 pt-4 mt-2">
                            <dt class="text-sm font-medium text-slate-500">Alasan Penggunaan</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->alasan_penggunaan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-3">
                            <dt class="text-sm font-medium text-slate-500">Kondisi Keluarga</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->kondisi_keluarga ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-3">
                            <dt class="text-sm font-medium text-slate-500">Kondisi Lingkungan</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->kondisi_lingkungan ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Bagian 5: Hasil Asesmen Terpadu & Kesimpulan -->
            <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-900">5. Hasil Asesmen Terpadu & Kesimpulan</h3>
                </div>
                <div class="p-6 border-t border-slate-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Hasil Asesmen Hukum</dt>
                            <dd class="mt-1 text-sm text-slate-900 bg-slate-50 p-3 rounded-md whitespace-pre-wrap">{{ $asesmen->hasil_asesmen_hukum ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Hasil Asesmen Medis</dt>
                            <dd class="mt-1 text-sm text-slate-900 bg-slate-50 p-3 rounded-md whitespace-pre-wrap">{{ $asesmen->hasil_asesmen_medis ?? '-' }}</dd>
                        </div>

                        <!-- Box Kesimpulan TAT -->
                        <div class="sm:col-span-2 border border-blue-200 bg-blue-50 p-4 rounded-lg mt-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-blue-800">Rekomendasi TAT / Tempat Rehabilitasi</dt>
                                    <dd class="mt-1 text-base font-bold text-blue-900">{{ $asesmen->rekomendasi->tempat_rehabilitasi ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-blue-800">Pelaksanaan Rekomendasi?</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $asesmen->pelaksanaan == 'YA' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $asesmen->pelaksanaan ?? '-' }}
                                        </span>
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2 border-t border-slate-100 pt-4 mt-2">
                            <dt class="text-sm font-medium text-slate-500">Keterangan Tambahan</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->keterangan ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Saran Case Conference</dt>
                            <dd class="mt-1 text-sm text-slate-900 whitespace-pre-wrap">{{ $asesmen->saran ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
