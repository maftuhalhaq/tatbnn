<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('asesmen.index') }}" class="text-slate-500 hover:text-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Tambah Data Asesmen & TAT</h2>
                <p class="text-sm text-slate-500 mt-1">Isi formulir secara lengkap. Kolom dengan tanda bintang (*) wajib diisi.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('asesmen.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- SEKSI 1: ADMINISTRASI SURAT & REGISTRASI -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">1. Administrasi Surat & Registrasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Register</label>
                            <input type="text" name="no_register" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No / BLN</label>
                            <input type="text" name="no_bln" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Asal Pengajuan</label>
                            <input type="text" name="asal_pengajuan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Surat Pengajuan</label>
                            <input type="text" name="no_surat_pengajuan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. LKN / LP / LI</label>
                            <input type="text" name="no_lkn" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Surat</label>
                            <input type="text" name="tgl_surat" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Berkas Diterima</label>
                            <input type="text" name="tgl_berkas" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Pelaksanaan</label>
                            <input type="text" name="tgl_pelaksanaan" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Penangkapan</label>
                            <input type="text" name="tgl_tangkap" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: IDENTITAS KLIEN -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">2. Identitas Klien</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" required maxlength="16" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Handphone</label>
                            <input type="text" name="no_hp" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                            <input type="text" name="tgl_lahir" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="L">Laki-Laki (L)</option>
                                <option value="P">Perempuan (P)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Agama</label>
                            <select name="agama" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pendidikan</label>
                            <!-- DIPERBARUI: Menggunakan text input biasa -->
                            <input type="text" name="pendidikan_input" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pekerjaan</label>
                            <!-- DIPERBARUI: Menggunakan text input biasa -->
                            <input type="text" name="pekerjaan_input" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Penghasilan Rata-Rata</label>
                            <input type="text" name="penghasilan_rata_rata" id="penghasilan_rupiah" placeholder="Misal: Rp 3.000.000" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Alamat KTP</label>
                            <textarea name="alamat_ktp" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEKSI 3: PERKARA HUKUM & BARANG BUKTI -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">3. Perkara Hukum & Kasus</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Narkotika</label>
                            <select name="narkotika_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Narkotika --</option>
                                @foreach($masterNarkotika as $n)
                                    <option value="{{ $n->id }}">{{ $n->jenis_narkotika }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Berat Barang Bukti (Gram)</label>
                            <input type="number" step="0.01" name="berat_bb" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status Hukum (Tersangka/Saksi)</label>
                            <input type="text" name="status_hukum" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-slate-700">Deskripsi Barang Bukti</label>
                            <textarea name="deskripsi_bb" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pasal yang Disangkakan</label>
                            <input type="text" name="pasal_sangkaan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Keterlibatan Jaringan</label>
                            <input type="text" name="keterlibatan_jaringan" placeholder="Ya / Tidak" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Cara Mendapatkan</label>
                            <input type="text" name="cara_mendapatkan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Dapat Dari Siapa</label>
                            <input type="text" name="dapat_dari_siapa" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Tes Urine</label>
                            <!-- DIPERBARUI: Menggunakan text input biasa -->
                            <input type="text" name="tes_urine" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 4: HASIL TAT AWAL -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">4. Hasil Asesmen Awal (Rekap TAT)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Hukum</label>
                            <textarea name="hasil_asesmen_hukum" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Medis</label>
                            <textarea name="hasil_asesmen_medis" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Rekomendasi TAT</label>
                            <!-- DIPERBARUI: Menggunakan text input biasa -->
                            <input type="text" name="rekomendasi_input" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status Pelaksanaan Rekomendasi</label>
                            <select name="pelaksanaan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="TIDAK">Belum Dilaksanakan (TIDAK)</option>
                                <option value="YA">Sudah Dilaksanakan (YA)</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Keterangan Tambahan TAT</label>
                            <input type="text" name="keterangan_tambahan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 5: ANALISIS CASE CONFERENCE (Bisa diisi belakangan) -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200 shadow-sm">
                    <h3 class="text-lg font-bold text-blue-900 mb-4 border-b border-blue-300 pb-2">5. Hasil Case Conference (Opsional saat daftar awal)</h3>
                    <p class="text-xs text-blue-700 mb-4">Bagian ini bisa dikosongkan saat pendaftaran awal dan diubah/diedit (Edit Data) setelah sidang Case Conference selesai.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Analisis Aspek Hukum</label>
                            <textarea name="aspek_hukum" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Analisis Aspek Medis</label>
                            <textarea name="aspek_medis" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kesehatan Fisik</label>
                            <textarea name="kesehatan_fisik" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kondisi Psikologi</label>
                            <textarea name="psikologi" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:col-span-2">
                             <div>
                                <label class="block text-sm font-medium text-slate-700">Alasan Penggunaan</label>
                                <textarea name="alasan_penggunaan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Kondisi Keluarga</label>
                                <textarea name="kondisi_keluarga" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tingkat Ketergantungan</label>
                            <input type="text" name="tingkat_ketergantungan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pola Pemakaian</label>
                            <input type="text" name="pola_pemakaian" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Kondisi Lingkungan</label>
                            <textarea name="kondisi_lingkungan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Saran Case Conference</label>
                            <textarea name="saran_case_conference" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('asesmen.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" style="background-color: #3890f5;" class="px-6 py-2.5 text-white font-medium rounded-lg hover:opacity-90 transition shadow-sm">
                        Simpan Data Asesmen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript untuk Format Rupiah -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var penghasilanInput = document.getElementById('penghasilan_rupiah');

            if (penghasilanInput) {
                penghasilanInput.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, 'Rp. ');
                });

                if(penghasilanInput.value) {
                    penghasilanInput.value = formatRupiah(penghasilanInput.value, 'Rp. ');
                }
            }

            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
        });
    </script>
</x-app-layout>
