<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Form Berita Acara Rapat TAT</h1>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data untuk mengisi variabel/placeholder pada template Berita Acara (.docx)</p>
            </div>
            <div>
                <a href="{{ route('asesmen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Ringkasan Biodata Klien (Read-Only) -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h2 class="text-lg font-semibold text-blue-900">Data Klien Terdaftar (Otomatis)</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 block">Nama Lengkap:</span>
                    <span class="font-bold text-gray-800">{{ $asesmen->nama_lengkap }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">No. Register:</span>
                    <span class="font-bold text-gray-800">{{ $asesmen->no_register ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Jenis Kelamin / Usia:</span>
                    <span class="font-bold text-gray-800">{{ $asesmen->jenis_kelamin }}, {{ $asesmen->umur ?? '-' }} Tahun</span>
                </div>
            </div>
        </div>

        <form action="{{ route('asesmen.berita-acara.generate', $asesmen->id) }}" method="POST">
            @csrf

            <!-- Card 1: Informasi Administrasi Rapat -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">1. Informasi Administrasi Rapat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="lg:col-span-2">
                        <label for="no_ba" class="block text-sm font-medium text-gray-700 mb-2">Nomor Berita Acara <span class="text-red-500">*</span></label>
                        <input type="text" name="no_ba" id="no_ba" value="{{ old('no_ba', 'BA-TAT/08/2026/BNNK') }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="hari_ba" class="block text-sm font-medium text-gray-700 mb-2">Hari Rapat <span class="text-red-500">*</span></label>
                        <input type="text" name="hari_ba" id="hari_ba" placeholder="Contoh: Minggu" value="{{ old('hari_ba') }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="tgl_ba" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rapat <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_ba" id="tgl_ba" value="{{ old('tgl_ba', date('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div class="lg:col-span-2">
                        <label for="tgl_sk_tim" class="block text-sm font-medium text-gray-700 mb-2">Tanggal SK Penunjukan Tim (KEP/10/IV...) <span class="text-red-500">*</span></label>
                        <input type="text" name="tgl_sk_tim" id="tgl_sk_tim" placeholder="Contoh: 14 April 2026" value="{{ old('tgl_sk_tim') }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div class="lg:col-span-2">
                        <label for="tgl_pelaksanaan_surat" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pelaksanaan Surat Asesmen <span class="text-red-500">*</span></label>
                        <input type="text" name="tgl_pelaksanaan_surat" id="tgl_pelaksanaan_surat" placeholder="Contoh: 01 Agustus 2026" value="{{ old('tgl_pelaksanaan_surat') }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Card 2: Susunan Tim Medis -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">2. Susunan Tim Medis</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Medis 1 -->
                    <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 space-y-4">
                        <h4 class="font-bold text-sm text-blue-800">Tim Medis 1</h4>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="med_1_nama" required class="w-full rounded-md border-gray-300 focus:border-blue-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIP</label>
                            <input type="text" name="med_1_nip" required class="w-full rounded-md border-gray-300 focus:border-blue-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jabatan</label>
                            <input type="text" name="med_1_jabatan" required class="w-full rounded-md border-gray-300 focus:border-blue-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                    </div>
                    <!-- Medis 2 -->
                    <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 space-y-4">
                        <h4 class="font-bold text-sm text-blue-800">Tim Medis 2</h4>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="med_2_nama" required class="w-full rounded-md border-gray-300 focus:border-blue-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">SIP</label>
                            <input type="text" name="med_2_sip" required class="w-full rounded-md border-gray-300 focus:border-blue-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jabatan</label>
                            <input type="text" name="med_2_jabatan" required class="w-full rounded-md border-gray-300 focus:border-blue-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Susunan Tim Hukum -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">3. Susunan Tim Hukum</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Hukum 1 -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                        <h4 class="font-bold text-sm text-gray-700">Tim Hukum 1</h4>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="huk_1_nama" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pangkat</label>
                            <input type="text" name="huk_1_pangkat" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIP / NRP</label>
                            <input type="text" name="huk_1_nip" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jabatan</label>
                            <input type="text" name="huk_1_jabatan" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                    </div>
                    <!-- Hukum 2 -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                        <h4 class="font-bold text-sm text-gray-700">Tim Hukum 2</h4>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="huk_2_nama" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pangkat</label>
                            <input type="text" name="huk_2_pangkat" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIP / NRP</label>
                            <input type="text" name="huk_2_nip" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jabatan</label>
                            <input type="text" name="huk_2_jabatan" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                    </div>
                    <!-- Hukum 3 -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                        <h4 class="font-bold text-sm text-gray-700">Tim Hukum 3</h4>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="huk_3_nama" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pangkat</label>
                            <input type="text" name="huk_3_pangkat" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIP / NRP</label>
                            <input type="text" name="huk_3_nip" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jabatan</label>
                            <input type="text" name="huk_3_jabatan" required class="w-full rounded-md border-gray-300 focus:border-gray-500 text-sm py-2 px-3 border shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Narasi Pemeriksaan Medis -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-2 pb-2 border-b border-gray-100">4. Hasil Pemeriksaan Tim Medis</h3>
                <div>
                    <label for="hasil_medis" class="block text-sm font-medium text-gray-700 mb-2">Narasi Hasil Medis <span class="text-red-500">*</span></label>
                    <textarea name="hasil_medis" id="hasil_medis" rows="4" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm p-3 border shadow-sm" placeholder="Contoh: Saat dilakukan asesmen medis klien dalam keadaan sadar dan kooperatif..."></textarea>
                </div>
            </div>

            <!-- Card 5: Narasi Pemeriksaan Hukum & Pasal -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-2 pb-2 border-b border-gray-100">5. Hasil Pemeriksaan Tim Hukum</h3>
                <div class="space-y-6">
                    <div>
                        <label for="pasal_sangkaan" class="block text-sm font-medium text-gray-700 mb-2">Pasal Sangkaan (Untuk Header) <span class="text-red-500">*</span></label>
                        <input type="text" name="pasal_sangkaan" id="pasal_sangkaan" placeholder="Contoh: Pasal 127 ayat (1) huruf a UU. RI. No. 35 tahun 2009" value="{{ old('pasal_sangkaan', $asesmen->pasal_sangkaan ?? '') }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="hasil_hukum" class="block text-sm font-medium text-gray-700 mb-2">Narasi Hukum <span class="text-red-500">*</span></label>
                        <textarea name="hasil_hukum" id="hasil_hukum" rows="6" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm p-3 border shadow-sm" placeholder="Contoh: Saat ini tersangka menyatakan penyesalannya dan bersedia mempertanggung jawabkan..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Card 6: Kelengkapan Alat Bukti & Surat -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">6. Kelengkapan Alat Bukti & Surat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="no_surat_narkoba" class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat Tes Narkoba / Urine <span class="text-red-500">*</span></label>
                        <input type="text" name="no_surat_narkoba" id="no_surat_narkoba" placeholder="Contoh: NAL/91283/w9020016" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="tgl_surat_narkoba" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Tes Narkoba <span class="text-red-500">*</span></label>
                        <input type="text" name="tgl_surat_narkoba" id="tgl_surat_narkoba" placeholder="Contoh: 02 Agustus 2026" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="dokter_penandatangan" class="block text-sm font-medium text-gray-700 mb-2">Dokter Penandatangan (Tanpa Gelar dr.) <span class="text-red-500">*</span></label>
                        <input type="text" name="dokter_penandatangan" id="dokter_penandatangan" placeholder="Contoh: agsutin" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="hasil_urine" class="block text-sm font-medium text-gray-700 mb-2">Hasil Pemeriksaan Urine <span class="text-red-500">*</span></label>
                        <input type="text" name="hasil_urine" id="hasil_urine" placeholder="Contoh: posith" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="hasil_lab_for" class="block text-sm font-medium text-gray-700 mb-2">Surat Keterangan Laboratorium Forensik</label>
                        <input type="text" name="hasil_lab_for" id="hasil_lab_for" placeholder="Contoh: hasil forensi" class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Card 7: Kesimpulan & Diagnosis -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">7. Kesimpulan Asesmen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="jenis_narkotika" class="block text-sm font-medium text-gray-700 mb-2">Jenis Narkotika Digunakan <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_narkotika" id="jenis_narkotika" placeholder="Contoh: sabut" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="pola_pemakaian" class="block text-sm font-medium text-gray-700 mb-2">Pola Pemakaian <span class="text-red-500">*</span></label>
                        <input type="text" name="pola_pemakaian" id="pola_pemakaian" placeholder="Contoh: Situasional" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm bg-white">
                    </div>
                    <div>
                        <label for="kategori_ketergantungan" class="block text-sm font-medium text-gray-700 mb-2">Kategori Ketergantungan <span class="text-red-500">*</span></label>
                        <input type="text" name="kategori_ketergantungan" id="kategori_ketergantungan" placeholder="Contoh: Ringan" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm bg-white">
                    </div>
                    <div>
                        <label for="diagnosis_medis" class="block text-sm font-medium text-gray-700 mb-2">Diagnosis Medis <span class="text-red-500">*</span></label>
                        <input type="text" name="diagnosis_medis" id="diagnosis_medis" placeholder="Contoh: iya ganguan mental" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                </div>
                <!-- Input Kalimat Kesimpulan Keterlibatan Jaringan -->
                <div>
                    <label for="kesimpulan_keterlibatan" class="block text-sm font-medium text-gray-700 mb-2">Pernyataan Keterlibatan Jaringan (Poin b) <span class="text-red-500">*</span></label>
                    <textarea name="kesimpulan_keterlibatan" id="kesimpulan_keterlibatan" rows="2" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm p-3 border shadow-sm">Bahwa belum didapatkan indikasi keterlibatan klien dalam jaringan peredaran gelap narkotika.</textarea>
                    <p class="text-xs text-gray-500 mt-1">Ubah teks ini jika klien terindikasi terlibat jaringan peredaran.</p>
                </div>
            </div>

            <!-- Card 8: Rekomendasi TAT -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">8. Rekomendasi Tim Asesmen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis_rehabilitasi" class="block text-sm font-medium text-gray-700 mb-2">Jenis Tindakan / Rehab <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_rehabilitasi" id="jenis_rehabilitasi" placeholder="Contoh: rehab saja" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div>
                        <label for="lama_rehabilitasi" class="block text-sm font-medium text-gray-700 mb-2">Durasi / Lama Rehabilitasi <span class="text-red-500">*</span></label>
                        <input type="text" name="lama_rehabilitasi" id="lama_rehabilitasi" placeholder="Contoh: 1 tahun" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="tempat_rehabilitasi" class="block text-sm font-medium text-gray-700 mb-2">Tempat / Lembaga <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_rehabilitasi" id="tempat_rehabilitasi" placeholder="Contoh: lembanga abg" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm py-2.5 px-3 border shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="flex items-center justify-end gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                <a href="{{ route('asesmen.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Generate Berita Acara (.docx)
                </button>
            </div>
        </form>
    </div>
</x-app-layout>