<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Surat Rekomendasi TAT</h2>
                <p class="text-sm text-slate-500 mt-1">Data Klien: <span class="font-semibold text-slate-700">{{ $asesmen->nama_lengkap }}</span></p>
            </div>
            <div>
                <a href="{{ route('asesmen.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-wider hover:bg-slate-50 transition shadow-sm">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Pembuatan Dokumen Surat Rekomendasi TAT</h3>
                <p class="text-sm text-slate-500 mb-6">Lengkapi data insidental di bawah ini. Data identitas klien akan diisikan secara otomatis ke dalam dokumen Word.</p>

                <!-- Data Preview (Autofill dari Database) -->
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3 pb-2 border-b border-blue-200">
                        <h4 class="text-sm font-bold text-blue-800 uppercase tracking-wider">Preview Data Otomatis</h4>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nama Lengkap</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">NIK</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->nik }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">No. Surat Pengajuan</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->no_surat_pengajuan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Jenis Narkotika</p>
                        <p class="font-medium text-slate-900">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-slate-500">Tempat Rehabilitasi</p>
                        <p class="font-medium text-purple-700">{{ $asesmen->rekomendasi->tempat_rehabilitasi ?? 'Belum ada data' }}</p>
                    </div>
                </div>

               <!-- Form Input Manual -->
                <form action="{{ route('asesmen.rekomendasi.unduh', $asesmen->id) }}" method="POST">
                    @csrf

                    <h4 class="text-md font-semibold text-slate-800 mb-4 border-b pb-2">1. Administrasi Surat</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nomor Surat Rekomendasi BNN *</label>
                            <input type="text" name="no_surat_rekomendasi" value="{{ old('no_surat_rekomendasi', $asesmen->no_surat_rekomendasi) }}" placeholder="Misal: R/397/VII/Ka/PB.06.00/2026/BNNK" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Pembuatan Surat *</label>
                            <!-- DIPERBARUI: class datepicker-id -->
                            <input type="text" name="tgl_rekomendasi" value="{{ old('tgl_rekomendasi', $asesmen->tgl_rekomendasi ?? date('Y-m-d')) }}" required class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tujuan Surat (Kepada Yth) *</label>
                            <input type="text" name="kepada_yth" list="list_kepada" value="{{ old('kepada_yth', $asesmen->kepada_yth) }}" placeholder="Misal: Kepala Kepolisian Resor Malang" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" autocomplete="off">
                            <datalist id="list_kepada">
                                @foreach($riwayat_kepada as $item) <option value="{{ $item->kepada_yth }}"></option> @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kewarganegaraan Klien</label>
                            <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $asesmen->kewarganegaraan ?? 'Indonesia (WNI)') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>

                    <h4 class="text-md font-semibold text-slate-800 mb-4 border-b pb-2">2. Dasar Hukum & Pengajuan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nomor Keputusan Penunjukan Tim TAT</label>
                            <input type="text" name="no_keputusan" list="list_nokeputusan" value="{{ old('no_keputusan', $asesmen->no_keputusan) }}" placeholder="Misal: KEP/10/IV/KA/KP/2026/BNN Kab. Malang" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" autocomplete="off">
                            <datalist id="list_nokeputusan">
                                @foreach($riwayat_no_keputusan as $item) <option value="{{ $item->no_keputusan }}"></option> @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Keputusan Penunjukan Tim TAT</label>
                            <!-- DIPERBARUI: class datepicker-id -->
                            <input type="text" name="tgl_keputusan" value="{{ old('tgl_keputusan', $asesmen->tgl_keputusan) }}" required class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Pilih Tanggal">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Tentang Permohonan (Isi Surat Pengajuan)</label>
                            <input type="text" name="tentang_permohonan" list="list_tentang" value="{{ old('tentang_permohonan', $asesmen->tentang_permohonan) }}" placeholder="Misal: Permohonan Bantuan Asesmen dalam Proses Hukum" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" autocomplete="off">
                            <datalist id="list_tentang">
                                @foreach($riwayat_tentang as $item) <option value="{{ $item->tentang_permohonan }}"></option> @endforeach
                            </datalist>
                        </div>
                    </div>

                    <h4 class="text-md font-semibold text-slate-800 mb-4 border-b pb-2">3. Diagnosis & Rekomendasi Medis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Golongan Narkotika (Medis)</label>
                            <input type="text" name="nama_narkotika_medis" list="list_narkotika_medis" value="{{ old('nama_narkotika_medis', $asesmen->nama_narkotika_medis) }}" placeholder="Misal: Metamphetamine" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" autocomplete="off">
                            <datalist id="list_narkotika_medis">
                                @foreach($riwayat_narkotika as $item) <option value="{{ $item->nama_narkotika_medis }}"></option> @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Lama Waktu Perawatan / Rehabilitasi</label>
                            <input type="text" name="lama_perawatan" list="list_perawatan" value="{{ old('lama_perawatan', $asesmen->lama_perawatan) }}" placeholder="Misal: 1 (satu) sampai 3 (tiga) bulan" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" autocomplete="off">
                            <datalist id="list_perawatan">
                                @foreach($riwayat_perawatan as $item) <option value="{{ $item->lama_perawatan }}"></option> @endforeach
                            </datalist>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Keterangan Diagnosis</label>
                            <input type="text" name="keterangan_diagnosis" list="list_diagnosis" value="{{ old('keterangan_diagnosis', $asesmen->keterangan_diagnosis) }}" placeholder="Misal: didiagnosis Gangguan Mental dan Perilaku..." required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" autocomplete="off">
                            <datalist id="list_diagnosis">
                                @foreach($riwayat_diagnosis as $item) <option value="{{ $item->keterangan_diagnosis }}"></option> @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 bg-slate-50 p-4 border border-slate-200 rounded-lg">
                        <button type="submit" style="background-color: #9333ea;" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg font-semibold text-white hover:opacity-90 focus:outline-none transition shadow-sm">
                            Generate Surat Rekomendasi (Word)
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
