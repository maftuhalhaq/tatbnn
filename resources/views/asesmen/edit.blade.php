<x-app-layout>
    <!-- CSS Custom untuk Scrollbar Dropdown -->
    <style>
        .custom-select-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-select-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 8px;
        }
        .custom-select-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        .custom-select-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animasi Indikator Autosave */
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .autosave-active {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <div class="py-8 sm:py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- ========================================== -->
            <!-- 1. HEADER (SEAMLESS HERO) -->
            <!-- ========================================== -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('asesmen.index') }}" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider mb-1">
                            Mode Edit Data
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight flex items-center gap-3">
                            Edit Klien: {{ $asesmen->nama_lengkap }}
                            <span id="autosaveIndicator" class="hidden text-[10px] font-bold text-emerald-600 bg-emerald-100 border border-emerald-200 px-2 py-1 rounded-md uppercase tracking-wider autosave-active">
                                Perubahan Tersimpan Otomatis
                            </span>
                        </h1>
                    </div>
                </div>
                <div class="text-sm text-slate-500 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm hidden md:block">
                    Perbarui data dengan teliti. Tanda <span class="text-rose-500 font-bold">*</span> wajib diisi.
                </div>
            </div>

            <!-- ========================================== -->
            <!-- BANNER NOTIFIKASI -->
            <!-- ========================================== -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <div>
                        <h4 class="text-sm font-extrabold text-emerald-800">Berhasil!</h4>
                        <p class="text-[13px] font-semibold text-emerald-600 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-sm font-extrabold text-rose-800">Peringatan Sistem!</h4>
                        <p class="text-[13px] font-semibold text-rose-600 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-sm font-extrabold text-rose-800">Gagal Menyimpan Data!</h4>
                        <ul class="text-[13px] font-semibold text-rose-600 mt-1 list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form id="formEditKlien" action="{{ route('asesmen.update', $asesmen->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- ========================================== -->
                <!-- CARD 1: IDENTITAS PROFIL KLIEN & UPLOAD FOTO -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden z-30">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        1. Profil Klien
                    </h3>

                    <!-- GRID 1: IDENTITAS DASAR KLIEN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                        <!-- UPLOAD FOTO -->
                        <div class="md:col-span-2 lg:col-span-3 mb-4 bg-slate-50 p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-3">Pas Foto Klien <span class="text-slate-400 font-normal lowercase">(Opsional)</span></label>
                            <div class="flex flex-col sm:flex-row items-center gap-5">
                                <div class="shrink-0 w-24 h-32 bg-white rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden relative group shadow-sm">
                                    <img id="previewFoto" src="{{ $asesmen->foto_klien ? asset('storage/' . $asesmen->foto_klien) : '#' }}" alt="Preview" class="{{ $asesmen->foto_klien ? 'w-full h-full object-cover' : 'hidden w-full h-full object-cover' }}">
                                    <svg id="iconPlaceholder" class="{{ $asesmen->foto_klien ? 'hidden' : '' }} w-8 h-8 text-slate-300 group-hover:text-blue-400 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <div class="w-full">
                                    <input type="file" name="foto_klien" id="foto_klien" accept="image/jpeg, image/png, image/jpg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-all cursor-pointer" onchange="previewImage(event)">
                                    <p class="text-[11px] font-medium text-slate-400 mt-2 leading-relaxed">Format yang didukung: JPG, JPEG, PNG.<br>Upload foto baru jika ingin mengubah foto lama.</p>
                                </div>
                            </div>
                        </div>

                        <!-- NAMA LENGKAP -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $asesmen->nama_lengkap) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>

                        <!-- NIK KTP -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">NIK KTP <span class="text-rose-500">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik', $asesmen->nik) }}" required maxlength="16"
                                @class([
                                    'block w-full rounded-xl shadow-sm sm:text-sm transition-all focus:bg-white',
                                    'border-rose-500 ring-2 ring-rose-200 bg-rose-50 text-rose-900' => $errors->has('nik'),
                                    'border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-slate-50' => !$errors->has('nik')
                                ])
                                placeholder="16 Digit NIK">
                            @error('nik')
                                <p class="text-[11px] text-rose-600 font-bold mt-1.5 flex items-start gap-1">
                                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- JENIS KELAMIN -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                <option value="L" {{ old('jenis_kelamin', $asesmen->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                                <option value="P" {{ old('jenis_kelamin', $asesmen->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>

                        <!-- TEMPAT LAHIR -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $asesmen->tempat_lahir) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tanggal Lahir</label>
                            <input type="text" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->format('Y-m-d') : '') }}" class="datepicker-id block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Pilih Tanggal">
                        </div>

                        <!-- USIA OTOMATIS -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Usia <span class="text-blue-500 ml-1 lowercase font-normal">(Saat Didaftarkan)</span></label>
                            <input type="text" name="usia" id="usia" value="{{ old('usia') }}" class="block w-full rounded-xl border-slate-200 shadow-sm sm:text-sm transition-all bg-blue-50 text-blue-800 font-bold pointer-events-none focus:ring-0" placeholder="0 Tahun" readonly>
                        </div>

                        <!-- KEWARGANEGARAAN -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $asesmen->kewarganegaraan ?? 'WNI') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>

                        <!-- AGAMA -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Agama</label>
                            <select name="agama" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                <option value="">-- Pilih Agama --</option>
                                @php $agamaList = ['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; @endphp
                                @foreach($agamaList as $ag)
                                    <option value="{{ $ag }}" {{ old('agama', $asesmen->agama) == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- PEKERJAAN -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan_input" value="{{ old('pekerjaan_input', $asesmen->pekerjaan_input ?? ($asesmen->pekerjaan->nama_pekerjaan ?? '')) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                    </div>

                    <!-- ===================================== -->
                    <!-- GRID 2: ALAMAT KTP & DOMISILI -->
                    <!-- ===================================== -->
                    <div class="col-span-1 sm:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6 pt-6 border-t border-slate-200">

                        <!-- ALAMAT KTP -->
                        <div class="space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col h-full">
                            <h4 class="text-sm font-extrabold text-slate-800 border-b border-slate-200 pb-2 mb-2">Alamat Sesuai KTP</h4>

                            <div class="bg-indigo-50/70 border border-indigo-100 p-3 rounded-lg text-xs text-indigo-700 mb-2 leading-relaxed">
                                <span class="font-bold text-indigo-800">Sistem Edit Cerdas:</span> Alamat lama klien telah terisi di kolom <span class="font-bold">Manual</span>. Jika ingin merombak menggunakan dropdown, silakan ganti opsi ke Otomatis.
                            </div>

                            <!-- OPSI MODE PENGISIAN -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 p-3 bg-white rounded-lg border border-slate-200 shadow-sm mb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="mode_ktp" value="otomatis" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeKtp()">
                                    <span class="ml-2 text-xs font-bold text-slate-700">Otomatis (Kab. Malang)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="mode_ktp" value="manual" checked class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeKtp()">
                                    <span class="ml-2 text-xs font-bold text-slate-700">Manual (Luar Daerah)</span>
                                </label>
                            </div>

                            <!-- BLOK OTOMATIS KTP -->
                            <div id="blok_otomatis_ktp" class="hidden space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Detail Alamat (Jalan / RT RW)</label>
                                    <input type="text" id="jalan_ktp" name="jalan_ktp" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cth: Jl. Diponegoro RT 14 RW 02" autocomplete="off" />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Desa / Kelurahan</label>
                                        <div class="flex gap-2 mt-1">
                                            <select id="tipe_desa_ktp" name="tipe_desa_ktp" class="w-[45%] border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-semibold text-slate-700">
                                                <option value="Desa">Desa</option>
                                                <option value="Kelurahan">Kelurahan</option>
                                            </select>

                                            <!-- FITUR BARU: CUSTOM SEARCHABLE DROPDOWN KTP -->
                                            <div class="relative w-[55%]">
                                                <input type="text" id="desa_ktp" name="desa_ktp" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm pr-8 cursor-pointer" placeholder="Ketik/Pilih..." autocomplete="off">
                                                <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer text-slate-400 hover:text-slate-600 toggle-dropdown-ktp">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                                <div id="list_desa_ktp" class="hidden absolute z-[60] w-full mt-1 bg-white border border-slate-200 shadow-lg rounded-md py-1 text-sm custom-select-scroll" style="max-height: 200px; overflow-y: auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Kecamatan</label>
                                        <select id="kecamatan_ktp" name="kecamatan_ktp" class="border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="Ampelgading">Ampelgading</option><option value="Bantur">Bantur</option><option value="Bululawang">Bululawang</option><option value="Dampit">Dampit</option><option value="Dau">Dau</option><option value="Donomulyo">Donomulyo</option><option value="Gedangan">Gedangan</option><option value="Gondanglegi">Gondanglegi</option><option value="Jabung">Jabung</option><option value="Kalipare">Kalipare</option><option value="Karangploso">Karangploso</option><option value="Kasembon">Kasembon</option><option value="Kepanjen">Kepanjen</option><option value="Kromengan">Kromengan</option><option value="Lawang">Lawang</option><option value="Ngajum">Ngajum</option><option value="Ngantang">Ngantang</option><option value="Pagak">Pagak</option><option value="Pagelaran">Pagelaran</option><option value="Pakis">Pakis</option><option value="Pakisaji">Pakisaji</option><option value="Poncokusumo">Poncokusumo</option><option value="Pujon">Pujon</option><option value="Singosari">Singosari</option><option value="Sumbermanjing Wetan">Sumbermanjing Wetan</option><option value="Sumberpucung">Sumberpucung</option><option value="Tajinan">Tajinan</option><option value="Tirtoyudo">Tirtoyudo</option><option value="Tumpang">Tumpang</option><option value="Turen">Turen</option><option value="Wagir">Wagir</option><option value="Wajak">Wajak</option><option value="Wonosari">Wonosari</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Kota/Kabupaten</label>
                                        <input type="text" id="kabupaten_ktp" name="kabupaten_ktp" class="border-slate-300 rounded-md shadow-sm mt-1 block w-full text-sm bg-slate-100 font-semibold text-slate-700 pointer-events-none" value="Kabupaten Malang" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- BLOK MANUAL KTP -->
                            <div id="blok_manual_ktp" class="hidden space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Ketik Alamat Lengkap (Bisa Diedit Manual)</label>
                                    <textarea id="manual_ktp" name="manual_ktp" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cth: Jl. Jendral Sudirman No. 10...">{{ old('alamat_ktp', $asesmen->alamat_ktp) }}</textarea>
                                </div>
                            </div>

                            <!-- KOTAK PREVIEW (Read-Only) YANG AKAN DIKIRIM -->
                            <div class="mt-auto pt-4 border-t border-slate-200">
                                <label class="block font-bold text-sm text-indigo-600">Hasil Akhir Alamat KTP</label>
                                <input type="hidden" name="alamat_ktp" id="hidden_alamat_ktp" value="{{ old('alamat_ktp', $asesmen->alamat_ktp) }}">
                                <textarea id="preview_alamat_ktp" rows="2" class="mt-1 block w-full text-[13px] bg-indigo-50 border-indigo-200 rounded-md shadow-sm font-bold text-indigo-800 resize-none cursor-not-allowed" disabled placeholder="Alamat akan terangkai otomatis beserta tanda koma di sini...">{{ old('alamat_ktp', $asesmen->alamat_ktp) }}</textarea>
                            </div>
                        </div>

                        <!-- 2. ALAMAT DOMISILI -->
                        <div class="space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col h-full relative">
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                <h4 class="text-sm font-extrabold text-slate-800">Alamat Domisili Saat Ini</h4>
                                <!-- Tombol centang salin alamat -->
                                <label class="inline-flex items-center cursor-pointer bg-white px-2 py-1 rounded border border-slate-200 hover:bg-slate-100 transition shadow-sm">
                                    <input type="checkbox" id="copy_alamat" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3.5 h-3.5" onchange="salinAlamat()">
                                    <span class="ml-2 text-[10px] font-bold text-slate-600 uppercase">Sama dgn KTP</span>
                                </label>
                            </div>

                            <!-- OPSI MODE PENGISIAN -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 p-3 bg-white rounded-lg border border-slate-200 shadow-sm mb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="mode_domisili" value="otomatis" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeDomisili()">
                                    <span class="ml-2 text-xs font-bold text-slate-700">Otomatis (Kab. Malang)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="mode_domisili" value="manual" checked class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleModeDomisili()">
                                    <span class="ml-2 text-xs font-bold text-slate-700">Manual (Luar Daerah)</span>
                                </label>
                            </div>

                            <!-- BLOK OTOMATIS DOMISILI -->
                            <div id="blok_otomatis_domisili" class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Detail Alamat (Jalan / RT RW)</label>
                                    <input type="text" id="jalan_domisili" name="jalan_domisili" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cth: Jl. Diponegoro RT 14 RW 02" autocomplete="off" />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Desa / Kelurahan</label>
                                        <div class="flex gap-2 mt-1">
                                            <select id="tipe_desa_domisili" name="tipe_desa_domisili" class="w-[45%] border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-semibold text-slate-700">
                                                <option value="Desa">Desa</option>
                                                <option value="Kelurahan">Kelurahan</option>
                                            </select>

                                            <!-- FITUR BARU: CUSTOM SEARCHABLE DROPDOWN DOMISILI -->
                                            <div class="relative w-[55%]">
                                                <input type="text" id="desa_domisili" name="desa_domisili" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm pr-8 cursor-pointer" placeholder="Ketik/Pilih..." autocomplete="off">
                                                <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer text-slate-400 hover:text-slate-600 toggle-dropdown-dom">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                                <div id="list_desa_domisili" class="hidden absolute z-[60] w-full mt-1 bg-white border border-slate-200 shadow-lg rounded-md py-1 text-sm custom-select-scroll" style="max-height: 200px; overflow-y: auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Kecamatan</label>
                                        <select id="kecamatan_domisili" name="kecamatan_domisili" class="border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="Ampelgading">Ampelgading</option><option value="Bantur">Bantur</option><option value="Bululawang">Bululawang</option><option value="Dampit">Dampit</option><option value="Dau">Dau</option><option value="Donomulyo">Donomulyo</option><option value="Gedangan">Gedangan</option><option value="Gondanglegi">Gondanglegi</option><option value="Jabung">Jabung</option><option value="Kalipare">Kalipare</option><option value="Karangploso">Karangploso</option><option value="Kasembon">Kasembon</option><option value="Kepanjen">Kepanjen</option><option value="Kromengan">Kromengan</option><option value="Lawang">Lawang</option><option value="Ngajum">Ngajum</option><option value="Ngantang">Ngantang</option><option value="Pagak">Pagak</option><option value="Pagelaran">Pagelaran</option><option value="Pakis">Pakis</option><option value="Pakisaji">Pakisaji</option><option value="Poncokusumo">Poncokusumo</option><option value="Pujon">Pujon</option><option value="Singosari">Singosari</option><option value="Sumbermanjing Wetan">Sumbermanjing Wetan</option><option value="Sumberpucung">Sumberpucung</option><option value="Tajinan">Tajinan</option><option value="Tirtoyudo">Tirtoyudo</option><option value="Tumpang">Tumpang</option><option value="Turen">Turen</option><option value="Wagir">Wagir</option><option value="Wajak">Wajak</option><option value="Wonosari">Wonosari</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Kota/Kabupaten</label>
                                        <input type="text" id="kabupaten_domisili" name="kabupaten_domisili" class="border-slate-300 rounded-md shadow-sm mt-1 block w-full text-sm bg-slate-100 font-semibold text-slate-700 pointer-events-none" value="Kabupaten Malang" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- BLOK MANUAL DOMISILI -->
                            <div id="blok_manual_domisili" class="hidden space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Ketik Alamat Lengkap (Luar Daerah)</label>
                                    <textarea id="manual_domisili" name="manual_domisili" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cth: Jl. Jendral Sudirman No. 10...">{{ old('alamat_domisili', $asesmen->alamat_domisili) }}</textarea>
                                </div>
                            </div>

                            <!-- KOTAK PREVIEW (Read-Only) YANG AKAN DIKIRIM -->
                            <div class="mt-auto pt-4 border-t border-slate-200">
                                <label class="block font-bold text-sm text-indigo-600">Hasil Rakitan Alamat Domisili</label>
                                <input type="hidden" name="alamat_domisili" id="hidden_alamat_domisili" value="{{ old('alamat_domisili', $asesmen->alamat_domisili) }}">
                                <textarea id="preview_alamat_domisili" rows="2" class="mt-1 block w-full text-[13px] bg-indigo-50 border-indigo-200 rounded-md shadow-sm font-bold text-indigo-800 resize-none cursor-not-allowed" disabled placeholder="Alamat akan terangkai otomatis beserta tanda koma di sini...">{{ old('alamat_domisili', $asesmen->alamat_domisili) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ===================================== -->
                    <!-- GRID 3: PENDIDIKAN, PENGHASILAN & KONTAK -->
                    <!-- ===================================== -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6 pt-6 border-t border-slate-200">

                        <!-- DROPDOWN PENDIDIKAN DENGAN MODAL KELOLA -->
                        <div class="md:col-span-2 lg:col-span-1">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pendidikan</label>
                            <div class="flex gap-2">
                                <select name="pendidikan_input" id="selectPendidikan" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    @php $currentPendidikan = $asesmen->pendidikan->nama_pendidikan ?? ''; @endphp
                                    @foreach($masterPendidikan ?? [] as $p)
                                        @if(trim($p->nama_pendidikan) !== '')
                                            <option value="{{ $p->nama_pendidikan }}" {{ old('pendidikan_input', $asesmen->pendidikan_input ?? ($asesmen->pendidikan->nama_pendidikan ?? '')) == $p->nama_pendidikan ? 'selected' : '' }}>
                                                {{ $p->nama_pendidikan }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" onclick="openModalTambahPendidikan()" class="shrink-0 px-3 py-2 bg-blue-100 text-blue-700 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider rounded-xl hover:bg-blue-200 transition-colors shadow-sm">+ Tambah</button>
                                <button type="button" onclick="openModalKelolaPendidikan()" class="shrink-0 px-3 py-2 bg-slate-100 text-slate-700 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider rounded-xl hover:bg-slate-200 transition-colors shadow-sm">Kelola</button>
                            </div>
                        </div>

                        <!-- PENGHASILAN -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Penghasilan Rata-Rata</label>
                            <input type="text" name="penghasilan_rata_rata" id="penghasilan_rupiah" value="{{ old('penghasilan_rata_rata', $asesmen->penghasilan_rata_rata) }}" placeholder="Misal: Rp 3.000.000" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>

                        <!-- NO HANDPHONE -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $asesmen->no_hp) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 2: ADMINISTRASI SURAT & REGISTRASI -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden z-20">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        2. Data Administrasi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No / BLN</label>
                            <input type="text" name="no_bln" value="{{ old('no_bln', $asesmen->no_bln) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Asal Pengajuan</label>
                            <input type="text" name="asal_pengajuan" value="{{ old('asal_pengajuan', $asesmen->asal_pengajuan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Surat</label>
                            <input type="text" name="tgl_surat" value="{{ old('tgl_surat', $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->format('Y-m-d') : '') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Berkas Diterima</label>
                            <input type="text" name="tgl_berkas" value="{{ old('tgl_berkas', $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('Y-m-d') : '') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Pelaksanaan</label>
                            <input type="text" name="tgl_pelaksanaan" value="{{ old('tgl_pelaksanaan', $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('Y-m-d') : '') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Surat Pengajuan</label>
                            <input type="text" name="no_surat_pengajuan" value="{{ old('no_surat_pengajuan', $asesmen->no_surat_pengajuan) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. LKN / LP / LI</label>
                            <input type="text" name="no_lkn" value="{{ old('no_lkn', $asesmen->no_lkn) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tanggal Penangkapan</label>
                            <input type="text" name="tgl_tangkap" value="{{ old('tgl_tangkap', $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->format('Y-m-d') : '') }}" class="datepicker-id mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">No. Register</label>
                            <input type="text" name="no_register" value="{{ old('no_register', $asesmen->no_register) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>


                <!-- ========================================== -->
                <!-- CARD 3: DATA HUKUM, MEDIS & HASIL ASESMEN -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden z-20">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-amber-500"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        3. Perkara Hukum, Medis & Rekomendasi TAT
                    </h3>

                    <!-- BLOK A: HUKUM -->
                    <div class="mb-8">
                        <h4 class="font-extrabold text-[12px] text-amber-700 uppercase tracking-wider mb-4 flex items-center gap-2 bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                            <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded">A</span> Bidang Hukum & Barang Bukti
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Status Hukum</label>
                                <input type="text" name="status_hukum" value="{{ old('status_hukum', $asesmen->status_hukum) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Cth: Tersangka">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Keterlibatan Jaringan</label>
                                <select name="keterlibatan_jaringan" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                    <option value="">-- Pilih --</option>
                                    <option value="Ya" {{ old('keterlibatan_jaringan', $asesmen->keterlibatan_jaringan) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('keterlibatan_jaringan', $asesmen->keterlibatan_jaringan) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            
                            <!-- Checkbox Multi Narkotika -->
                            <div class="relative" id="narkotika-wrapper">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">
                                    Zat / Narkotika <span class="text-[10px] font-normal text-slate-400 normal-case ml-1">(Bisa pilih lebih dari 1)</span>
                                </label>

                                <!-- Trigger Button -->
                                <button type="button" onclick="toggleNarkotika()" class="w-full text-left bg-white focus:bg-white border border-slate-300 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-amber-100 focus:border-amber-500 transition-all flex justify-between items-center sm:text-sm">
                                    <span id="narkotika-text" class="text-slate-500 truncate">-- Pilih Jenis Narkotika --</span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <!-- Dropdown List Checkbox -->
                                <div id="narkotika-dropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-slate-200 shadow-xl rounded-xl py-2 max-h-56 overflow-y-auto custom-select-scroll">
                                    @php
                                        $narkotikaTersimpan = [];
                                        if(isset($asesmen) && $asesmen->narkotika_id) {
                                            $narkotikaTersimpan = is_array($asesmen->narkotika_id) ? $asesmen->narkotika_id : explode(',', $asesmen->narkotika_id);
                                        }
                                        $selectedNarkotika = old('narkotika_id', $narkotikaTersimpan);
                                        if (!is_array($selectedNarkotika)) {
                                            $selectedNarkotika = $selectedNarkotika ? explode(',', $selectedNarkotika) : [];
                                        }
                                    @endphp

                                    @foreach($masterNarkotika ?? [] as $n)
                                        <label class="flex items-center px-4 py-2.5 hover:bg-amber-50 cursor-pointer transition-colors group border-b border-slate-50 last:border-0">
                                            <input type="checkbox" name="narkotika_id[]" value="{{ $n->id }}"
                                                onchange="updateNarkotikaText()"
                                                class="narkotika-cb w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500 transition-all cursor-pointer"
                                                {{ in_array($n->id, $selectedNarkotika) ? 'checked' : '' }}
                                                data-label="{{ $n->jenis_narkotika }}">
                                            <span class="ml-3 text-sm text-slate-700 group-hover:text-amber-800 font-medium cursor-pointer">{{ $n->jenis_narkotika }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Berat Barang Bukti (Gr)</label>
                                <input type="number" step="0.01" name="berat_bb" value="{{ old('berat_bb', $asesmen->berat_bb) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Contoh: 2.50">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Detail Barang Bukti</label>
                                <input type="text" name="deskripsi_bb" value="{{ old('deskripsi_bb', $asesmen->deskripsi_bb) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                            </div>

                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Cara Mendapatkan</label>
                                <input type="text" name="cara_mendapatkan" value="{{ old('cara_mendapatkan', $asesmen->cara_mendapatkan) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Dapat Dari Siapa</label>
                                <input type="text" name="dapat_dari_siapa" value="{{ old('dapat_dari_siapa', $asesmen->dapat_dari_siapa) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                            </div>

                            <div class="md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-5 mt-2 border-t border-slate-100 pt-4">
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Analisis Aspek Hukum <span class="text-slate-400 font-normal normal-case">(Case Conference)</span></label>
                                    <textarea name="aspek_hukum" rows="3" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Opsional...">{{ old('aspek_hukum', $asesmen->aspek_hukum) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOK B: MEDIS -->
                    <div class="mb-8">
                        <h4 class="font-extrabold text-[12px] text-blue-700 uppercase tracking-wider mb-4 flex items-center gap-2 bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded">B</span> Bidang Medis
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kesehatan (Fisik)</label>
                                <textarea name="kesehatan_fisik" rows="2" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">{{ old('kesehatan_fisik', $asesmen->kesehatan_fisik) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Psikologi</label>
                                <textarea name="psikologi" rows="2" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">{{ old('psikologi', $asesmen->psikologi) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Hasil Tes Urine</label>
                                <input type="text" name="tes_urine" value="{{ old('tes_urine', $asesmen->tes_urine) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Cth: Positif Sabu">
                            </div>

                            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-5 mt-2 border-t border-slate-100 pt-4">
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Analisis Aspek Medis <span class="text-slate-400 font-normal normal-case">(Case Conference)</span></label>
                                    <textarea name="aspek_medis" rows="3" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Opsional...">{{ old('aspek_medis', $asesmen->aspek_medis) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOK C: KONDISI SOSIAL & POLA -->
                    <div class="mb-8">
                        <h4 class="font-extrabold text-[12px] text-purple-700 uppercase tracking-wider mb-4 flex items-center gap-2 bg-purple-50/50 p-3 rounded-xl border border-purple-100">
                            <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded">C</span> Kondisi Sosio-Psikologis & Pola Pemakaian
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Alasan Penggunaan</label>
                                <textarea name="alasan_penggunaan" rows="2" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">{{ old('alasan_penggunaan', $asesmen->alasan_penggunaan) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kondisi Keluarga</label>
                                <textarea name="kondisi_keluarga" rows="2" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">{{ old('kondisi_keluarga', $asesmen->kondisi_keluarga) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Kondisi Lingkungan</label>
                                <textarea name="kondisi_lingkungan" rows="2" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">{{ old('kondisi_lingkungan', $asesmen->kondisi_lingkungan) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tingkat Ketergantungan</label>
                                <input type="text" name="tingkat_ketergantungan" value="{{ old('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pola Pemakaian</label>
                                <input type="text" name="pola_pemakaian" value="{{ old('pola_pemakaian', $asesmen->pola_pemakaian) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- BLOK D: KESIMPULAN REKOMENDASI -->
                    <div>
                        <h4 class="font-extrabold text-[12px] text-emerald-700 uppercase tracking-wider mb-4 flex items-center gap-2 bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">D</span> Keputusan Rekomendasi & Saran
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dropdown Rekomendasi -->
                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-2">Rekomendasi TAT <span class="text-rose-500">*</span></label>

                                @php
                                    $rawRek = old('rekomendasi_input', $asesmen->rekomendasi_input ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? '');

                                    $isJalan = str_starts_with($rawRek, 'Rawat Jalan');
                                    $isInap = str_starts_with($rawRek, 'Rawat Inap');
                                    $isLapas = str_starts_with($rawRek, 'Rehab di Lapas / Rutan');
                                    $isTidakRehab = str_starts_with($rawRek, 'Tidak Rehab (Proses Hukum)');

                                    $tempatRek = '';
                                    if ($isJalan) {
                                        $tempatRek = trim(str_replace('Rawat Jalan', '', $rawRek));
                                        $tempatRek = ltrim($tempatRek, ' -');
                                    } elseif ($isInap) {
                                        $tempatRek = trim(str_replace('Rawat Inap', '', $rawRek));
                                        $tempatRek = ltrim($tempatRek, ' -');
                                    } elseif ($isLapas) {
                                        $tempatRek = trim(str_replace('Rehab di Lapas / Rutan', '', $rawRek));
                                        $tempatRek = ltrim($tempatRek, ' -');
                                    } elseif ($isTidakRehab) {
                                        $tempatRek = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $rawRek));
                                        $tempatRek = ltrim($tempatRek, ' -');
                                    } else {
                                        $tempatRek = $rawRek;
                                    }
                                @endphp

                                <div class="flex flex-wrap gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl mb-3">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="kategori_rekomendasi" value="Rawat Jalan" {{ $isJalan ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleRekomendasi()">
                                        <span class="ml-2 text-sm font-bold text-slate-700">Rawat Jalan</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="kategori_rekomendasi" value="Rawat Inap" {{ $isInap ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleRekomendasi()">
                                        <span class="ml-2 text-sm font-bold text-slate-700">Rawat Inap</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="kategori_rekomendasi" value="Rehab di Lapas / Rutan" {{ $isLapas ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleRekomendasi()">
                                        <span class="ml-2 text-sm font-bold text-slate-700">Rehab di Lapas / Rutan</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="kategori_rekomendasi" value="Tidak Rehab (Proses Hukum)" {{ $isTidakRehab ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" onchange="toggleRekomendasi()">
                                        <span class="ml-2 text-sm font-bold text-slate-700">Tidak Rehab (Proses Hukum)</span>
                                    </label>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2">
                                    <select id="selectTempatRekomendasi" disabled class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-100 text-slate-400 cursor-not-allowed" onchange="updateRekomendasiPreview()">
                                        <option value="">-- Pilih Tempat / Instansi --</option>
                                        <option value="Bawaan Sistem" data-kategori="Rawat Jalan" style="display:none;" disabled>Hanya Rawat Jalan (Tanpa Instansi)</option>
                                        <option value="Bawaan Sistem" data-kategori="Rawat Inap" style="display:none;" disabled>Hanya Rawat Inap (Tanpa Instansi)</option>
                                        <option value="Bawaan Sistem" data-kategori="Rehab di Lapas / Rutan" style="display:none;" disabled>Hanya Rehab di Lapas / Rutan (Tanpa Instansi)</option>
                                        <option value="Bawaan Sistem" data-kategori="Tidak Rehab (Proses Hukum)" style="display:none;" disabled>Hanya Tidak Rehab (Tanpa Instansi)</option>

                                        @foreach($masterRekomendasi ?? [] as $r)
                                            @if(trim($r->nama_rekomendasi) !== '')
                                                @php
                                                    $kategoriItem = 'Semua';
                                                    $namaItem = $r->nama_rekomendasi;

                                                    if (str_starts_with($namaItem, 'Rawat Jalan - ')) {
                                                        $kategoriItem = 'Rawat Jalan';
                                                        $namaItem = trim(substr($namaItem, 14));
                                                    } elseif (str_starts_with($namaItem, 'Rawat Inap - ')) {
                                                        $kategoriItem = 'Rawat Inap';
                                                        $namaItem = trim(substr($namaItem, 13));
                                                    } elseif (str_starts_with($namaItem, 'Rehab di Lapas / Rutan - ')) {
                                                        $kategoriItem = 'Rehab di Lapas / Rutan';
                                                        $namaItem = trim(substr($namaItem, 25));
                                                    } elseif (str_starts_with($namaItem, 'Tidak Rehab (Proses Hukum) - ')) {
                                                        $kategoriItem = 'Tidak Rehab (Proses Hukum)';
                                                        $namaItem = trim(substr($namaItem, 29));
                                                    }
                                                @endphp
                                                <option value="{{ $namaItem }}" data-kategori="{{ $kategoriItem }}" data-full="{{ $r->nama_rekomendasi }}" style="display:none;" disabled>
                                                    {{ $namaItem }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="flex gap-2">
                                        <button type="button" id="btnTambahRekomendasi" disabled onclick="openModalTambahRekomendasi()" class="flex-1 sm:flex-none px-4 py-2 bg-slate-200 text-slate-400 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider rounded-xl transition-colors shadow-sm cursor-not-allowed">+ Tambah</button>
                                        <button type="button" id="btnKelolaRekomendasi" disabled onclick="openModalKelolaRekomendasi()" class="flex-1 sm:flex-none px-4 py-2 bg-slate-100 text-slate-600 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider rounded-xl transition-colors shadow-sm cursor-not-allowed">Kelola</button>
                                    </div>
                                </div>

                                <input type="hidden" name="rekomendasi_input" id="hidden_rekomendasi_input" value="{{ old('rekomendasi_input', $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input) }}">
                                <p class="text-[11px] text-slate-500 italic mt-2" id="teks_preview_rekomendasi">Hasil akhir Rekomendasi: <span class="font-bold text-slate-400">Belum dipilih</span></p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Keterangan Tambahan TAT</label>
                                <input type="text" name="keterangan_tambahan" value="{{ old('keterangan_tambahan', $asesmen->keterangan_tambahan) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                            </div>

                            <!-- 4 BARIS FORM SARAN SIDANG CASE CONFERENCE -->
                            <div class="md:col-span-2">
                                <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Saran Sidang Case Conference</label>

                                <!-- Input tersembunyi ini yang akan dikirim ke Laravel -->
                                <input type="hidden" name="saran_case_conference" id="hidden_saran_cc" value="{{ old('saran_case_conference', $asesmen->saran_case_conference) }}">

                                @php
                                    $saranLama = old('saran_case_conference', $asesmen->saran_case_conference);
                                    // Memecah berdasarkan koma (dan membersihkan spasi)
                                    $saranList = $saranLama ? array_map('trim', explode(",", $saranLama)) : [];
                                @endphp

                                <div class="grid grid-cols-1 gap-3 saran-grid-container">
                                    <input type="text" class="saran-cc-input block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="1. Saran pertama..." value="{{ $saranList[0] ?? '' }}">
                                    <input type="text" class="saran-cc-input block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="2. Saran kedua..." value="{{ $saranList[1] ?? '' }}">
                                    <input type="text" class="saran-cc-input block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="3. Saran ketiga..." value="{{ $saranList[2] ?? '' }}">
                                    <input type="text" class="saran-cc-input block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="4. Saran keempat..." value="{{ $saranList[3] ?? '' }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- CARD 4: HASIL ASESMEN FINAL & PELAKSANAAN -->
                <!-- ========================================== -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden z-20">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-400"></div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3 flex items-center gap-2 mt-1">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        4. Hasil Asesmen Final & Pelaksanaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jenis Narkotika Otomatis (Display Only) -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Jenis Narkotika</label>
                            <input type="text" id="display_jenis_narkotika" class="block w-full rounded-xl border-slate-200 shadow-sm sm:text-sm bg-slate-100 text-slate-500 font-bold pointer-events-none focus:ring-0" placeholder="Otomatis dari pilihan di atas" readonly>
                        </div>

                        <!-- Berat BB Otomatis (Display Only) -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Berat Barang Bukti (Gr)</label>
                            <input type="text" id="display_berat_bb" class="block w-full rounded-xl border-slate-200 shadow-sm sm:text-sm bg-slate-100 text-slate-500 font-bold pointer-events-none focus:ring-0" placeholder="Otomatis dari input di atas" readonly>
                        </div>

                        <!-- Pasal Sangkaan Otomatis (Display Only) -->
                        <div class="md:col-span-2">
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Pasal Yang Disangkakan</label>
                            <input type="text" id="display_pasal_sangkaan" class="block w-full rounded-xl border-slate-200 shadow-sm sm:text-sm bg-slate-100 text-slate-500 font-bold pointer-events-none focus:ring-0" placeholder="Otomatis dari input di atas" readonly>
                        </div>

                        <!-- Hasil Asesmen Hukum -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Hasil Asesmen Hukum</label>
                            <textarea name="hasil_asesmen_hukum" rows="4" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Ketik kronologi mentah asesmen hukum...">{{ old('hasil_asesmen_hukum', $asesmen->hasil_asesmen_hukum) }}</textarea>
                        </div>

                        <!-- Hasil Asesmen Medis -->
                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Hasil Asesmen Medis</label>
                            <textarea name="hasil_asesmen_medis" rows="4" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100 sm:text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Ketik kronologi mentah asesmen medis...">{{ old('hasil_asesmen_medis', $asesmen->hasil_asesmen_medis) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-1">Status Pelaksanaan Rekomendasi</label>
                            <select name="pelaksanaan" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100 sm:text-sm transition-all bg-slate-50 focus:bg-white">
                                <option value="TIDAK" {{ old('pelaksanaan', $asesmen->pelaksanaan) == 'TIDAK' ? 'selected' : '' }}>Belum Dilaksanakan (TIDAK)</option>
                                <option value="YA" {{ old('pelaksanaan', $asesmen->pelaksanaan) == 'YA' ? 'selected' : '' }}>Sudah Dilaksanakan (YA)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TOMBOL SUBMIT -->
                <!-- ========================================== -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 pb-10">
                    <a href="{{ route('asesmen.index') }}" class="inline-flex justify-center items-center px-8 py-3.5 bg-white border border-slate-300 rounded-xl text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm focus:ring-2 focus:ring-slate-200">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-200 focus:ring-4 focus:ring-indigo-100">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Simpan Perubahan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH PENDIDIKAN -->
    <div id="modalTambahPendidikan" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center transition-all opacity-0">
        <div class="bg-white w-full max-w-md p-6 sm:p-8 rounded-3xl shadow-2xl transform scale-95 transition-all relative">
            <button type="button" onclick="closeModalTambahPendidikan()" class="absolute top-5 right-5 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-extrabold text-slate-900 mb-6">Tambah Pendidikan</h3>

            <!-- PERBAIKAN: FORM JS MURNI (ANTI 404) -->
            <form onsubmit="tambahPendidikanJS(event)">
                <div class="mb-6">
                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-2">Nama Pendidikan Baru</label>
                    <input type="text" id="inputPendidikanBaru" required placeholder="Cth: S1 Teknik Informatika" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:text-sm transition-all bg-slate-50 focus:bg-white p-3">
                </div>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeModalTambahPendidikan()" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-extrabold text-sm rounded-2xl hover:bg-slate-50 transition-colors shadow-sm">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-extrabold text-sm rounded-2xl hover:bg-blue-700 transition-colors shadow-sm">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KELOLA PENDIDIKAN -->
    <div id="modalKelolaPendidikan" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center transition-all opacity-0">
        <div class="bg-white w-full max-w-lg p-6 sm:p-8 rounded-3xl shadow-2xl transform scale-95 transition-all relative">
            <button type="button" onclick="closeModalKelolaPendidikan()" class="absolute top-5 right-5 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-extrabold text-slate-900 mb-6">Kelola Pendidikan</h3>
            <div id="listKelolaPendidikan" class="max-h-[60vh] overflow-y-auto pr-2 space-y-3 mb-8 custom-scrollbar">
                @forelse($masterPendidikan ?? [] as $p)
                    @if(trim($p->nama_pendidikan) !== '')
                    <div class="flex justify-between items-center p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors">
                        <span class="text-sm font-bold text-slate-700">{{ $p->nama_pendidikan }}</span>
                        <form action="{{ Route::has('pendidikan.destroy') ? route('pendidikan.destroy', $p->id) : url('pendidikan/'.$p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-rose-500 bg-white hover:bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </form>
                    </div>
                    @endif
                @empty
                <div class="text-sm text-slate-500 text-center py-6 italic empty-msg">Belum ada data pendidikan.</div>
                @endforelse
            </div>
            <div class="flex justify-center">
                <button type="button" onclick="closeModalKelolaPendidikan()" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-extrabold text-sm rounded-2xl hover:bg-slate-50 transition-colors w-full sm:w-auto shadow-sm">Tutup Kelola</button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH REKOMENDASI TAT -->
    <!-- ========================================== -->
    <div id="modalTambahRekomendasi" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center transition-all opacity-0">
        <div class="bg-white w-full max-w-md p-6 sm:p-8 rounded-3xl shadow-2xl transform scale-95 transition-all relative">
            <button type="button" onclick="closeModalTambahRekomendasi()" class="absolute top-5 right-5 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-extrabold text-slate-900 mb-6">Tambah Opsi Rekomendasi</h3>

            <form onsubmit="tambahRekomendasiJS(event)">
                <div class="mb-4">
                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-2">Kategori Perawatan Saat Ini</label>
                    <div class="flex gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span id="tambahKategoriDisplay" class="text-sm font-black text-indigo-700">Pilih Radio Dahulu</span>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-[12px] font-bold text-slate-700 uppercase tracking-wide mb-2">Nama Instansi / Detail Spesifik <span class="text-rose-500">*</span></label>
                    <input type="text" id="inputRekomendasiDetail" required placeholder="Cth: Klinik Pratama BNNK" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100 sm:text-sm transition-all bg-slate-50 focus:bg-white p-3">
                </div>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeModalTambahRekomendasi()" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-extrabold text-sm rounded-2xl hover:bg-slate-50 transition-colors shadow-sm">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-slate-800 text-white font-extrabold text-sm rounded-2xl hover:bg-slate-900 transition-colors shadow-sm">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL KELOLA REKOMENDASI TAT -->
    <!-- ========================================== -->
    <div id="modalKelolaRekomendasi" class="fixed inset-0 z-[99] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center transition-all opacity-0">
        <div class="bg-white w-full max-w-lg p-6 sm:p-8 rounded-3xl shadow-2xl transform scale-95 transition-all relative">
            <button type="button" onclick="closeModalKelolaRekomendasi(true)" class="absolute top-5 right-5 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 id="modalKelolaRekomendasiTitle" class="text-xl font-extrabold text-slate-900 mb-6">Kelola Opsi Rekomendasi</h3>

            <div id="listKelolaRekomendasi" class="max-h-[60vh] overflow-y-auto pr-2 space-y-3 mb-8 custom-scrollbar">

                <!-- Bawaan Sistem Tidak Bisa Dihapus -->
                <div data-kategori="Rawat Jalan" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-100 bg-slate-50 rounded-2xl opacity-70">
                    <span class="text-sm font-bold text-slate-500">Hanya Rawat Jalan (Tanpa Instansi)</span>
                    <button type="button" disabled class="text-xs font-bold text-slate-300 px-4 py-2 cursor-not-allowed">Tetap</button>
                </div>
                <div data-kategori="Rawat Inap" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-100 bg-slate-50 rounded-2xl opacity-70">
                    <span class="text-sm font-bold text-slate-500">Hanya Rawat Inap (Tanpa Instansi)</span>
                    <button type="button" disabled class="text-xs font-bold text-slate-300 px-4 py-2 cursor-not-allowed">Tetap</button>
                </div>
                <div data-kategori="Rehab di Lapas / Rutan" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-100 bg-slate-50 rounded-2xl opacity-70">
                    <span class="text-sm font-bold text-slate-500">Hanya Rehab di Lapas / Rutan (Tanpa Instansi)</span>
                    <button type="button" disabled class="text-xs font-bold text-slate-300 px-4 py-2 cursor-not-allowed">Tetap</button>
                </div>
                <div data-kategori="Tidak Rehab (Proses Hukum)" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-100 bg-slate-50 rounded-2xl opacity-70">
                    <span class="text-sm font-bold text-slate-500">Hanya Tidak Rehab (Tanpa Instansi)</span>
                    <button type="button" disabled class="text-xs font-bold text-slate-300 px-4 py-2 cursor-not-allowed">Tetap</button>
                </div>

                <!-- Tarikan dari Database -->
                @foreach($masterRekomendasi ?? [] as $r)
                    @php
                        $namaAsli = $r->tempat_rehabilitasi ?? $r->nama_rekomendasi ?? '';
                    @endphp
                    @if(trim($namaAsli) !== '')
                        @php
                            $kategoriItem = 'Semua';
                            $namaTampil = trim($namaAsli);

                            if (str_starts_with($namaTampil, 'Rawat Jalan')) {
                                $kategoriItem = 'Rawat Jalan';
                                $namaTampil = trim(str_replace('Rawat Jalan', '', $namaTampil));
                                $namaTampil = ltrim($namaTampil, ' -');
                            } elseif (str_starts_with($namaTampil, 'Rawat Inap')) {
                                $kategoriItem = 'Rawat Inap';
                                $namaTampil = trim(str_replace('Rawat Inap', '', $namaTampil));
                                $namaTampil = ltrim($namaTampil, ' -');
                            } elseif (str_starts_with($namaTampil, 'Rehab di Lapas / Rutan')) {
                                $kategoriItem = 'Rehab di Lapas / Rutan';
                                $namaTampil = trim(str_replace('Rehab di Lapas / Rutan', '', $namaTampil));
                                $namaTampil = ltrim($namaTampil, ' -');
                            } elseif (str_starts_with($namaTampil, 'Tidak Rehab (Proses Hukum)')) {
                                $kategoriItem = 'Tidak Rehab (Proses Hukum)';
                                $namaTampil = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $namaTampil));
                                $namaTampil = ltrim($namaTampil, ' -');
                            }
                        @endphp
                        <div data-kategori="{{ $kategoriItem }}" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors">
                            <span class="text-sm font-bold text-slate-700">{{ $namaTampil }}</span>
                            <form action="{{ Route::has('rekomendasi.destroy') ? route('rekomendasi.destroy', $r->id) : url('rekomendasi/'.$r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-500 bg-white hover:bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                            </form>
                        </div>
                    @endif
                @endforeach

                <div id="emptyMsgRekomendasi" class="hidden text-sm text-slate-500 text-center py-6 italic">Belum ada data opsi rekomendasi tambahan.</div>
            </div>
            <div class="flex justify-center">
                <button type="button" onclick="closeModalKelolaRekomendasi(true)" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-extrabold text-sm rounded-2xl hover:bg-slate-50 transition-colors w-full sm:w-auto shadow-sm">Tutup Kelola</button>
            </div>
        </div>
    </div>

    <!-- Script JavaScript Terintegrasi -->
    <script>
        // Toggle Buka/Tutup Dropdown Narkotika
        function toggleNarkotika() {
            const dropdown = document.getElementById('narkotika-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Update Teks Button Saat Checkbox Dipilih & Update Tampilan Preview
        function updateNarkotikaText() {
            const checkboxes = document.querySelectorAll('.narkotika-cb:checked');
            const textSpan = document.getElementById('narkotika-text');
            const displayEl = document.getElementById('display_jenis_narkotika'); // Element Preview

            if (checkboxes.length === 0) {
                textSpan.textContent = '-- Pilih Jenis Narkotika --';
                textSpan.classList.remove('text-slate-900', 'font-bold');
                textSpan.classList.add('text-slate-500');
                if (displayEl) displayEl.value = '';
            } else {
                const labels = Array.from(checkboxes).map(cb => cb.getAttribute('data-label'));
                const joinedLabels = labels.join(', ');
                textSpan.textContent = joinedLabels;
                textSpan.classList.remove('text-slate-500');
                textSpan.classList.add('text-slate-900', 'font-bold');
                if (displayEl) displayEl.value = joinedLabels;
            }
        }

        // Menutup dropdown narkotika jika user klik di luar area
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('narkotika-wrapper');
            const dropdown = document.getElementById('narkotika-dropdown');
            if (wrapper && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Sinkronisasi Saran Case Conference
        const saranInputs = document.querySelectorAll('.saran-cc-input');
        const hiddenSaran = document.getElementById('hidden_saran_cc');

        function updateSaranHidden() {
            if(!hiddenSaran) return;
            let combinedSaran = [];
            saranInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    combinedSaran.push(input.value.trim());
                }
            });
            // DIGABUNG MENGGUNAKAN KOMA DAN SPASI
            hiddenSaran.value = combinedSaran.join(', ');
            if (typeof saveFormDraft === 'function') saveFormDraft();
        }

        saranInputs.forEach(input => {
            input.addEventListener('input', updateSaranHidden);
        });

        // Sinkronisasi Display BB dari Input Form Atas
        document.querySelector('input[name="berat_bb"]').addEventListener('input', function() {
            const displayEl = document.getElementById('display_berat_bb');
            if (displayEl) {
                displayEl.value = this.value ? this.value + ' Gr' : '';
            }
        });

        document.querySelector('input[name="pasal_sangkaan"]').addEventListener('input', function() {
            const displayEl = document.getElementById('display_pasal_sangkaan');
            if (displayEl) {
                displayEl.value = this.value;
            }
        });

        // Trigger Sync awal untuk mode Edit
        window.addEventListener('load', function() {
            updateNarkotikaText();

            const bbInput = document.querySelector('input[name="berat_bb"]');
            if(bbInput && bbInput.value) {
                const displayBb = document.getElementById('display_berat_bb');
                if(displayBb) displayBb.value = bbInput.value + ' Gr';
            }

            const pasalInput = document.querySelector('input[name="pasal_sangkaan"]');
            if(pasalInput && pasalInput.value) {
                const displayPasal = document.getElementById('display_pasal_sangkaan');
                if(displayPasal) displayPasal.value = pasalInput.value;
            }

            // Memisahkan nilai dari hidden input ke dalam 4 text input (jika load dari db / old / draft)
            if(hiddenSaran && hiddenSaran.value) {
                const splitSaran = hiddenSaran.value.split(',');
                saranInputs.forEach((input, index) => {
                    input.value = splitSaran[index] ? splitSaran[index].trim() : '';
                });
            }
        });

        // Modal Handlers Pendidikan
        function openModalTambahPendidikan() {
            const modal = document.getElementById('modalTambahPendidikan');
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Add flex dynamically to avoid CSS conflict
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.children[0].classList.remove('scale-95');
            }, 10);
        }

        function closeModalTambahPendidikan() {
            const modal = document.getElementById('modalTambahPendidikan');
            modal.classList.add('opacity-0');
            modal.children[0].classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex'); // Remove flex dynamically
            }, 300);
        }

        function openModalKelolaPendidikan() {
            const modal = document.getElementById('modalKelolaPendidikan');
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Add flex dynamically to avoid CSS conflict
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.children[0].classList.remove('scale-95');
            }, 10);
        }

        function closeModalKelolaPendidikan() {
            const modal = document.getElementById('modalKelolaPendidikan');
            modal.classList.add('opacity-0');
            modal.children[0].classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex'); // Remove flex dynamically
            }, 300);
        }

        // FUNGSI JAVASCRIPT UNTUK TAMBAH PENDIDIKAN TANPA REFRESH
        function tambahPendidikanJS(event) {
            event.preventDefault(); // Mencegah reload/404 page
            const inputVal = document.getElementById('inputPendidikanBaru').value.trim();

            if(inputVal) {
                const selectElement = document.querySelector('select[name="pendidikan_input"]');

                // Cek apakah data sudah ada
                let exists = Array.from(selectElement.options).some(opt => opt.value === inputVal);

                if(!exists) {
                    const newOption = document.createElement('option');
                    newOption.value = inputVal;
                    newOption.textContent = inputVal;
                    newOption.style.display = '';
                    newOption.disabled = false;
                    selectElement.appendChild(newOption);

                    // ==============================================================
                    // FITUR BARU: Menambahkan DRAFT ke Daftar "Kelola Pendidikan"
                    // ==============================================================
                    const listKelola = document.getElementById('listKelolaPendidikan');

                    // Hapus pesan kosong jika ada
                    const emptyMsg = listKelola.querySelector('.empty-msg');
                    if(emptyMsg) emptyMsg.remove();

                    // Buat ID unik untuk draft (menghilangkan spasi)
                    const draftId = 'draft-' + inputVal.replace(/\s+/g, '-').toLowerCase();

                    // Render HTML baris draft
                    const draftHtml = `
                        <div class="flex justify-between items-center p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors" id="${draftId}">
                            <span class="text-sm font-bold text-slate-700">${inputVal}</span>
                            <button type="button" onclick="hapusDraftPendidikan('${inputVal}', '${draftId}')" class="text-xs font-bold text-rose-500 bg-white hover:bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </div>
                    `;

                    // Sisipkan baris di urutan paling atas daftar
                    listKelola.insertAdjacentHTML('afterbegin', draftHtml);

                    let savedPend = JSON.parse(localStorage.getItem('customPendidikan')) || [];
                    if (!savedPend.includes(inputVal)) {
                        savedPend.push(inputVal); localStorage.setItem('customPendidikan', JSON.stringify(savedPend));
                    }
                }

                selectElement.value = inputVal;

                closeModalTambahPendidikan();
                document.getElementById('inputPendidikanBaru').value = '';

                saveFormDraft();
                alert('Pendidikan "' + inputVal + '" berhasil ditambahkan ke pilihan! Data akan tersimpan permanen ke database saat Anda menyimpan formulir utama.');
            }
        }

        // ==============================================================
        // FITUR BARU: Fungsi untuk membatalkan (menghapus) DRAFT dari browser
        // ==============================================================
        function hapusDraftPendidikan(val, elementId) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                return;
            }

            // 1. Hapus dari <select> dropdown utama
            const selectElement = document.querySelector('select[name="pendidikan_input"]');
            const optionToRemove = Array.from(selectElement.options).find(opt => opt.value === val);
            if (optionToRemove) {
                optionToRemove.remove();
            }

            // 2. Hapus dari jendela modal
            const draftElement = document.getElementById(elementId);
            if (draftElement) {
                draftElement.remove();
            }

            // Kembalikan ke pilihan default ("-- Pilih Pendidikan --")
            selectElement.value = "";
            saveFormDraft();

            let savedPend = JSON.parse(localStorage.getItem('customPendidikan')) || [];
            savedPend = savedPend.filter(item => item !== val);
            localStorage.setItem('customPendidikan', JSON.stringify(savedPend));
        }

        // Database Desa dan Kelurahan Kabupaten Malang
        const daftarKelurahan = [ "Ardirejo", "Candirenggo", "Cepokomulyo", "Dampit", "Kalirejo", "Kepanjen", "Lawang", "Losari", "Pagentan", "Penarukan", "Sedayu", "Turen" ];
        const daftarDesa = [ "Amadanom", "Ampeldento (Karangploso)", "Ampeldento (Pakis)", "Ampelgading", "Ardimulyo", "Argosari", "Argosuko", "Argotirto", "Argoyuwono", "Arjosari", "Arjowilangun", "Asrikaton", "Babadan", "Bakalan", "Balearjo", "Balesari", "Bambang", "Bandungrejo", "Bangelan", "Banjararum", "Banjarejo (Donomulyo)", "Banjarejo (Ngantang)", "Banjarejo (Pagelaran)", "Banjarejo (Pakis)", "Banjarsari", "Bantur", "Banturejo", "Baturetno (Dampit)", "Baturetno (Singosari)", "Bayem", "Bedali", "Belung", "Bendosari", "Benjor", "Blayu", "Bocek", "Bokor", "Bringin", "Brongkal", "Bululawang", "Bulupitu", "Bumirejo", "Bunutwetan", "Clumprit", "Codo", "Curungrejo", "Dadapan", "Dalisodo", "Dawuhan", "Dengkol", "Dilem", "Donomulyo", "Donowarih", "Druju", "Duwet", "Duwet Krajan", "Gading", "Gadingkembar", "Gadingkulon", "Gadungsari", "Gajahrejo", "Gampingan", "Ganjaran", "Gedangan", "Gedog Kulon", "Gedog Wetan", "Genengan", "Girimoyo", "Girimulyo", "Glanggang", "Gondanglegi Kulon", "Gondanglegi Wetan", "Gondowangi", "Gubukklakah", "Gunung Jati", "Gunungrejo", "Gunungronggo", "Gunungsari", "Harjokuncaran", "Jabung", "Jambangan", "Jambearjo", "Jambesari", "Jambuwer", "Jatiguwi", "Jatikerto", "Jatirejoyoso", "Jatisari (Pakisaji)", "Jatisari (Tajinan)", "Jedong", "Jenggolo", "Jeru (Tumpang)", "Jeru (Turen)", "Jogomulyan", "Jombok", "Kademangan", "Kaliasri", "Kalipare", "Kalirejo", "Kalisongo", "Kambingan", "Kanigoro", "Karanganyar", "Karangduren", "Karangkates", "Karangnongko", "Karangpandan", "Karangrejo", "Karangsari", "Karangsuko", "Karangwidoro", "Kasembon (Bululawang)", "Kasembon (Kasembon)", "Kasri", "Kaumrejo", "Kebobang", "Kebonagung", "Kedok", "Kedungbanteng", "Kedungpedaringan", "Kedungrejo", "Kedungsalam", "Kemantren", "Kemiri (Jabung)", "Kemiri (Kepanjen)", "Kemulan", "Kendalpayak", "Kenongo", "Kepatihan", "Kepuharjo", "Kesamben", "Ketawang", "Ketindan", "Kidal", "Kidangbang", "Klampok", "Klepu", "Kluwut", "Kranggan", "Krebet", "Krebet Senggrong", "Kromengan", "Kucur", "Kuwolu", "Landungsari", "Lang-Lang", "Lebakharjo", "Lumbangsari", "Madiredo", "Maguan", "Majangtengah", "Malangsuko", "Mangliawan", "Mangunrejo", "Mendalanwangi", "Mentaraman", "Mojosari", "Mulyoagung", "Mulyoarjo", "Mulyoasri", "Mulyorejo", "Ngabab", "Ngadas", "Ngadilangkung", "Ngadirejo (Jabung)", "Ngadirejo (Kromengan)", "Ngadireso", "Ngajum", "Ngantru", "Ngasem", "Ngawonggo", "Ngebruk (Poncokusumo)", "Ngebruk (Sumberpucung)", "Ngembal", "Ngenep", "Ngijo", "Ngingit", "Ngroto", "Pagak", "Pagedangan", "Pagelaran", "Pagersari", "Pait", "Pajaran", "Pakisaji", "Pakisjajar", "Pakiskembar", "Palaan", "Pamotan", "Pandanajeng", "Pandanlandung", "Pandanmulyo", "Pandanrejo (Pagak)", "Pandanrejo (Wagir)", "Pandansari (Ngantang)", "Pandansari (Poncokusumo)", "Pandansari Lor", "Pandesari", "Panggungrejo (Gondanglegi)", "Panggungrejo (Kepanjen)", "Parangargo", "Patokpicis", "Peniwen", "Permanu", "Petungsewu (Dau)", "Petungsewu (Wagir)", "Plandi", "Plaosan", "Pojok", "Poncokusumo", "Pondokagung", "Pringgodani", "Pringu", "Pucangsongo", "Pujiharjo", "Pujon Kidul", "Pujon Lor", "Pulungdowo", "Purwoasri", "Purwodadi (Donomulyo)", "Purwodadi (Tirtoyudo)", "Purwoharjo", "Purworejo (Donomulyo)", "Purworejo (Ngantang)", "Purwosekar", "Putat Kidul", "Putat Lor", "Putukrejo (Gondanglegi)", "Putukrejo (Kalipare)", "Randuagung", "Randugading", "Rejosari", "Rejoyoso", "Rembun", "Ringinkembar", "Ringinsari", "Sambigede", "Sanankerto", "Sananrejo", "Saptorenggo", "Sawahan", "Segaran", "Sekarbanyu", "Sekarpuro", "Selorejo", "Sempalwadak", "Sempol", "Senggreng", "Sengguruh", "Sepanjang", "Sidoasri", "Sidodadi (Gedangan)", "Sidodadi (Lawang)", "Sidodadi (Ngantang)", "Sidoluhur", "Sidomulyo", "Sidorahayu", "Sidorejo (Jabung)", "Sidorejo (Pagelaran)", "Sidorenggo", "Simojayan", "Sindurejo", "Sitiarjo", "Sitirejo", "Slamet", "Slamparejo", "Slorok", "Sonowangi", "Srigading", "Srigonco", "Srimulyo", "Sudimoro", "Sukoanyar (Pakis)", "Sukoanyar (Wajak)", "Sukodadi", "Sukodono", "Sukolilo (Jabung)", "Sukolilo (Wajak)", "Sukomulyo", "Sukonolo", "Sukopuro", "Sukoraharjo", "Sukorejo (Gondanglegi)", "Sukorejo (Tirtoyudo)", "Sukosari (Gondanglegi)", "Sukosari (Kasembon)", "Sukowilangun", "Sumberagung (Ngantang)", "Sumberagung (Sumbermanjing Wetan)", "Sumberbening", "Sumberdem", "Sumberejo (Gedangan)", "Sumberejo (Pagak)", "Sumberejo (Poncokusumo)", "Sumberjaya", "Sumberkerto", "Sumberkradenan", "Sumbermanjing Kulon", "Sumbermanjing Wetan", "Sumberngepoh", "Sumberoto", "Sumberpasir", "Sumberpetung", "Sumberporong", "Sumberpucung", "Sumberputih", "Sumbersekar", "Sumbersuko (Dampit)", "Sumbersuko (Tajinan)", "Sumbersuko (Wagir)", "Sumbertangkil", "Sumbertempur", "Sutojayan", "Suwaru", "Taji", "Tajinan", "Talangagung", "Talangsuko", "Talok", "Tamanasri", "Tamanharjo", "Tamankuncaran", "Tamansari", "Tamansatriyan", "Tambakasri (Sumbermanjing Wetan)", "Tambakasri (Tajinan)", "Tambakrejo", "Tanggung", "Tangkilsari", "Tawangagung", "Tawangargo", "Tawangrejeni", "Tawangsari", "Tegalgondo", "Tegalrejo", "Tegalsari", "Tegalweru", "Tempursari", "Ternyang", "Tirtomarto", "Tirtomoyo (Ampelgading)", "Tirtomoyo (Pakis)", "Tirtoyudo", "Tlogorejo", "Tlogosari (Donomulyo)", "Tlogosari (Tirtoyudo)", "Toyomarto", "Tulungrejo (Donomulyo)", "Tulungrejo (Ngantang)", "Tulusbesar", "Tumpakrejo (Gedangan)", "Tumpakrejo (Kalipare)", "Tumpang", "Tumpukrenteng", "Tunjungtirto", "Turirejo", "Undaan", "Urek-Urek", "Wadung", "Wajak", "Wandanpuro", "Watugede", "Waturejo", "Wirotaman", "Wiyurejo", "Wonoagung (Kasembon)", "Wonoagung (Tirtoyudo)", "Wonoayu", "Wonokerso", "Wonokerto", "Wonomulyo", "Wonorejo (Bantur)", "Wonorejo (Lawang)", "Wonorejo (Poncokusumo)", "Wonorejo (Singosari)", "Wonosari", "Wringinanom", "Wringinsongo" ];

        function initSearchableDropdown(inputId, listId, tipeId, updateFunc) {
            const input = document.getElementById(inputId);
            const list = document.getElementById(listId);
            const tipe = document.getElementById(tipeId);
            const toggleIcon = list.previousElementSibling;

            function render(filter = '') {
                list.innerHTML = '';
                const isKelurahan = tipe.value === 'Kelurahan';
                const data = isKelurahan ? daftarKelurahan : daftarDesa;

                const filtered = data.filter(item => item.toLowerCase().includes(filter.toLowerCase()));

                if (filtered.length === 0) {
                    list.innerHTML = '<div class="px-3 py-2 text-slate-400 italic text-xs">Tidak ditemukan</div>';
                    return;
                }

                filtered.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 hover:bg-indigo-50 cursor-pointer text-slate-700 transition-colors text-sm border-b border-slate-50 last:border-0';
                    div.textContent = item;
                    div.onclick = function() {
                        input.value = item;
                        list.classList.add('hidden');
                        updateFunc();
                    };
                    list.appendChild(div);
                });
            }

            input.addEventListener('focus', function() {
                render(this.value);
                list.classList.remove('hidden');
            });

            input.addEventListener('input', function() {
                render(this.value);
                list.classList.remove('hidden');
                updateFunc();
            });

            toggleIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                if (list.classList.contains('hidden')) {
                    render(input.value);
                    list.classList.remove('hidden');
                    input.focus();
                } else {
                    list.classList.add('hidden');
                }
            });

            tipe.addEventListener('change', function() {
                input.value = '';
                render();
                updateFunc();
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            ['list_desa_ktp', 'list_desa_domisili'].forEach(id => {
                const list = document.getElementById(id);
                const inputId = id.replace('list_', '');
                const input = document.getElementById(inputId);

                if (list && input) {
                    const toggleIcon = list.previousElementSibling;
                    if (!input.contains(e.target) && !list.contains(e.target) && !toggleIcon.contains(e.target)) {
                        list.classList.add('hidden');
                    }
                }
            });
        });

        // Toggles Animasi Form KTP
        function toggleModeKtp() {
            try {
                const mode = document.querySelector('input[name="mode_ktp"]:checked').value;
                if (mode === 'otomatis') {
                    document.getElementById('blok_otomatis_ktp').classList.remove('hidden');
                    document.getElementById('blok_manual_ktp').classList.add('hidden');
                } else {
                    document.getElementById('blok_otomatis_ktp').classList.add('hidden');
                    document.getElementById('blok_manual_ktp').classList.remove('hidden');
                }
                updateAlamatKtp();
            } catch (e) { console.error(e); }
        }

        // Toggles Animasi Form Domisili
        function toggleModeDomisili() {
            try {
                const mode = document.querySelector('input[name="mode_domisili"]:checked').value;
                if (mode === 'otomatis') {
                    document.getElementById('blok_otomatis_domisili').classList.remove('hidden');
                    document.getElementById('blok_manual_domisili').classList.add('hidden');
                } else {
                    document.getElementById('blok_otomatis_domisili').classList.add('hidden');
                    document.getElementById('blok_manual_domisili').classList.remove('hidden');
                }
                updateAlamatDomisili();
            } catch(e) { console.error(e); }
        }

        // Fungsi Salin Alamat KTP -> Domisili
        function salinAlamat() {
            try {
                const isChecked = document.getElementById('copy_alamat').checked;
                if (isChecked) {
                    // Menyamakan mode radio button
                    const modeKtp = document.querySelector('input[name="mode_ktp"]:checked').value;
                    document.querySelector(`input[name="mode_domisili"][value="${modeKtp}"]`).checked = true;
                    toggleModeDomisili();

                    if (modeKtp === 'otomatis') {
                        document.getElementById('jalan_domisili').value = document.getElementById('jalan_ktp').value;
                        document.getElementById('tipe_desa_domisili').value = document.getElementById('tipe_desa_ktp').value;

                        // Trigger perenderan agar daftar list cocok dengan tipe baru
                        const ddlInput = document.getElementById('desa_domisili');
                        const ddlList = document.getElementById('list_desa_domisili');
                        ddlInput.value = document.getElementById('desa_ktp').value;

                        document.getElementById('kecamatan_domisili').value = document.getElementById('kecamatan_ktp').value;
                    } else {
                        document.getElementById('manual_domisili').value = document.getElementById('manual_ktp').value;
                    }
                } else {
                    document.getElementById('jalan_domisili').value = '';
                    document.getElementById('desa_domisili').value = '';
                    document.getElementById('kecamatan_domisili').value = '';
                    document.getElementById('manual_domisili').value = '';
                }
                updateAlamatDomisili();
                saveFormDraft();
            } catch(e) { console.error(e); }
        }

        function loadCustomOptions() {
            // Load Pendidikan
            let savedPend = JSON.parse(localStorage.getItem('customPendidikan')) || [];
            const selectPend = document.getElementById('selectPendidikan');
            const listKelolaPend = document.getElementById('listKelolaPendidikan');

            savedPend.forEach(val => {
                let exists = Array.from(selectPend.options).some(opt => opt.value === val);
                if (!exists) {
                    const newOption = document.createElement('option');
                    newOption.value = val;
                    newOption.textContent = val;
                    selectPend.appendChild(newOption);

                    const emptyMsg = listKelolaPend.querySelector('.empty-msg');
                    if(emptyMsg) emptyMsg.remove();
                    const draftId = 'draft-' + val.replace(/\s+/g, '-').toLowerCase();
                    const draftHtml = `
                        <div class="flex justify-between items-center p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors" id="${draftId}">
                            <span class="text-sm font-bold text-slate-700">${val}</span>
                            <button type="button" onclick="hapusDraftPendidikan('${val}', '${draftId}')" class="text-xs font-bold text-rose-500 bg-white hover:bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </div>
                    `;
                    listKelolaPend.insertAdjacentHTML('afterbegin', draftHtml);
                }
            });

            // Load Rekomendasi TAT
            let savedRek = JSON.parse(localStorage.getItem('customRekomendasi')) || [];
            const selectRek = document.getElementById('selectTempatRekomendasi');
            const listKelolaRek = document.getElementById('listKelolaRekomendasi');

            savedRek.forEach(obj => {
                let exists = Array.from(selectRek.options).some(opt => opt.value === obj.val && opt.getAttribute('data-kategori') === obj.cat);
                if (!exists) {
                    const newOption = document.createElement('option');
                    newOption.value = obj.val;
                    newOption.textContent = obj.val;
                    newOption.setAttribute('data-kategori', obj.cat);
                    newOption.style.display = 'none';
                    newOption.disabled = true;
                    selectRek.appendChild(newOption);

                    const emptyMsg = listKelolaRek.querySelector('.empty-msg');
                    if(emptyMsg) emptyMsg.remove();
                    const draftId = 'draft-rek-' + obj.val.replace(/\s+/g, '-').toLowerCase() + '-' + obj.cat.replace(/\s+/g, '-').toLowerCase();
                    const draftHtml = `
                        <div data-kategori="${obj.cat}" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors" id="${draftId}">
                            <span class="text-sm font-bold text-slate-700">${obj.val}</span>
                            <button type="button" onclick="hapusDraftRekomendasi('${obj.val}', '${draftId}')" class="text-xs font-bold text-rose-500 bg-white hover:bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </div>
                    `;
                    listKelolaRek.insertAdjacentHTML('afterbegin', draftHtml);
                }
            });
        }

        const mainForm = document.getElementById('formEditKlien');
        const autosaveIndicator = document.getElementById('autosaveIndicator');

        function saveFormDraft() {
            if(!mainForm) return;
            const formData = new FormData(mainForm);
            const data = {};
            formData.forEach((value, key) => {
                // Ignore tokens, files, and multi-select arrays to avoid issues in localStorage
                if(key !== '_token' && key !== '_method' && key !== 'foto_klien' && !key.includes('narkotika_id[]')) {
                    data[key] = value;
                }
            });
            localStorage.setItem('formKlienEditDraft_{{ $asesmen->id }}', JSON.stringify(data));
            if(autosaveIndicator) {
                autosaveIndicator.classList.remove('hidden');
                setTimeout(() => { autosaveIndicator.classList.add('hidden'); }, 3000);
            }
        }

        function loadFormDraft() {
            if(!mainForm) return;
            const draft = localStorage.getItem('formKlienEditDraft_{{ $asesmen->id }}');
            if (draft) {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    const elements = mainForm.querySelectorAll(`[name="${key}"]`);
                    if (elements.length > 0) {
                        const el = elements[0];
                        if (el.type === 'radio' || el.type === 'checkbox') {
                            const target = mainForm.querySelector(`[name="${key}"][value="${data[key]}"]`);
                            if (target) target.checked = true;
                        } else {
                            el.value = data[key];
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Restore Custom Options
            loadCustomOptions();

            // Restore Form Draft
            loadFormDraft();

            // Render ulang 4 grid saran case conference jika ada draft yang terload
            const hiddenSaranVal = document.getElementById('hidden_saran_cc');
            if(hiddenSaranVal && hiddenSaranVal.value) {
                const splitSaran = hiddenSaranVal.value.split(',');
                const saranVisuals = document.querySelectorAll('.saran-cc-input');
                saranVisuals.forEach((el, index) => {
                    el.value = splitSaran[index] ? splitSaran[index].trim() : '';
                });
            }

            // Jalankan Toggle Tampilan Default Berdasarkan Form Draft
            toggleModeKtp();
            toggleModeDomisili();

            // =========================================================
            // INISIALISASI STATE AWAL DATA EDIT REKOMENDASI TAT
            // =========================================================
            let oldRek = "{!! old('rekomendasi_input', $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? '') !!}";

            if (oldRek) {
                let kategori = "";
                let tempat = "";

                if (oldRek.startsWith('Rawat Jalan')) {
                    kategori = 'Rawat Jalan';
                    tempat = oldRek.substring(11).replace(/^[\s-]+/, '').trim();
                } else if (oldRek.startsWith('Rawat Inap')) {
                    kategori = 'Rawat Inap';
                    tempat = oldRek.substring(10).replace(/^[\s-]+/, '').trim();
                } else if (oldRek.startsWith('Rehab di Lapas / Rutan')) {
                    kategori = 'Rehab di Lapas / Rutan';
                    tempat = oldRek.substring(22).replace(/^[\s-]+/, '').trim();
                } else if (oldRek.startsWith('Tidak Rehab (Proses Hukum)')) {
                    kategori = 'Tidak Rehab (Proses Hukum)';
                    tempat = oldRek.substring(26).replace(/^[\s-]+/, '').trim();
                }

                if (kategori) {
                    let radio = document.querySelector(`input[name="kategori_rekomendasi"][value="${kategori}"]`);
                    if (radio) {
                        radio.checked = true;
                        toggleRekomendasi();

                        const selectRek = document.getElementById('selectTempatRekomendasi');
                        if (tempat) {
                            let exists = Array.from(selectRek.options).some(opt => opt.value === tempat);
                            if(!exists) {
                               const newOption = document.createElement('option');
                               newOption.value = tempat;
                               newOption.textContent = tempat;
                               newOption.setAttribute('data-kategori', kategori);
                               newOption.style.display = '';
                               newOption.disabled = false;
                               selectRek.appendChild(newOption);
                            }
                            selectRek.value = tempat;
                        } else {
                            selectRek.value = 'Bawaan Sistem';
                        }
                    }
                }
            }
            updateRekomendasiPreview();

            // Trigger Sync awal untuk mode Edit Multi-Select
            updateNarkotikaText();

            // Trigger bb dan pasal
            const bbInput = document.querySelector('input[name="berat_bb"]');
            if(bbInput && bbInput.value) {
                const displayBb = document.getElementById('display_berat_bb');
                if(displayBb) displayBb.value = bbInput.value + ' Gr';
            }

            const pasalInput = document.querySelector('input[name="pasal_sangkaan"]');
            if(pasalInput && pasalInput.value) {
                const displayPasal = document.getElementById('display_pasal_sangkaan');
                if(displayPasal) displayPasal.value = pasalInput.value;
            }

            // Jalankan Searchable Dropdown
            initSearchableDropdown('desa_ktp', 'list_desa_ktp', 'tipe_desa_ktp', updateAlamatKtp);
            initSearchableDropdown('desa_domisili', 'list_desa_domisili', 'tipe_desa_domisili', updateAlamatDomisili);

            // Format Rupiah
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

            // HITUNG USIA OTOMATIS CERDAS & ANTI GAGAL (Memakai created_at database)
            function hitungUsia() {
                try {
                    const tglLahirInput = document.getElementById('tgl_lahir').value.trim();
                    const usiaInput = document.getElementById('usia');

                    if (!tglLahirInput) {
                        usiaInput.value = '';
                        return;
                    }

                    let rawDate = tglLahirInput.split(' ')[0];
                    let parts = rawDate.split('-');
                    let dob;

                    if (parts.length === 3) {
                        let p0 = parts[0];
                        let p2 = parts[2];
                        if (p0.length === 4) {
                            dob = new Date(parseInt(p0), parseInt(parts[1]) - 1, parseInt(p2));
                        } else if (p2.length === 4) {
                            dob = new Date(parseInt(p2), parseInt(parts[1]) - 1, parseInt(p0));
                        }
                    }

                    if (!dob || isNaN(dob.getTime())) {
                        dob = new Date(rawDate);
                    }

                    if (isNaN(dob.getTime())) {
                        return;
                    }

                    let refDateStr = "{{ $asesmen->created_at ? \Carbon\Carbon::parse($asesmen->created_at)->format('Y-m-d') : now()->format('Y-m-d') }}";
                    let refDate = new Date(refDateStr);

                    let age = refDate.getFullYear() - dob.getFullYear();
                    let m = refDate.getMonth() - dob.getMonth();

                    if (m < 0 || (m === 0 && refDate.getDate() < dob.getDate())) {
                        age--;
                    }

                    if(age >= 0) {
                        usiaInput.value = age + ' Tahun';
                    }
                } catch(e) {
                    console.error("Penghitungan Usia Gagal:", e);
                }
            }

            const tglLahirEl = document.getElementById('tgl_lahir');
            if(tglLahirEl) {
                tglLahirEl.addEventListener('change', hitungUsia);
                tglLahirEl.addEventListener('input', hitungUsia);
                tglLahirEl.addEventListener('blur', hitungUsia);
                hitungUsia();
            }

            let lastTgl = tglLahirEl ? tglLahirEl.value : '';
            setInterval(function() {
                let currentTgl = tglLahirEl ? tglLahirEl.value : '';
                if(currentTgl !== lastTgl) {
                    lastTgl = currentTgl;
                    hitungUsia();
                }
            }, 500);

            // Fungsi Real-time Update Alamat KTP
            function updateAlamatKtp() {
                try {
                    const mode = document.querySelector('input[name="mode_ktp"]:checked').value;
                    let finalAlamat = '';

                    if (mode === 'otomatis') {
                        const jalan = document.getElementById('jalan_ktp').value.trim();
                        const tipeDesa = document.getElementById('tipe_desa_ktp').value;
                        const desa = document.getElementById('desa_ktp').value.trim();
                        const kec = document.getElementById('kecamatan_ktp').value;
                        const kab = document.getElementById('kabupaten_ktp').value;

                        let parts = [];
                        if (jalan) parts.push(jalan);
                        if (desa) parts.push(tipeDesa + ' ' + desa);
                        if (kec) parts.push('Kecamatan ' + kec);
                        if (kab) parts.push(kab);

                        finalAlamat = parts.join(', ');
                    } else {
                        finalAlamat = document.getElementById('manual_ktp').value.trim();
                    }

                    document.getElementById('preview_alamat_ktp').value = finalAlamat;
                    document.getElementById('hidden_alamat_ktp').value = finalAlamat;
                } catch(e) { console.error(e); }
            }

            // Fungsi Real-time Update Alamat Domisili
            function updateAlamatDomisili() {
                try {
                    const mode = document.querySelector('input[name="mode_domisili"]:checked').value;
                    let finalAlamat = '';

                    if (mode === 'otomatis') {
                        const jalan = document.getElementById('jalan_domisili').value.trim();
                        const tipeDesa = document.getElementById('tipe_desa_domisili').value;
                        const desa = document.getElementById('desa_domisili').value.trim();
                        const kec = document.getElementById('kecamatan_domisili').value;
                        const kab = document.getElementById('kabupaten_domisili').value;

                        let parts = [];
                        if (jalan) parts.push(jalan);
                        if (desa) parts.push(tipeDesa + ' ' + desa);
                        if (kec) parts.push('Kecamatan ' + kec);
                        if (kab) parts.push(kab);

                        finalAlamat = parts.join(', ');
                    } else {
                        finalAlamat = document.getElementById('manual_domisili').value.trim();
                    }

                    document.getElementById('preview_alamat_domisili').value = finalAlamat;
                    document.getElementById('hidden_alamat_domisili').value = finalAlamat;
                } catch(e) { console.error(e); }
            }

            const otomatisKtpFields = ['jalan_ktp', 'kecamatan_ktp', 'kabupaten_ktp'];
            otomatisKtpFields.forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateAlamatKtp);
                    el.addEventListener('change', updateAlamatKtp);
                }
            });

            const otomatisDomisiliFields = ['jalan_domisili', 'kecamatan_domisili', 'kabupaten_domisili'];
            otomatisDomisiliFields.forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateAlamatDomisili);
                    el.addEventListener('change', updateAlamatDomisili);
                }
            });

            document.getElementById('manual_ktp').addEventListener('input', updateAlamatKtp);
            document.getElementById('manual_domisili').addEventListener('input', updateAlamatDomisili);

            if(mainForm) {
                mainForm.addEventListener('input', saveFormDraft);
                mainForm.addEventListener('change', saveFormDraft);

                // Mencegah data gagal simpan saat submit
                mainForm.addEventListener('submit', function(e) {
                    updateAlamatKtp();
                    updateAlamatDomisili();
                    updateRekomendasiPreview();
                    localStorage.removeItem('formKlienEditDraft_{{ $asesmen->id }}');
                });
            }

        });

        // PREVIEW FOTO
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('previewFoto');
            const placeholder = document.getElementById('iconPlaceholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                if ("{{ $asesmen->foto_klien }}") {
                    preview.src = "{{ asset('storage/' . $asesmen->foto_klien) }}";
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    preview.src = '#';
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }
            }
        }

        // FUNGSI REKOMENDASI
        function toggleRekomendasi() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked');
            const select = document.getElementById('selectTempatRekomendasi');
            const btnTambah = document.getElementById('btnTambahRekomendasi');
            const btnKelola = document.getElementById('btnKelolaRekomendasi');

            select.disabled = false;
            select.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            select.classList.add('bg-slate-50', 'text-slate-700');
            btnTambah.disabled = false;
            btnTambah.classList.remove('bg-slate-200', 'text-slate-400', 'cursor-not-allowed');
            btnTambah.classList.add('bg-indigo-100', 'text-indigo-700', 'hover:bg-indigo-200');
            btnKelola.disabled = false;
            btnKelola.classList.remove('bg-slate-200', 'text-slate-400', 'cursor-not-allowed');
            btnKelola.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');

            if (radio) {
                const selectedCat = radio.value;
                const options = select.querySelectorAll('option');
                options.forEach(opt => {
                    if (opt.value === '') {
                        opt.style.display = '';
                        opt.disabled = false;
                    }
                    else if (opt.getAttribute('data-kategori') === selectedCat || opt.getAttribute('data-kategori') === 'Semua') {
                        opt.style.display = '';
                        opt.disabled = false;
                    }
                    else {
                        opt.style.display = 'none';
                        opt.disabled = true;
                    }
                });

                let currentVal = select.value;
                let isValid = Array.from(options).some(opt => opt.value === currentVal && opt.style.display !== 'none');
                if(!isValid) select.value = '';
            }
            updateRekomendasiPreview();
        }

        function updateRekomendasiPreview() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked');
            const select = document.getElementById('selectTempatRekomendasi');
            const hiddenInput = document.getElementById('hidden_rekomendasi_input');
            const preview = document.getElementById('teks_preview_rekomendasi');

            if (radio) {
                let finalValue = radio.value;
                if (select.value && select.value !== 'Bawaan Sistem') {
                    finalValue += ' - ' + select.value;
                }
                hiddenInput.value = finalValue;
                preview.innerHTML = `Hasil akhir Rekomendasi: <span class="font-bold text-indigo-600">${finalValue}</span>`;
            } else {
                hiddenInput.value = '';
                preview.innerHTML = `Hasil akhir Rekomendasi: <span class="font-bold text-slate-400">Belum dipilih</span>`;
            }
        }

        function openModalTambahRekomendasi() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked');
            if (!radio) { alert("Pilih Kategori Perawatan (Radio Button) terlebih dahulu di form utama!"); return; }
            document.getElementById('tambahKategoriDisplay').textContent = radio.value;
            const modal = document.getElementById('modalTambahRekomendasi');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.classList.remove('opacity-0'); modal.children[0].classList.remove('scale-95'); }, 10);
        }

        function closeModalTambahRekomendasi() {
            const modal = document.getElementById('modalTambahRekomendasi');
            modal.classList.add('opacity-0'); modal.children[0].classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        function openModalKelolaRekomendasi() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked');
            if (!radio) { alert("Pilih Kategori Perawatan (Radio Button) terlebih dahulu di form utama!"); return; }
            const selectedCat = radio.value;
            const modalTitle = document.getElementById('modalKelolaRekomendasiTitle');
            if(modalTitle) modalTitle.innerText = 'Kelola Opsi (' + selectedCat + ')';
            const items = document.querySelectorAll('#listKelolaRekomendasi .rekomendasi-item');
            let visibleCount = 0;
            items.forEach(item => {
                if (item.getAttribute('data-kategori') === selectedCat || item.getAttribute('data-kategori') === 'Semua') {
                    item.classList.remove('hidden'); item.classList.add('flex'); visibleCount++;
                } else {
                    item.classList.remove('flex'); item.classList.add('hidden');
                }
            });
            const emptyMsg = document.getElementById('emptyMsgRekomendasi');
            if (emptyMsg) {
                if (visibleCount === 0) emptyMsg.classList.remove('hidden'); else emptyMsg.classList.add('hidden');
            }
            const modal = document.getElementById('modalKelolaRekomendasi');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.classList.remove('opacity-0'); modal.children[0].classList.remove('scale-95'); }, 10);
        }

        function closeModalKelolaRekomendasi(force = false) {
            const modal = document.getElementById('modalKelolaRekomendasi');
            modal.classList.add('opacity-0'); modal.children[0].classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        function tambahRekomendasiJS(event) {
            event.preventDefault();
            const jenisRadio = document.querySelector('input[name="kategori_rekomendasi"]:checked');
            if (!jenisRadio) { alert("Pilih Kategori Perawatan (Radio Button) terlebih dahulu di form utama!"); return; }
            const detailVal = document.getElementById('inputRekomendasiDetail').value.trim();
            if (!detailVal) return;

            const fullValue = jenisRadio.value + " - " + detailVal;
            const selectElement = document.getElementById('selectTempatRekomendasi');
            let exists = Array.from(selectElement.options).some(opt => opt.value === detailVal && opt.getAttribute('data-kategori') === jenisRadio.value);

            if(!exists) {
                const newOption = document.createElement('option');
                newOption.value = detailVal;
                newOption.textContent = detailVal;
                newOption.setAttribute('data-kategori', jenisRadio.value);
                newOption.style.display = '';
                newOption.disabled = false;

                selectElement.appendChild(newOption);

                const listKelola = document.getElementById('listKelolaRekomendasi');
                const emptyMsg = listKelola.querySelector('.empty-msg');
                if(emptyMsg) emptyMsg.remove();
                const draftId = 'draft-rek-' + detailVal.replace(/\s+/g, '-').toLowerCase() + '-' + jenisRadio.value.replace(/\s+/g, '-').toLowerCase();
                const draftHtml = `
                    <div data-kategori="${jenisRadio.value}" class="rekomendasi-item flex justify-between items-center p-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors" id="${draftId}">
                        <span class="text-sm font-bold text-slate-700">${detailVal}</span>
                        <button type="button" onclick="hapusDraftRekomendasi('${detailVal}', '${draftId}')" class="text-xs font-bold text-rose-500 bg-white hover:bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                    </div>
                `;
                listKelola.insertAdjacentHTML('afterbegin', draftHtml);

                let savedRek = JSON.parse(localStorage.getItem('customRekomendasi')) || [];
                let isSaved = savedRek.some(r => r.val === detailVal && r.cat === jenisRadio.value);
                if (!isSaved) {
                    savedRek.push({ val: detailVal, cat: jenisRadio.value, full: fullValue });
                    localStorage.setItem('customRekomendasi', JSON.stringify(savedRek));
                }
            }
            selectElement.value = detailVal;
            updateRekomendasiPreview();
            closeModalTambahRekomendasi();
            document.getElementById('inputRekomendasiDetail').value = '';
            saveFormDraft();
            alert('Rekomendasi "' + fullValue + '" berhasil ditambahkan ke pilihan dan tersimpan otomatis!');
        }

        function hapusDraftRekomendasi(val, elementId) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;
            const selectElement = document.getElementById('selectTempatRekomendasi');
            const optionToRemove = Array.from(selectElement.options).find(opt => opt.value === val);
            if (optionToRemove) optionToRemove.remove();

            const draftElement = document.getElementById(elementId);
            if (draftElement) draftElement.remove();

            selectElement.value = "";
            updateRekomendasiPreview();
            saveFormDraft();

            let savedRek = JSON.parse(localStorage.getItem('customRekomendasi')) || [];
            savedRek = savedRek.filter(item => item.val !== val);
            localStorage.setItem('customRekomendasi', JSON.stringify(savedRek));
        }

    </script>
</x-app-layout>