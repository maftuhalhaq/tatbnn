<x-app-layout>
    <!-- CSS Custom untuk Scrollbar Dropdown & Animasi -->
    <style>
        .custom-select-scroll::-webkit-scrollbar { width: 6px; }
        .custom-select-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-select-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media (prefers-color-scheme: dark) {
            .custom-select-scroll::-webkit-scrollbar-thumb { background: #334155; }
            .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
        }

        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .autosave-active { animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -40px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-blob { animation: blob 15s infinite alternate; }
        .animation-delay-2000 { animation-delay: 4s; }
        .animation-delay-4000 { animation-delay: 8s; }
    </style>

    <!-- BACKGROUND BASE & BLOBS -->
    <div class="fixed inset-0 z-0 bg-[#F8FAFC] dark:bg-[#0B1120] transition-colors duration-500 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- HEADER -->
    <x-slot name="header">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 max-w-[1400px] mx-auto w-full">
            <div class="flex items-center gap-4">
                <a href="{{ route('asesmen.index') }}" class="w-12 h-12 rounded-[1rem] bg-white dark:bg-[#1E293B] text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-slate-200 dark:border-slate-700/50 shadow-sm shrink-0 hover:scale-105 transition-transform group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-1 border border-blue-200 dark:border-blue-800/50 shadow-sm">
                        Registrasi Klien
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                        Tambah Data Baru
                        <span id="autosaveIndicator" class="hidden text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800/50 px-2 py-1 rounded-md uppercase tracking-wider autosave-active">
                            Draft Disimpan Otomatis
                        </span>
                    </h1>
                </div>
            </div>
            <p class="text-xs font-bold text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] px-4 py-2 rounded-xl border border-rose-100 dark:border-rose-900/50 shadow-sm hidden md:block">
                Isi data dengan teliti. Tanda <span class="text-rose-500 font-bold">*</span> wajib diisi.
            </p>
        </div>
    </x-slot>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="relative z-10 py-8 min-h-screen">
        <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/50 rounded-2xl flex items-start shadow-sm transition-colors duration-300">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-sm font-extrabold text-rose-800 dark:text-rose-400">Gagal Menyimpan Data!</h4>
                        <ul class="text-[13px] font-semibold text-rose-600 dark:text-rose-300 mt-1 list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- KONTAINER RAKSASA MAC SOFT UI -->
            <div class="bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-white/60 dark:border-slate-700/50 overflow-visible transition-colors duration-300 p-6 sm:p-10 space-y-8 pb-32">

                <!-- 1 FORM UTAMA -->
                <form id="formCreateKlien" action="{{ route('asesmen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf

                    <!-- ========================================== -->
                    <!-- CARD 1: PROFIL KLIEN -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-visible z-[50] transition-colors">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#3B82F6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            1. Profil Klien
                        </h3>

                        <!-- Foto Upload -->
                        <div class="mb-5 bg-slate-50 dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                            <label class="block text-[12px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-3">Pas Foto Klien <span class="text-slate-400 dark:text-slate-500 font-normal lowercase tracking-normal">(Opsional)</span></label>
                            <div class="flex flex-col sm:flex-row items-center gap-5">
                                <div class="shrink-0 w-24 h-32 bg-white dark:bg-[#0F172A] rounded-[1rem] border-2 border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden relative group shadow-sm transition-colors">
                                    <img id="previewFoto" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                                    <svg id="iconPlaceholder" class="w-8 h-8 text-slate-300 dark:text-slate-600 group-hover:text-[#3B82F6] transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <div class="w-full">
                                    <input type="file" name="foto_klien" id="foto_klien" accept="image/jpeg, image/png, image/jpg" class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-[1rem] file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-blue-50 dark:file:bg-blue-900/30 file:text-[#3B82F6] dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/50 transition-all cursor-pointer" onchange="previewImage(event)">
                                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 mt-2 leading-relaxed">Format yang didukung: JPG, JPEG, PNG.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 9 Elemen Identitas Dasar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NIK KTP <span class="text-rose-500">*</span></label>
                                <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" class="block w-full rounded-[1.25rem] shadow-sm {{ $errors->has('nik') ? 'border-rose-500 ring-2 ring-rose-200 dark:ring-rose-900/50 bg-rose-50 dark:bg-rose-900/20 text-rose-900 dark:text-rose-200' : 'border-slate-200 dark:border-slate-700 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A]' }} text-sm font-medium transition-all px-4 py-3 outline-none" placeholder="16 Digit NIK">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <select name="jenis_kelamin" required class="block w-full">
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Lahir</label>
                                <input type="text" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Usia <span class="text-[#3B82F6] dark:text-blue-400 ml-1 lowercase font-bold">(Otomatis)</span></label>
                                <input type="text" name="usia" id="usia" value="" class="block w-full rounded-[1.25rem] border-transparent shadow-inner text-sm font-black bg-blue-50 dark:bg-[#1E293B] text-[#3B82F6] dark:text-blue-400 px-4 py-3 pointer-events-none" placeholder="0 Tahun" readonly>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kewarganegaraan</label>
                                <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia (WNI)') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Agama</label>
                                <select name="agama" class="block w-full">
                                    <option value="">-- Pilih Agama --</option>
                                    @php $agamaList = ['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; @endphp
                                    @foreach($agamaList as $ag)
                                        <option value="{{ $ag }}" {{ old('agama') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown Pekerjaan -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pekerjaan</label>
                                <div class="flex flex-wrap items-start gap-2">
                                    <div class="flex-1 min-w-[200px] w-full">
                                        <select name="pekerjaan_input" id="selectPekerjaan" class="block w-full">
                                            <option value="">-- Pilih Pekerjaan --</option>
                                            @foreach($masterPekerjaan ?? [] as $pk)
                                                @if(trim($pk->nama_pekerjaan) !== '')
                                                    <option value="{{ $pk->nama_pekerjaan }}" {{ old('pekerjaan_input') == $pk->nama_pekerjaan ? 'selected' : '' }}>
                                                        {{ $pk->nama_pekerjaan }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex gap-2 shrink-0">
                                        <button type="button" onclick="openModalTambahPekerjaan()" class="shrink-0 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-100 dark:hover:bg-blue-800/50 transition-colors shadow-sm">+ Tambah</button>
                                        <button type="button" onclick="openModalKelolaPekerjaan()" class="shrink-0 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Kelola</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BLOK ALAMAT KTP & DOMISILI -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8 pt-8 border-t border-slate-100 dark:border-slate-800/60">
                            <!-- ALAMAT KTP -->
                            <div class="bg-white dark:bg-[#1E293B] p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col transition-colors">
                                <h4 class="text-[13px] font-black text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-3 mb-4">Alamat Sesuai KTP</h4>

                                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 p-4 mb-4 bg-slate-50 dark:bg-[#0F172A] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="mode_ktp" value="otomatis" checked class="text-[#3B82F6] focus:ring-[#3B82F6] w-4 h-4 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleModeKtp()"><span class="ml-2 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300 group-hover:text-[#3B82F6] transition-colors">Otomatis (Kab. Malang)</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="mode_ktp" value="manual" class="text-[#3B82F6] focus:ring-[#3B82F6] w-4 h-4 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleModeKtp()"><span class="ml-2 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300 group-hover:text-[#3B82F6] transition-colors">Manual (Luar Daerah)</span></label>
                                </div>

                                <div id="blok_otomatis_ktp" class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jalan / RT RW</label>
                                        <input type="text" id="jalan_ktp" name="jalan_ktp" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-2.5 outline-none transition-all focus:bg-white dark:focus:bg-[#1E293B]" autocomplete="off" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Desa/Kelurahan</label>
                                        <div class="flex gap-2">
                                            <div class="w-[110px] shrink-0">
                                                <select id="tipe_desa_ktp" name="tipe_desa_ktp" class="w-full">
                                                    <option value="Desa">Desa</option>
                                                    <option value="Kelurahan">Kelurahan</option>
                                                </select>
                                            </div>
                                            <div class="relative flex-1">
                                                <input type="text" id="desa_ktp" name="desa_ktp" class="w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm text-xs font-bold bg-slate-50 dark:bg-[#0F172A] focus:bg-white dark:focus:bg-[#1E293B] text-slate-700 dark:text-slate-300 outline-none pr-8 px-4 py-2.5 cursor-pointer placeholder:text-slate-400 transition-colors" placeholder="Ketik nama lalu pilih..." autocomplete="off">
                                                <div class="absolute inset-y-0 right-0 flex items-center px-3 cursor-pointer text-slate-400 toggle-dropdown-ktp"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg></div>
                                                <div id="list_desa_ktp" class="hidden absolute z-[60] w-full mt-2 bg-white dark:bg-[#1E293B] border border-slate-200 dark:border-slate-700 shadow-xl rounded-xl py-2 max-h-48 overflow-y-auto custom-select-scroll"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kecamatan</label>
                                            <select id="kecamatan_ktp" name="kecamatan_ktp" class="block w-full">
                                                <option value="">-- Pilih --</option>
                                                <option value="Ampelgading">Ampelgading</option><option value="Bantur">Bantur</option><option value="Bululawang">Bululawang</option><option value="Dampit">Dampit</option><option value="Dau">Dau</option><option value="Donomulyo">Donomulyo</option><option value="Gedangan">Gedangan</option><option value="Gondanglegi">Gondanglegi</option><option value="Jabung">Jabung</option><option value="Kalipare">Kalipare</option><option value="Karangploso">Karangploso</option><option value="Kasembon">Kasembon</option><option value="Kepanjen">Kepanjen</option><option value="Kromengan">Kromengan</option><option value="Lawang">Lawang</option><option value="Ngajum">Ngajum</option><option value="Ngantang">Ngantang</option><option value="Pagak">Pagak</option><option value="Pagelaran">Pagelaran</option><option value="Pakis">Pakis</option><option value="Pakisaji">Pakisaji</option><option value="Poncokusumo">Poncokusumo</option><option value="Pujon">Pujon</option><option value="Singosari">Singosari</option><option value="Sumbermanjing Wetan">Sumbermanjing Wetan</option><option value="Sumberpucung">Sumberpucung</option><option value="Tajinan">Tajinan</option><option value="Tirtoyudo">Tirtoyudo</option><option value="Tumpang">Tumpang</option><option value="Turen">Turen</option><option value="Wagir">Wagir</option><option value="Wajak">Wajak</option><option value="Wonosari">Wonosari</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kota/Kab</label>
                                            <input type="text" id="kabupaten_ktp" name="kabupaten_ktp" class="block w-full rounded-xl border-transparent shadow-inner text-sm font-bold bg-slate-200/50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 px-3 py-2.5 pointer-events-none" value="Kabupaten Malang" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div id="blok_manual_ktp" class="hidden space-y-4 pt-2">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Ketik Lengkap (Luar Daerah)</label>
                                        <textarea id="manual_ktp" name="manual_ktp" rows="3" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all focus:bg-white dark:focus:bg-[#1E293B]">{{ old('alamat_ktp') }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-auto pt-6 border-t border-slate-100 dark:border-slate-700/50">
                                    <label class="block text-[10px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Hasil Rakitan Alamat KTP</label>
                                    <input type="hidden" name="alamat_ktp" id="hidden_alamat_ktp" value="{{ old('alamat_ktp') }}">
                                    <textarea id="preview_alamat_ktp" rows="2" class="block w-full rounded-xl border border-blue-100 dark:border-blue-900/50 shadow-inner text-[13px] font-bold bg-blue-50/50 dark:bg-blue-900/10 text-[#3B82F6] dark:text-blue-300 px-4 py-3 resize-none cursor-not-allowed" disabled>{{ old('alamat_ktp') }}</textarea>
                                </div>
                            </div>

                            <!-- ALAMAT DOMISILI -->
                            <div class="bg-white dark:bg-[#1E293B] p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col transition-colors">
                                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/50 pb-3 mb-4">
                                    <h4 class="text-[13px] font-black text-slate-800 dark:text-white">Alamat Domisili</h4>
                                    <label class="inline-flex items-center cursor-pointer bg-white dark:bg-[#0F172A] px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm group">
                                        <input type="checkbox" id="copy_alamat" class="rounded text-[#3B82F6] focus:ring-[#3B82F6] w-3.5 h-3.5 bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="salinAlamat()">
                                        <span class="ml-2 text-[9px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Sama Dgn KTP</span>
                                    </label>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 p-4 mb-4 bg-slate-50 dark:bg-[#0F172A] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="mode_domisili" value="otomatis" checked class="text-[#3B82F6] focus:ring-[#3B82F6] w-4 h-4 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleModeDomisili()"><span class="ml-2 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300 group-hover:text-[#3B82F6] transition-colors">Otomatis (Kab. Malang)</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="mode_domisili" value="manual" class="text-[#3B82F6] focus:ring-[#3B82F6] w-4 h-4 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleModeDomisili()"><span class="ml-2 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300 group-hover:text-[#3B82F6] transition-colors">Manual (Luar)</span></label>
                                </div>
                                <div id="blok_otomatis_domisili" class="hidden space-y-4 pt-2">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jalan / RT RW</label>
                                        <input type="text" id="jalan_domisili" name="jalan_domisili" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-2.5 outline-none transition-all focus:bg-white dark:focus:bg-[#1E293B]" autocomplete="off" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Desa/Kelurahan</label>
                                        <div class="flex gap-2">
                                            <div class="w-[110px] shrink-0">
                                                <select id="tipe_desa_domisili" name="tipe_desa_domisili" class="w-full">
                                                    <option value="Desa">Desa</option>
                                                    <option value="Kelurahan">Kelurahan</option>
                                                </select>
                                            </div>
                                            <div class="relative flex-1">
                                                <input type="text" id="desa_domisili" name="desa_domisili" class="w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm text-xs font-bold bg-slate-50 dark:bg-[#0F172A] focus:bg-white dark:focus:bg-[#1E293B] text-slate-700 dark:text-slate-300 outline-none pr-8 px-4 py-2.5 cursor-pointer placeholder:text-slate-400 transition-colors" placeholder="Ketik nama lalu pilih..." autocomplete="off">
                                                <div class="absolute inset-y-0 right-0 flex items-center px-3 cursor-pointer text-slate-400 toggle-dropdown-dom"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg></div>
                                                <div id="list_desa_domisili" class="hidden absolute z-[60] w-full mt-2 bg-white dark:bg-[#1E293B] border border-slate-200 dark:border-slate-700 shadow-xl rounded-xl py-2 max-h-48 overflow-y-auto custom-select-scroll"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kecamatan</label>
                                            <select id="kecamatan_domisili" name="kecamatan_domisili" class="block w-full">
                                                <option value="">-- Pilih --</option>
                                                <option value="Ampelgading">Ampelgading</option><option value="Bantur">Bantur</option><option value="Bululawang">Bululawang</option><option value="Dampit">Dampit</option><option value="Dau">Dau</option><option value="Donomulyo">Donomulyo</option><option value="Gedangan">Gedangan</option><option value="Gondanglegi">Gondanglegi</option><option value="Jabung">Jabung</option><option value="Kalipare">Kalipare</option><option value="Karangploso">Karangploso</option><option value="Kasembon">Kasembon</option><option value="Kepanjen">Kepanjen</option><option value="Kromengan">Kromengan</option><option value="Lawang">Lawang</option><option value="Ngajum">Ngajum</option><option value="Ngantang">Ngantang</option><option value="Pagak">Pagak</option><option value="Pagelaran">Pagelaran</option><option value="Pakis">Pakis</option><option value="Pakisaji">Pakisaji</option><option value="Poncokusumo">Poncokusumo</option><option value="Pujon">Pujon</option><option value="Singosari">Singosari</option><option value="Sumbermanjing Wetan">Sumbermanjing Wetan</option><option value="Sumberpucung">Sumberpucung</option><option value="Tajinan">Tajinan</option><option value="Tirtoyudo">Tirtoyudo</option><option value="Tumpang">Tumpang</option><option value="Turen">Turen</option><option value="Wagir">Wagir</option><option value="Wajak">Wajak</option><option value="Wonosari">Wonosari</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kota/Kab</label>
                                            <input type="text" id="kabupaten_domisili" name="kabupaten_domisili" class="block w-full rounded-xl border-transparent shadow-inner text-sm font-bold bg-slate-200/50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 px-3 py-2.5 pointer-events-none" value="Kabupaten Malang" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div id="blok_manual_domisili" class="hidden space-y-4 pt-2">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Ketik Lengkap (Luar Daerah)</label>
                                        <textarea id="manual_domisili" name="manual_domisili" rows="3" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all focus:bg-white dark:focus:bg-[#1E293B]">{{ old('alamat_domisili') }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-auto pt-6 border-t border-slate-100 dark:border-slate-700/50">
                                    <label class="block text-[10px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Hasil Rakitan Alamat Domisili</label>
                                    <input type="hidden" name="alamat_domisili" id="hidden_alamat_domisili" value="{{ old('alamat_domisili') }}">
                                    <textarea id="preview_alamat_domisili" rows="2" class="block w-full rounded-xl border border-blue-100 dark:border-blue-900/50 shadow-inner text-[13px] font-bold bg-blue-50/50 dark:bg-blue-900/10 text-[#3B82F6] dark:text-blue-300 px-4 py-3 resize-none cursor-not-allowed" disabled>{{ old('alamat_domisili') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 3 Elemen Tambahan (Grid Bawah) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8 pt-8 border-t border-slate-100 dark:border-slate-800/60">
                            <!-- Dropdown Pendidikan -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pendidikan</label>
                                <div class="flex flex-wrap items-start gap-2">
                                    <div class="flex-1 min-w-[200px] w-full">
                                        <select name="pendidikan_input" id="selectPendidikan" class="block w-full">
                                            <option value="">-- Pilih Pendidikan --</option>
                                            @foreach($masterPendidikan ?? [] as $p)
                                                @if(trim($p->nama_pendidikan) !== '')
                                                    <option value="{{ $p->nama_pendidikan }}" {{ old('pendidikan_input') == $p->nama_pendidikan ? 'selected' : '' }}>
                                                        {{ $p->nama_pendidikan }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex gap-2 shrink-0">
                                        <button type="button" onclick="openModalTambahPendidikan()" class="shrink-0 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-100 dark:hover:bg-blue-800/50 transition-colors shadow-sm">+ Tambah</button>
                                        <button type="button" onclick="openModalKelolaPendidikan()" class="shrink-0 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Kelola</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Penghasilan Rata-Rata</label>
                                <input type="text" name="penghasilan_rata_rata" id="penghasilan_rupiah" value="{{ old('penghasilan_rata_rata') }}" placeholder="Misal: Rp 3.000.000" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. Handphone</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08..." class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                        </div>

                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 2: DATA ADMINISTRASI -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-visible z-[40] transition-colors mt-8">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500 rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            2. Data Administrasi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No / Bulan</label>
                                <input type="text" name="no_bln" value="{{ old('no_bln') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Asal Pengajuan</label>
                                <input type="text" name="asal_pengajuan" value="{{ old('asal_pengajuan') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Surat</label>
                                <input type="text" name="tgl_surat" value="{{ old('tgl_surat') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tgl Berkas Diterima</label>
                                <input type="text" name="tgl_berkas" value="{{ old('tgl_berkas') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tgl Pelaksanaan TAT</label>
                                <input type="text" name="tgl_pelaksanaan" value="{{ old('tgl_pelaksanaan') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. Surat Pengajuan</label>
                                <input type="text" name="no_surat_pengajuan" value="{{ old('no_surat_pengajuan') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. LKN / LP / LI</label>
                                <input type="text" name="no_lkn" value="{{ old('no_lkn') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal Penangkapan</label>
                                <input type="text" name="tgl_tangkap" value="{{ old('tgl_tangkap') }}" class="datepicker-id block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Pilih Tanggal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">No. Register</label>
                                <input type="text" name="no_register" value="{{ old('no_register') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 3: CASE CONFERENCE (MAC SOFT UI) -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-visible z-[30] transition-colors mt-8">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#FFBD2E] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-8 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#FFBD2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            3. Case Conference
                        </h3>

                        <!-- ===================================== -->
                        <!-- A. SIDANG HUKUM & BARANG BUKTI -->
                        <!-- ===================================== -->
                        <div class="flex items-center gap-3 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 p-3 rounded-xl mb-6">
                            <span class="bg-amber-200 dark:bg-amber-600 text-amber-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">A</span>
                            <span class="text-[11px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest">Sidang Hukum & Barang Bukti</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- 1. status_hukum -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status Hukum</label>
                                <input type="text" name="status_hukum" value="{{ old('status_hukum') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Cth: Tersangka">
                            </div>

                            <!-- 2. keterlibatan_jaringan (DIUBAH MENJADI INPUT TEKS) -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterlibatan Jaringan</label>
                                <input type="text" name="keterlibatan_jaringan" value="{{ old('keterlibatan_jaringan') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Masukkan keterangan jaringan...">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- 4. berat_bb -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Berat Barang Bukti (GR)</label>
                                <input type="number" step="0.01" name="berat_bb" value="{{ old('berat_bb') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Contoh: 2.50">
                            </div>
                            <!-- 5. deskripsi_bb -->
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Detail Barang Bukti</label>
                                <input type="text" name="deskripsi_bb" value="{{ old('deskripsi_bb') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- 6. cara_mendapatkan -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Cara Mendapatkan</label>
                                <input type="text" name="cara_mendapatkan" value="{{ old('cara_mendapatkan') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <!-- 7. dapat_dari_siapa -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dapat Dari Siapa</label>
                                <input type="text" name="dapat_dari_siapa" value="{{ old('dapat_dari_siapa') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                        </div>

                        <!-- ===================================== -->
                        <!-- B. SIDANG MEDIS -->
                        <!-- ===================================== -->
                        <div class="flex items-center gap-3 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 p-3 rounded-xl mb-6 mt-8">
                            <span class="bg-blue-200 dark:bg-blue-600 text-blue-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">B</span>
                            <span class="text-[11px] font-black text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest">Sidang Medis</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- 9. kesehatan_fisik -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kesehatan (Fisik)</label>
                                <textarea name="kesehatan_fisik" rows="2" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('kesehatan_fisik') }}</textarea>
                            </div>
                            <!-- 10. psikologi -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Psikologi</label>
                                <textarea name="psikologi" rows="2" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('psikologi') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <!-- 11. tes_urine (DIGABUNG DENGAN ZAT NARKOTIKA) -->
                            <div class="bg-slate-50 dark:bg-[#1E293B] p-5 rounded-[1.25rem] border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3 ml-1">Hasil Tes Urine Lengkap <span class="text-rose-500">*</span></label>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start w-full">
                                    <!-- Dropdown Status -->
                                    <div class="w-full lg:col-span-1">
                                        <select id="tes_urine_status" class="block w-full" onchange="updateTesUrineFinal()">
                                            <option value="">-- Pilih Status Urine --</option>
                                            @php
                                                $oldUrine = old('tes_urine', '');
                                                $isPos = stripos($oldUrine, 'POSITIF') !== false;
                                                $isNeg = stripos($oldUrine, 'NEGATIF') !== false;
                                            @endphp
                                            <option value="POSITIF" {{ $isPos ? 'selected' : '' }}>POSITIF</option>
                                            <option value="NEGATIF" {{ $isNeg ? 'selected' : '' }}>NEGATIF</option>
                                        </select>
                                    </div>

                                    <!-- Checkbox Zat Narkotika -->
                                    <div class="w-full lg:col-span-2">
                                        <div id="tes_urine_zat_container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 {{ $isPos ? '' : 'opacity-50 pointer-events-none' }}">
                                            @php
                                                $zatUrineList = ['Amphetamine', 'Benzodiazepine', 'Cocain', 'Methamphetamine', 'Morphine', 'THC/Marijuana'];
                                            @endphp
                                            @foreach($zatUrineList as $zat)
                                                <label class="flex items-start gap-3 p-3 rounded-xl hover:bg-white dark:hover:bg-[#0F172A] border border-transparent hover:border-slate-200 dark:hover:border-slate-700 cursor-pointer transition-colors group">
                                                    <input type="checkbox" value="{{ $zat }}" class="tes-urine-cb shrink-0 w-4 h-4 mt-0.5 rounded text-[#3B82F6] border-slate-300 dark:border-slate-600 dark:bg-slate-800 focus:ring-[#3B82F6]" onchange="updateTesUrineFinal()" {{ stripos($oldUrine, $zat) !== false ? 'checked' : '' }}>
                                                    <span class="text-[12px] font-bold text-slate-700 dark:text-slate-300 group-hover:text-[#3B82F6] transition-colors leading-tight break-words min-w-0">{{ $zat }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Output Hidden & Preview -->
                                <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-700/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Preview Data Tersimpan:</span>
                                        <p id="tes_urine_preview" class="text-sm font-black text-[#3B82F6] dark:text-blue-400">{{ $oldUrine ?: '-' }}</p>
                                    </div>
                                    <input type="hidden" name="tes_urine" id="tes_urine_hidden" value="{{ $oldUrine }}">
                                </div>
                            </div>

                            <!-- NEW: JENIS ZAT NARKOTIKA -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis Narkotika / Zat Adiktif</label>
                                <div class="flex flex-wrap items-start gap-2">
                                    <div class="flex-1 min-w-[200px] w-full">
                                        <select name="narkotika_input" id="selectNarkotika" class="block w-full">
                                            <option value="">-- Pilih Jenis Narkotika --</option>
                                            @foreach($masterNarkotika ?? [] as $nk)
                                                @if(trim($nk->jenis_narkotika) !== '')
                                                    <option value="{{ $nk->jenis_narkotika }}" {{ old('narkotika_input') == $nk->jenis_narkotika ? 'selected' : '' }}>
                                                        {{ $nk->jenis_narkotika }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex gap-2 shrink-0">
                                        <button type="button" onclick="openModalTambahNarkotika()" class="shrink-0 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-100 dark:hover:bg-blue-800/50 transition-colors shadow-sm">+ Tambah</button>
                                        <button type="button" onclick="openModalKelolaNarkotika()" class="shrink-0 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Kelola</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===================================== -->
                        <!-- C. KONDISI SOSIO-PSIKOLOGIS & POLA PEMAKAIAN -->
                        <!-- ===================================== -->
                        <div class="flex items-center gap-3 bg-purple-50/50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-800/30 p-3 rounded-xl mb-6 mt-8">
                            <span class="bg-purple-200 dark:bg-purple-600 text-purple-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">C</span>
                            <span class="text-[11px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">Kondisi Sosio-Psikologis & Pola Pemakaian</span>
                        </div>

                        <!-- 13. Alasan Penggunaan & 14. Kondisi Keluarga -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Alasan Penggunaan</label>
                                <textarea name="alasan_penggunaan" rows="2" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-400 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('alasan_penggunaan') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kondisi Keluarga</label>
                                <textarea name="kondisi_keluarga" rows="2" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-400 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('kondisi_keluarga') }}</textarea>
                            </div>
                        </div>

                        <!-- 16. Tingkat Ketergantungan & 17. Pola Pemakaian -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tingkat Ketergantungan</label>
                                <input type="text" name="tingkat_ketergantungan" value="{{ old('tingkat_ketergantungan') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-400 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pola Pemakaian</label>
                                <input type="text" name="pola_pemakaian" value="{{ old('pola_pemakaian') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-400 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">
                            </div>
                        </div>

                        <!-- 15. Kondisi Lingkungan -->
                        <div class="mb-8">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kondisi Lingkungan</label>
                            <textarea name="kondisi_lingkungan" rows="2" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-purple-400 focus:ring-2 focus:ring-purple-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('kondisi_lingkungan') }}</textarea>
                        </div>


                        <!-- ===================================== -->
                        <!-- D. KEPUTUSAN REKOMENDASI & SARAN -->
                        <!-- ===================================== -->
                        <div class="flex items-center gap-3 bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 p-3 rounded-xl mb-6 mt-8">
                            <span class="bg-emerald-200 dark:bg-emerald-600 text-emerald-800 dark:text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-sm">D</span>
                            <span class="text-[11px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Keputusan Rekomendasi & Saran</span>
                        </div>

                        <!-- 18. kategori_rekomendasi -->
                        <div class="relative z-[20]">
                            <label class="block text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-3 ml-1">Rekomendasi TAT (Instansi)</label>

                            <div class="flex flex-wrap gap-4 p-4 bg-slate-50 dark:bg-[#1E293B] border border-slate-200 dark:border-slate-700 rounded-2xl mb-4 shadow-sm transition-colors">
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="kategori_rekomendasi" value="Rawat Jalan" {{ old('kategori_rekomendasi') == 'Rawat Jalan' ? 'checked' : '' }} class="text-emerald-500 focus:ring-emerald-500 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 transition-colors">Rawat Jalan</span></label>
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="kategori_rekomendasi" value="Rawat Inap" {{ old('kategori_rekomendasi') == 'Rawat Inap' ? 'checked' : '' }} class="text-emerald-500 focus:ring-emerald-500 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 transition-colors">Rawat Inap</span></label>
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="kategori_rekomendasi" value="Rehab di Lapas / Rutan" {{ old('kategori_rekomendasi') == 'Rehab di Lapas / Rutan' ? 'checked' : '' }} class="text-emerald-500 focus:ring-emerald-500 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 transition-colors">Rehab di Lapas / Rutan</span></label>
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" name="kategori_rekomendasi" value="Tidak Rehab (Proses Hukum)" {{ old('kategori_rekomendasi') == 'Tidak Rehab (Proses Hukum)' ? 'checked' : '' }} class="text-emerald-500 focus:ring-emerald-500 bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600" onchange="toggleRekomendasi()"><span class="ml-2 text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 transition-colors">Tidak Rehab (Proses Hukum)</span></label>
                            </div>

                            <!-- 19. selectTempatRekomendasi -->
                            <div class="flex flex-col sm:flex-row gap-3 mb-5">
                                <div class="flex-1">
                                    <select id="selectTempatRekomendasi" disabled class="block w-full">
                                        <option value="">-- Pilih Tempat --</option>
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
                                            <!-- INI TAMBAHANNYA UNTUK FILTER OPSI KOSONG -->
                                            @if(trim($nm) !== '')
                                                <option value="{{ trim($nm) }}" data-kategori="{{ $kat }}" style="display:none;" disabled>{{ trim($nm) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="btnTambahRekomendasi" disabled onclick="openModalTambahRekomendasi()" class="shrink-0 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-800/50 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">+ Tambah</button>
                                <button type="button" id="btnKelolaRekomendasi" disabled onclick="openModalKelolaRekomendasi()" class="shrink-0 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">Kelola</button>
                            </div>
                            <input type="hidden" name="rekomendasi_input" id="hidden_rekomendasi_input" value="{{ old('rekomendasi_input') }}">
                        </div>

                        <!-- 21. saran_case_conference -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Saran Sidang Case Conference</label>
                            <input type="hidden" name="saran_case_conference" id="hidden_saran_cc" value="{{ old('saran_case_conference') }}">
                            @php
                                $saranLama = old('saran_case_conference');
                                $saranList = $saranLama ? array_map('trim', explode(",", $saranLama)) : [];
                            @endphp
                            <div class="grid grid-cols-1 gap-3">
                                <input type="text" class="saran-cc-input block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="1. ..." value="{{ $saranList[0] ?? '' }}">
                                <input type="text" class="saran-cc-input block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="2. ..." value="{{ $saranList[1] ?? '' }}">
                                <input type="text" class="saran-cc-input block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="3. ..." value="{{ $saranList[2] ?? '' }}">
                                <input type="text" class="saran-cc-input block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="4. ..." value="{{ $saranList[3] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 4: HASIL ASESMEN FINAL & PELAKSANAAN -->
                    <!-- ========================================== -->
                    <div class="bg-white dark:bg-[#0F172A] p-6 md:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-visible z-[20] transition-colors mt-8">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#27C93F] rounded-t-[2rem]"></div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-[#27C93F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            4. Hasil Asesmen Final & Pelaksanaan
                        </h3>

                        <!-- TAMBAHAN FORM BARU -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pasal Yang Disangkakan</label>
                                <input type="text" name="pasal_sangkaan" value="{{ old('pasal_sangkaan') }}" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#27C93F] focus:ring-2 focus:ring-green-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Cth: Pasal 114 ayat (1)...">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Asesmen Hukum Final</label>
                                <textarea name="hasil_asesmen_hukum" rows="3" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#27C93F] focus:ring-2 focus:ring-green-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('hasil_asesmen_hukum') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Hasil Asesmen Medis Final</label>
                                <textarea name="hasil_asesmen_medis" rows="3" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#27C93F] focus:ring-2 focus:ring-green-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none">{{ old('hasil_asesmen_medis') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status Pelaksanaan Rekomendasi</label>
                                <select name="pelaksanaan" class="block w-full text-sm">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="YA" {{ old('pelaksanaan') == 'YA' ? 'selected' : '' }}>YA (Dilaksanakan)</option>
                                    <option value="TIDAK" {{ old('pelaksanaan') == 'TIDAK' ? 'selected' : '' }}>TIDAK (Belum/Batal)</option>
                                </select>
                            </div>

                            <!-- BARU: Keterangan Tambahan -->
                            <div class="md:col-span-2 mt-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan Tambahan</label>
                                <textarea name="keterangan_tambahan" rows="3" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#27C93F] focus:ring-2 focus:ring-green-500/20 text-sm font-medium bg-slate-50 dark:bg-[#1E293B] text-slate-900 dark:text-white focus:bg-white dark:focus:bg-[#0F172A] transition-all px-4 py-3 outline-none" placeholder="Opsional...">{{ old('keterangan_tambahan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL SUBMIT -->
                    <div class="border-t border-slate-200 dark:border-slate-800/60 pt-8 pb-10 flex flex-col sm:flex-row justify-end gap-4 fade-in-up delay-200 relative z-10">
                        <a href="{{ route('asesmen.index') }}" class="inline-flex justify-center items-center px-8 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Batal</a>
                        <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#4F46E5] dark:bg-[#3B82F6] text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-indigo-600 dark:hover:bg-blue-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-500/30 dark:shadow-blue-900/40">
                            Simpan Data Klien
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- KUMPULAN MODAL POPUP (MAC SOFT UI)                             -->
    <!-- ============================================================== -->

    <!-- MODAL TAMBAH PENDIDIKAN -->
    <div id="modalTambahPendidikan" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalTambahPendidikan()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Tambah Pendidikan</h3>
            <input type="text" id="inputPendidikanBaru" placeholder="Cth: S2" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none mb-6">
            <div class="flex justify-end gap-2">
                <button onclick="closeModalTambahPendidikan()" type="button" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button onclick="tambahPendidikanJS(event)" type="button" class="px-5 py-2.5 bg-[#3B82F6] text-white font-bold text-xs rounded-xl hover:bg-blue-600 transition-colors shadow-md">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL KELOLA PENDIDIKAN -->
    <div id="modalKelolaPendidikan" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalKelolaPendidikan()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Kelola Pendidikan</h3>
            <div id="listKelolaPendidikan" class="max-h-[50vh] overflow-y-auto mb-6 custom-select-scroll space-y-2 pr-2">
                @forelse($masterPendidikan ?? [] as $p)
                    @if(trim($p->nama_pendidikan) !== '')
                    <div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $p->nama_pendidikan }}</span>
                        <form action="{{ Route::has('pendidikan.destroy') ? route('pendidikan.destroy', $p->id) : url('pendidikan/'.$p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen dari database?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </form>
                    </div>
                    @endif
                @empty
                    <div class="text-sm text-slate-500 dark:text-slate-400 text-center py-6 italic empty-msg bg-slate-50 dark:bg-[#0F172A] rounded-2xl">Belum ada data pendidikan.</div>
                @endforelse
            </div>
            <button onclick="closeModalKelolaPendidikan()" type="button" class="w-full px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup</button>
        </div>
    </div>

    <!-- MODAL TAMBAH REKOMENDASI -->
    <div id="modalTambahRekomendasi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalTambahRekomendasi()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Tambah Rekomendasi</h3>
            <span id="tambahKategoriDisplay" class="font-bold text-emerald-600 dark:text-emerald-400 block mb-6 text-sm"></span>
            <input type="text" id="inputRekomendasiDetail" placeholder="Nama Instansi/Klinik..." class="block w-full rounded-xl border-slate-200 dark:border-slate-700 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none mb-6">
            <div class="flex justify-end gap-2">
                <button onclick="closeModalTambahRekomendasi()" type="button" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button onclick="tambahRekomendasiJS(event)" type="button" class="px-5 py-2.5 bg-emerald-600 text-white font-bold text-xs rounded-xl hover:bg-emerald-700 transition-colors shadow-md">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL KELOLA REKOMENDASI -->
    <div id="modalKelolaRekomendasi" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalKelolaRekomendasi(true)"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight" id="modalKelolaRekomendasiTitle">Kelola Rekomendasi</h3>
            <div id="listKelolaRekomendasi" class="max-h-[50vh] overflow-y-auto mb-6 custom-select-scroll space-y-2 pr-2">
                @forelse($masterRekomendasi ?? [] as $r)
                    @php
                        $kat = 'Semua'; $nm = $r->nama_rekomendasi;
                        if(str_starts_with($nm, 'Rawat Jalan - ')){ $kat='Rawat Jalan'; $nm=substr($nm,14); }
                        elseif(str_starts_with($nm, 'Rawat Inap - ')){ $kat='Rawat Inap'; $nm=substr($nm,13); }
                        elseif(str_starts_with($nm, 'Rehab di Lapas / Rutan - ')){ $kat='Rehab di Lapas / Rutan'; $nm=substr($nm,25); }
                        elseif(str_starts_with($nm, 'Tidak Rehab (Proses Hukum) - ')){ $kat='Tidak Rehab (Proses Hukum)'; $nm=substr($nm,29); }
                    @endphp
                    @if(trim($nm) !== '')
                    <div data-kategori="{{ $kat }}" class="rekomendasi-item flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ trim($nm) }}</span>
                        <form action="{{ Route::has('rekomendasi.destroy') ? route('rekomendasi.destroy', $r->id) : url('rekomendasi/'.$r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen dari database?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </form>
                    </div>
                    @endif
                @empty
                    <div class="text-sm text-slate-500 dark:text-slate-400 text-center py-6 italic empty-msg bg-slate-50 dark:bg-[#0F172A] rounded-2xl">Belum ada data rekomendasi.</div>
                @endforelse
            </div>
            <button onclick="closeModalKelolaRekomendasi(true)" type="button" class="w-full px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup</button>
        </div>
    </div>

    <!-- MODAL TAMBAH PEKERJAAN -->
    <div id="modalTambahPekerjaan" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalTambahPekerjaan()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <button type="button" onclick="closeModalTambahPekerjaan()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Tambah Pekerjaan Baru</h3>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Pekerjaan</label>
                <input type="text" id="inputPekerjaanBaru" required placeholder="Cth: Pegawai Swasta" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all">
            </div>
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" onclick="closeModalTambahPekerjaan()" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Batal</button>
                <button type="button" onclick="tambahPekerjaanJS(event)" class="px-6 py-3 bg-[#3B82F6] hover:bg-blue-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-md hover:-translate-y-0.5">Tambahkan</button>
            </div>
        </div>
    </div>

    <!-- MODAL KELOLA PEKERJAAN -->
    <div id="modalKelolaPekerjaan" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalKelolaPekerjaan()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <button type="button" onclick="closeModalKelolaPekerjaan()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Kelola Pekerjaan</h3>
            <div id="listKelolaPekerjaan" class="max-h-[50vh] overflow-y-auto pr-2 space-y-2 mb-8 custom-select-scroll">
                @forelse($masterPekerjaan ?? [] as $pk)
                    @if(trim($pk->nama_pekerjaan) !== '')
                    <div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $pk->nama_pekerjaan }}</span>
                        <form action="{{ Route::has('pekerjaan.destroy') ? route('pekerjaan.destroy', $pk->id) : url('pekerjaan/'.$pk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen dari database?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </form>
                    </div>
                    @endif
                @empty
                <div class="text-sm text-slate-500 dark:text-slate-400 text-center py-6 italic empty-msg bg-slate-50 dark:bg-[#0F172A] rounded-2xl">Belum ada data pekerjaan.</div>
                @endforelse
            </div>
            <div class="flex justify-center">
                <button type="button" onclick="closeModalKelolaPekerjaan()" class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors w-full sm:w-auto shadow-sm">Tutup Kelola</button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH NARKOTIKA -->
    <div id="modalTambahNarkotika" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalTambahNarkotika()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <button type="button" onclick="closeModalTambahNarkotika()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Tambah Jenis Narkotika</h3>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Jenis Zat / Narkotika</label>
                <input type="text" id="inputNarkotikaBaru" required placeholder="Cth: Sabu-Sabu" class="block w-full rounded-[1.25rem] border-slate-200 dark:border-slate-700 shadow-sm focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-slate-50 dark:bg-[#0F172A] text-slate-900 dark:text-white px-4 py-3 outline-none transition-all">
            </div>
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" onclick="closeModalTambahNarkotika()" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Batal</button>
                <button type="button" onclick="tambahNarkotikaJS(event)" class="px-6 py-3 bg-[#3B82F6] hover:bg-blue-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-md hover:-translate-y-0.5">Tambahkan</button>
            </div>
        </div>
    </div>

    <!-- MODAL KELOLA NARKOTIKA -->
    <div id="modalKelolaNarkotika" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity" onclick="closeModalKelolaNarkotika()"></div>
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg p-8 rounded-[2rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] transform scale-95 opacity-0 transition-all border border-white/50 dark:border-slate-700/50">
            <button type="button" onclick="closeModalKelolaNarkotika()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Kelola Narkotika</h3>
            <div id="listKelolaNarkotika" class="max-h-[50vh] overflow-y-auto pr-2 space-y-2 mb-8 custom-select-scroll">
                @forelse($masterNarkotika ?? [] as $nk)
                    @if(trim($nk->jenis_narkotika) !== '')
                    <div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $nk->jenis_narkotika }}</span>
                        <form action="{{ Route::has('narkotika.destroy') ? route('narkotika.destroy', $nk->id) : url('narkotika/'.$nk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen dari database?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </form>
                    </div>
                    @endif
                @empty
                <div class="text-sm text-slate-500 dark:text-slate-400 text-center py-6 italic empty-msg bg-slate-50 dark:bg-[#0F172A] rounded-2xl">Belum ada data narkotika.</div>
                @endforelse
            </div>
            <div class="flex justify-center">
                <button type="button" onclick="closeModalKelolaNarkotika()" class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors w-full sm:w-auto shadow-sm">Tutup Kelola</button>
            </div>
        </div>
    </div>

    <!-- Script Utama -->
    <script>
        // === MESIN SINKRONISASI HASIL TES URINE ===
        function syncTesUrine() {
            const hiddenInput = document.getElementById('tes_urine_hidden');
            if (!hiddenInput) return;
            const storageKey = 'shared_tes_urine_new';

            let isSyncingUrine = false;

            // Baca saat pertama kali load
            let sharedUrine = localStorage.getItem(storageKey);
            if (sharedUrine && sharedUrine !== hiddenInput.value) {
                applyUrineData(sharedUrine);
            }

            // Listener untuk sinkronisasi dari tab/form lain
            window.addEventListener('storage', function(e) {
                if (e.key === storageKey && !isSyncingUrine) {
                    applyUrineData(e.newValue);
                }
            });

            const formEl = hiddenInput.closest('form');
            if(formEl) {
                formEl.addEventListener('submit', () => localStorage.removeItem(storageKey));
            }
        }

        function applyUrineData(val) {
            if (!val) return;
            const statusEl = document.getElementById('tes_urine_status');
            if (val.includes('POSITIF')) statusEl.value = 'POSITIF';
            else if (val.includes('NEGATIF')) statusEl.value = 'NEGATIF';
            else statusEl.value = '';

            document.querySelectorAll('.tes-urine-cb').forEach(cb => {
                cb.checked = val.includes(cb.value);
            });

            updateTesUrineFinal();
            setTimeout(() => { statusEl.dispatchEvent(new Event('change', { bubbles: true })); }, 100);
        }

        function updateTesUrineFinal() {
            const statusEl = document.getElementById('tes_urine_status');
            const container = document.getElementById('tes_urine_zat_container');
            const checkboxes = document.querySelectorAll('.tes-urine-cb');
            const hiddenInput = document.getElementById('tes_urine_hidden');
            const preview = document.getElementById('tes_urine_preview');

            let status = statusEl.value;
            let finalString = "";

            if (status === 'NEGATIF') {
                container.classList.add('opacity-50', 'pointer-events-none');
                checkboxes.forEach(cb => cb.checked = false);
                finalString = "NEGATIF";
            } else if (status === 'POSITIF') {
                container.classList.remove('opacity-50', 'pointer-events-none');
                let selectedZats = [];
                checkboxes.forEach(cb => {
                    if (cb.checked) selectedZats.push(cb.value);
                });
                if (selectedZats.length > 0) {
                    finalString = "POSITIF : " + selectedZats.join(', ');
                } else {
                    finalString = "POSITIF";
                }
            } else {
                container.classList.add('opacity-50', 'pointer-events-none');
                checkboxes.forEach(cb => cb.checked = false);
                finalString = "";
            }

            hiddenInput.value = finalString;
            preview.innerText = finalString || '-';

            if (typeof saveFormDraft === 'function') saveFormDraft();
            localStorage.setItem('shared_tes_urine_new', finalString);
        }

        // MESIN CUSTOM SELECT MAC SOFT UI
        function makeCustomSelect(select) {
            let skipCondition1 = !select;
            let skipCondition2 = select ? select.classList.contains('no-custom') : false;
            let skipCondition3 = select ? select.classList.contains('hidden') : false;
            let skipCondition4 = select ? (select.id === 'narkotika-dropdown') : false;

            if (skipCondition1 || skipCondition2 || skipCondition3 || skipCondition4) return;

            let isRendering = false; let renderTimeout;

            function render() {
                if (isRendering) return; isRendering = true;

                let checkExisting = select.nextElementSibling;
                if (checkExisting && checkExisting.classList.contains('mac-select-wrapper')) {
                    checkExisting.remove();
                }

                const wrapper = document.createElement('div'); wrapper.className = 'mac-select-wrapper relative w-full'; wrapper.style.zIndex = '1';
                const btn = document.createElement('button'); btn.type = 'button';

                let isEmerald = select.classList.contains('bg-emerald-50') ? true : select.classList.contains('text-emerald-700');
                let isRose = select.classList.contains('bg-rose-50') ? true : select.classList.contains('text-rose-700');

                if (select.id === 'tes_urine_status') {
                    if (select.value === 'POSITIF') { isRose = true; isEmerald = false; }
                    else if (select.value === 'NEGATIF') { isEmerald = true; isRose = false; }
                }

                let btnBaseClass = 'flex justify-between items-center w-full px-4 py-3 rounded-[1.25rem] border transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-sm text-sm font-medium text-left min-w-0 ';
                if (select.disabled) { btn.className = btnBaseClass + 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:text-slate-500'; btn.disabled = true; }
                else if (isEmerald) { btn.className = btnBaseClass + 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400'; }
                else if (isRose) { btn.className = btnBaseClass + 'bg-rose-50 border-rose-100 text-rose-700 hover:border-rose-300 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400'; }
                else { btn.className = btnBaseClass + 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 dark:bg-[#0F172A] dark:border-slate-700 dark:text-slate-200'; }

                const span = document.createElement('span'); span.className = 'truncate pointer-events-none flex-1 min-w-0 mr-2';
                let selectedText = '-- Pilih --';

                let isIndexValid = select.selectedIndex >= 0;
                let isOptionValid = isIndexValid ? select.options[select.selectedIndex] : false;

                if (isOptionValid) {
                    selectedText = select.options[select.selectedIndex].text;
                }
                else if (select.options.length > 0) {
                    selectedText = select.options[0].text;
                }
                span.innerText = selectedText;

                const icon = document.createElement('div'); icon.className = 'shrink-0 ml-3 transition-transform duration-200 pointer-events-none flex items-center justify-center';
                let iconColor = isEmerald ? 'text-emerald-500 dark:text-emerald-400' : (isRose ? 'text-rose-500 dark:text-rose-400' : 'text-slate-400');
                icon.innerHTML = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`;

                btn.appendChild(span); btn.appendChild(icon);
                const listWrapper = document.createElement('div'); listWrapper.className = 'mac-select-list absolute z-[90] top-full left-0 mt-2 w-full bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] rounded-2xl py-2 max-h-60 overflow-y-auto hidden custom-select-scroll transition-all opacity-0 translate-y-2';

                let hasVisibleOptions = false;
                Array.from(select.options).forEach((opt, index) => {
                    if(opt.style.display === 'none') return;
                    hasVisibleOptions = true;
                    const item = document.createElement('div'); item.className = 'px-4 py-2.5 mx-1.5 mt-0.5 rounded-xl cursor-pointer text-sm transition-colors ';

                    let conditionDisableEmpty = opt.disabled ? (opt.value === '') : false;

                    if (conditionDisableEmpty) item.className += 'text-slate-400 font-bold opacity-70 cursor-default pointer-events-none';
                    else if (opt.disabled) item.className += 'text-slate-400 cursor-not-allowed bg-slate-50 pointer-events-none';
                    else if (opt.selected ? (opt.value !== '') : false) item.className += 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400';
                    else item.className += 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800';

                    item.innerText = opt.text;
                    if (!opt.disabled) {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation(); select.selectedIndex = index; span.innerText = opt.text; closeAllMacSelects();

                            if (select.id === 'tes_urine_status') {
                                if (opt.value === "POSITIF") { btn.className = btnBaseClass + 'bg-rose-50 border-rose-100 text-rose-700 hover:border-rose-300 focus:ring-rose-500/20 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400'; icon.innerHTML = `<svg class="w-4 h-4 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`; }
                                else if (opt.value === "NEGATIF") { btn.className = btnBaseClass + 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300 focus:ring-emerald-500/20 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400'; icon.innerHTML = `<svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`; }
                                else { btn.className = btnBaseClass + 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 dark:bg-[#0F172A] dark:border-slate-700 dark:text-slate-200'; icon.innerHTML = `<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`; }
                            }
                            select.dispatchEvent(new Event('change', {bubbles: true}));
                        });
                    }
                    listWrapper.appendChild(item);
                });

                if(!hasVisibleOptions) {
                    const empty = document.createElement('div'); empty.className = 'px-4 py-2 text-sm text-slate-400 italic text-center'; empty.innerText = 'Tidak ada pilihan'; listWrapper.appendChild(empty);
                }

                btn.addEventListener('click', (e) => {
                    e.preventDefault(); e.stopPropagation(); if(select.disabled) return;
                    const isHidden = listWrapper.classList.contains('hidden'); closeAllMacSelects();
                    if (isHidden) {
                        wrapper.style.zIndex = '999';
                        const pCard = wrapper.closest('.bg-white.dark\\:bg-\\[\\#0F172A\\]'); if(pCard) pCard.style.zIndex = '999';
                        if (listWrapper.closeTimeout) clearTimeout(listWrapper.closeTimeout);
                        listWrapper.classList.remove('hidden'); requestAnimationFrame(() => { listWrapper.classList.remove('opacity-0', 'translate-y-2'); icon.classList.add('rotate-180'); });
                    }
                });

                wrapper.appendChild(btn); wrapper.appendChild(listWrapper); select.style.display = 'none';
                if (select.classList.contains('w-[110px]')) { const o = document.createElement('div'); o.className = 'w-[110px] shrink-0'; select.parentNode.insertBefore(o, select); o.appendChild(select); o.appendChild(wrapper); }
                else { select.parentNode.insertBefore(wrapper, select.nextSibling); }
                isRendering = false;
            }
            function triggerRender() { clearTimeout(renderTimeout); renderTimeout = setTimeout(render, 50); }
            triggerRender();
            const observer = new MutationObserver(() => triggerRender()); observer.observe(select, { childList: true, attributes: true, attributeFilter: ['disabled'] });
            select.addEventListener('change', () => triggerRender());
        }

        function closeAllMacSelects() {
            document.querySelectorAll('.mac-select-wrapper').forEach(wrapper => {
                wrapper.style.zIndex = '1'; const pCard = wrapper.closest('.bg-white.dark\\:bg-\\[\\#0F172A\\]'); if(pCard) pCard.style.zIndex = '';
                const list = wrapper.querySelector('.mac-select-list'); const icon = wrapper.querySelector('button > div');

                let isListVisible = false;
                if (list) {
                    if (!list.classList.contains('hidden')) {
                        isListVisible = true;
                    }
                }

                if(isListVisible) {
                    list.classList.add('opacity-0', 'translate-y-2'); if(icon) icon.classList.remove('rotate-180');
                    if (list.closeTimeout) clearTimeout(list.closeTimeout);
                    list.closeTimeout = setTimeout(() => { list.classList.add('hidden'); }, 200);
                }
            });
        }
        document.addEventListener('click', closeAllMacSelects);

        function initAllCustomSelects() { document.querySelectorAll('select').forEach(select => { makeCustomSelect(select); }); }

        // Master Data Wilayah
        const daftarKelurahan = [ "Ardirejo", "Candirenggo", "Cepokomulyo", "Dampit", "Kalirejo", "Kepanjen", "Lawang", "Losari", "Pagentan", "Penarukan", "Sedayu", "Turen" ];
        const daftarDesa = [ "Amadanom", "Ampeldento (Karangploso)", "Ampeldento (Pakis)", "Ampelgading", "Ardimulyo", "Argosari", "Argosuko", "Argotirto", "Argoyuwono", "Arjosari", "Arjowilangun", "Asrikaton", "Babadan", "Bakalan", "Balearjo", "Balesari", "Bambang", "Bandungrejo", "Bangelan", "Banjararum", "Banjarejo (Donomulyo)", "Banjarejo (Ngantang)", "Banjarejo (Pagelaran)", "Banjarejo (Pakis)", "Banjarsari", "Bantur", "Banturejo", "Baturetno (Dampit)", "Baturetno (Singosari)", "Bayem", "Bedali", "Belung", "Bendosari", "Benjor", "Blayu", "Bocek", "Bokor", "Bringin", "Brongkal", "Bululawang", "Bulupitu", "Bumirejo", "Bunutwetan", "Clumprit", "Codo", "Curungrejo", "Dadapan", "Dalisodo", "Dawuhan", "Dengkol", "Dilem", "Donomulyo", "Donowarih", "Druju", "Duwet", "Duwet Krajan", "Gading", "Gadingkembar", "Gadingkulon", "Gadungsari", "Gajahrejo", "Gampingan", "Ganjaran", "Gedangan", "Gedog Kulon", "Gedog Wetan", "Genengan", "Girimoyo", "Girimulyo", "Glanggang", "Gondanglegi Kulon", "Gondanglegi Wetan", "Gondowangi", "Gubukklakah", "Gunung Jati", "Gunungrejo", "Gunungronggo", "Gunungsari", "Harjokuncaran", "Jabung", "Jambangan", "Jambearjo", "Jambesari", "Jambuwer", "Jatiguwi", "Jatikerto", "Jatirejoyoso", "Jatisari (Pakisaji)", "Jatisari (Tajinan)", "Jedong", "Jenggolo", "Jeru (Tumpang)", "Jeru (Turen)", "Jogomulyan", "Jombok", "Kademangan", "Kaliasri", "Kalipare", "Kalirejo", "Kalisongo", "Kambingan", "Kanigoro", "Karanganyar", "Karangduren", "Karangkates", "Karangnongko", "Karangpandan", "Karangrejo", "Karangsari", "Karangsuko", "Karangwidoro", "Kasembon (Bululawang)", "Kasembon (Kasembon)", "Kasri", "Kaumrejo", "Kebobang", "Kebonagung", "Kedok", "Kedungbanteng", "Kedungpedaringan", "Kedungrejo", "Kedungsalam", "Kemantren", "Kemiri (Jabung)", "Kemiri (Kepanjen)", "Kemulan", "Kendalpayak", "Kenongo", "Kepatihan", "Kepuharjo", "Kesamben", "Ketawang", "Ketindan", "Kidal", "Kidangbang", "Klampok", "Klepu", "Kluwut", "Kranggan", "Krebet", "Krebet Senggrong", "Kromengan", "Kucur", "Kuwolu", "Landungsari", "Lang-Lang", "Lebakharjo", "Lumbangsari", "Madiredo", "Maguan", "Majangtengah", "Malangsuko", "Mangliawan", "Mangunrejo", "Mendalanwangi", "Mentaraman", "Mojosari", "Mulyoagung", "Mulyoarjo", "Mulyoasri", "Mulyorejo", "Ngabab", "Ngadas", "Ngadilangkung", "Ngadirejo (Jabung)", "Ngadirejo (Kromengan)", "Ngadireso", "Ngajum", "Ngantru", "Ngasem", "Ngawonggo", "Ngebruk (Poncokusumo)", "Ngebruk (Sumberpucung)", "Ngembal", "Ngenep", "Ngijo", "Ngingit", "Ngroto", "Pagak", "Pagedangan", "Pagelaran", "Pagersari", "Pait", "Pajaran", "Pakisaji", "Pakisjajar", "Pakiskembar", "Palaan", "Pamotan", "Pandanajeng", "Pandanlandung", "Pandanmulyo", "Pandanrejo (Pagak)", "Pandanrejo (Wagir)", "Pandansari (Ngantang)", "Pandansari (Poncokusumo)", "Pandansari Lor", "Pandesari", "Panggungrejo (Gondanglegi)", "Panggungrejo (Kepanjen)", "Parangargo", "Patokpicis", "Peniwen", "Permanu", "Petungsewu (Dau)", "Petungsewu (Wagir)", "Plandi", "Plaosan", "Pojok", "Poncokusumo", "Pondokagung", "Pringgodani", "Pringu", "Pucangsongo", "Pujiharjo", "Pujon Kidul", "Pujon Lor", "Pulungdowo", "Purwoasri", "Purwodadi (Donomulyo)", "Purwodadi (Tirtoyudo)", "Purwoharjo", "Purworejo (Donomulyo)", "Purworejo (Ngantang)", "Purwosekar", "Putat Kidul", "Putat Lor", "Putukrejo (Gondanglegi)", "Putukrejo (Kalipare)", "Randuagung", "Randugading", "Rejosari", "Rejoyoso", "Rembun", "Ringinkembar", "Ringinsari", "Sambigede", "Sanankerto", "Sananrejo", "Saptorenggo", "Sawahan", "Segaran", "Sekarbanyu", "Sekarpuro", "Selorejo", "Sempalwadak", "Sempol", "Senggreng", "Sengguruh", "Sepanjang", "Sidoasri", "Sidodadi (Gedangan)", "Sidodadi (Lawang)", "Sidodadi (Ngantang)", "Sidoluhur", "Sidomulyo", "Sidorahayu", "Sidorejo (Jabung)", "Sidorejo (Pagelaran)", "Sidorenggo", "Simojayan", "Sindurejo", "Sitiarjo", "Sitirejo", "Slamet", "Slamparejo", "Slorok", "Sonowangi", "Srigading", "Srigonco", "Srimulyo", "Sudimoro", "Sukoanyar (Pakis)", "Sukoanyar (Wajak)", "Sukodadi", "Sukodono", "Sukolilo (Jabung)", "Sukolilo (Wajak)", "Sukomulyo", "Sukonolo", "Sukopuro", "Sukoraharjo", "Sukorejo (Gondanglegi)", "Sukorejo (Tirtoyudo)", "Sukosari (Gondanglegi)", "Sukosari (Kasembon)", "Sukowilangun", "Sumberagung (Ngantang)", "Sumberagung (Sumbermanjing Wetan)", "Sumberbening", "Sumberdem", "Sumberejo (Gedangan)", "Sumberejo (Pagak)", "Sumberejo (Poncokusumo)", "Sumberjaya", "Sumberkerto", "Sumberkradenan", "Sumbermanjing Kulon", "Sumbermanjing Wetan", "Sumberngepoh", "Sumberoto", "Sumberpasir", "Sumberpetung", "Sumberporong", "Sumberpucung", "Sumberputih", "Sumbersekar", "Sumbersuko (Dampit)", "Sumbersuko (Tajinan)", "Sumbersuko (Wagir)", "Sumbertangkil", "Sumbertempur", "Sutojayan", "Suwaru", "Taji", "Tajinan", "Talangagung", "Talangsuko", "Talok", "Tamanasri", "Tamanharjo", "Tamankuncaran", "Tamansari", "Tamansatriyan", "Tambakasri (Sumbermanjing Wetan)", "Tambakasri (Tajinan)", "Tambakrejo", "Tanggung", "Tangkilsari", "Tawangagung", "Tawangargo", "Tawangrejeni", "Tawangsari", "Tegalgondo", "Tegalrejo", "Tegalsari", "Tegalweru", "Tempursari", "Ternyang", "Tirtomarto", "Tirtomoyo (Ampelgading)", "Tirtomoyo (Pakis)", "Tirtoyudo", "Tlogorejo", "Tlogosari (Donomulyo)", "Tlogosari (Tirtoyudo)", "Toyomarto", "Tulungrejo (Donomulyo)", "Tulungrejo (Ngantang)", "Tulusbesar", "Tumpakrejo (Gedangan)", "Tumpakrejo (Kalipare)", "Tumpang", "Tumpukrenteng", "Tunjungtirto", "Turirejo", "Undaan", "Urek-Urek", "Wadung", "Wajak", "Wandanpuro", "Watugede", "Waturejo", "Wirotaman", "Wiyurejo", "Wonoagung (Kasembon)", "Wonoagung (Tirtoyudo)", "Wonoayu", "Wonokerso", "Wonokerto", "Wonomulyo", "Wonorejo (Bantur)", "Wonorejo (Lawang)", "Wonorejo (Poncokusumo)", "Wonorejo (Singosari)", "Wonosari", "Wringinanom", "Wringinsongo" ];

        function initSearchableDropdown(inputId, listId, tipeId, updateFunc) {
            const input = document.getElementById(inputId);
            const list = document.getElementById(listId);
            const tipe = document.getElementById(tipeId);

            // Tambahan safety check
            if (!input || !list || !tipe) return;

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
                    div.className = 'px-3 py-2 hover:bg-indigo-50 cursor-pointer text-slate-700 border-b border-slate-50 last:border-0 text-sm font-medium';
                    div.textContent = item;
                    div.onclick = function() {
                        input.value = item;
                        list.classList.add('hidden');
                        updateFunc();
                    };
                    list.appendChild(div);
                });
            }
            input.addEventListener('focus', function() { render(this.value); list.classList.remove('hidden'); });
            input.addEventListener('input', function() { render(this.value); list.classList.remove('hidden'); updateFunc(); });
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
            tipe.addEventListener('change', function() { input.value = ''; render(); updateFunc(); });
        }

        document.addEventListener('click', function(e) {
            ['list_desa_ktp', 'list_desa_domisili'].forEach(id => {
                const list = document.getElementById(id); const inputId = id.replace('list_', ''); const input = document.getElementById(inputId);
                if (list && input) { const toggleIcon = list.previousElementSibling; if (!input.contains(e.target) && !list.contains(e.target) && !toggleIcon.contains(e.target)) { list.classList.add('hidden'); } }
            });
            const wrapper = document.getElementById('narkotika-wrapper'); const dropdown = document.getElementById('narkotika-dropdown');
            if (wrapper && !wrapper.contains(e.target) && dropdown) { dropdown.classList.add('hidden'); }
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
                    document.getElementById('jalan_domisili').value = document.getElementById('jalan_ktp').value;
                    document.getElementById('tipe_desa_domisili').value = document.getElementById('tipe_desa_ktp').value;
                    document.getElementById('tipe_desa_domisili').dispatchEvent(new Event('change'));
                    document.getElementById('desa_domisili').value = document.getElementById('desa_ktp').value;
                    document.getElementById('kecamatan_domisili').value = document.getElementById('kecamatan_ktp').value;
                    document.getElementById('kecamatan_domisili').dispatchEvent(new Event('change'));
                } else { document.getElementById('manual_domisili').value = document.getElementById('manual_ktp').value; }
            } else {
                document.getElementById('jalan_domisili').value = ''; document.getElementById('desa_domisili').value = ''; document.getElementById('kecamatan_domisili').value = ''; document.getElementById('manual_domisili').value = '';
                document.getElementById('tipe_desa_domisili').dispatchEvent(new Event('change'));
                document.getElementById('kecamatan_domisili').dispatchEvent(new Event('change'));
            }
            updateAlamatDomisili(); saveFormDraft();
        }

        function updateAlamatKtp() {
            const modeInput = document.querySelector('input[name="mode_ktp"]:checked');
            if(!modeInput) return;
            const mode = modeInput.value;
            let res = '';
            if (mode === 'otomatis') {
                let parts = [];
                const j = document.getElementById('jalan_ktp').value.trim(); if(j) parts.push(j);
                const d = document.getElementById('desa_ktp').value.trim();
                if(d) {
                    const tipe = document.getElementById('tipe_desa_ktp').value;
                    parts.push(tipe + ' ' + d);
                }
                const c = document.getElementById('kecamatan_ktp').value; if(c) parts.push('Kecamatan ' + c);
                const k = document.getElementById('kabupaten_ktp').value; if(k) parts.push(k);
                res = parts.join(', ');
            } else {
                res = document.getElementById('manual_ktp').value.trim();
            }
            document.getElementById('preview_alamat_ktp').value = res; document.getElementById('hidden_alamat_ktp').value = res;
        }

        function updateAlamatDomisili() {
            const modeInput = document.querySelector('input[name="mode_domisili"]:checked');
            if(!modeInput) return;
            const mode = modeInput.value;
            let res = '';
            if (mode === 'otomatis') {
                let parts = [];
                const j = document.getElementById('jalan_domisili').value.trim(); if(j) parts.push(j);
                const d = document.getElementById('desa_domisili').value.trim();
                if(d) {
                    const tipe = document.getElementById('tipe_desa_domisili').value;
                    parts.push(tipe + ' ' + d);
                }
                const c = document.getElementById('kecamatan_domisili').value; if(c) parts.push('Kecamatan ' + c);
                const k = document.getElementById('kabupaten_domisili').value; if(k) parts.push(k);
                res = parts.join(', ');
            } else {
                res = document.getElementById('manual_domisili').value.trim();
            }
            document.getElementById('preview_alamat_domisili').value = res; document.getElementById('hidden_alamat_domisili').value = res;
        }

        function toggleNarkotika() { document.getElementById('narkotika-dropdown').classList.toggle('hidden'); }
        function updateNarkotikaText() {
            const textSpan = document.getElementById('narkotika-text');

            // KUNCI PERBAIKAN: Hentikan fungsi jika elemen tidak ditemukan agar skrip lain tidak ikut mati
            if (!textSpan) return;

            const checkboxes = document.querySelectorAll('.narkotika-cb:checked');
            if (checkboxes.length === 0) {
                textSpan.textContent = '-- Pilih Jenis Narkotika --';
                textSpan.classList.remove('text-slate-900', 'dark:text-white', 'font-bold');
                textSpan.classList.add('text-slate-500', 'dark:text-slate-400');
            } else {
                textSpan.textContent = Array.from(checkboxes).map(cb => cb.getAttribute('data-label')).join(', ');
                textSpan.classList.add('text-slate-900', 'dark:text-white', 'font-bold');
                textSpan.classList.remove('text-slate-500', 'dark:text-slate-400');
            }
            saveFormDraft();
        }

        const saranInputs = document.querySelectorAll('.saran-cc-input');
        const hiddenSaran = document.getElementById('hidden_saran_cc');
        function updateSaranHidden() {
            let arr = []; saranInputs.forEach(input => { if(input.value.trim() !== '') arr.push(input.value.trim()); });
            if(hiddenSaran) hiddenSaran.value = arr.join(', '); saveFormDraft();
        }
        saranInputs.forEach(i => i.addEventListener('input', updateSaranHidden));

        function formatRupiah(angka, prefix) {
            let num = angka.replace(/[^,\d]/g, '').toString(), spl = num.split(','), sisa = spl[0].length % 3, rup = spl[0].substr(0, sisa), rib = spl[0].substr(sisa).match(/\d{3}/gi);
            if (rib) { let sep = sisa ? '.' : ''; rup += sep + rib.join('.'); } rup = spl[1] != undefined ? rup + ',' + spl[1] : rup; return prefix == undefined ? rup : (rup ? 'Rp. ' + rup : '');
        }
        function hitungUsia() {
            const tl = document.getElementById('tgl_lahir').value.trim(); const us = document.getElementById('usia');
            if (!tl) { us.value = ''; return; }
            let rd = tl.split(' ')[0], pts = rd.split('-'); let dob;
            if (pts.length === 3) { let p0 = pts[0], p2 = pts[2]; if (p0.length === 4) dob = new Date(parseInt(p0), parseInt(pts[1]) - 1, parseInt(p2)); else if (p2.length === 4) dob = new Date(parseInt(p2), parseInt(pts[1]) - 1, parseInt(p0)); }
            if (!dob || isNaN(dob.getTime())) dob = new Date(rd); if (isNaN(dob.getTime())) return;
            let refDateStr = "{{ now()->format('Y-m-d') }}";
            let refDate = new Date(refDateStr); let age = refDate.getFullYear() - dob.getFullYear(); let m = refDate.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && refDate.getDate() < dob.getDate())) age--;
            if(age >= 0) us.value = age + ' Tahun';
        }

        function previewImage(event) {
            const input = event.target; const preview = document.getElementById('previewFoto'); const placeholder = document.getElementById('iconPlaceholder');
            if (input.files && input.files[0]) { const reader = new FileReader(); reader.onload = function(e) { preview.src = e.target.result; preview.classList.remove('hidden'); placeholder.classList.add('hidden'); }; reader.readAsDataURL(input.files[0]); }
            else { preview.src = '#'; preview.classList.add('hidden'); placeholder.classList.remove('hidden'); }
        }

        const mainForm = document.getElementById('formCreateKlien'); const autosaveIndicator = document.getElementById('autosaveIndicator');
        function saveFormDraft() {
            if(!mainForm) return; const formData = new FormData(mainForm); const data = {};
            formData.forEach((value, key) => { if(key !== '_token' && key !== '_method' && key !== 'foto_klien' && !key.includes('narkotika_id[]')) data[key] = value; });
            localStorage.setItem('formKlienCreateDraft', JSON.stringify(data));
            if(autosaveIndicator) { autosaveIndicator.classList.remove('hidden'); setTimeout(() => autosaveIndicator.classList.add('hidden'), 3000); }
        }
        function loadFormDraft() {
            const draft = localStorage.getItem('formKlienCreateDraft');
            if (draft && mainForm) {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    if (key === 'tes_urine') {
                        applyUrineData(data[key]);
                        return;
                    }

                    const els = mainForm.querySelectorAll(`[name="${key}"]`);
                    if (els.length > 0) {
                        if (els[0].type === 'radio' || els[0].type === 'checkbox') {
                            const t = mainForm.querySelector(`[name="${key}"][value="${data[key]}"]`);
                            if (t) { t.checked = true; t.dispatchEvent(new Event('change', {bubbles: true})); }
                        }
                        else {
                            els[0].value = data[key];
                            els[0].dispatchEvent(new Event('change', {bubbles: true}));
                        }
                    }
                });
            }
        }

        function toggleRekomendasi() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked'); const select = document.getElementById('selectTempatRekomendasi');
            select.disabled = false; select.classList.remove('bg-slate-100', 'dark:bg-slate-800/50', 'cursor-not-allowed', 'text-slate-500'); select.classList.add('bg-slate-50', 'dark:bg-[#1E293B]', 'text-slate-900', 'dark:text-white');
            document.getElementById('btnTambahRekomendasi').disabled = false;
            const btnKelola = document.getElementById('btnKelolaRekomendasi');
            if(btnKelola) btnKelola.disabled = false;

            if (radio) {
                const sc = radio.value;
                select.querySelectorAll('option').forEach(opt => {
                    if (opt.value === '' || opt.getAttribute('data-kategori') === sc || opt.getAttribute('data-kategori') === 'Semua') { opt.style.display = ''; opt.disabled = false; } else { opt.style.display = 'none'; opt.disabled = true; }
                });
                select.dispatchEvent(new Event('change'));
            }
            updateRekomendasiPreview();
        }
        function updateRekomendasiPreview() {
            const radio = document.querySelector('input[name="kategori_rekomendasi"]:checked'); const select = document.getElementById('selectTempatRekomendasi');
            if (radio) { let v = radio.value; if (select.value && select.value !== 'Bawaan Sistem') v += ' - ' + select.value; document.getElementById('hidden_rekomendasi_input').value = v; }
            else document.getElementById('hidden_rekomendasi_input').value = '';
        }

        function openModalTambahPekerjaan() {
            const modal = document.getElementById('modalTambahPekerjaan');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.children[1].classList.remove('opacity-0', 'scale-95'); document.getElementById('inputPekerjaanBaru').focus(); }, 10);
        }
        function closeModalTambahPekerjaan() {
            const modal = document.getElementById('modalTambahPekerjaan');
            modal.children[1].classList.add('opacity-0', 'scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }
        function openModalKelolaPekerjaan() {
            const modal = document.getElementById('modalKelolaPekerjaan');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.children[1].classList.remove('opacity-0', 'scale-95'); }, 10);
        }
        function closeModalKelolaPekerjaan() {
            const modal = document.getElementById('modalKelolaPekerjaan');
            modal.children[1].classList.add('opacity-0', 'scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        function tambahPekerjaanJS(event) {
            event.preventDefault();
            const inputVal = document.getElementById('inputPekerjaanBaru').value.trim();
            if(inputVal) {
                const selectElement = document.querySelector('select[name="pekerjaan_input"]');
                let exists = Array.from(selectElement.options).some(opt => opt.value === inputVal);
                if(!exists) {
                    const newOption = document.createElement('option');
                    newOption.value = inputVal; newOption.textContent = inputVal;
                    newOption.style.display = ''; newOption.disabled = false;
                    newOption.className = 'dark:bg-slate-800';
                    selectElement.appendChild(newOption);

                    const listKelola = document.getElementById('listKelolaPekerjaan');
                    const emptyMsg = listKelola.querySelector('.empty-msg');
                    if(emptyMsg) emptyMsg.remove();

                    const draftId = 'draft-pek-' + inputVal.replace(/\s+/g, '-').toLowerCase();
                    const draftHtml = `
                        <div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors" id="${draftId}">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">${inputVal}</span>
                            <button type="button" onclick="hapusDraftPekerjaan('${inputVal}', '${draftId}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button>
                        </div>
                    `;
                    listKelola.insertAdjacentHTML('afterbegin', draftHtml);

                    let savedPek = JSON.parse(localStorage.getItem('customPekerjaan')) || [];
                    if (!savedPek.includes(inputVal)) { savedPek.push(inputVal); localStorage.setItem('customPekerjaan', JSON.stringify(savedPek)); }
                }
                selectElement.value = inputVal;
                selectElement.dispatchEvent(new Event('change'));
                closeModalTambahPekerjaan();
                document.getElementById('inputPekerjaanBaru').value = '';
                saveFormDraft();
            }
        }

        function hapusDraftPekerjaan(val, elementId) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini dari daftar memori?')) return;
            const selectElement = document.querySelector('select[name="pekerjaan_input"]');
            const optionToRemove = Array.from(selectElement.options).find(opt => opt.value === val);
            if (optionToRemove) optionToRemove.remove();
            const draftElement = document.getElementById(elementId);
            if (draftElement) draftElement.remove();
            selectElement.value = "";
            selectElement.dispatchEvent(new Event('change'));
            saveFormDraft();

            let savedPek = JSON.parse(localStorage.getItem('customPekerjaan')) || [];
            savedPek = savedPek.filter(item => item !== val);
            localStorage.setItem('customPekerjaan', JSON.stringify(savedPek));
        }

        function openModalTambahPendidikan() { const m = document.getElementById('modalTambahPendidikan'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.children[1].classList.remove('opacity-0', 'scale-95'), 10); }
        function closeModalTambahPendidikan() { const m = document.getElementById('modalTambahPendidikan'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }
        function openModalKelolaPendidikan() { const m = document.getElementById('modalKelolaPendidikan'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.children[1].classList.remove('opacity-0', 'scale-95'), 10); }
        function closeModalKelolaPendidikan() { const m = document.getElementById('modalKelolaPendidikan'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }

        function tambahPendidikanJS(e) {
            e.preventDefault();
            const v = document.getElementById('inputPendidikanBaru').value.trim();
            if(v){
                const s = document.querySelector('select[name="pendidikan_input"]');
                let exists = Array.from(s.options).some(opt => opt.value === v);
                if(!exists) {
                    let op = document.createElement('option'); op.value=v; op.textContent=v; op.className = 'dark:bg-slate-800'; s.appendChild(op);

                    const listKelola = document.getElementById('listKelolaPendidikan');
                    const emptyMsg = listKelola.querySelector('.empty-msg');
                    if(emptyMsg) emptyMsg.remove();
                    const draftId = 'draft-pend-' + v.replace(/\s+/g, '-').toLowerCase();
                    const draftHtml = `<div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors" id="${draftId}"><span class="text-sm font-bold text-slate-700 dark:text-slate-300">${v}</span><button type="button" onclick="hapusDraftPendidikan('${v}', '${draftId}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`;
                    listKelola.insertAdjacentHTML('afterbegin', draftHtml);

                    let savedPend = JSON.parse(localStorage.getItem('customPendidikan')) || [];
                    if (!savedPend.includes(v)) { savedPend.push(v); localStorage.setItem('customPendidikan', JSON.stringify(savedPend)); }
                }
                s.value=v; s.dispatchEvent(new Event('change'));
                closeModalTambahPendidikan(); document.getElementById('inputPendidikanBaru').value=''; saveFormDraft();
            }
        }

        function hapusDraftPendidikan(val, elementId) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini dari daftar memori?')) return;
            const selectElement = document.querySelector('select[name="pendidikan_input"]');
            const optionToRemove = Array.from(selectElement.options).find(opt => opt.value === val);
            if (optionToRemove) optionToRemove.remove();
            const draftElement = document.getElementById(elementId);
            if (draftElement) draftElement.remove();
            selectElement.value = ""; selectElement.dispatchEvent(new Event('change')); saveFormDraft();

            let savedPend = JSON.parse(localStorage.getItem('customPendidikan')) || [];
            savedPend = savedPend.filter(item => item !== val);
            localStorage.setItem('customPendidikan', JSON.stringify(savedPend));
        }

        function openModalTambahRekomendasi() { const r = document.querySelector('input[name="kategori_rekomendasi"]:checked'); if(!r){alert("Pilih Radio Kategori Dulu!");return;} document.getElementById('tambahKategoriDisplay').textContent = r.value; const m = document.getElementById('modalTambahRekomendasi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.children[1].classList.remove('opacity-0', 'scale-95'), 10); }
        function closeModalTambahRekomendasi() { const m = document.getElementById('modalTambahRekomendasi'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }

        function openModalKelolaRekomendasi() {
            const r = document.querySelector('input[name="kategori_rekomendasi"]:checked');
            if(!r) { alert("Pilih Kategori Rekomendasi Terlebih Dahulu!"); return; }

            const items = document.querySelectorAll('#listKelolaRekomendasi .rekomendasi-item');
            let visibleCount = 0;
            items.forEach(item => {
                if (item.getAttribute('data-kategori') === r.value) {
                    item.classList.remove('hidden'); item.classList.add('flex');
                    visibleCount++;
                } else {
                    item.classList.add('hidden'); item.classList.remove('flex');
                }
            });

            let emptyMsg = document.querySelector('#listKelolaRekomendasi .empty-msg');
            if (visibleCount === 0) {
                if (!emptyMsg) {
                    document.getElementById('listKelolaRekomendasi').insertAdjacentHTML('beforeend', '<div class="text-sm text-slate-500 dark:text-slate-400 text-center py-6 italic empty-msg bg-slate-50 dark:bg-[#0F172A] rounded-2xl">Belum ada data rekomendasi untuk kategori ini.</div>');
                } else {
                    emptyMsg.style.display = 'block';
                }
            } else if (emptyMsg) {
                emptyMsg.style.display = 'none';
            }

            const m = document.getElementById('modalKelolaRekomendasi'); m.classList.remove('hidden'); m.classList.add('flex'); setTimeout(()=>m.children[1].classList.remove('opacity-0', 'scale-95'), 10);
        }

        function closeModalKelolaRekomendasi() { const m = document.getElementById('modalKelolaRekomendasi'); m.children[1].classList.add('opacity-0', 'scale-95'); setTimeout(()=>{ m.classList.add('hidden'); m.classList.remove('flex'); },300); }

        function tambahRekomendasiJS(e) {
            e.preventDefault();
            const r = document.querySelector('input[name="kategori_rekomendasi"]:checked'); if(!r)return;
            const v = document.getElementById('inputRekomendasiDetail').value.trim();
            if(v){
                const s = document.getElementById('selectTempatRekomendasi');
                let exists = Array.from(s.options).some(opt => opt.value === v && opt.getAttribute('data-kategori') === r.value);
                if(!exists) {
                    let op = document.createElement('option'); op.value=v; op.textContent=v; op.className = 'dark:bg-slate-800'; op.setAttribute('data-kategori', r.value); s.appendChild(op);

                    const listKelola = document.getElementById('listKelolaRekomendasi');
                    const emptyMsg = listKelola.querySelector('.empty-msg');
                    if(emptyMsg) emptyMsg.style.display = 'none';

                    // PERBAIKAN: Hilangkan karakter kutip/spasi yang merusak syntax HTML & JS
                    const safeV = v.replace(/'/g, "\\'").replace(/"/g, "&quot;").replace(/\n/g, " ");
                    const safeIdV = v.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase();
                    const draftId = 'draft-rek-' + safeIdV + '-' + r.value.replace(/\s+/g, '-').toLowerCase();

                    const draftHtml = `<div data-kategori="${r.value}" class="rekomendasi-item flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors" id="${draftId}"><span class="text-sm font-bold text-slate-700 dark:text-slate-300">${v}</span><button type="button" onclick="hapusDraftRekomendasi('${safeV}', '${draftId}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`;
                    listKelola.insertAdjacentHTML('afterbegin', draftHtml);

                    let savedRek = JSON.parse(localStorage.getItem('customRekomendasi')) || [];
                    if (!savedRek.some(item => item.val === v && item.cat === r.value)) {
                        savedRek.push({val: v, cat: r.value}); localStorage.setItem('customRekomendasi', JSON.stringify(savedRek));
                    }
                }
                s.value=v; s.dispatchEvent(new Event('change')); updateRekomendasiPreview(); closeModalTambahRekomendasi(); document.getElementById('inputRekomendasiDetail').value=''; saveFormDraft();
            }
        }

        function hapusDraftRekomendasi(val, elementId) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini dari daftar memori?')) return;
            const s = document.getElementById('selectTempatRekomendasi');
            const opt = Array.from(s.options).find(o => o.value === val);
            if (opt) opt.remove();
            const el = document.getElementById(elementId);
            if (el) el.remove();
            s.value = "Bawaan Sistem"; s.dispatchEvent(new Event('change')); updateRekomendasiPreview(); saveFormDraft();

            let savedRek = JSON.parse(localStorage.getItem('customRekomendasi')) || [];
            savedRek = savedRek.filter(item => item.val !== val);
            localStorage.setItem('customRekomendasi', JSON.stringify(savedRek));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Panggil Fungsi Sinkronisasi Urine
            syncTesUrine();

            const lsPend = JSON.parse(localStorage.getItem('customPendidikan'))||[]; const sPend = document.getElementById('selectPendidikan'); const lPend = document.getElementById('listKelolaPendidikan'); lsPend.forEach(v => { if(sPend && !Array.from(sPend.options).some(o=>o.value===v)){ const op=document.createElement('option');op.value=v;op.textContent=v;op.className='dark:bg-slate-800';sPend.appendChild(op); if(lPend){ const em=lPend.querySelector('.empty-msg');if(em)em.remove(); const id='draft-pend-'+v.replace(/\s+/g,'-').toLowerCase(); lPend.insertAdjacentHTML('afterbegin',`<div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors" id="${id}"><span class="text-sm font-bold text-slate-700 dark:text-slate-300">${v}</span><button type="button" onclick="hapusDraftPendidikan('${v}', '${id}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`); } } });

            const lsPek = JSON.parse(localStorage.getItem('customPekerjaan'))||[]; const sPek = document.getElementById('selectPekerjaan'); const lPek = document.getElementById('listKelolaPekerjaan'); lsPek.forEach(v => { if(sPek && !Array.from(sPek.options).some(o=>o.value===v)){ const op=document.createElement('option');op.value=v;op.textContent=v;op.className='dark:bg-slate-800';sPek.appendChild(op); if(lPek){ const em=lPek.querySelector('.empty-msg');if(em)em.remove(); const id='draft-pek-'+v.replace(/\s+/g,'-').toLowerCase(); lPek.insertAdjacentHTML('afterbegin',`<div class="flex justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors" id="${id}"><span class="text-sm font-bold text-slate-700 dark:text-slate-300">${v}</span><button type="button" onclick="hapusDraftPekerjaan('${v}', '${id}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`); } } });

            const lsRek = JSON.parse(localStorage.getItem('customRekomendasi'))||[];
            const sRek = document.getElementById('selectTempatRekomendasi');
            const lRek = document.getElementById('listKelolaRekomendasi');
            lsRek.forEach(obj => {
                if(sRek && !Array.from(sRek.options).some(o=>o.value===obj.val && o.getAttribute('data-kategori')===obj.cat)){
                    const op=document.createElement('option');op.value=obj.val;op.textContent=obj.val;op.setAttribute('data-kategori',obj.cat);op.className='dark:bg-slate-800';op.style.display='none';op.disabled=true;sRek.appendChild(op);
                    if(lRek){
                        const em=lRek.querySelector('.empty-msg');if(em)em.remove();

                        // PERBAIKAN: Render ulang dengan string yang di-escape dari local storage
                        const safeVal = obj.val.replace(/'/g, "\\'").replace(/"/g, "&quot;").replace(/\n/g, " ");
                        const safeIdV = obj.val.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase();
                        const id = 'draft-rek-' + safeIdV + '-' + obj.cat.replace(/\s+/g,'-').toLowerCase();

                        lRek.insertAdjacentHTML('afterbegin',`<div data-kategori="${obj.cat}" class="rekomendasi-item hidden justify-between items-center p-4 border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-[#0F172A]/50 rounded-2xl hover:bg-slate-100 dark:hover:bg-[#0F172A] transition-colors" id="${id}"><span class="text-sm font-bold text-slate-700 dark:text-slate-300">${obj.val}</span><button type="button" onclick="hapusDraftRekomendasi('${safeVal}', '${id}')" class="text-[10px] uppercase tracking-widest font-black text-rose-500 dark:text-rose-400 bg-white dark:bg-[#1E293B] hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-rose-100 dark:border-rose-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm">Hapus</button></div>`);
                    }
                }
            });

            initAllCustomSelects();
            loadFormDraft();
            toggleModeKtp(); toggleModeDomisili(); updateNarkotikaText();

            // Mengamankan logika rakitan real-time Alamat
            ['jalan_ktp', 'desa_ktp', 'kecamatan_ktp'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateAlamatKtp);
                    el.addEventListener('change', updateAlamatKtp);
                }
            });
            const tipeKtp = document.getElementById('tipe_desa_ktp');
            if (tipeKtp) tipeKtp.addEventListener('change', updateAlamatKtp);

            ['jalan_domisili', 'desa_domisili', 'kecamatan_domisili'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateAlamatDomisili);
                    el.addEventListener('change', updateAlamatDomisili);
                }
            });
            const tipeDom = document.getElementById('tipe_desa_domisili');
            if (tipeDom) tipeDom.addEventListener('change', updateAlamatDomisili);

            initSearchableDropdown('desa_ktp', 'list_desa_ktp', 'tipe_desa_ktp', updateAlamatKtp);
            initSearchableDropdown('desa_domisili', 'list_desa_domisili', 'tipe_desa_domisili', updateAlamatDomisili);

            const pr = document.getElementById('penghasilan_rupiah'); if (pr) { pr.addEventListener('keyup', function() { this.value = formatRupiah(this.value, 'Rp. '); }); if(pr.value) pr.value = formatRupiah(pr.value, 'Rp. '); }
            const tl = document.getElementById('tgl_lahir'); if(tl) { tl.addEventListener('change', hitungUsia); tl.addEventListener('blur', hitungUsia); hitungUsia(); }

            document.getElementById('manual_ktp').addEventListener('input', updateAlamatKtp); document.getElementById('manual_domisili').addEventListener('input', updateAlamatDomisili);

            if(mainForm) { mainForm.addEventListener('input', saveFormDraft); mainForm.addEventListener('change', saveFormDraft); mainForm.addEventListener('submit', function(e) { updateAlamatKtp(); updateAlamatDomisili(); updateRekomendasiPreview(); updateSaranHidden(); localStorage.removeItem('formKlienCreateDraft'); localStorage.removeItem('shared_tes_urine_new'); }); }
        });
    </script>
</x-app-layout>
