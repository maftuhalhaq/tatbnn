<x-app-layout>
    <style>
        /* Animasi Lembut Mac UI */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -40px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-blob { animation: blob 15s infinite alternate; }
        .animation-delay-2000 { animation-delay: 4s; }
        .animation-delay-4000 { animation-delay: 8s; }

        /* Custom Scrollbar Dropdown Mac Style */
        .custom-select-scroll::-webkit-scrollbar { width: 5px; }
        .custom-select-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-select-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @media (prefers-color-scheme: dark) {
            .custom-select-scroll::-webkit-scrollbar-thumb { background: #334155; }
            .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
        }
    </style>

    <!-- BACKGROUND BASE & BLOBS (Berada di lapisan paling belakang) -->
    <div class="fixed inset-0 z-0 bg-[#F8FAFC] dark:bg-[#0B1120] transition-colors duration-500 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <x-slot name="header">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 max-w-[1400px] mx-auto w-full">
            <div>
                <h2 class="font-black text-2xl text-slate-900 dark:text-white tracking-tight">Data Asesmen & Case Conference</h2>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Kelola data klien, status hukum, dan hasil rekomendasi TAT.</p>
            </div>

            <!-- HANYA MANAGE-DATA YANG BISA TAMBAH DATA -->
            @can('manage-data')
            <a href="{{ route('asesmen.create') }}" class="inline-flex items-center px-6 py-3 bg-[#3B82F6] hover:bg-blue-600 rounded-full font-black text-[11px] text-white uppercase tracking-widest transition shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40 hover:-translate-y-0.5 transform-gpu">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data Baru
            </a>
            @endcan
        </div>
    </x-slot>

    <!-- Konten dibungkus dengan relative z-10 agar berada di atas background animasi -->
    <div class="relative z-10 py-8 min-h-screen">
        <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. ALERT MESSAGES -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-2xl flex items-start shadow-sm transition-colors duration-300">
                    <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="ml-3 text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/50 rounded-2xl flex items-start shadow-sm transition-colors duration-300">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="ml-3 text-sm font-bold text-rose-800 dark:text-rose-400">{{ session('error') }}</p>
                </div>
            @endif

            <!-- 2. BLOK MANAJEMEN DATA MASSAL (SOLID PUTIH) -->
            @can('manage-data')
            <div class="bg-white dark:bg-[#1E293B] p-6 sm:p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] transition-colors duration-300 relative z-20 isolate transform-gpu">
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-800/40 text-emerald-500 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-700/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-slate-200 text-base tracking-tight">Manajemen Data Massal Excel</h3>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">Unduh template kosong untuk diisi secara luring (offline), lalu unggah kembali ke sistem untuk mempercepat proses input data banyak klien sekaligus.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
                        <!-- DUA TOMBOL TEMPLATE -->
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <a href="{{ route('asesmen.downloadTemplate', ['type' => 'rekap']) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-slate-50 dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:border-emerald-200 transition-colors shadow-sm transform-gpu" title="Unduh Template Rekap TAT">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Template Rekap
                            </a>
                            <a href="{{ route('asesmen.downloadTemplate', ['type' => 'cc']) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-slate-50 dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:border-emerald-200 transition-colors shadow-sm transform-gpu" title="Unduh Template Case Conference">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Template Case Conf.
                            </a>
                        </div>

                        <!-- TOMBOL IMPORT -->
                        <form action="{{ route('asesmen.import') }}" method="POST" enctype="multipart/form-data" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-2 bg-slate-50 dark:bg-[#0F172A] p-1.5 rounded-[1.25rem] border border-slate-200 dark:border-slate-700 shadow-sm transform-gpu">
                            @csrf
                            <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-white dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer transition-colors shadow-sm">
                            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] uppercase tracking-widest font-black rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition shadow-md">Import</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan

            <!-- ============================================== -->
            <!-- 3. BOKS PENCARIAN & FILTER (SOLID PUTIH MAC UI)-->
            <!-- ============================================== -->
            <div class="bg-white dark:bg-[#1E293B] p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] mb-6 transition-colors duration-300 relative z-40 isolate transform-gpu">
                <form action="{{ route('asesmen.index') }}" method="GET" class="flex flex-col lg:flex-row gap-5">

                    <!-- Kolom Pencarian Teks -->
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Pencarian Data</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Klien, NIK, atau No. Register..." class="block w-full py-3 pl-11 pr-4 rounded-[1.25rem] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0F172A] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-sm">
                        </div>
                    </div>

                    <!-- Kolom Filter Waktu (Tanggal, Bulan, Tahun - Custom Mac UI Dropdown) -->
                    <div class="flex flex-col sm:flex-row gap-3">

                        <!-- Filter Tanggal Harian (DATEPICKER-ID) -->
                        <div class="w-full sm:w-auto relative">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" name="tanggal" value="{{ request('tanggal') }}" class="datepicker-id block w-full py-3 pl-10 pr-4 rounded-[1.25rem] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0F172A] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm cursor-pointer" placeholder="Pilih Tanggal" autocomplete="off">
                            </div>
                        </div>

                        <!-- Filter Bulan (Custom Alpine Dropdown) -->
                        @php
                            $bulanList = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                            $currentBulan = request('bulan');
                            $labelBulan = $currentBulan ? $bulanList[$currentBulan] : '-- Semua Bulan --';
                        @endphp
                        <div class="w-full sm:w-48">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Bulan</label>
                            <div x-data="{ open: false, selectedLabel: '{{ $labelBulan }}' }" class="relative w-full">
                                <select name="bulan" id="bulan_select" class="hidden">
                                    <option value="">-- Semua Bulan --</option>
                                    @foreach($bulanList as $num => $name)
                                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between w-full py-3 px-4 rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0F172A] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm">
                                    <span x-text="selectedLabel" class="truncate"></span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" style="display: none;"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                     class="absolute z-50 top-full left-0 mt-2 w-full rounded-2xl bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] p-2 max-h-60 overflow-y-auto custom-select-scroll">

                                    <button type="button" @click="document.getElementById('bulan_select').value = ''; selectedLabel = '-- Semua Bulan --'; open = false;" class="w-full text-left px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('bulan') == '' ? 'bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">-- Semua Bulan --</button>

                                    @foreach($bulanList as $num => $name)
                                        <button type="button" @click="document.getElementById('bulan_select').value = '{{ $num }}'; selectedLabel = '{{ $name }}'; open = false;" class="mt-0.5 w-full text-left px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('bulan') == $num ? 'bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">{{ $name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Filter Tahun (Custom Alpine Dropdown) -->
                        @php
                            $currentTahun = request('tahun');
                            $labelTahun = $currentTahun ? $currentTahun : '-- Semua Tahun --';
                        @endphp
                        <div class="w-full sm:w-48">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Tahun</label>
                            <div x-data="{ open: false, selectedLabel: '{{ $labelTahun }}' }" class="relative w-full">
                                <select name="tahun" id="tahun_select" class="hidden">
                                    <option value="">-- Semua Tahun --</option>
                                    @foreach($tahunTersedia as $thn)
                                        <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                                    @endforeach
                                </select>
                                <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between w-full py-3 px-4 rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0F172A] text-slate-800 dark:text-slate-200 text-sm font-medium focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 transition-all outline-none shadow-sm">
                                    <span x-text="selectedLabel" class="truncate"></span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" style="display: none;"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                     class="absolute z-50 top-full right-0 mt-2 w-full rounded-2xl bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] p-2 max-h-60 overflow-y-auto custom-select-scroll">

                                    <button type="button" @click="document.getElementById('tahun_select').value = ''; selectedLabel = '-- Semua Tahun --'; open = false;" class="w-full text-left px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('tahun') == '' ? 'bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">-- Semua Tahun --</button>

                                    @foreach($tahunTersedia as $thn)
                                        <button type="button" @click="document.getElementById('tahun_select').value = '{{ $thn }}'; selectedLabel = '{{ $thn }}'; open = false;" class="mt-0.5 w-full text-left px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('tahun') == $thn ? 'bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">{{ $thn }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-end gap-2 mt-4 lg:mt-0">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-[#3B82F6] hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest rounded-[1.25rem] shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 transition flex items-center justify-center transform-gpu">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'tanggal', 'bulan', 'tahun']))
                            <a href="{{ route('asesmen.index') }}" class="w-full sm:w-auto px-4 py-3 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[11px] font-black uppercase tracking-widest rounded-[1.25rem] hover:bg-rose-100 dark:hover:bg-rose-500/20 border border-rose-200 dark:border-rose-800/50 transition-colors flex items-center justify-center transform-gpu" title="Hapus semua filter">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ============================================== -->
            <!-- 4. TABEL DATA (SOLID PUTIH)                    -->
            <!-- ============================================== -->
            <div class="bg-white dark:bg-[#1E293B] shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors duration-300 relative z-20 isolate transform-gpu">
                <!-- Header Card Tabel -->
                <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-slate-800 dark:text-white text-lg tracking-tight">Tabel Data Klien</h3>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">

                        <!-- Custom Sort Dropdown -->
                        @php
                            $sortList = [
                                'terbaru' => 'Terbaru Ditambahkan',
                                'terlama' => 'Terlama Ditambahkan',
                                'a-z' => 'Abjad (A - Z)',
                                'z-a' => 'Abjad (Z - A)'
                            ];
                            $currentSort = request('sort', 'terbaru');
                            $labelSort = $sortList[$currentSort] ?? 'Terbaru Ditambahkan';
                        @endphp
                        <form action="{{ route('asesmen.index') }}" method="GET" class="flex-1 sm:flex-none">
                            @foreach(request()->except('sort', 'page') as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach

                            <div x-data="{ open: false, selectedLabel: '{{ $labelSort }}' }" class="relative w-full sm:w-56">
                                <select name="sort" id="sort_select" class="hidden">
                                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama Ditambahkan</option>
                                    <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Abjad (A - Z)</option>
                                    <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Abjad (Z - A)</option>
                                </select>
                                <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between w-full py-2 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0F172A] text-slate-700 dark:text-slate-300 text-xs font-bold hover:border-[#3B82F6] hover:ring-2 hover:ring-blue-500/20 transition-all outline-none shadow-sm">
                                    <span x-text="selectedLabel" class="truncate"></span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" style="display: none;"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                     class="absolute z-50 top-full right-0 mt-2 w-full rounded-2xl bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] p-1.5 max-h-60 overflow-y-auto custom-select-scroll">

                                    @foreach($sortList as $val => $label)
                                        <button type="button" @click="document.getElementById('sort_select').value = '{{ $val }}'; selectedLabel = '{{ $label }}'; open = false; document.getElementById('sort_select').form.submit();" class="w-full text-left px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('sort', 'terbaru') == $val ? 'bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </form>

                        @can('manage-data')
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <!-- EXPORT LAMA -->
                            <a href="{{ route('asesmen.export-excel', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 dark:border-slate-700 text-[10px] uppercase tracking-widest font-black rounded-xl shadow-sm text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-[#0F172A] hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors transform-gpu">
                                Export (Filter)
                            </a>
                            <a href="{{ route('asesmen.export-excel', ['sort' => request('sort', 'terbaru')]) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 dark:border-slate-700 text-[10px] uppercase tracking-widest font-black rounded-xl shadow-sm text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-[#0F172A] hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors transform-gpu">
                                Export (Sorting)
                            </a>

                            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 my-auto hidden sm:block mx-1"></div>

                            <!-- EXPORT TEMPLATE BARU -->
                            <a href="{{ route('asesmen.export-excel', array_merge(request()->query(), ['type' => 'rekap'])) }}" class="inline-flex items-center px-4 py-2 border border-emerald-200 dark:border-emerald-800/50 text-[10px] uppercase tracking-widest font-black rounded-xl shadow-sm text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors transform-gpu" title="Unduh dengan Format Rekap TAT BNNP">
                                <svg class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Rekap TAT
                            </a>
                            <a href="{{ route('asesmen.export-excel', array_merge(request()->query(), ['type' => 'cc'])) }}" class="inline-flex items-center px-4 py-2 border border-sky-200 dark:border-sky-800/50 text-[10px] uppercase tracking-widest font-black rounded-xl shadow-sm text-sky-700 dark:text-sky-400 bg-sky-50 dark:bg-sky-500/10 hover:bg-sky-100 dark:hover:bg-sky-500/20 transition-colors transform-gpu" title="Unduh dengan Format Case Conference">
                                <svg class="h-3.5 w-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Case Conf.
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

                <!-- Bagian Tabel -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#0F172A] text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-black border-b border-slate-100 dark:border-slate-800">
                                <th class="px-6 py-4 w-16">No</th>
                                <th class="px-6 py-4">Identitas Klien</th>
                                <th class="px-6 py-4">Perkara Hukum</th>
                                <th class="px-6 py-4">Medis & Rekomendasi</th>
                                <th class="px-6 py-4 text-right pr-8">Tindakan & Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 bg-white dark:bg-[#1E293B]">
                            @forelse($asesmens as $index => $item)
                                @php
                                    $usiaKlien = '-';
                                    if (!empty($item->tgl_lahir)) {
                                        try { $usiaKlien = \Carbon\Carbon::parse($item->tgl_lahir)->age . ' Tahun'; } catch (\Exception $e) {}
                                    }
                                    $bb_gabungan = ($item->berat_bb ? $item->berat_bb . ' gr' : '') . ($item->berat_bb && $item->deskripsi_bb ? ' - ' : '') . ($item->deskripsi_bb ?? '');

                                    // LOGIKA KELENGKAPAN DATA
                                    $kolomWajib = [
                                        'no_register', 'no_bln', 'asal_pengajuan', 'no_surat_pengajuan', 'no_lkn',
                                        'tgl_surat', 'tgl_berkas', 'tgl_pelaksanaan', 'tgl_tangkap',
                                        'nama_lengkap', 'nik', 'kewarganegaraan', 'agama', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'no_hp',
                                        'pendidikan_id', 'pekerjaan_id', 'penghasilan_rata_rata', 'alamat_ktp', 'alamat_domisili',
                                        'narkotika_id', 'berat_bb', 'deskripsi_bb', 'pasal_sangkaan',
                                        'status_hukum', 'keterlibatan_jaringan', 'cara_mendapatkan', 'dapat_dari_siapa',
                                        'tes_urine', 'hasil_asesmen_hukum', 'hasil_asesmen_medis',
                                        'aspek_hukum', 'aspek_medis', 'kesehatan_fisik', 'psikologi',
                                        'alasan_penggunaan', 'kondisi_keluarga', 'tingkat_ketergantungan', 'pola_pemakaian', 'kondisi_lingkungan', 'saran_case_conference'
                                    ];

                                    $isDataUtamaLengkap = true;
                                    foreach($kolomWajib as $kol) {
                                        if ($item->$kol === null || trim((string)$item->$kol) === '') {
                                            $isDataUtamaLengkap = false;
                                            break;
                                        }
                                    }

                                    $isBaLengkap = !empty($item->no_ba) && !empty($item->ketua_tat_nama);
                                    $isRekomLengkap = !empty($item->no_surat_rekomendasi) && !empty($item->kepada_yth);
                                    $isLengkapTotal = $isDataUtamaLengkap && $isBaLengkap && $isRekomLengkap;
                                @endphp

                                <tr class="hover:bg-slate-50 dark:hover:bg-[#0F172A] transition-colors duration-200 group">
                                    <td class="px-6 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 align-top">{{ $asesmens->firstItem() + $index }}</td>

                                    <!-- Identitas Klien -->
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 w-10 h-10 relative">
                                                @if ($item->foto_klien)
                                                    <img class="w-full h-full rounded-full object-cover shadow-sm ring-2 ring-white dark:ring-slate-800" src="{{ asset('storage/' . $item->foto_klien) }}">
                                                @else
                                                    <div class="w-full h-full rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold flex items-center justify-center text-lg shadow-inner ring-2 ring-white dark:ring-slate-800">{{ strtoupper(substr($item->nama_lengkap ?? 'A', 0, 1)) }}</div>
                                                @endif
                                            </div>
                                            <div class="space-y-1">
                                                <div class="font-black text-slate-900 dark:text-white text-sm text-left group-hover:text-[#3B82F6] transition-colors">{{ $item->nama_lengkap }}</div>
                                                <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400">NIK: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $item->nik ?? '-' }}</span></div>
                                                <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Reg: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $item->no_register ?? '-' }}</span></div>
                                                <div class="mt-2.5 flex flex-col gap-2">
                                                    <div>
                                                        @if($item->is_lengkap)
    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm mt-2">
        DATA LENGKAP
    </span>
@else
    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-500 border border-amber-200 dark:border-amber-800/50 shadow-sm mt-2">
        BELUM LENGKAP
    </span>
@endif
                                                    </div>
                                                    <div class="pt-2">
                                                        <div id="text-tanggal-{{ $item->id }}" class="flex items-center gap-1.5">
                                                            <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Ditambahkan:</span>
                                                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</span>
                                                            @can('manage-data')
                                                            <button type="button" onclick="toggleEditTanggal('{{ $item->id }}', true)" class="text-slate-400 hover:text-[#3B82F6] transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                            </button>
                                                            @endcan
                                                        </div>
                                                        @can('manage-data')
                                                        <form id="form-tanggal-{{ $item->id }}" action="{{ route('asesmen.update-tanggal', $item->id) }}" method="POST" class="hidden items-center gap-1.5 mt-1">
                                                            @csrf @method('PATCH')
                                                            <input type="date" name="tanggal_ditambahkan" value="{{ $item->created_at ? $item->created_at->format('Y-m-d') : date('Y-m-d') }}" class="text-[10px] font-bold border-slate-200 dark:border-slate-700 bg-white dark:bg-[#0F172A] text-slate-800 dark:text-white rounded-lg py-1 px-2 focus:ring-[#3B82F6] h-7 outline-none shadow-sm">
                                                            <button type="submit" class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] uppercase tracking-widest font-black rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 h-7 transition-colors">Simpan</button>
                                                            <button type="button" onclick="toggleEditTanggal('{{ $item->id }}', false)" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[9px] uppercase tracking-widest font-black rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 h-7 transition-colors">Batal</button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Perkara Hukum -->
                                    <td class="px-6 py-5 align-top">
                                        <div class="text-xs font-black text-slate-800 dark:text-white">
                                            {{ $item->narkotika->jenis_narkotika ?? 'Belum diset' }}
                                            @if($item->berat_bb !== null) <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold ml-1 tracking-wider">({{ $item->berat_bb }} gr)</span> @endif
                                        </div>
                                        @if($item->status_hukum)
                                            <div class="mt-2">
                                                <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-widest border border-slate-200/50 dark:border-slate-700/50">{{ $item->status_hukum }}</span>
                                            </div>
                                        @endif
                                        <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 max-w-[220px] leading-relaxed" title="{{ $item->pasal_sangkaan }}">{{ $item->pasal_sangkaan ?? 'Pasal belum diinput' }}</div>
                                    </td>

                                    <!-- Medis & Rekomendasi -->
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Urine:</span>
                                            @php
                                                // LOGIKA CERDAS PENDETEKSI URINE
                                                $tesUrineDB = strtoupper($item->tes_urine ?? '');
                                                $isPositif = stripos($tesUrineDB, 'POSITIF') !== false;
                                                $isNegatif = stripos($tesUrineDB, 'NEGATIF') !== false;
                                            @endphp

                                            @if($isPositif)
                                                <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 tracking-widest uppercase border border-rose-200/50 dark:border-rose-500/20">POSITIF</span>
                                            @elseif($isNegatif)
                                                <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 tracking-widest uppercase border border-emerald-200/50 dark:border-emerald-500/20">NEGATIF</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 tracking-widest uppercase border border-slate-200/50 dark:border-slate-700/50">N/A</span>
                                            @endif
                                        </div>
                                        <div class="text-xs font-black text-[#3B82F6] dark:text-blue-400 line-clamp-2 max-w-[220px] leading-snug">
                                            {{ $item->rekomendasi_input ?? $item->rekomendasi->tempat_rehabilitasi ?? 'Belum ada rekomendasi' }}
                                        </div>
                                    </td>

                                    <!-- Tindakan & Dokumen -->
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-col gap-2 min-w-[180px] float-right">
                                            <!-- BARIS 1: Detail, Edit, Hapus -->
                                            <div class="flex gap-1 w-full">
                                                <a href="{{ route('asesmen.show', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 text-[10px] uppercase tracking-widest font-black text-slate-600 dark:text-slate-300 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-[#3B82F6] dark:hover:text-blue-400 transition-colors shadow-sm transform-gpu">
                                                    Detail
                                                </a>
                                                @can('manage-data')
                                                <a href="{{ route('asesmen.edit', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 text-[10px] uppercase tracking-widest font-black text-slate-600 dark:text-slate-300 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-amber-500 transition-colors shadow-sm transform-gpu">
                                                    Edit
                                                </a>
                                                <form action="{{ route('asesmen.destroy', $item->id) }}" method="POST" class="m-0 flex">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center px-2 py-1.5 text-slate-400 dark:text-slate-500 bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/30 hover:text-rose-500 dark:hover:text-rose-400 transition-colors shadow-sm transform-gpu" title="Hapus Data" onclick="return confirm('Hapus klien permanen?')">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>

                                            <!-- BARIS 2: B. Acara & Rekom -->
                                            @can('akses-dokumen')
                                            <div class="flex gap-1 w-full mt-0.5">
                                                <a href="{{ route('asesmen.berita-acara', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/50 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors text-[9px] uppercase tracking-widest font-black shadow-sm transform-gpu">
                                                    B. Acara
                                                </a>
                                                <a href="{{ route('asesmen.rekomendasi', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 bg-purple-50 dark:bg-purple-500/10 border border-purple-200/50 dark:border-purple-800/50 text-purple-600 dark:text-purple-400 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-500/20 transition-colors text-[9px] uppercase tracking-widest font-black shadow-sm transform-gpu">
                                                    Rekom
                                                </a>
                                            </div>
                                            @endcan

                                            <!-- BARIS 3 & 4: Detail Tambahan & PDF -->
                                            <button type="button" onclick="toggleExpand('{{ $item->id }}')" class="inline-flex items-center justify-between px-3 py-2 bg-blue-50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700/50 text-[#3B82F6] dark:text-blue-400 rounded-xl hover:bg-blue-100 dark:hover:bg-slate-800 transition-colors text-[10px] uppercase tracking-widest font-black w-full shadow-sm group mt-0.5 transform-gpu">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6] dark:bg-blue-400"></span>
                                                    Case Conference
                                                </div>
                                                <svg id="icon-expand-{{ $item->id }}" class="w-3.5 h-3.5 text-blue-400 dark:text-slate-500 group-hover:text-[#3B82F6] dark:group-hover:text-blue-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                            </button>
                                            <a href="{{ route('asesmen.cetakPdf', $item->id) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-800/50 text-rose-500 dark:text-rose-400 rounded-xl hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors text-[10px] uppercase tracking-widest font-black w-full shadow-sm transform-gpu">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                                Unduh PDF Ringkasan
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- EXPANDABLE ROW (Case Conference Data) -->
                                <tr id="expand-row-{{ $item->id }}" class="hidden bg-slate-50 dark:bg-[#0F172A] border-b-2 border-[#3B82F6]/30 dark:border-blue-900/50 shadow-inner">
                                    <td colspan="5" class="p-4 sm:p-6">
                                        <div class="bg-white dark:bg-[#1E293B] rounded-[2rem] border border-slate-100 dark:border-slate-700/50 shadow-sm p-6 lg:p-8 space-y-6">
                                            <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-700/50 pb-4">
                                                <div class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></div>
                                                <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Detail Case Conference: <span class="text-[#3B82F6] dark:text-blue-400">{{ $item->nama_lengkap }}</span></h4>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                                <!-- Identitas -->
                                                <div class="space-y-4">
                                                    <h5 class="text-[10px] font-black text-slate-400 dark:text-slate-500 tracking-widest border-b border-slate-100 dark:border-slate-700/50 pb-2 mb-3">IDENTITAS</h5>
                                                    <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Usia</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $usiaKlien }}</p></div>
                                                    <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">P / L (Jenis Kelamin)</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->jenis_kelamin == 'L' ? 'Laki-Laki' : ($item->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p></div>
                                                    <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pekerjaan</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->pekerjaan_input ?? $item->pekerjaan->nama_pekerjaan ?? '-' }}</p></div>
                                                    <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pendidikan Terakhir</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->pendidikan_input ?? $item->pendidikan->nama_pendidikan ?? '-' }}</p></div>
                                                    <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Domisili</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200 leading-snug">{{ $item->alamat_domisili ?? '-' }}</p></div>
                                                </div>

                                                <!-- Hukum & Medis -->
                                                <div class="space-y-6">
                                                    <div class="space-y-4">
                                                        <h5 class="text-[10px] font-black text-amber-500 tracking-widest border-b border-amber-100 dark:border-amber-900/30 pb-2 mb-3">HUKUM</h5>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Status Hukum</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->status_hukum ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Keterlibatan Jaringan</span><p class="font-bold text-sm text-rose-500 dark:text-rose-400 uppercase">{{ $item->keterlibatan_jaringan ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Barang Bukti</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $bb_gabungan ?: '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Cara Mendapatkan</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->cara_mendapatkan ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Dapat Dari</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->dapat_dari_siapa ?? '-' }}</p></div>
                                                    </div>
                                                    <div class="space-y-4 pt-2">
                                                        <h5 class="text-[10px] font-black text-blue-500 tracking-widest border-b border-blue-100 dark:border-blue-900/30 pb-2 mb-3">MEDIS</h5>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kesehatan</span><p class="font-medium text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $item->kesehatan_fisik ?? $item->aspek_medis ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Psikologi</span><p class="font-medium text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $item->psikologi ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Hasil Tes Urine</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->tes_urine ?? '-' }}</p></div>
                                                    </div>
                                                </div>

                                                <!-- Kondisi & Riwayat -->
                                                <div class="space-y-6">
                                                    <div class="space-y-4">
                                                        <h5 class="text-[10px] font-black text-slate-400 dark:text-slate-500 tracking-widest border-b border-slate-100 dark:border-slate-700/50 pb-2 mb-3">KONDISI & RIWAYAT</h5>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Alasan Penggunaan</span><p class="font-medium text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $item->alasan_penggunaan ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Keluarga</span><p class="font-medium text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $item->kondisi_keluarga ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Tingkat Ketergantungan</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->tingkat_ketergantungan ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pola Pemakaian</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->pola_pemakaian ?? '-' }}</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Lingkungan</span><p class="font-medium text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $item->kondisi_lingkungan ?? '-' }}</p></div>
                                                    </div>
                                                    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                                        <div>
                                                            <span class="block text-[9px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-1">Rekomendasi (Instansi)</span>
                                                            <p class="font-black text-sm text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 px-3 py-1.5 rounded-lg inline-block">{{ $item->rekomendasi_input ?? $item->rekomendasi->tempat_rehabilitasi ?? '-' }}</p>
                                                        </div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Keterangan Hukum</span><p class="font-medium text-sm text-slate-600 dark:text-slate-400 italic">"{{ $item->rekomendasi_keterangan ?? $item->keterangan_tambahan ?? '-' }}"</p></div>
                                                        <div><span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Saran Sidang</span><p class="font-bold text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-[#0F172A] p-3.5 rounded-xl border border-slate-100 dark:border-slate-700/50 whitespace-pre-line">{{ $item->saran_case_conference ?? '-' }}</p></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border-t border-slate-100 dark:border-slate-700/50 pt-5 flex justify-end">
                                                <button onclick="toggleExpand('{{ $item->id }}')" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">Tutup Detail</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center bg-white dark:bg-[#1E293B]">
                                        <div class="mx-auto w-24 h-24 mb-6 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-700">
                                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">Belum ada data klien</h3>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8">Mulai dengan menambahkan data klien secara manual, atau import langsung melalui template Excel yang disediakan di atas.</p>
                                        @can('manage-data')
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('asesmen.create') }}" class="px-8 py-3.5 bg-[#3B82F6] text-white text-[11px] uppercase tracking-widest font-black rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40 hover:bg-blue-600 hover:-translate-y-0.5 transition-all transform-gpu">Tambah Data Klien</a>
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($asesmens->hasPages())
                    <div class="px-6 py-5 border-t border-slate-100 dark:border-slate-800/50 bg-white dark:bg-[#1E293B]">
                        {{ $asesmens->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Script Animasi Filter & Expand -->
    <script>
        function toggleExpand(id) {
            const expandRow = document.getElementById('expand-row-' + id);
            const icon = document.getElementById('icon-expand-' + id);
            if (expandRow.classList.contains('hidden')) {
                expandRow.classList.remove('hidden');
                // animasi panah
                icon.classList.add('rotate-180');
            } else {
                expandRow.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        function toggleEditTanggal(id, isOpening) {
            const textDiv = document.getElementById('text-tanggal-' + id);
            const formDiv = document.getElementById('form-tanggal-' + id);
            if (isOpening) {
                if (confirm("Apakah Anda yakin ingin mengedit tanggal klien ditambahkan?")) {
                    formDiv.classList.remove('hidden'); formDiv.classList.add('flex');
                    textDiv.classList.add('hidden'); textDiv.classList.remove('flex');
                }
            } else {
                formDiv.classList.add('hidden'); formDiv.classList.remove('flex');
                textDiv.classList.remove('hidden'); textDiv.classList.add('flex');
            }
        }
    </script>
</x-app-layout>
