<x-app-layout>
    <!-- Tambahkan library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS Custom untuk Scrollbar -->
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
    </style>

    <div class="py-8 sm:py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- ========================================== -->
            <!-- 1. HEADER HERO DASHBOARD -->
            <!-- ========================================== -->
            <div class="bg-gradient-to-r from-indigo-800 to-blue-700 rounded-3xl p-8 sm:p-10 shadow-lg text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-32 -mb-10 w-32 h-32 bg-blue-400 opacity-20 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-900/50 border border-indigo-500/30 text-indigo-100 text-xs font-semibold mb-4 tracking-wide uppercase">
                        Command Center TAT
                    </div>
                    <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight mb-2">Dashboard Analisis BNN</h2>
                    <p class="text-indigo-100 text-sm sm:text-base max-w-2xl leading-relaxed">
                        Selamat datang, <strong>{{ explode(' ', Auth::user()->name)[0] }}</strong>. Berikut adalah ringkasan performa Tim Asesmen Terpadu dan tren peredaran narkotika untuk membantu pengambilan keputusan strategis.
                    </p>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 2. KPI CARDS (KEY PERFORMANCE INDICATORS) -->
            <!-- ========================================== -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Klien -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-indigo-300 transition-colors">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Klien Terdaftar</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalKlien) }}</h3>
                        </div>
                        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Kasus Menunggu TAT -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-amber-300 transition-colors">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Menunggu Sidang TAT</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($menungguTAT) }}</h3>
                            <p class="text-[10px] text-amber-600 font-bold mt-1 bg-amber-100 inline-block px-2 py-0.5 rounded">Butuh Tindakan</p>
                        </div>
                        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Kasus Selesai -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-emerald-300 transition-colors">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Selesai Diasesmen</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($selesaiTAT) }}</h3>
                        </div>
                        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Barang Bukti -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-rose-300 transition-colors">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Bukti Disita</p>
                            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalBB, 1, ',', '.') }} <span class="text-base font-bold text-slate-500">Gram</span></h3>
                        </div>
                        <div class="p-3 bg-rose-100 text-rose-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 3. ANALISIS DEMOGRAFI & DAFTAR KERJA -->
            <!-- ========================================== -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                <!-- Kolom Kiri: Demografi Gender -->
                <div class="xl:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                            <h3 class="font-extrabold text-slate-800">Demografi Gender Klien</h3>
                            <button type="button" onclick="openModal('modalDetailGender')" class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                Lihat Detail &rarr;
                            </button>
                        </div>
                        <div class="space-y-4">
                            @foreach($demografiGender as $gender)
                                <div>
                                    <div class="flex justify-between text-sm mb-1 font-bold text-slate-700">
                                        <span>{{ $gender->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                                        <span>{{ $gender->total }} Orang</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                                        <div class="bg-{{ $gender->jenis_kelamin == 'L' ? 'blue' : 'rose' }}-500 h-2.5 rounded-full" style="width: {{ ($totalKlien > 0) ? ($gender->total / $totalKlien) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Kasus To-Do List / Terbaru -->
                <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-start">
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-lg">Aktivitas Asesmen Terbaru</h3>
                            @if($tanggalTerakhir)
                                <p class="text-[12px] text-slate-500 mt-1">
                                    Menampilkan <span class="font-bold text-indigo-600">{{ $aktivitasTerbaru->count() }} klien</span> yang diinput pada batch terakhir
                                    (<span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalTerakhir)->translatedFormat('d F Y') }}</span>).
                                </p>
                            @else
                                <p class="text-[12px] text-slate-500 mt-1">Belum ada data klien terdaftar.</p>
                            @endif
                        </div>
                        <a href="{{ route('asesmen.index') }}" class="text-xs font-bold text-indigo-600 bg-white border border-indigo-200 px-4 py-2 rounded-lg hover:bg-indigo-50 shadow-sm transition-colors whitespace-nowrap">Lihat Semua Data &rarr;</a>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 max-h-[400px] space-y-3 custom-select-scroll">
                        @forelse($aktivitasTerbaru as $item)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100/50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-blue-200 text-indigo-700 font-bold flex items-center justify-center border border-indigo-200 shrink-0 text-lg">
                                        {{ strtoupper(substr($item->nama_lengkap ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $item->nama_lengkap }}</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">No. Reg: <span class="font-semibold">{{ $item->no_register ?? '-' }}</span></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    @if($item->pelaksanaan === 'YA')
                                        <span class="hidden sm:inline-flex px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-md uppercase tracking-wider border border-emerald-200">Selesai TAT</span>
                                    @else
                                        <span class="hidden sm:inline-flex px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-md uppercase tracking-wider border border-amber-200">Menunggu TAT</span>
                                    @endif

                                    <a href="{{ route('asesmen.show', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-100 hover:text-slate-900 transition-all shadow-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500 text-sm font-medium">
                                Belum ada aktivitas yang dapat ditampilkan.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- CARD: DEMOGRAFI USIA KLIEN (DINAMIS) -->
            <!-- ========================================== -->
            <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm mt-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-4 mb-6 gap-4">

                    <div class="flex items-center gap-3">
                        <h3 class="text-[15px] font-extrabold text-slate-800">Demografi Usia Klien</h3>
                        <button type="button" onclick="openModal('modalDetailUsia')" class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Lihat Detail &rarr;
                        </button>
                    </div>

                    <!-- Dropdown Pilihan Rentang Interval Usia -->
                    <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm w-full sm:w-auto mt-2 sm:mt-0">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Rentang Usia:</label>
                        <select id="intervalPilihan" class="w-full sm:w-auto border-transparent focus:border-transparent focus:ring-0 bg-transparent text-sm font-extrabold text-indigo-700 py-1 pl-1 pr-6 cursor-pointer" onchange="olahDataUsia()">
                            <option value="2">Per 2 Tahun</option>
                            <option value="5">Per 5 Tahun</option>
                            <option value="10" selected>Per 10 Tahun</option>
                            <option value="15">Per 15 Tahun</option>
                            <option value="20">Per 20 Tahun</option>
                        </select>
                    </div>
                </div>

                <div id="wadahGrafikUsia" class="space-y-6">
                    <!-- Rendered by JS -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CARD: DEMOGRAFI WILAYAH (TOP 5 & PENCARIAN) -->
            <!-- ========================================== -->
            <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm mt-6 mb-10">
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center border-b border-slate-100 pb-4 mb-6 gap-4">

                    <div class="flex items-center gap-3">
                        <h3 class="text-[15px] font-extrabold text-slate-800 whitespace-nowrap">Demografi Wilayah Klien (Top 5)</h3>
                        <button type="button" onclick="bukaModalWilayah()" class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Lihat Detail &rarr;
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
                        <!-- Sakelar Tab Opsi (KTP vs Domisili) -->
                        <div class="flex bg-slate-100 p-1 rounded-lg w-full sm:w-auto">
                            <button id="btnTabKtp" onclick="switchWilayah('ktp')" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-md bg-white shadow-sm text-indigo-700 transition-all">Sesuai KTP</button>
                            <button id="btnTabDomisili" onclick="switchWilayah('domisili')" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-md text-slate-500 hover:text-slate-700 transition-all">Domisili Saat Ini</button>
                        </div>

                        <!-- Search Bar -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="searchWilayah" onkeyup="cariWilayah()" class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Cari Desa/Kel/Kec...">
                        </div>
                    </div>
                </div>

                <!-- Hasil Search Engine -->
                <div id="hasilSearchWilayah" class="hidden bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 mb-6 space-y-2 max-h-48 overflow-y-auto shadow-inner custom-select-scroll">
                    <!-- Javascript merender ke sini -->
                </div>

                <!-- WADAH TOP 5 KTP -->
                <div id="wadahTopKtp" class="grid grid-cols-1 md:grid-cols-3 gap-8 transition-opacity duration-300">
                    @foreach(['Kecamatan' => $dataKtp['topKecamatan'], 'Desa' => $dataKtp['topDesa'], 'Kelurahan' => $dataKtp['topKelurahan']] as $tipe => $topData)
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Top 5 {{ $tipe }}</h4>
                        <div class="space-y-4">
                            @forelse($topData as $nama => $jumlah)
                                @php
                                    $persen = round(($jumlah / max(1, $dataKtp['total'])) * 100, 1);
                                    $warnaTema = $tipe == 'Kecamatan' ? 'bg-indigo-500 text-indigo-600' : ($tipe == 'Desa' ? 'bg-emerald-500 text-emerald-600' : 'bg-amber-500 text-amber-600');
                                    $bgBar = explode(' ', $warnaTema)[0];
                                    $textNum = explode(' ', $warnaTema)[1];
                                @endphp
                                <div class="relative pt-1">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-bold text-slate-700 truncate w-3/4">{{ $nama }}</span>
                                        <span class="text-[12px] font-bold {{ $textNum }}">{{ $jumlah }} <span class="text-[10px] text-slate-400 font-normal">({{ $persen }}%)</span></span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2"><div class="{{ $bgBar }} h-2 rounded-full" style="width: {{ $persen }}%"></div></div>
                                </div>
                            @empty
                                <p class="text-[13px] text-slate-400 italic">Data kosong</p>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- WADAH TOP 5 DOMISILI -->
                <div id="wadahTopDomisili" class="hidden grid-cols-1 md:grid-cols-3 gap-8 transition-opacity duration-300">
                    @foreach(['Kecamatan' => $dataDomisili['topKecamatan'], 'Desa' => $dataDomisili['topDesa'], 'Kelurahan' => $dataDomisili['topKelurahan']] as $tipe => $topData)
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Top 5 {{ $tipe }}</h4>
                        <div class="space-y-4">
                            @forelse($topData as $nama => $jumlah)
                                @php
                                    $persen = round(($jumlah / max(1, $dataDomisili['total'])) * 100, 1);
                                    $warnaTema = $tipe == 'Kecamatan' ? 'bg-indigo-500 text-indigo-600' : ($tipe == 'Desa' ? 'bg-emerald-500 text-emerald-600' : 'bg-amber-500 text-amber-600');
                                    $bgBar = explode(' ', $warnaTema)[0];
                                    $textNum = explode(' ', $warnaTema)[1];
                                @endphp
                                <div class="relative pt-1">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-bold text-slate-700 truncate w-3/4">{{ $nama }}</span>
                                        <span class="text-[12px] font-bold {{ $textNum }}">{{ $jumlah }} <span class="text-[10px] text-slate-400 font-normal">({{ $persen }}%)</span></span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2"><div class="{{ $bgBar }} h-2 rounded-full" style="width: {{ $persen }}%"></div></div>
                                </div>
                            @empty
                                <p class="text-[13px] text-slate-400 italic">Data kosong</p>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CARD: DEMOGRAFI PENDIDIKAN                 -->
            <!-- ========================================== -->
            <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm mt-6 mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4 mt-1">
                    <h3 class="text-[15px] font-extrabold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422M12 14v7m0-7l-6.16-3.422"></path></svg>
                        Demografi Tingkat Pendidikan Klien
                    </h3>
                    <button type="button" onclick="openModal('modalDetailPendidikan')" class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-lg transition-all shadow-sm">
                        Lihat Detail &rarr;
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <!-- List Grafik Progres Bar (Kiri) -->
                    <div class="lg:col-span-2 space-y-5">
                        @forelse($demografiPendidikan as $index => $item)
                            @php
                                $persen = $totalPendidikan > 0 ? round(($item->total / $totalPendidikan) * 100, 1) : 0;
                                $warnaArray = ['bg-blue-500', 'bg-indigo-500', 'bg-cyan-500', 'bg-emerald-500', 'bg-sky-500'];
                                $warnaBg = $warnaArray[$index % count($warnaArray)];
                                $warnaTeks = str_replace('bg-', 'text-', $warnaBg);
                            @endphp
                            <div class="relative pt-1">
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-sm font-bold text-slate-700 uppercase tracking-wide">{{ $item->nama }}</span>
                                    <span class="text-[13px] font-extrabold {{ $warnaTeks }}">{{ $item->total }} Klien <span class="text-[11px] text-slate-400 font-medium ml-1">({{ $persen }}%)</span></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5">
                                    <div class="{{ $warnaBg }} h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $persen }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 bg-slate-50 border border-slate-100 rounded-xl">
                                <p class="text-sm text-slate-400 font-medium italic">Data demografi pendidikan klien belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Kotak Insight Singkat (Kanan) -->
                    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6 flex flex-col justify-center h-full shadow-inner">
                        <div class="mb-4">
                            <span class="text-[10px] font-extrabold text-blue-500 uppercase tracking-widest bg-white shadow-sm border border-blue-100 px-3 py-1.5 rounded-lg">Total Data Masuk</span>
                        </div>
                        <div class="text-4xl font-black text-slate-800 mb-2">{{ $totalPendidikan }} <span class="text-lg text-slate-500 font-bold">Klien</span></div>
                        <p class="text-xs font-semibold text-slate-500 leading-relaxed mt-2 pt-4 border-t border-blue-100/60">
                            Distribusi latar belakang ini dapat membantu Tim Hukum & Medis dalam merancang penyampaian komunikasi serta pendekatan rehabilitasi yang paling sesuai.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- KUMPULAN MODAL POPUP (MENGGUNAKAN STANDAR TAILWIND ANTI-JEBOL) -->
    <!-- ============================================================== -->

    <!-- MODAL 1: DETAIL GENDER KLIEN -->
    <div id="modalDetailGender" class="fixed inset-0 z-50 hidden bg-slate-900/70 backdrop-blur-sm p-4 sm:p-6 md:p-10 items-center justify-center">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[85vh]">

            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Klien Berdasarkan Gender</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Klik pada nama klien untuk melihat rincian data lengkap.</p>
                </div>
                <button type="button" onclick="closeModal('modalDetailGender')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Kolom Laki-Laki -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-blue-100 mb-4">
                            <span class="text-sm font-black text-blue-700 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                Laki-Laki
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-blue-100 text-blue-800">
                                {{ $listKlienGender->get('L', collect())->count() }} Klien
                            </span>
                        </div>
                        <div class="space-y-2 max-h-[50vh] overflow-y-auto custom-select-scroll pr-1">
                            @forelse($listKlienGender->get('L', []) as $klien)
                                <a href="{{ route('asesmen.show', $klien->id) }}" class="block p-3 bg-slate-50 hover:bg-blue-50 rounded-xl border border-slate-100 hover:border-blue-200 transition-colors group">
                                    <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700">{{ $klien->nama_lengkap }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5">{{ $klien->no_register ?? 'No Reg: -' }}</div>
                                </a>
                            @empty
                                <div class="text-xs text-slate-400 italic text-center p-4 bg-slate-50 rounded-xl border border-slate-100">Belum ada data klien Laki-laki.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Kolom Perempuan -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-rose-100 mb-4">
                            <span class="text-sm font-black text-rose-600 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                Perempuan
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-700">
                                {{ $listKlienGender->get('P', collect())->count() }} Klien
                            </span>
                        </div>
                        <div class="space-y-2 max-h-[50vh] overflow-y-auto custom-select-scroll pr-1">
                            @forelse($listKlienGender->get('P', []) as $klien)
                                <a href="{{ route('asesmen.show', $klien->id) }}" class="block p-3 bg-slate-50 hover:bg-rose-50 rounded-xl border border-slate-100 hover:border-rose-200 transition-colors group">
                                    <div class="font-bold text-slate-800 text-sm group-hover:text-rose-700">{{ $klien->nama_lengkap }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5">{{ $klien->no_register ?? 'No Reg: -' }}</div>
                                </a>
                            @empty
                                <div class="text-xs text-slate-400 italic text-center p-4 bg-slate-50 rounded-xl border border-slate-100">Belum ada data klien Perempuan.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailGender')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: DETAIL PENDIDIKAN KLIEN -->
    <!-- ============================================================== -->
    <div id="modalDetailPendidikan" class="fixed inset-0 z-50 hidden bg-slate-900/70 backdrop-blur-sm p-4 sm:p-6 md:p-10 items-center justify-center">
        <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[85vh]">

            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Klien Berdasarkan Tingkat Pendidikan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Klik pada nama klien untuk melihat rincian data lengkap.</p>
                </div>
                <button type="button" onclick="closeModal('modalDetailPendidikan')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 space-y-6">
                @forelse($listKlienPendidikan as $namaPendidikan => $kliens)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-indigo-100 mb-4">
                            <span class="text-sm font-black text-indigo-700 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                {{ $namaPendidikan }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-indigo-100 text-indigo-800">
                                {{ $kliens->count() }} Klien
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($kliens as $klien)
                                <a href="{{ route('asesmen.show', $klien->id) }}" class="block p-3 bg-slate-50 hover:bg-indigo-50 rounded-xl border border-slate-100 hover:border-indigo-200 transition-colors group">
                                    <div class="font-bold text-slate-800 text-sm group-hover:text-indigo-700 truncate">{{ $klien->nama_lengkap }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien->no_register ?? 'No Reg: -' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-400 italic text-center p-8 bg-white rounded-2xl border border-slate-200">
                        Belum ada data klien yang memiliki rincian pendidikan.
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailPendidikan')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 3: DETAIL USIA KLIEN (DINAMIS DENGAN JS)                 -->
    <!-- ============================================================== -->
    <div id="modalDetailUsia" class="fixed inset-0 z-50 hidden bg-slate-900/70 backdrop-blur-sm p-4 sm:p-6 md:p-10 items-center justify-center">
        <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[85vh]">

            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Klien Berdasarkan Rentang Usia</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kelompok usia mengikuti pilihan interval yang sedang aktif.</p>
                </div>
                <button type="button" onclick="closeModal('modalDetailUsia')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div id="kontenModalUsia" class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 space-y-6">
                <!-- Javascript merender daftar klien berdasarkan usia di sini -->
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailUsia')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 4: DETAIL WILAYAH TOP 5 (KTP & DOMISILI)                 -->
    <!-- ============================================================== -->
    <div id="modalDetailWilayah" class="fixed inset-0 z-50 hidden bg-slate-900/70 backdrop-blur-sm p-4 sm:p-6 md:p-10 items-center justify-center">
        <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[85vh]">

            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-white">
                <div>
                    <h3 id="modalWilayahTitle" class="text-lg font-black text-slate-800 tracking-tight">Daftar Klien Wilayah Top 5 (Sesuai KTP)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Rincian data klien pada 5 besar Kecamatan, Desa, dan Kelurahan.</p>
                </div>
                <button type="button" onclick="closeModal('modalDetailWilayah')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Konten Modal Wilayah KTP -->
            <div id="kontenModalWilayahKtp" class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 space-y-8">
                <!-- Top 5 Kecamatan KTP -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-indigo-700 uppercase tracking-widest bg-indigo-50 border border-indigo-100 px-3 py-2 rounded-xl">
                        Top 5 Kecamatan (KTP)
                    </h4>
                    @forelse($dataKtp['topKecamatanKliens'] as $kec => $kliens)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                <span class="font-extrabold text-slate-800 text-sm">Kecamatan {{ $kec }}</span>
                                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 text-xs font-black rounded-full">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50 hover:bg-indigo-50 rounded-xl border border-slate-100 hover:border-indigo-200 transition-colors group">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-indigo-700 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white rounded-xl border border-slate-200">Data kecamatan kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Desa KTP -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-emerald-700 uppercase tracking-widest bg-emerald-50 border border-emerald-100 px-3 py-2 rounded-xl">
                        Top 5 Desa (KTP)
                    </h4>
                    @forelse($dataKtp['topDesaKliens'] as $desa => $kliens)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                <span class="font-extrabold text-slate-800 text-sm">Desa {{ $desa }}</span>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-black rounded-full">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl border border-slate-100 hover:border-emerald-200 transition-colors group">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-emerald-700 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white rounded-xl border border-slate-200">Data desa kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Kelurahan KTP -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-amber-700 uppercase tracking-widest bg-amber-50 border border-amber-100 px-3 py-2 rounded-xl">
                        Top 5 Kelurahan (KTP)
                    </h4>
                    @forelse($dataKtp['topKelurahanKliens'] as $kel => $kliens)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                <span class="font-extrabold text-slate-800 text-sm">Kelurahan {{ $kel }}</span>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-black rounded-full">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50 hover:bg-amber-50 rounded-xl border border-slate-100 hover:border-amber-200 transition-colors group">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-amber-700 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white rounded-xl border border-slate-200">Data kelurahan kosong.</div>
                    @endforelse
                </div>
            </div>

            <!-- Konten Modal Wilayah Domisili -->
            <div id="kontenModalWilayahDomisili" class="hidden p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 space-y-8">
                <!-- Top 5 Kecamatan Domisili -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-indigo-700 uppercase tracking-widest bg-indigo-50 border border-indigo-100 px-3 py-2 rounded-xl">
                        Top 5 Kecamatan (Domisili Saat Ini)
                    </h4>
                    @forelse($dataDomisili['topKecamatanKliens'] as $kec => $kliens)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                <span class="font-extrabold text-slate-800 text-sm">Kecamatan {{ $kec }}</span>
                                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 text-xs font-black rounded-full">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50 hover:bg-indigo-50 rounded-xl border border-slate-100 hover:border-indigo-200 transition-colors group">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-indigo-700 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white rounded-xl border border-slate-200">Data kecamatan kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Desa Domisili -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-emerald-700 uppercase tracking-widest bg-emerald-50 border border-emerald-100 px-3 py-2 rounded-xl">
                        Top 5 Desa (Domisili Saat Ini)
                    </h4>
                    @forelse($dataDomisili['topDesaKliens'] as $desa => $kliens)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                <span class="font-extrabold text-slate-800 text-sm">Desa {{ $desa }}</span>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-black rounded-full">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl border border-slate-100 hover:border-emerald-200 transition-colors group">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-emerald-700 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white rounded-xl border border-slate-200">Data desa kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Kelurahan Domisili -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-amber-700 uppercase tracking-widest bg-amber-50 border border-amber-100 px-3 py-2 rounded-xl">
                        Top 5 Kelurahan (Domisili Saat Ini)
                    </h4>
                    @forelse($dataDomisili['topKelurahanKliens'] as $kel => $kliens)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                <span class="font-extrabold text-slate-800 text-sm">Kelurahan {{ $kel }}</span>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-black rounded-full">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50 hover:bg-amber-50 rounded-xl border border-slate-100 hover:border-amber-200 transition-colors group">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-amber-700 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white rounded-xl border border-slate-200">Data kelurahan kosong.</div>
                    @endforelse
                </div>
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailWilayah')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SCRIPT RENDER GRAFIK & PENCARIAN -->
    <!-- ========================================== -->

    <!-- Script Manajemen Modal Universal -->
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        function bukaModalWilayah() {
            const title = document.getElementById('modalWilayahTitle');
            const divKtp = document.getElementById('kontenModalWilayahKtp');
            const divDom = document.getElementById('kontenModalWilayahDomisili');

            if (modeWilayahAktif === 'ktp') {
                title.textContent = 'Daftar Klien Wilayah Top 5 (Sesuai KTP)';
                divKtp.classList.remove('hidden');
                divDom.classList.add('hidden');
            } else {
                title.textContent = 'Daftar Klien Wilayah Top 5 (Domisili Saat Ini)';
                divKtp.classList.add('hidden');
                divDom.classList.remove('hidden');
            }

            openModal('modalDetailWilayah');
        }

        // Menutup modal jika area gelap di luar modal diklik
        window.addEventListener('click', function(e) {
            if (e.target.id === 'modalDetailGender') closeModal('modalDetailGender');
            if (e.target.id === 'modalDetailPendidikan') closeModal('modalDetailPendidikan');
            if (e.target.id === 'modalDetailUsia') closeModal('modalDetailUsia');
            if (e.target.id === 'modalDetailWilayah') closeModal('modalDetailWilayah');
        });
    </script>

    <!-- Script Grafik Demografi Usia -->
    <script>
        const dataUsiaMentah = @json($dataKlienUsia ?? []);
        let grupUsiaTerbaru = {};

        function olahDataUsia() {
            const interval = parseInt(document.getElementById('intervalPilihan').value);
            grupUsiaTerbaru = {};

            if (dataUsiaMentah.length === 0) {
                document.getElementById('wadahGrafikUsia').innerHTML = '<p class="text-sm font-semibold text-slate-400 italic">Belum ada data usia klien yang dapat dihitung.</p>';
                document.getElementById('kontenModalUsia').innerHTML = '<div class="text-sm text-slate-400 italic text-center p-8 bg-white border border-slate-200 rounded-2xl">Belum ada data klien yang dapat ditampilkan.</div>';
                return;
            }

            dataUsiaMentah.forEach(klien => {
                let start, end, label;
                let usia = klien.usia;

                if (usia < 1) {
                    start = 0; end = 0; label = "Di bawah 1 Tahun";
                } else {
                    start = Math.floor((usia - 1) / interval) * interval + 1;
                    end = start + interval - 1;
                    label = `${start} - ${end} Tahun`;
                }

                if (!grupUsiaTerbaru[label]) {
                    grupUsiaTerbaru[label] = { hitung: 0, sortKey: start, kliens: [] };
                }
                grupUsiaTerbaru[label].hitung++;
                grupUsiaTerbaru[label].kliens.push(klien);
            });

            renderGrafikUsia();
            renderModalUsia();
        }

        function renderGrafikUsia() {
            const wadah = document.getElementById('wadahGrafikUsia');
            wadah.innerHTML = '';

            let kunciUrut = Object.keys(grupUsiaTerbaru).sort((a, b) => grupUsiaTerbaru[a].sortKey - grupUsiaTerbaru[b].sortKey);
            const totalData = dataUsiaMentah.length;
            const warnaWarni = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-cyan-500', 'bg-purple-500', 'bg-blue-500'];

            kunciUrut.forEach((label, index) => {
                let jumlah = grupUsiaTerbaru[label].hitung;
                let calcPersen = (jumlah / totalData) * 100;
                let persentase = calcPersen % 1 === 0 ? calcPersen : calcPersen.toFixed(1);
                let warna = warnaWarni[index % warnaWarni.length];

                let htmlBar = `
                    <div class="relative pt-1">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-extrabold text-slate-700">
                                ${label}
                                <span class="text-[11px] font-bold text-slate-400 ml-1 tracking-wider">(${persentase}%)</span>
                            </span>
                            <span class="text-[13px] font-bold text-slate-800">${jumlah} Klien</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 shadow-inner overflow-hidden">
                            <div class="${warna} h-3 rounded-full transition-all duration-1000 ease-out" style="width: 0%;" data-target-width="${persentase}%"></div>
                        </div>
                    </div>
                `;
                wadah.insertAdjacentHTML('beforeend', htmlBar);
            });

            setTimeout(() => {
                const bars = wadah.querySelectorAll('[data-target-width]');
                bars.forEach(bar => {
                    bar.style.width = bar.getAttribute('data-target-width');
                });
            }, 50);
        }

        function renderModalUsia() {
            const modalWadah = document.getElementById('kontenModalUsia');
            modalWadah.innerHTML = '';
            let kunciUrut = Object.keys(grupUsiaTerbaru).sort((a, b) => grupUsiaTerbaru[a].sortKey - grupUsiaTerbaru[b].sortKey);

            kunciUrut.forEach((label) => {
                let grup = grupUsiaTerbaru[label];
                let cardsHtml = '';

                grup.kliens.sort((a, b) => a.nama_lengkap.localeCompare(b.nama_lengkap));

                grup.kliens.forEach(klien => {
                    let noReg = klien.no_register ? klien.no_register : 'No Reg: -';
                    cardsHtml += `
                        <a href="/asesmen/${klien.id}" class="block p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl border border-slate-100 hover:border-emerald-200 transition-colors group">
                            <div class="font-bold text-slate-800 text-sm group-hover:text-emerald-700 truncate">${klien.nama_lengkap}</div>
                            <div class="flex justify-between items-center mt-1">
                                <div class="text-[11px] font-semibold text-slate-500 truncate w-3/4">${noReg}</div>
                                <div class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded shadow-sm">${klien.usia} Thn</div>
                            </div>
                        </a>
                    `;
                });

                let blockHtml = `
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-emerald-100 mb-4">
                            <span class="text-sm font-black text-emerald-700 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Usia ${label}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 shadow-sm border border-emerald-200">
                                ${grup.hitung} Klien
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            ${cardsHtml}
                        </div>
                    </div>
                `;
                modalWadah.insertAdjacentHTML('beforeend', blockHtml);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if(document.getElementById('intervalPilihan')) {
                olahDataUsia();
            }
        });

        // ==========================================
        // SCRIPT PENCARIAN WILAYAH CERDAS
        // ==========================================
        const dataJsonKtp = @json($dataKtp['full'] ?? []);
        const dataJsonDomisili = @json($dataDomisili['full'] ?? []);
        let modeWilayahAktif = 'ktp';

        function switchWilayah(mode) {
            modeWilayahAktif = mode;

            if(mode === 'ktp') {
                document.getElementById('btnTabKtp').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-md bg-white shadow-sm text-indigo-700 transition-all";
                document.getElementById('btnTabDomisili').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-md text-slate-500 hover:text-slate-700 transition-all";

                document.getElementById('wadahTopKtp').classList.replace('hidden', 'grid');
                document.getElementById('wadahTopDomisili').classList.replace('grid', 'hidden');
            } else {
                document.getElementById('btnTabDomisili').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-md bg-white shadow-sm text-indigo-700 transition-all";
                document.getElementById('btnTabKtp').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-md text-slate-500 hover:text-slate-700 transition-all";

                document.getElementById('wadahTopDomisili').classList.replace('hidden', 'grid');
                document.getElementById('wadahTopKtp').classList.replace('grid', 'hidden');
            }

            cariWilayah();
        }

        function cariWilayah() {
            let kataKunci = document.getElementById('searchWilayah').value.toLowerCase();
            let divHasil = document.getElementById('hasilSearchWilayah');

            let idTopWadahAktif = modeWilayahAktif === 'ktp' ? 'wadahTopKtp' : 'wadahTopDomisili';
            let divTop = document.getElementById(idTopWadahAktif);
            let currentData = modeWilayahAktif === 'ktp' ? dataJsonKtp : dataJsonDomisili;

            if(kataKunci.trim() === '') {
                divHasil.classList.add('hidden');
                divTop.style.display = '';
                return;
            }

            divHasil.classList.remove('hidden');
            divTop.style.display = 'none';

            let htmlKeluaran = '';
            let totalDitemukan = 0;

            currentData.kecamatan.forEach(item => {
                if(item.nama.toLowerCase().includes(kataKunci)) {
                    htmlKeluaran += cetakBarHasil('Kec', item.nama, item.count, item.persen, 'text-indigo-700', 'bg-indigo-200');
                    totalDitemukan++;
                }
            });
            currentData.desa.forEach(item => {
                if(item.nama.toLowerCase().includes(kataKunci)) {
                    htmlKeluaran += cetakBarHasil('Desa', item.nama, item.count, item.persen, 'text-emerald-700', 'bg-emerald-200');
                    totalDitemukan++;
                }
            });
            currentData.kelurahan.forEach(item => {
                if(item.nama.toLowerCase().includes(kataKunci)) {
                    htmlKeluaran += cetakBarHasil('Kel', item.nama, item.count, item.persen, 'text-amber-700', 'bg-amber-200');
                    totalDitemukan++;
                }
            });

            if(totalDitemukan === 0) {
                divHasil.innerHTML = `<div class="text-sm text-rose-500 font-semibold italic py-2 text-center">Wilayah "${document.getElementById('searchWilayah').value}" tidak ditemukan dalam data ${modeWilayahAktif.toUpperCase()}.</div>`;
            } else {
                divHasil.innerHTML = `<div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Ditemukan ${totalDitemukan} Hasil (Data ${modeWilayahAktif.toUpperCase()}):</div>` + htmlKeluaran;
            }
        }

        function cetakBarHasil(tipe, nama, count, persen, textClass, bgClass) {
            return `
                <div class="flex justify-between items-center py-2 border-b border-indigo-100/50 last:border-0 hover:bg-white/50 px-2 rounded transition-colors">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold ${bgClass} ${textClass} uppercase w-12 text-center">${tipe}</span>
                        <span class="text-sm font-bold text-slate-700">${nama}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-slate-800">${count} Klien</span>
                        <span class="text-xs font-bold text-slate-500 ml-1 w-10 inline-block text-right">(${persen}%)</span>
                    </div>
                </div>
            `;
        }
    </script>
</x-app-layout>
