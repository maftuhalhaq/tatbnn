<x-app-layout>
    <!-- CSS Custom untuk Scrollbar Dropdown -->
    <style>
        .custom-select-scroll::-webkit-scrollbar { width: 6px; }
        .custom-select-scroll::-webkit-scrollbar-track { background: #f8fafc; border-radius: 8px; }
        .custom-select-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Animasi Indikator Autosave */
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .autosave-active { animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>

    <div class="py-8 sm:py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('asesmen.index') }}" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider mb-1">
                            Formulir Registrasi
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            Tambah Data Klien TAT
                            <span id="autosaveIndicator" class="hidden text-[10px] font-bold text-emerald-600 bg-emerald-100 border border-emerald-200 px-2 py-1 rounded-md uppercase tracking-wider autosave-active">
                                Tersimpan Otomatis
                            </span>
                        </h1>
                    </div>
                </div>
                <p class="text-sm text-slate-500 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm hidden md:block">
                    Kolom dengan tanda <span class="text-rose-500 font-bold">*</span> wajib diisi.
                </p>
            </div>

            <!-- Banner Pesan Peringatan -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div><h4 class="text-sm font-extrabold text-rose-800">Peringatan Sistem!</h4><p class="text-[13px] font-semibold text-rose-600 mt-1">{{ session('error') }}</p></div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-sm font-extrabold text-rose-800">Gagal Menyimpan Data!</h4>
                        <ul class="text-[13px] font-semibold text-rose-600 mt-1 list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form id="formTambahKlien" action="{{ route('asesmen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- ========================================== -->
                <!-- CARD 1: PROFIL KLIEN -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden z-30">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        1. Profil Klien
                    </h3>

                    <!-- Foto Upload -->
                    <div class="mb-5 bg-slate-50 p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-3">Pas Foto Klien <span class="text-slate-400 font-normal lowercase">(Opsional)</span></label>
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <div class="shrink-0 w-24 h-32 bg-white rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden relative group shadow-sm">
                                <img id="previewFoto" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                                <svg id="iconPlaceholder" class="w-8 h-8 text-slate-300 group-hover:text-blue-400 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div class="w-full">
                                <input type="file" name="foto_klien" id="foto_klien" accept="image/jpeg, image/png, image/jpg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-all cursor-pointer" onchange="previewImage(event)">
                                <p class="text-[11px] font-medium text-slate-400 mt-2 leading-relaxed">Format yang didukung: JPG, JPEG, PNG. Tidak ada batasan ukuran file.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Identitas Dasar -->
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nama Lengkap <span class="text-rose-500">*</span></label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"></div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">NIK KTP <span class="text-rose-500">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" class="block w-full rounded-xl {{ $errors->has('nik') ? 'border-rose-500 ring-2 ring-rose-200 bg-rose-50 text-rose-900' : 'border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-slate-50' }} sm:text-sm focus:bg-white" placeholder="16 Digit NIK">
                        </div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label><select name="jenis_kelamin" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"><option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option><option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option></select></div>
                        
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal Lahir</label><input type="text" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}" class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Usia <span class="text-blue-500 ml-1 lowercase font-normal">(Saat Didaftarkan)</span></label><input type="text" name="usia" id="usia" value="{{ old('usia') }}" class="block w-full rounded-xl border-slate-200 shadow-sm sm:text-sm bg-blue-50 text-blue-800 font-bold pointer-events-none" placeholder="0 Tahun" readonly></div>
                        
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kewarganegaraan</label><input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia (WNI)') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Agama</label><select name="agama" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"><option value="">-- Pilih Agama --</option><option value="Islam">Islam</option><option value="Kristen Protestan">Kristen Protestan</option><option value="Katolik">Katolik</option><option value="Hindu">Hindu</option><option value="Buddha">Buddha</option><option value="Konghucu">Konghucu</option></select></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pekerjaan</label><input type="text" name="pekerjaan_input" value="{{ old('pekerjaan_input') }}" list="listPekerjaan" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"></div>

                        <!-- Dropdown Pendidikan dengan Modal JS -->
                        <div class="md:col-span-2 lg:col-span-1">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pendidikan</label>
                            <div class="flex gap-2">
                                <select name="pendidikan_input" id="selectPendidikan" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white">
                                    <option value="">-- Pilih --</option>
                                    @foreach($masterPendidikan ?? [] as $p)
                                        @if(trim($p->nama_pendidikan) !== '') <option value="{{ $p->nama_pendidikan }}" {{ old('pendidikan_input') == $p->nama_pendidikan ? 'selected' : '' }}>{{ $p->nama_pendidikan }}</option> @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openModalTambahPendidikan()" class="shrink-0 px-3 py-2 bg-blue-100 text-blue-700 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider rounded-xl hover:bg-blue-200">+ Tambah</button>
                                <button type="button" onclick="openModalKelolaPendidikan()" class="shrink-0 px-3 py-2 bg-slate-100 text-slate-700 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider rounded-xl hover:bg-slate-200">Kelola</button>
                            </div>
                        </div>

                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Penghasilan Rata-Rata</label><input type="text" name="penghasilan_rata_rata" id="penghasilan_rupiah" value="{{ old('penghasilan_rata_rata') }}" placeholder="Misal: Rp 3.000.000" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">No. Handphone</label><input type="text" name="no_hp" value="{{ old('no_hp') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm bg-slate-50 focus:bg-white"></div>
                    </div>

                    <!-- BLOK ALAMAT KTP & DOMISILI -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6 pt-6 border-t border-slate-200">
                        <!-- ALAMAT KTP (JS Otomatis vs Manual) -->
                        <div class="space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                            <h4 class="text-sm font-extrabold text-slate-800 border-b border-slate-200 pb-2">Alamat Sesuai KTP</h4>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 p-3 bg-white rounded-lg border border-slate-200 shadow-sm">
                                <label class="inline-flex items-center cursor-pointer"><input type="radio" name="mode_ktp" value="otomatis" checked class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeKtp()"><span class="ml-2 text-xs font-bold text-slate-700">Otomatis (Kab. Malang)</span></label>
                                <label class="inline-flex items-center cursor-pointer"><input type="radio" name="mode_ktp" value="manual" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeKtp()"><span class="ml-2 text-xs font-bold text-slate-700">Manual (Luar)</span></label>
                            </div>
                            <div id="blok_otomatis_ktp" class="space-y-4">
                                <div><label class="block font-medium text-sm text-gray-700">Jalan / RT RW</label><input type="text" id="jalan_ktp" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 sm:text-sm" placeholder="Jl. Diponegoro RT 14 RW 02" autocomplete="off" /></div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Desa/Kelurahan</label>
                                        <div class="flex gap-2 mt-1">
                                            <select id="tipe_desa_ktp" class="w-[45%] border-slate-300 rounded-md shadow-sm text-sm"><option value="Desa">Desa</option><option value="Kelurahan">Kelurahan</option></select>
                                            <div class="relative w-[55%]">
                                                <input type="text" id="desa_ktp" class="w-full border-slate-300 rounded-md shadow-sm text-sm pr-8 cursor-pointer" placeholder="Ketik/Pilih..." autocomplete="off">
                                                <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer text-slate-400 toggle-dropdown-ktp"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                                <div id="list_desa_ktp" class="hidden absolute z-[60] w-full mt-1 bg-white border border-slate-200 shadow-lg rounded-md py-1 max-h-48 overflow-y-auto custom-select-scroll"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div><label class="block font-medium text-sm text-gray-700">Kecamatan</label><select id="kecamatan_ktp" class="border-slate-300 rounded-md shadow-sm mt-1 w-full text-sm"><option value="">-- Pilih --</option><option value="Ampelgading">Ampelgading</option><option value="Bantur">Bantur</option><option value="Bululawang">Bululawang</option><option value="Dampit">Dampit</option><option value="Dau">Dau</option><option value="Donomulyo">Donomulyo</option><option value="Gedangan">Gedangan</option><option value="Gondanglegi">Gondanglegi</option><option value="Jabung">Jabung</option><option value="Kalipare">Kalipare</option><option value="Karangploso">Karangploso</option><option value="Kasembon">Kasembon</option><option value="Kepanjen">Kepanjen</option><option value="Kromengan">Kromengan</option><option value="Lawang">Lawang</option><option value="Ngajum">Ngajum</option><option value="Ngantang">Ngantang</option><option value="Pagak">Pagak</option><option value="Pagelaran">Pagelaran</option><option value="Pakis">Pakis</option><option value="Pakisaji">Pakisaji</option><option value="Poncokusumo">Poncokusumo</option><option value="Pujon">Pujon</option><option value="Singosari">Singosari</option><option value="Sumbermanjing Wetan">Sumbermanjing Wetan</option><option value="Sumberpucung">Sumberpucung</option><option value="Tajinan">Tajinan</option><option value="Tirtoyudo">Tirtoyudo</option><option value="Tumpang">Tumpang</option><option value="Turen">Turen</option><option value="Wagir">Wagir</option><option value="Wajak">Wajak</option><option value="Wonosari">Wonosari</option></select></div>
                                    <div><label class="block font-medium text-sm text-gray-700">Kota/Kabupaten</label><input type="text" id="kabupaten_ktp" class="border-slate-300 rounded-md shadow-sm mt-1 w-full text-sm bg-slate-100 font-semibold pointer-events-none" value="Kabupaten Malang" readonly></div>
                                </div>
                            </div>
                            <div id="blok_manual_ktp" class="hidden space-y-4">
                                <div><label class="block font-medium text-sm text-gray-700">Ketik Lengkap (Luar Daerah)</label><textarea id="manual_ktp" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm sm:text-sm"></textarea></div>
                            </div>
                            <div class="mt-auto pt-4 border-t border-slate-200">
                                <label class="block font-bold text-sm text-indigo-600">Hasil Rakitan</label>
                                <input type="hidden" name="alamat_ktp" id="hidden_alamat_ktp" value="{{ old('alamat_ktp') }}">
                                <textarea id="preview_alamat_ktp" rows="2" class="mt-1 block w-full text-[13px] bg-indigo-50 border-indigo-200 rounded-md font-bold text-indigo-800 resize-none cursor-not-allowed" disabled></textarea>
                            </div>
                        </div>

                        <!-- ALAMAT DOMISILI -->
                        <div class="space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                                <h4 class="text-sm font-extrabold text-slate-800">Alamat Domisili</h4>
                                <label class="inline-flex items-center cursor-pointer bg-white px-2 py-1 rounded border border-slate-200"><input type="checkbox" id="copy_alamat" class="rounded text-indigo-600 w-3.5 h-3.5" onchange="salinAlamat()"><span class="ml-2 text-[10px] font-bold text-slate-600">SAMA DGN KTP</span></label>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 p-3 bg-white rounded-lg border border-slate-200 shadow-sm">
                                <label class="inline-flex items-center cursor-pointer"><input type="radio" name="mode_domisili" value="otomatis" checked class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeDomisili()"><span class="ml-2 text-xs font-bold text-slate-700">Otomatis (Kab. Malang)</span></label>
                                <label class="inline-flex items-center cursor-pointer"><input type="radio" name="mode_domisili" value="manual" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeDomisili()"><span class="ml-2 text-xs font-bold text-slate-700">Manual (Luar)</span></label>
                            </div>
                            <div id="blok_otomatis_domisili" class="space-y-4">
                                <div><label class="block font-medium text-sm text-gray-700">Jalan / RT RW</label><input type="text" id="jalan_domisili" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 sm:text-sm" autocomplete="off" /></div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Desa/Kelurahan</label>
                                        <div class="flex gap-2 mt-1">
                                            <select id="tipe_desa_domisili" class="w-[45%] border-slate-300 rounded-md shadow-sm text-sm"><option value="Desa">Desa</option><option value="Kelurahan">Kelurahan</option></select>
                                            <div class="relative w-[55%]">
                                                <input type="text" id="desa_domisili" class="w-full border-slate-300 rounded-md shadow-sm text-sm pr-8 cursor-pointer" autocomplete="off">
                                                <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer text-slate-400 toggle-dropdown-dom"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                                <div id="list_desa_domisili" class="hidden absolute z-[60] w-full mt-1 bg-white border border-slate-200 shadow-lg rounded-md py-1 max-h-48 overflow-y-auto custom-select-scroll"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div><label class="block font-medium text-sm text-gray-700">Kecamatan</label><select id="kecamatan_domisili" class="border-slate-300 rounded-md shadow-sm mt-1 w-full text-sm"><option value="">-- Pilih --</option><option value="Ampelgading">Ampelgading</option><option value="Bantur">Bantur</option><option value="Bululawang">Bululawang</option><option value="Dampit">Dampit</option><option value="Dau">Dau</option><option value="Donomulyo">Donomulyo</option><option value="Gedangan">Gedangan</option><option value="Gondanglegi">Gondanglegi</option><option value="Jabung">Jabung</option><option value="Kalipare">Kalipare</option><option value="Karangploso">Karangploso</option><option value="Kasembon">Kasembon</option><option value="Kepanjen">Kepanjen</option><option value="Kromengan">Kromengan</option><option value="Lawang">Lawang</option><option value="Ngajum">Ngajum</option><option value="Ngantang">Ngantang</option><option value="Pagak">Pagak</option><option value="Pagelaran">Pagelaran</option><option value="Pakis">Pakis</option><option value="Pakisaji">Pakisaji</option><option value="Poncokusumo">Poncokusumo</option><option value="Pujon">Pujon</option><option value="Singosari">Singosari</option><option value="Sumbermanjing Wetan">Sumbermanjing Wetan</option><option value="Sumberpucung">Sumberpucung</option><option value="Tajinan">Tajinan</option><option value="Tirtoyudo">Tirtoyudo</option><option value="Tumpang">Tumpang</option><option value="Turen">Turen</option><option value="Wagir">Wagir</option><option value="Wajak">Wajak</option><option value="Wonosari">Wonosari</option></select></div>
                                    <div><label class="block font-medium text-sm text-gray-700">Kota/Kabupaten</label><input type="text" id="kabupaten_domisili" class="border-slate-300 rounded-md shadow-sm mt-1 w-full text-sm bg-slate-100 font-semibold pointer-events-none" value="Kabupaten Malang" readonly></div>
                                </div>
                            </div>
                            <div id="blok_manual_domisili" class="hidden space-y-4">
                                <div><label class="block font-medium text-sm text-gray-700">Ketik Lengkap (Luar Daerah)</label><textarea id="manual_domisili" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm sm:text-sm"></textarea></div>
                            </div>
                            <div class="mt-auto pt-4 border-t border-slate-200">
                                <label class="block font-bold text-sm text-indigo-600">Hasil Rakitan</label>
                                <input type="hidden" name="alamat_domisili" id="hidden_alamat_domisili" value="{{ old('alamat_domisili') }}">
                                <textarea id="preview_alamat_domisili" rows="2" class="mt-1 block w-full text-[13px] bg-indigo-50 border-indigo-200 rounded-md font-bold text-indigo-800 resize-none cursor-not-allowed" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 2: DATA ADMINISTRASI -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        2. Data Administrasi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">No / Bulan</label><input type="text" name="no_bln" value="{{ old('no_bln') }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Asal Pengajuan</label><input type="text" name="asal_pengajuan" value="{{ old('asal_pengajuan') }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Surat</label><input type="text" name="tgl_surat" value="{{ old('tgl_surat') }}" class="datepicker-id w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Tgl Berkas Diterima</label><input type="text" name="tgl_berkas" value="{{ old('tgl_berkas') }}" class="datepicker-id w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Tgl Pelaksanaan TAT</label><input type="text" name="tgl_pelaksanaan" value="{{ old('tgl_pelaksanaan') }}" class="datepicker-id w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">No. Surat Pengajuan</label><input type="text" name="no_surat_pengajuan" value="{{ old('no_surat_pengajuan') }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">No. LKN / LP / LI</label><input type="text" name="no_lkn" value="{{ old('no_lkn') }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Penangkapan</label><input type="text" name="tgl_tangkap" value="{{ old('tgl_tangkap') }}" class="datepicker-id w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">No. Register</label><input type="text" name="no_register" value="{{ old('no_register') }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 3: CASE CONFERENCE -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden z-20">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-amber-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        3. Case Conference
                    </h3>

                    <!-- BLOK HUKUM & BB -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Status Hukum</label><input type="text" name="status_hukum" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Keterlibatan Jaringan</label><select name="keterlibatan_jaringan" class="w-full rounded-xl border-slate-300 text-sm"><option value="">-- Pilih --</option><option value="Ya">Ya</option><option value="Tidak">Tidak</option></select></div>
                        
                        <!-- Checkbox Multi Narkotika -->
                        <div class="relative" id="narkotika-wrapper">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Zat / Narkotika</label>
                            <button type="button" onclick="toggleNarkotika()" class="w-full text-left bg-white border border-slate-300 rounded-xl px-4 py-2.5 flex justify-between sm:text-sm">
                                <span id="narkotika-text" class="text-slate-500 truncate">-- Pilih Zat --</span>
                                <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="narkotika-dropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-slate-200 shadow-xl rounded-xl py-2 max-h-56 overflow-y-auto custom-select-scroll">
                                @foreach($masterNarkotika ?? [] as $n)
                                    <label class="flex items-center px-4 py-2.5 hover:bg-amber-50 cursor-pointer border-b border-slate-50">
                                        <input type="checkbox" name="narkotika_id[]" value="{{ $n->id }}" onchange="updateNarkotikaText()" class="narkotika-cb w-4 h-4 text-amber-500 rounded border-slate-300" data-label="{{ $n->jenis_narkotika }}">
                                        <span class="ml-3 text-sm font-medium">{{ $n->jenis_narkotika }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Berat Bukti (BB)</label><input type="number" step="0.01" name="berat_bb" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div class="md:col-span-2"><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Detail Barang Bukti</label><input type="text" name="deskripsi_bb" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Cara Dapat</label><input type="text" name="cara_mendapatkan" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Dapat Dari</label><input type="text" name="dapat_dari_siapa" class="w-full rounded-xl border-slate-300 text-sm"></div>
                    </div>

                    <!-- BLOK ANALISIS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 pt-5 border-t border-slate-100">
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Analisis Aspek Hukum</label><textarea name="aspek_hukum" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Fisik & Medis</label><textarea name="kesehatan_fisik" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Psikologi</label><textarea name="psikologi" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Hasil Tes Urine</label><input type="text" name="tes_urine" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div class="md:col-span-2"><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Analisis Aspek Medis</label><textarea name="aspek_medis" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                    </div>

                    <!-- BLOK POLA & REKOM -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 pt-5 border-t border-slate-100">
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Alasan Penggunaan</label><textarea name="alasan_penggunaan" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Kondisi Keluarga</label><textarea name="kondisi_keluarga" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div class="md:col-span-2"><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Kondisi Lingkungan</label><textarea name="kondisi_lingkungan" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Tingkat Ketergantungan</label><input type="text" name="tingkat_ketergantungan" class="w-full rounded-xl border-slate-300 text-sm"></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Pola Pemakaian</label><input type="text" name="pola_pemakaian" class="w-full rounded-xl border-slate-300 text-sm"></div>
                    </div>

                    <!-- REKOMENDASI TAT -->
                    <div class="pt-5 border-t border-slate-100">
                        <label class="block text-[12px] font-bold text-emerald-700 uppercase mb-2">Rekomendasi TAT (Instansi)</label>
                        <div class="flex flex-wrap gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl mb-3">
                            <label class="inline-flex"><input type="radio" name="kategori_rekomendasi" value="Rawat Jalan" class="w-4 h-4" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700">Rawat Jalan</span></label>
                            <label class="inline-flex"><input type="radio" name="kategori_rekomendasi" value="Rawat Inap" class="w-4 h-4" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700">Rawat Inap</span></label>
                            <label class="inline-flex"><input type="radio" name="kategori_rekomendasi" value="Rehab di Lapas / Rutan" class="w-4 h-4" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700">Rehab di Lapas / Rutan</span></label>
                            <label class="inline-flex"><input type="radio" name="kategori_rekomendasi" value="Tidak Rehab (Proses Hukum)" class="w-4 h-4" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700">Tidak Rehab (Proses Hukum)</span></label>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 mb-4">
                            <select id="selectTempatRekomendasi" disabled class="w-full rounded-xl border-slate-300 bg-slate-100 cursor-not-allowed text-sm" onchange="updateRekomendasiPreview()">
                                <option value="">-- Pilih --</option>
                                <option value="Bawaan Sistem" data-kategori="Rawat Jalan" style="display:none;" disabled>Hanya Rawat Jalan (Tanpa Instansi)</option>
                                <option value="Bawaan Sistem" data-kategori="Rawat Inap" style="display:none;" disabled>Hanya Rawat Inap (Tanpa Instansi)</option>
                                <option value="Bawaan Sistem" data-kategori="Rehab di Lapas / Rutan" style="display:none;" disabled>Hanya Rehab di Lapas / Rutan (Tanpa Instansi)</option>
                                <option value="Bawaan Sistem" data-kategori="Tidak Rehab (Proses Hukum)" style="display:none;" disabled>Hanya Tidak Rehab (Tanpa Instansi)</option>
                                @foreach($masterRekomendasi ?? [] as $r)
                                    @php
                                        $kat = 'Semua'; $nm = $r->nama_rekomendasi;
                                        if(str_starts_with($nm, 'Rawat Jalan - ')){ $kat='Rawat Jalan'; $nm=substr($nm,14); }
                                        elseif(str_starts_with($nm, 'Rawat Inap - ')){ $kat='Rawat Inap'; $nm=substr($nm,13); }
                                        elseif(str_starts_with($nm, 'Rehab di Lapas / Rutan - ')){ $kat='Rehab di Lapas / Rutan'; $nm=substr($nm,25); }
                                        elseif(str_starts_with($nm, 'Tidak Rehab (Proses Hukum) - ')){ $kat='Tidak Rehab (Proses Hukum)'; $nm=substr($nm,29); }
                                    @endphp
                                    <option value="{{ trim($nm) }}" data-kategori="{{ $kat }}" style="display:none;" disabled>{{ trim($nm) }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="btnTambahRekomendasi" disabled onclick="openModalTambahRekomendasi()" class="px-4 py-2 bg-slate-200 rounded-xl text-xs font-bold">+ Tambah</button>
                            <button type="button" id="btnKelolaRekomendasi" disabled onclick="openModalKelolaRekomendasi()" class="px-4 py-2 bg-slate-200 rounded-xl text-xs font-bold">Kelola</button>
                        </div>
                        <input type="hidden" name="rekomendasi_input" id="hidden_rekomendasi_input">
                        
                        <div class="mb-4">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Keterangan Tambahan TAT</label>
                            <textarea name="keterangan_tambahan" rows="2" class="w-full rounded-xl border-slate-300 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Saran Sidang Case Conference</label>
                            <input type="hidden" name="saran_case_conference" id="hidden_saran_cc">
                            <div class="grid grid-cols-1 gap-2">
                                <input type="text" class="saran-cc-input w-full rounded-xl border-slate-300 text-sm" placeholder="1. ...">
                                <input type="text" class="saran-cc-input w-full rounded-xl border-slate-300 text-sm" placeholder="2. ...">
                                <input type="text" class="saran-cc-input w-full rounded-xl border-slate-300 text-sm" placeholder="3. ...">
                                <input type="text" class="saran-cc-input w-full rounded-xl border-slate-300 text-sm" placeholder="4. ...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 4: HASIL ASESMEN FINAL & PELAKSANAAN -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-400"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        4. Hasil Asesmen Final & Pelaksanaan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Pasal Yang Disangkakan</label>
                            <input type="text" id="display_pasal_sangkaan" class="w-full rounded-xl border-slate-200 bg-slate-100 font-bold text-slate-500 cursor-not-allowed text-sm" readonly placeholder="Otomatis dari input di atas">
                        </div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Hasil Asesmen Hukum</label><textarea name="hasil_asesmen_hukum" rows="3" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Hasil Rujukan Medis</label><textarea name="hasil_asesmen_medis" rows="3" class="w-full rounded-xl border-slate-300 text-sm"></textarea></div>
                        <div class="md:col-span-2"><label class="block text-[12px] font-bold text-slate-700 uppercase mb-1">Status Pelaksanaan Rekomendasi</label><select name="pelaksanaan" class="w-full rounded-xl border-slate-300 text-sm"><option value="">-- Pilih Status --</option><option value="YA">YA (Dilaksanakan)</option><option value="TIDAK">TIDAK (Belum/Batal)</option></select></div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 pb-10">
                    <a href="{{ route('asesmen.index') }}" class="px-8 py-3.5 bg-white border border-slate-300 rounded-xl text-slate-700 font-bold hover:bg-slate-50 transition-all">Batal</a>
                    <button type="submit" class="px-8 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg">Simpan Data Klien</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH & KELOLA PENDIDIKAN & REKOMENDASI (TETAP DIPERTAHANKAN) -->
    <div id="modalTambahPendidikan" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center opacity-0"><div class="bg-white w-full max-w-md p-6 rounded-3xl"><h3 class="text-xl font-bold mb-4">Tambah Pendidikan</h3><input type="text" id="inputPendidikanBaru" class="w-full rounded-xl border-slate-300 mb-4"><div class="flex justify-end gap-2"><button onclick="closeModalTambahPendidikan()" class="px-4 py-2 border rounded-xl">Batal</button><button onclick="tambahPendidikanJS(event)" class="px-4 py-2 bg-blue-600 text-white rounded-xl">Simpan</button></div></div></div>
    <div id="modalKelolaPendidikan" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center opacity-0"><div class="bg-white w-full max-w-md p-6 rounded-3xl"><h3 class="text-xl font-bold mb-4">Kelola Pendidikan</h3><div id="listKelolaPendidikan" class="max-h-40 overflow-auto mb-4"></div><button onclick="closeModalKelolaPendidikan()" class="px-4 py-2 border rounded-xl w-full">Tutup</button></div></div>
    
    <div id="modalTambahRekomendasi" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center opacity-0"><div class="bg-white w-full max-w-md p-6 rounded-3xl"><h3 class="text-xl font-bold mb-4">Tambah Rekomendasi</h3><span id="tambahKategoriDisplay" class="font-bold text-indigo-600 block mb-2"></span><input type="text" id="inputRekomendasiDetail" class="w-full rounded-xl border-slate-300 mb-4"><div class="flex justify-end gap-2"><button onclick="closeModalTambahRekomendasi()" class="px-4 py-2 border rounded-xl">Batal</button><button onclick="tambahRekomendasiJS(event)" class="px-4 py-2 bg-slate-800 text-white rounded-xl">Simpan</button></div></div></div>
    <div id="modalKelolaRekomendasi" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center opacity-0"><div class="bg-white w-full max-w-md p-6 rounded-3xl"><h3 class="text-xl font-bold mb-4" id="modalKelolaRekomendasiTitle">Kelola Rekomendasi</h3><div id="listKelolaRekomendasi" class="max-h-40 overflow-auto mb-4"></div><button onclick="closeModalKelolaRekomendasi(true)" class="px-4 py-2 border rounded-xl w-full">Tutup</button></div></div>

    <script>
        // === SCRIPT JAVASCRIPT LENGKAP TETAP ADA DI SINI ===
        const daftarKelurahan = [ "Ardirejo", "Candirenggo", "Cepokomulyo", "Dampit", "Kalirejo", "Kepanjen", "Lawang", "Losari", "Pagentan", "Penarukan", "Sedayu", "Turen" ];
        const daftarDesa = [ "Amadanom", "Ampeldento (Karangploso)", "Ampeldento (Pakis)", "Ampelgading", "Ardimulyo", "Argosari", "Argosuko", "Argotirto", "Argoyuwono", "Arjosari", "Arjowilangun", "Asrikaton", "Babadan", "Bakalan", "Balearjo", "Balesari", "Bambang", "Bandungrejo", "Bangelan", "Banjararum", "Banjarejo (Donomulyo)", "Banjarejo (Ngantang)", "Banjarejo (Pagelaran)", "Banjarejo (Pakis)", "Banjarsari", "Bantur", "Banturejo", "Baturetno (Dampit)", "Baturetno (Singosari)", "Bayem", "Bedali", "Belung", "Bendosari", "Benjor", "Blayu", "Bocek", "Bokor", "Bringin", "Brongkal", "Bululawang", "Bulupitu", "Bumirejo", "Bunutwetan", "Clumprit", "Codo", "Curungrejo", "Dadapan", "Dalisodo", "Dawuhan", "Dengkol", "Dilem", "Donomulyo", "Donowarih", "Druju", "Duwet", "Duwet Krajan", "Gading", "Gadingkembar", "Gadingkulon", "Gadungsari", "Gajahrejo", "Gampingan", "Ganjaran", "Gedangan", "Gedog Kulon", "Gedog Wetan", "Genengan", "Girimoyo", "Girimulyo", "Glanggang", "Gondanglegi Kulon", "Gondanglegi Wetan", "Gondowangi", "Gubukklakah", "Gunung Jati", "Gunungrejo", "Gunungronggo", "Gunungsari", "Harjokuncaran", "Jabung", "Jambangan", "Jambearjo", "Jambesari", "Jambuwer", "Jatiguwi", "Jatikerto", "Jatirejoyoso", "Jatisari (Pakisaji)", "Jatisari (Tajinan)", "Jedong", "Jenggolo", "Jeru (Tumpang)", "Jeru (Turen)", "Jogomulyan", "Jombok", "Kademangan", "Kaliasri", "Kalipare", "Kalirejo", "Kalisongo", "Kambingan", "Kanigoro", "Karanganyar", "Karangduren", "Karangkates", "Karangnongko", "Karangpandan", "Karangrejo", "Karangsari", "Karangsuko", "Karangwidoro", "Kasembon (Bululawang)", "Kasembon (Kasembon)", "Kasri", "Kaumrejo", "Kebobang", "Kebonagung", "Kedok", "Kedungbanteng", "Kedungpedaringan", "Kedungrejo", "Kedungsalam", "Kemantren", "Kemiri (Jabung)", "Kemiri (Kepanjen)", "Kemulan", "Kendalpayak", "Kenongo", "Kepatihan", "Kepuharjo", "Kesamben", "Ketawang", "Ketindan", "Kidal", "Kidangbang", "Klampok", "Klepu", "Kluwut", "Kranggan", "Krebet", "Krebet Senggrong", "Kromengan", "Kucur", "Kuwolu", "Landungsari", "Lang-Lang", "Lebakharjo", "Lumbangsari", "Madiredo", "Maguan", "Majangtengah", "Malangsuko", "Mangliawan", "Mangunrejo", "Mendalanwangi", "Mentaraman", "Mojosari", "Mulyoagung", "Mulyoarjo", "Mulyoasri", "Mulyorejo", "Ngabab", "Ngadas", "Ngadilangkung", "Ngadirejo (Jabung)", "Ngadirejo (Kromengan)", "Ngadireso", "Ngajum", "Ngantru", "Ngasem", "Ngawonggo", "Ngebruk (Poncokusumo)", "Ngebruk (Sumberpucung)", "Ngembal", "Ngenep", "Ngijo", "Ngingit", "Ngroto", "Pagak", "Pagedangan", "Pagelaran", "Pagersari", "Pait", "Pajaran", "Pakisaji", "Pakisjajar", "Pakiskembar", "Palaan", "Pamotan", "Pandanajeng", "Pandanlandung", "Pandanmulyo", "Pandanrejo (Pagak)", "Pandanrejo (Wagir)", "Pandansari (Ngantang)", "Pandansari (Poncokusumo)", "Pandansari Lor", "Pandesari", "Panggungrejo (Gondanglegi)", "Panggungrejo (Kepanjen)", "Parangargo", "Patokpicis", "Peniwen", "Permanu", "Petungsewu (Dau)", "Petungsewu (Wagir)", "Plandi", "Plaosan", "Pojok", "Poncokusumo", "Pondokagung", "Pringgodani", "Pringu", "Pucangsongo", "Pujiharjo", "Pujon Kidul", "Pujon Lor", "Pulungdowo", "Purwoasri", "Purwodadi (Donomulyo)", "Purwodadi (Tirtoyudo)", "Purwoharjo", "Purworejo (Donomulyo)", "Purworejo (Ngantang)", "Purwosekar", "Putat Kidul", "Putat Lor", "Putukrejo (Gondanglegi)", "Putukrejo (Kalipare)", "Randuagung", "Randugading", "Rejosari", "Rejoyoso", "Rembun", "Ringinkembar", "Ringinsari", "Sambigede", "Sanankerto", "Sananrejo", "Saptorenggo", "Sawahan", "Segaran", "Sekarbanyu", "Sekarpuro", "Selorejo", "Sempalwadak", "Sempol", "Senggreng", "Sengguruh", "Sepanjang", "Sidoasri", "Sidodadi (Gedangan)", "Sidodadi (Lawang)", "Sidodadi (Ngantang)", "Sidoluhur", "Sidomulyo", "Sidorahayu", "Sidorejo (Jabung)", "Sidorejo (Pagelaran)", "Sidorenggo", "Simojayan", "Sindurejo", "Sitiarjo", "Sitirejo", "Slamet", "Slamparejo", "Slorok", "Sonowangi", "Srigading", "Srigonco", "Srimulyo", "Sudimoro", "Sukoanyar (Pakis)", "Sukoanyar (Wajak)", "Sukodadi", "Sukodono", "Sukolilo (Jabung)", "Sukolilo (Wajak)", "Sukomulyo", "Sukonolo", "Sukopuro", "Sukoraharjo", "Sukorejo (Gondanglegi)", "Sukorejo (Tirtoyudo)", "Sukosari (Gondanglegi)", "Sukosari (Kasembon)", "Sukowilangun", "Sumberagung (Ngantang)", "Sumberagung (Sumbermanjing Wetan)", "Sumberbening", "Sumberdem", "Sumberejo (Gedangan)", "Sumberejo (Pagak)", "Sumberejo (Poncokusumo)", "Sumberjaya", "Sumberkerto", "Sumberkradenan", "Sumbermanjing Kulon", "Sumbermanjing Wetan", "Sumberngepoh", "Sumberoto", "Sumberpasir", "Sumberpetung", "Sumberporong", "Sumberpucung", "Sumberputih", "Sumbersekar", "Sumbersuko (Dampit)", "Sumbersuko (Tajinan)", "Sumbersuko (Wagir)", "Sumbertangkil", "Sumbertempur", "Sutojayan", "Suwaru", "Taji", "Tajinan", "Talangagung", "Talangsuko", "Talok", "Tamanasri", "Tamanharjo", "Tamankuncaran", "Tamansari", "Tamansatriyan", "Tambakasri (Sumbermanjing Wetan)", "Tambakasri (Tajinan)", "Tambakrejo", "Tanggung", "Tangkilsari", "Tawangagung", "Tawangargo", "Tawangrejeni", "Tawangsari", "Tegalgondo", "Tegalrejo", "Tegalsari", "Tegalweru", "Tempursari", "Ternyang", "Tirtomarto", "Tirtomoyo (Ampelgading)", "Tirtomoyo (Pakis)", "Tirtoyudo", "Tlogorejo", "Tlogosari (Donomulyo)", "Tlogosari (Tirtoyudo)", "Toyomarto", "Tulungrejo (Donomulyo)", "Tulungrejo (Ngantang)", "Tulusbesar", "Tumpakrejo (Gedangan)", "Tumpakrejo (Kalipare)", "Tumpang", "Tumpukrenteng", "Tunjungtirto", "Turirejo", "Undaan", "Urek-Urek", "Wadung", "Wajak", "Wandanpuro", "Watugede", "Waturejo", "Wirotaman", "Wiyurejo", "Wonoagung (Kasembon)", "Wonoagung (Tirtoyudo)", "Wonoayu", "Wonokerso", "Wonokerto", "Wonomulyo", "Wonorejo (Bantur)", "Wonorejo (Lawang)", "Wonorejo (Poncokusumo)", "Wonorejo (Singosari)", "Wonosari", "Wringinanom", "Wringinsongo" ];
        function initSearchableDropdown(inputId, listId, tipeId, updateFunc) {
            const input = document.getElementById(inputId); const list = document.getElementById(listId); const tipe = document.getElementById(tipeId); const toggleIcon = list.previousElementSibling;
            function render(filter = '') {
                list.innerHTML = ''; const isKelurahan = tipe.value === 'Kelurahan'; const data = isKelurahan ? daftarKelurahan : daftarDesa;
                const filtered = data.filter(item => item.toLowerCase().includes(filter.toLowerCase()));
                if (filtered.length === 0) { list.innerHTML = '<div class="px-3 py-2 text-slate-400 italic text-xs">Tidak ditemukan</div>'; return; }
                filtered.forEach(item => {
                    const div = document.createElement('div'); div.className = 'px-3 py-2 hover:bg-indigo-50 cursor-pointer text-slate-700 border-b border-slate-50 last:border-0'; div.textContent = item;
                    div.onclick = function() { input.value = item; list.classList.add('hidden'); updateFunc(); }; list.appendChild(div);
                });
            }
            input.addEventListener('focus', function() { render(this.value); list.classList.remove('hidden'); });
            input.addEventListener('input', function() { render(this.value); list.classList.remove('hidden'); updateFunc(); });
            toggleIcon.addEventListener('click', function(e) { e.stopPropagation(); if (list.classList.contains('hidden')) { render(input.value); list.classList.remove('hidden'); input.focus(); } else { list.classList.add('hidden'); } });
            tipe.addEventListener('change', function() { input.value = ''; render(); updateFunc(); });
        }
        document.addEventListener('click', function(e) {
            ['list_desa_ktp', 'list_desa_domisili'].forEach(id => {
                const list = document.getElementById(id); const inputId = id.replace('list_', ''); const input = document.getElementById(inputId);
                if (list && input) { const toggleIcon = list.previousElementSibling; if (!input.contains(e.target) && !list.contains(e.target) && !toggleIcon.contains(e.target)) { list.classList.add('hidden'); } }
            });
        });
        function toggleModeKtp() {
            const mode = document.querySelector('input[name="mode_ktp"]:checked').value;
            if (mode === 'otomatis') { document.getElementById('blok_otomatis_ktp').classList.remove('hidden'); document.getElementById('blok_manual_ktp').classList.add('hidden'); } 
            else { document.getElementById('blok_otomatis_ktp').classList.add('hidden'); document.getElementById('blok_manual_ktp').classList.remove('hidden'); }
            updateAlamatKtp();
        }
        function toggleModeDomisili() {
            const mode = document.querySelector('input[name="mode_domisili"]:checked').value;
            if (mode === 'otomatis') { document.getElementById('blok_otomatis_domisili').classList.remove('hidden'); document.getElementById('blok_manual_domisili').classList.add('hidden'); } 
            else { document.getElementById('blok_otomatis_domisili').classList.add('hidden'); document.getElementById('blok_manual_domisili').classList.remove('hidden'); }
            updateAlamatDomisili();
        }
        function salinAlamat() {
            const isChecked = document.getElementById('copy_alamat').checked;
            if (isChecked) {
                const modeKtp = document.querySelector('input[name="mode_ktp"]:checked').value; document.querySelector(`input[name="mode_domisili"][value="${modeKtp}"]`).checked = true; toggleModeDomisili();
                if (modeKtp === 'otomatis') {
                    document.getElementById('jalan_domisili').value = document.getElementById('jalan_ktp').value; document.getElementById('tipe_desa_domisili').value = document.getElementById('tipe_desa_ktp').value;
                    document.getElementById('desa_domisili').value = document.getElementById('desa_ktp').value; document.getElementById('kecamatan_domisili').value = document.getElementById('kecamatan_ktp').value;
                } else { document.getElementById('manual_domisili').value = document.getElementById('manual_ktp').value; }
            } else {
                document.getElementById('jalan_domisili').value = ''; document.getElementById('desa_domisili').value = ''; document.getElementById('kecamatan_domisili').value = ''; document.getElementById('manual_domisili').value = '';
            }
            updateAlamatDomisili(); saveFormDraft();
        }

        // Multi Select Narkotika
        function toggleNarkotika() { document.getElementById('narkotika-dropdown').classList.toggle('hidden'); }
        function updateNarkotikaText() {
            const checkboxes = document.querySelectorAll('.narkotika-cb:checked'); const textSpan = document.getElementById('narkotika-text');
            if (checkboxes.length === 0) { textSpan.textContent = '-- Pilih Zat --'; textSpan.classList.remove('text-slate-900', 'font-bold'); textSpan.classList.add('text-slate-500'); } 
            else { textSpan.textContent = Array.from(checkboxes).map(cb => cb.getAttribute('data-label')).join(', '); textSpan.classList.add('text-slate-900', 'font-bold'); textSpan.classList.remove('text-slate-500'); }
            saveFormDraft();
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('narkotika-wrapper'); const dropdown = document.getElementById('narkotika-dropdown');
            if (wrapper && !wrapper.contains(e.target)) dropdown.classList.add('hidden');
        });

        const saranInputs = document.querySelectorAll('.saran-cc-input');
        function updateSaranHidden() {
            let arr = []; saranInputs.forEach(input => { if(input.value.trim() !== '') arr.push(input.value.trim()); });
            document.getElementById('hidden_saran_cc').value = arr.join(', '); saveFormDraft();
        }
        saranInputs.forEach(i => i.addEventListener('input', updateSaranHidden));

        const mainForm = document.getElementById('formTambahKlien'); const autosaveIndicator = document.getElementById('autosaveIndicator');
        function saveFormDraft() {
            if(!mainForm) return; const formData = new FormData(mainForm); const data = {};
            formData.forEach((value, key) => { if(key !== '_token' && key !== '_method' && key !== 'foto_klien' && !key.includes('narkotika_id[]')) data[key] = value; });
            localStorage.setItem('formKlienDraft', JSON.stringify(data));
            if(autosaveIndicator) { autosaveIndicator.classList.remove('hidden'); setTimeout(() => autosaveIndicator.classList.add('hidden'), 3000); }
        }
        function loadFormDraft() {
            const draft = localStorage.getItem('formKlienDraft');
            if (draft && mainForm) {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    const els = mainForm.querySelectorAll(`[name="${key}"]`);
                    if (els.length > 0) {
                        if (els[0].type === 'radio' || els[0].type === 'checkbox') { const t = mainForm.querySelector(`[name="${key}"][value="${data[key]}"]`); if (t) t.checked = true; } 
                        else { els[0].value = data[key]; }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadFormDraft();
            
            const hdSaran = document.getElementById('hidden_saran_cc');
            if(hdSaran && hdSaran.value) {
                const spl = hdSaran.value.split(',');
                saranInputs.forEach((el, idx) => { el.value = spl[idx] ? spl[idx].trim() : ''; });
            }

            toggleModeKtp(); toggleModeDomisili(); updateNarkotikaText();

            // Link BB dan Pasal
            const inBB = document.querySelector('input[name="berat_bb"]'); const outBB = document.getElementById('display_berat_bb');
            if(inBB) { inBB.addEventListener('input', function(){ if(outBB) outBB.value = this.value ? this.value + ' Gr' : ''; }); if(inBB.value && outBB) outBB.value = inBB.value + ' Gr'; }
            const inPasal = document.querySelector('input[name="pasal_sangkaan"]'); const outPasal = document.getElementById('display_pasal_sangkaan');
            if(inPasal) { inPasal.addEventListener('input', function(){ if(outPasal) outPasal.value = this.value; }); if(inPasal.value && outPasal) outPasal.value = inPasal.value; }

            initSearchableDropdown('desa_ktp', 'list_desa_ktp', 'tipe_desa_ktp', updateAlamatKtp);
            initSearchableDropdown('desa_domisili', 'list_desa_domisili', 'tipe_desa_domisili', updateAlamatDomisili);

            const pr = document.getElementById('penghasilan_rupiah');
            if (pr) { pr.addEventListener('keyup', function() { this.value = formatRupiah(this.value, 'Rp. '); }); if(pr.value) pr.value = formatRupiah(pr.value, 'Rp. '); }

            const tglLahir = document.getElementById('tgl_lahir');
            if(tglLahir) { tglLahir.addEventListener('change', hitungUsia); tglLahir.addEventListener('blur', hitungUsia); hitungUsia(); }
            let lastTgl = tglLahir ? tglLahir.value : '';
            setInterval(() => { let c = tglLahir ? tglLahir.value : ''; if(c !== lastTgl){ lastTgl=c; hitungUsia(); } }, 500);

            if(mainForm) {
                mainForm.addEventListener('input', saveFormDraft); mainForm.addEventListener('change', saveFormDraft);
                mainForm.addEventListener('submit', function() { updateAlamatKtp(); updateAlamatDomisili(); updateRekomendasiPreview(); localStorage.removeItem('formKlienDraft'); });
            }
        });

        function formatRupiah(angka, prefix) {
            let num = angka.replace(/[^,\d]/g, '').toString(), spl = num.split(','), sisa = spl[0].length % 3, rup = spl[0].substr(0, sisa), rib = spl[0].substr(sisa).match(/\d{3}/gi);
            if (rib) { let sep = sisa ? '.' : ''; rup += sep + rib.join('.'); } rup = spl[1] != undefined ? rup + ',' + spl[1] : rup; return prefix == undefined ? rup : (rup ? 'Rp. ' + rup : '');
        }

        function hitungUsia() {
            const tl = document.getElementById('tgl_lahir').value.trim(); const us = document.getElementById('usia');
            if (!tl) { us.value = ''; return; }
            let rd = tl.split(' ')[0], pts = rd.split('-'), dob;
            if (pts.length === 3) { let p0 = pts[0], p2 = pts[2]; if (p0.length === 4) dob = new Date(parseInt(p0), parseInt(pts[1]) - 1, parseInt(p2)); else if (p2.length === 4) dob = new Date(parseInt(p2), parseInt(pts[1]) - 1, parseInt(p0)); }
            if (!dob || isNaN(dob.getTime())) dob = new Date(rd);
            if (isNaN(dob.getTime())) return;
            let rd2 = new Date(); let age = rd2.getFullYear() - dob.getFullYear(); let m = rd2.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && rd2.getDate() < dob.getDate())) age--;
            if(age >= 0) us.value = age + ' Tahun';
        }

        function updateAlamatKtp() {
            const mode = document.querySelector('input[name="mode_ktp"]:checked').value; let res = '';
            if (mode === 'otomatis') {
                let parts = []; const j = document.getElementById('jalan_ktp').value.trim(); if(j) parts.push(j);
                const d = document.getElementById('desa_ktp').value.trim(); if(d) parts.push(document.getElementById('tipe_desa_ktp').value + ' ' + d);
                const c = document.getElementById('kecamatan_ktp').value; if(c) parts.push('Kecamatan ' + c);
                const k = document.getElementById('kabupaten_ktp').value; if(k) parts.push(k); res = parts.join(', ');
            } else res = document.getElementById('manual_ktp').value.trim();
            document.getElementById('preview_alamat_ktp').value = res; document.getElementById('hidden_alamat_ktp').value = res;
        }
        function updateAlamatDomisili() {
            const mode = document.querySelector('input[name="mode_domisili"]:checked').value; let res = '';
            if (mode === 'otomatis') {
                let parts = []; const j = document.getElementById('jalan_domisili').value.trim(); if(j) parts.push(j);
                const d = document.getElementById('desa_domisili').value.trim(); if(d) parts.push(document.getElementById('tipe_desa_domisili').value + ' ' + d);
                const c = document.getElementById('kecamatan_domisili').value; if(c) parts.push('Kecamatan ' + c);
                const k = document.getElementById('kabupaten_domisili').value; if(k) parts.push(k); res = parts.join(', ');
            } else res = document.getElementById('manual_domisili').value.trim();
            document.getElementById('preview_alamat_domisili').value = res; document.getElementById('hidden_alamat_domisili').value = res;
        }
        ['jalan_ktp', 'kecamatan_ktp'].forEach(id => { const el = document.getElementById(id); if(el) { el.addEventListener('input', updateAlamatKtp); el.addEventListener('change', updateAlamatKtp); } });
        ['jalan_domisili', 'kecamatan_domisili'].forEach(id => { const el = document.getElementById(id); if(el) { el.addEventListener('input', updateAlamatDomisili); el.addEventListener('change', updateAlamatDomisili); } });
        document.getElementById('manual_ktp').addEventListener('input', updateAlamatKtp); document.getElementById('manual_domisili').addEventListener('input', updateAlamatDomisili);

        function previewImage(event) {
            const input = event.target; const preview = document.getElementById('previewFoto'); const placeholder = document.getElementById('iconPlaceholder');
            if (input.files && input.files[0]) { const reader = new FileReader(); reader.onload = function(e) { preview.src = e.target.result; preview.classList.remove('hidden'); placeholder.classList.add('hidden'); }; reader.readAsDataURL(input.files[0]); } 
            else { preview.src = '#'; preview.classList.add('hidden'); placeholder.classList.remove('hidden'); }
        }

        // Rekomendasi TAT
        function toggleRekomendasi() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked'); const select = document.getElementById('selectTempatRekomendasi');
            select.disabled = false; select.classList.remove('bg-slate-100', 'cursor-not-allowed'); select.classList.add('bg-white');
            document.getElementById('btnTambahRekomendasi').disabled = false; document.getElementById('btnKelolaRekomendasi').disabled = false;
            if (radio) {
                const sc = radio.value;
                select.querySelectorAll('option').forEach(opt => {
                    if (opt.value === '' || opt.getAttribute('data-kategori') === sc || opt.getAttribute('data-kategori') === 'Semua') { opt.style.display = ''; opt.disabled = false; } else { opt.style.display = 'none'; opt.disabled = true; }
                });
            }
            updateRekomendasiPreview();
        }
        function updateRekomendasiPreview() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked'); const select = document.getElementById('selectTempatRekomendasi');
            if (radio) { let v = radio.value; if (select.value && select.value !== 'Bawaan Sistem') v += ' - ' + select.value; document.getElementById('hidden_rekomendasi_input').value = v; } 
            else document.getElementById('hidden_rekomendasi_input').value = '';
        }
        
        // MODAL HANDLERS PEND & REKOM
        function openModalTambahPendidikan() { const m = document.getElementById('modalTambahPendidikan'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.classList.remove('opacity-0'), 10); }
        function closeModalTambahPendidikan() { const m = document.getElementById('modalTambahPendidikan'); m.classList.add('opacity-0'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }
        function openModalKelolaPendidikan() { const m = document.getElementById('modalKelolaPendidikan'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.classList.remove('opacity-0'), 10); }
        function closeModalKelolaPendidikan() { const m = document.getElementById('modalKelolaPendidikan'); m.classList.add('opacity-0'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }
        function tambahPendidikanJS(e) { e.preventDefault(); const v = document.getElementById('inputPendidikanBaru').value.trim(); if(v){ const s = document.querySelector('select[name="pendidikan_input"]'); let op = document.createElement('option'); op.value=v; op.textContent=v; s.appendChild(op); s.value=v; closeModalTambahPendidikan(); document.getElementById('inputPendidikanBaru').value=''; saveFormDraft(); } }

        function openModalTambahRekomendasi() { const r = document.querySelector('input[name="kategori_rekomendasi"]:checked'); if(!r){alert("Pilih Radio Kategori Dulu!");return;} document.getElementById('tambahKategoriDisplay').textContent = r.value; const m = document.getElementById('modalTambahRekomendasi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.classList.remove('opacity-0'), 10); }
        function closeModalTambahRekomendasi() { const m = document.getElementById('modalTambahRekomendasi'); m.classList.add('opacity-0'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }
        function openModalKelolaRekomendasi() { const m = document.getElementById('modalKelolaRekomendasi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.classList.remove('opacity-0'), 10); }
        function closeModalKelolaRekomendasi() { const m = document.getElementById('modalKelolaRekomendasi'); m.classList.add('opacity-0'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }
        function tambahRekomendasiJS(e) { e.preventDefault(); const r = document.querySelector('input[name="kategori_rekomendasi"]:checked'); if(!r)return; const v = document.getElementById('inputRekomendasiDetail').value.trim(); if(v){ const s = document.getElementById('selectTempatRekomendasi'); let op = document.createElement('option'); op.value=v; op.textContent=v; op.setAttribute('data-kategori', r.value); s.appendChild(op); s.value=v; updateRekomendasiPreview(); closeModalTambahRekomendasi(); document.getElementById('inputRekomendasiDetail').value=''; saveFormDraft(); } }
    </script>
</x-app-layout>