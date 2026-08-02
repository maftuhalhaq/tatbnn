<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Tambah Data Asesmen & Case Conference</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('asesmen.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Bagian 1: Identitas Klien -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">1. Identitas Klien</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap *</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">NIK *</label>
                            <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Usia Saat Ini</label>
                            <select name="usia" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                <option value="">-- Pilih Usia --</option>
                                @for ($i = 1; $i <= 100; $i++)
                                    <option value="{{ $i }}" {{ old('usia') == $i ? 'selected' : '' }}>
                                        {{ $i }} Tahun
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_input" list="daftar_pendidikan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Pilih atau ketik baru (misal: SD - Tidak Lulus)" autocomplete="off">
                            <datalist id="daftar_pendidikan">
                                @foreach($pendidikans as $item)
                                    <option value="{{ $item->nama_pendidikan }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm" placeholder="Contoh: Kuli Bangunan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Penghasilan Rata-rata</label>
                            <input type="text" name="penghasilan_rata_rata" id="penghasilan_rupiah" value="{{ old('penghasilan_rata_rata', $asesmen->penghasilan_rata_rata ?? '') }}" placeholder="Contoh: Rp. 3.000.000" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat Sesuai KTP</label>
                            <textarea name="alamat_ktp" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alamat_ktp') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="2" placeholder="Kosongi jika sama dengan KTP" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alamat_domisili') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Data Administrasi Dokumen -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">2. Data Administrasi Dokumen</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Registrasi</label>
                            <input type="text" name="no_register" value="{{ old('no_register') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No / Bulan</label>
                            <input type="text" name="no_bln" value="{{ old('no_bln') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Surat Pengajuan</label>
                            <input type="text" name="no_surat_pengajuan" value="{{ old('no_surat_pengajuan') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. LKN</label>
                            <input type="text" name="no_lkn" value="{{ old('no_lkn') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Surat</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_surat" value="{{ old('tgl_surat') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Berkas Masuk</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_berkas" value="{{ old('tgl_berkas') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Pelaksanaan TAT</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_pelaksanaan" value="{{ old('tgl_pelaksanaan') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Asal Pengajuan</label>
                            <input type="text" name="asal_pengajuan" list="daftar_asal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" autocomplete="off">
                            <datalist id="daftar_asal">
                                @foreach($asal_pengajuans as $item)
                                    <option value="{{ $item->asal_pengajuan }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Data Perkara & Aspek Hukum -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">3. Data Perkara & Aspek Hukum</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Tangkap</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_tangkap" value="{{ old('tgl_tangkap') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Narkotika</label>
                            <select name="narkotika_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Jenis Narkotika --</option>
                                @foreach($narkotikas as $n)
                                    <option value="{{ $n->id }}" {{ old('narkotika_id') == $n->id ? 'selected' : '' }}>
                                        {{ $n->jenis_narkotika }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Berat Barang Bukti (Gram)</label>
                            <input type="text" name="berat_bb" value="{{ old('berat_bb') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Barang Bukti (Deskripsi)</label>
                            <input type="text" name="barang_bukti" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm" placeholder="Contoh: 1 klip sabu, 1 buah bong, HP Samsung">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pasal Disangkakan</label>
                            <input type="text" name="pasal_sangkaan" list="daftar_pasal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" autocomplete="off">
                            <datalist id="daftar_pasal">
                                @foreach($pasal_sangkaans as $item)
                                    <option value="{{ $item->pasal_sangkaan }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Tambahan Hukum Case Conference -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status Hukum</label>
                            <input type="text" name="status_hukum" value="{{ old('status_hukum') }}" placeholder="Tersangka / Terdakwa / dll" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Keterlibatan Jaringan</label>
                            <input type="text" name="keterlibatan_jaringan" value="{{ old('keterlibatan_jaringan') }}" placeholder="Ada / Tidak Ada" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Cara Mendapatkan</label>
                            <input type="text" name="cara_mendapatkan" value="{{ old('cara_mendapatkan') }}" placeholder="Membeli / Diberi" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Dapat Dari Siapa</label>
                            <input type="text" name="dapat_dari" value="{{ old('dapat_dari') }}" placeholder="Nama / Inisial / DPO" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Bagian 4: Data Medis & Psikososial -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">4. Data Medis & Psikososial</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kesehatan</label>
                            <textarea name="kesehatan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('kesehatan') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Psikologi</label>
                            <textarea name="psikologi" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('psikologi') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Tes Urine</label>
                            <input type="text" name="tes_urine" list="daftar_urine" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Contoh: Positif / Negatif" autocomplete="off">
                            <datalist id="daftar_urine">
                                @foreach($tes_urines as $item)
                                    <option value="{{ $item->tes_urine }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tingkat Ketergantungan</label>
                            <input type="text" name="tingkat_ketergantungan" value="{{ old('tingkat_ketergantungan') }}" placeholder="Ringan / Sedang / Berat" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pola Pemakaian</label>
                            <input type="text" name="pola_pemakaian" value="{{ old('pola_pemakaian') }}" placeholder="Rekreasional / Rutin" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alasan Penggunaan</label>
                            <textarea name="alasan_penggunaan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alasan_penggunaan') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kondisi Keluarga</label>
                            <textarea name="kondisi_keluarga" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('kondisi_keluarga') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kondisi Lingkungan</label>
                            <textarea name="kondisi_lingkungan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('kondisi_lingkungan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Bagian 5: Hasil Asesmen Terpadu & Rekomendasi -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">5. Hasil Asesmen Terpadu & Kesimpulan</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Hukum</label>
                            <input type="text" name="hasil_asesmen_hukum" list="daftar_hukum" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" autocomplete="off">
                            <datalist id="daftar_hukum">
                                @foreach($hasil_hukums as $item)
                                    <option value="{{ $item->hasil_asesmen_hukum }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Medis</label>
                            <input type="text" name="hasil_asesmen_medis" list="daftar_medis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" autocomplete="off">
                            <datalist id="daftar_medis">
                                @foreach($hasil_medis as $item)
                                    <option value="{{ $item->hasil_asesmen_medis }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Rekomendasi TAT</label>
                            <input type="text" name="rekomendasi_input" list="daftar_rekomendasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" autocomplete="off">
                            <datalist id="daftar_rekomendasi">
                                @foreach($rekomendasis as $item)
                                    <option value="{{ $item->tempat_rehabilitasi }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pelaksanaan Rekomendasi?</label>
                            <select name="pelaksanaan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="TIDAK" {{ old('pelaksanaan') == 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                                <option value="YA" {{ old('pelaksanaan') == 'YA' ? 'selected' : '' }}>YA</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Saran Case Conference</label>
                            <textarea name="saran" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('saran') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <a href="{{ route('asesmen.index') }}" class="inline-flex justify-center rounded-md border border-slate-300 bg-white py-2 px-4 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">Batal & Kembali</a>
                    <button type="submit" style="background-color: #3890f5;" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 text-sm font-medium text-white shadow-sm hover:opacity-90">Simpan Data Asesmen</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var penghasilanInput = document.getElementById('penghasilan_rupiah');

            if (penghasilanInput) {
                // 1. Format saat pengguna mengetik di form
                penghasilanInput.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, 'Rp. ');
                });

                // 2. Format saat halaman pertama kali dimuat (berguna untuk halaman Edit)
                if(penghasilanInput.value) {
                    penghasilanInput.value = formatRupiah(penghasilanInput.value, 'Rp. ');
                }
            }

            /* Fungsi utama untuk memformat angka menjadi Rupiah */
            function formatRupiah(angka, prefix) {
                // Hapus semua karakter selain angka dan koma
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // Tambahkan titik setiap 3 digit angka
                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

                // Kembalikan hasil dengan prefix 'Rp. ' jika ada nilainya
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
        });
    </script>
</x-app-layout>
