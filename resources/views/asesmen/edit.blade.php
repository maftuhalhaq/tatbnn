<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('asesmen.index') }}" class="text-slate-500 hover:text-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Edit Data Asesmen: {{ $asesmen->nama_lengkap }}</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui informasi klien, hasil TAT, atau hasil Case Conference.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('asesmen.update', $asesmen->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- SEKSI 1: ADMINISTRASI SURAT & REGISTRASI -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">1. Administrasi Surat & Registrasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Register</label>
                            <input type="text" name="no_register" value="{{ old('no_register', $asesmen->no_register) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No / BLN</label>
                            <input type="text" name="no_bln" value="{{ old('no_bln', $asesmen->no_bln) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Asal Pengajuan</label>
                            <input type="text" name="asal_pengajuan" value="{{ old('asal_pengajuan', $asesmen->asal_pengajuan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Surat Pengajuan</label>
                            <input type="text" name="no_surat_pengajuan" value="{{ old('no_surat_pengajuan', $asesmen->no_surat_pengajuan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. LKN / LP / LI</label>
                            <input type="text" name="no_lkn" value="{{ old('no_lkn', $asesmen->no_lkn) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Surat</label>
                            <input type="date" name="tgl_surat" value="{{ old('tgl_surat', $asesmen->tgl_surat) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Berkas Diterima</label>
                            <input type="date" name="tgl_berkas" value="{{ old('tgl_berkas', $asesmen->tgl_berkas) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Pelaksanaan</label>
                            <input type="date" name="tgl_pelaksanaan" value="{{ old('tgl_pelaksanaan', $asesmen->tgl_pelaksanaan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Penangkapan</label>
                            <input type="date" name="tgl_tangkap" value="{{ old('tgl_tangkap', $asesmen->tgl_tangkap) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: IDENTITAS KLIEN -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">2. Identitas Klien</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $asesmen->nama_lengkap) }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik', $asesmen->nik) }}" required maxlength="16" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $asesmen->no_hp) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $asesmen->tempat_lahir) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $asesmen->tgl_lahir) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="L" {{ old('jenis_kelamin', $asesmen->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                                <option value="P" {{ old('jenis_kelamin', $asesmen->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $asesmen->kewarganegaraan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Agama</label>
                            <input type="text" name="agama" value="{{ old('agama', $asesmen->agama) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pendidikan</label>
                            <select name="pendidikan_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Pendidikan --</option>
                                @foreach($masterPendidikan as $p)
                                    <option value="{{ $p->id }}" {{ old('pendidikan_id', $asesmen->pendidikan_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_pendidikan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pekerjaan</label>
                            <select name="pekerjaan_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($masterPekerjaan as $p)
                                    <option value="{{ $p->id }}" {{ old('pekerjaan_id', $asesmen->pekerjaan_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Penghasilan Rata-Rata</label>
                            <input type="text" name="penghasilan_rata_rata" value="{{ old('penghasilan_rata_rata', $asesmen->penghasilan_rata_rata) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Alamat KTP</label>
                            <textarea name="alamat_ktp" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('alamat_ktp', $asesmen->alamat_ktp) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('alamat_domisili', $asesmen->alamat_domisili) }}</textarea>
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
                                    <option value="{{ $n->id }}" {{ old('narkotika_id', $asesmen->narkotika_id) == $n->id ? 'selected' : '' }}>{{ $n->jenis_narkotika }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Berat Barang Bukti (Gram)</label>
                            <input type="number" step="0.01" name="berat_bb" value="{{ old('berat_bb', $asesmen->berat_bb) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status Hukum</label>
                            <input type="text" name="status_hukum" value="{{ old('status_hukum', $asesmen->status_hukum) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-slate-700">Deskripsi Barang Bukti</label>
                            <textarea name="deskripsi_bb" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('deskripsi_bb', $asesmen->deskripsi_bb) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pasal yang Disangkakan</label>
                            <input type="text" name="pasal_sangkaan" value="{{ old('pasal_sangkaan', $asesmen->pasal_sangkaan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Keterlibatan Jaringan</label>
                            <input type="text" name="keterlibatan_jaringan" value="{{ old('keterlibatan_jaringan', $asesmen->keterlibatan_jaringan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Cara Mendapatkan</label>
                            <input type="text" name="cara_mendapatkan" value="{{ old('cara_mendapatkan', $asesmen->cara_mendapatkan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Dapat Dari Siapa</label>
                            <input type="text" name="dapat_dari_siapa" value="{{ old('dapat_dari_siapa', $asesmen->dapat_dari_siapa) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Tes Urine</label>
                            <select name="tes_urine" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Hasil --</option>
                                <option value="Positif" {{ old('tes_urine', $asesmen->tes_urine) == 'Positif' ? 'selected' : '' }}>Positif</option>
                                <option value="Negatif" {{ old('tes_urine', $asesmen->tes_urine) == 'Negatif' ? 'selected' : '' }}>Negatif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SEKSI 4: HASIL TAT AWAL -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">4. Hasil Asesmen Awal (Rekap TAT)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Hukum</label>
                            <textarea name="hasil_asesmen_hukum" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('hasil_asesmen_hukum', $asesmen->hasil_asesmen_hukum) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Hasil Asesmen Medis</label>
                            <textarea name="hasil_asesmen_medis" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('hasil_asesmen_medis', $asesmen->hasil_asesmen_medis) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Rekomendasi TAT</label>
                            <select name="rekomendasi_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Rekomendasi --</option>
                                @foreach($masterRekomendasi as $r)
                                    <option value="{{ $r->id }}" {{ old('rekomendasi_id', $asesmen->rekomendasi_id) == $r->id ? 'selected' : '' }}>{{ $r->tempat_rehabilitasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status Pelaksanaan Rekomendasi</label>
                            <select name="pelaksanaan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="TIDAK" {{ old('pelaksanaan', $asesmen->pelaksanaan) == 'TIDAK' ? 'selected' : '' }}>Belum Dilaksanakan (TIDAK)</option>
                                <option value="YA" {{ old('pelaksanaan', $asesmen->pelaksanaan) == 'YA' ? 'selected' : '' }}>Sudah Dilaksanakan (YA)</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Keterangan Tambahan TAT</label>
                            <input type="text" name="keterangan_tambahan" value="{{ old('keterangan_tambahan', $asesmen->keterangan_tambahan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 5: ANALISIS CASE CONFERENCE -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200 shadow-sm">
                    <h3 class="text-lg font-bold text-blue-900 mb-4 border-b border-blue-300 pb-2">5. Hasil Sidang Case Conference</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Analisis Aspek Hukum</label>
                            <textarea name="aspek_hukum" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('aspek_hukum', $asesmen->aspek_hukum) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Analisis Aspek Medis</label>
                            <textarea name="aspek_medis" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('aspek_medis', $asesmen->aspek_medis) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kesehatan Fisik</label>
                            <textarea name="kesehatan_fisik" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('kesehatan_fisik', $asesmen->kesehatan_fisik) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Kondisi Psikologi</label>
                            <textarea name="psikologi" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('psikologi', $asesmen->psikologi) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:col-span-2">
                             <div>
                                <label class="block text-sm font-medium text-slate-700">Alasan Penggunaan</label>
                                <textarea name="alasan_penggunaan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('alasan_penggunaan', $asesmen->alasan_penggunaan) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Kondisi Keluarga</label>
                                <textarea name="kondisi_keluarga" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('kondisi_keluarga', $asesmen->kondisi_keluarga) }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tingkat Ketergantungan</label>
                            <input type="text" name="tingkat_ketergantungan" value="{{ old('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Pola Pemakaian</label>
                            <input type="text" name="pola_pemakaian" value="{{ old('pola_pemakaian', $asesmen->pola_pemakaian) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Kondisi Lingkungan</label>
                            <textarea name="kondisi_lingkungan" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('kondisi_lingkungan', $asesmen->kondisi_lingkungan) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Saran Case Conference</label>
                            <textarea name="saran_case_conference" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('saran_case_conference', $asesmen->saran_case_conference) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('asesmen.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" style="background-color: #3890f5;" class="px-6 py-2.5 text-white font-medium rounded-lg hover:opacity-90 transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>