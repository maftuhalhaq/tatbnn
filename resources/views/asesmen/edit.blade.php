<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Edit Data Asesmen & Case Conference</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('asesmen.update', $asesmen->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Bagian 1: Identitas Klien -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">1. Identitas Klien</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap *</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $asesmen->nama_lengkap) }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">NIK *</label>
                            <input type="text" name="nik" value="{{ old('nik', $asesmen->nik) }}" required maxlength="16" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $asesmen->tempat_lahir) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_lahir" value="{{ old('tgl_lahir', $asesmen->tgl_lahir) }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin', $asesmen->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $asesmen->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $asesmen->no_hp) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pendidikan Terakhir</label>
                            <select name="pendidikan_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Pendidikan --</option>
                                @foreach($pendidikans as $item)
                                    <option value="{{ $item->id }}" {{ (old('pendidikan_id', $asesmen->pendidikan_id) == $item->id) ? 'selected' : '' }}>
                                        {{ $item->nama_pendidikan ?? $item->nama ?? $item->jenis_pendidikan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pekerjaan</label>
                            <select name="pekerjaan_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($pekerjaans as $p)
                                    <option value="{{ $p->id }}" {{ (old('pekerjaan_id', $asesmen->pekerjaan_id) == $p->id) ? 'selected' : '' }}>
                                        {{ $p->nama_pekerjaan ?? $p->nama ?? $p->jenis_pekerjaan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Penghasilan Rata-rata</label>
                            <input type="text" name="penghasilan_rata_rata" id="penghasilan_rupiah" value="{{ old('penghasilan_rata_rata', $asesmen->penghasilan_rata_rata ?? '') }}" placeholder="Contoh: Rp. 3.000.000" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat Sesuai KTP</label>
                            <textarea name="alamat_ktp" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alamat_ktp', $asesmen->alamat_ktp) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alamat_domisili', $asesmen->alamat_domisili) }}</textarea>
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
                            <input type="text" name="no_register" value="{{ old('no_register', $asesmen->no_register) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No / Bulan</label>
                            <input type="text" name="no_bln" value="{{ old('no_bln', $asesmen->no_bln) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Surat Pengajuan</label>
                            <input type="text" name="no_surat_pengajuan" value="{{ old('no_surat_pengajuan', $asesmen->no_surat_pengajuan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. LKN</label>
                            <input type="text" name="no_lkn" value="{{ old('no_lkn', $asesmen->no_lkn) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Surat</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_surat" value="{{ old('tgl_surat', $asesmen->tgl_surat) }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Berkas Masuk</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_berkas" value="{{ old('tgl_berkas', $asesmen->tgl_berkas) }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Pelaksanaan TAT</label>
                            <!-- DIPERBARUI: type="text" dan class="datepicker-id" -->
                            <input type="text" name="tgl_pelaksanaan" value="{{ old('tgl_pelaksanaan', $asesmen->tgl_pelaksanaan) }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
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
                            <input type="text" name="tgl_tangkap" value="{{ old('tgl_tangkap', $asesmen->tgl_tangkap) }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Narkotika</label>
                            <select name="narkotika_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Jenis Narkotika --</option>
                                @foreach($narkotikas as $n)
                                    <option value="{{ $n->id }}" {{ (old('narkotika_id', $asesmen->narkotika_id) == $n->id) ? 'selected' : '' }}>
                                        {{ $n->jenis_narkotika }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Berat Barang Bukti (Gram)</label>
                            <input type="text" name="berat_bb" value="{{ old('berat_bb', $asesmen->berat_bb) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pasal Disangkakan</label>
                            <input type="text" name="pasal_sangkaan" value="{{ old('pasal_sangkaan', $asesmen->pasal_sangkaan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Tambahan Hukum Case Conference -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status Hukum</label>
                            <input type="text" name="status_hukum" value="{{ old('status_hukum', $asesmen->status_hukum) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Keterlibatan Jaringan</label>
                            <input type="text" name="keterlibatan_jaringan" value="{{ old('keterlibatan_jaringan', $asesmen->keterlibatan_jaringan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Cara Mendapatkan</label>
                            <input type="text" name="cara_mendapatkan" value="{{ old('cara_mendapatkan', $asesmen->cara_mendapatkan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Dapat Dari Siapa</label>
                            <input type="text" name="dapat_dari" value="{{ old('dapat_dari', $asesmen->dapat_dari) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            <textarea name="kesehatan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('kesehatan', $asesmen->kesehatan) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Psikologi</label>
                            <textarea name="psikologi" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('psikologi', $asesmen->psikologi) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Tes Urine</label>
                            <select name="tes_urine" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Hasil --</option>
                                <option value="Positif" {{ old('tes_urine', $asesmen->tes_urine) == 'Positif' ? 'selected' : '' }}>Positif (+)</option>
                                <option value="Negatif" {{ old('tes_urine', $asesmen->tes_urine) == 'Negatif' ? 'selected' : '' }}>Negatif (-)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tingkat Ketergantungan</label>
                            <input type="text" name="tingkat_ketergantungan" value="{{ old('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pola Pemakaian</label>
                            <input type="text" name="pola_pemakaian" value="{{ old('pola_pemakaian', $asesmen->pola_pemakaian) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Alasan Penggunaan</label>
                            <textarea name="alasan_penggunaan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alasan_penggunaan', $asesmen->alasan_penggunaan) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kondisi Keluarga</label>
                            <textarea name="kondisi_keluarga" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('kondisi_keluarga', $asesmen->kondisi_keluarga) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kondisi Lingkungan</label>
                            <textarea name="kondisi_lingkungan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('kondisi_lingkungan', $asesmen->kondisi_lingkungan) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Bagian 5: Hasil Asesmen Terpadu & Kesimpulan -->
                <div class="bg-white shadow-xs rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">5. Hasil Asesmen Terpadu & Kesimpulan</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Hukum</label>
                            <textarea name="hasil_asesmen_hukum" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('hasil_asesmen_hukum', $asesmen->hasil_asesmen_hukum) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Medis</label>
                            <textarea name="hasil_asesmen_medis" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('hasil_asesmen_medis', $asesmen->hasil_asesmen_medis) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Rekomendasi TAT</label>
                            <select name="rekomendasi_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Rekomendasi --</option>
                                @foreach($rekomendasis as $r)
                                    <option value="{{ $r->id }}" {{ (old('rekomendasi_id', $asesmen->rekomendasi_id) == $r->id) ? 'selected' : '' }}>
                                        {{ $r->tempat_rehabilitasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pelaksanaan Rekomendasi?</label>
                            <select name="pelaksanaan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="TIDAK" {{ old('pelaksanaan', $asesmen->pelaksanaan) == 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                                <option value="YA" {{ old('pelaksanaan', $asesmen->pelaksanaan) == 'YA' ? 'selected' : '' }}>YA</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan', $asesmen->keterangan) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Saran Case Conference</label>
                            <textarea name="saran" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('saran', $asesmen->saran) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Update -->
                <div class="flex justify-end gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <a href="{{ route('asesmen.index') }}" class="inline-flex justify-center rounded-md border border-slate-300 bg-white py-2 px-4 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">Batal & Kembali</a>
                    <button type="submit" style="background-color: #f59e0b;" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 text-sm font-medium text-white shadow-sm hover:opacity-90">Update Data Asesmen</button>
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
