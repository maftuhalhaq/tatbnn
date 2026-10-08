<x-app-layout>
    @php
        // [FIX BACKEND] Mengambil data detail klien untuk Modal Top 5 Pekerjaan
        // Menggunakan join ke relasi karena nama pekerjaan disimpan di tabel m_pekerjaan.
        if (!isset($topPekerjaanKliens)) {
            $topPekerjaanKliens = [];
            if(isset($topPekerjaan)) {
                foreach ($topPekerjaan as $pekerjaan) {
                    $namaPek = $pekerjaan->pekerjaan_input;
                    if (!empty(trim($namaPek))) {
                        $pekerjaanModel = \App\Models\Pekerjaan::where('nama_pekerjaan', $namaPek)->first();

                        if ($pekerjaanModel) {
                            $topPekerjaanKliens[$namaPek] = \App\Models\Asesmen::where('pekerjaan_id', $pekerjaanModel->id)
                                ->select('id', 'nama_lengkap', 'no_register')
                                ->get()
                                ->toArray();
                        } else {
                            $topPekerjaanKliens[$namaPek] = [];
                        }
                    }
                }
            }
        }

        // [FITUR BARU BACKEND] Mengambil data detail klien untuk Modal Statistik Rekomendasi TAT
        if (!isset($rekomendasiKliens)) {
            $rekomendasiKliens = [
                'Rawat Jalan' => [],
                'Rawat Inap' => [],
                'Rehab di Lapas / Rutan' => [],
                'Tidak Rehab (Proses Hukum)' => [],
                'Belum Ada Keterangan' => [],
            ];

            $tahunIni = $tahunIni ?? date('Y');
            $asesmenTahunIni = \App\Models\Asesmen::with('rekomendasi')
                ->whereYear('created_at', $tahunIni)
                ->select('id', 'nama_lengkap', 'no_register', 'rekomendasi_id') // FIX ERROR: Hapus 'rekomendasi_input' karena tidak ada di tabel asesmens
                ->get();

            foreach ($asesmenTahunIni as $klien) {
                // Gunakan relasi rekomendasi yang sah
                $rawTempat = $klien->rekomendasi->tempat_rehabilitasi ?? '';
                $tempatBersih = trim($rawTempat);

                $kategori = 'Belum Ada Keterangan';
                if (str_starts_with($tempatBersih, 'Rawat Jalan')) {
                    $kategori = 'Rawat Jalan';
                } elseif (str_starts_with($tempatBersih, 'Rawat Inap')) {
                    $kategori = 'Rawat Inap';
                } elseif (str_starts_with($tempatBersih, 'Rehab di Lapas / Rutan')) {
                    $kategori = 'Rehab di Lapas / Rutan';
                } elseif (str_starts_with($tempatBersih, 'Tidak Rehab (Proses Hukum)')) {
                    $kategori = 'Tidak Rehab (Proses Hukum)';
                }

                $rekomendasiKliens[$kategori][] = $klien->toArray();
            }
        }
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        /* Custom Scrollbar */
        .custom-select-scroll::-webkit-scrollbar { width: 6px; }
        .custom-select-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-select-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @media (prefers-color-scheme: dark) {
            .custom-select-scroll::-webkit-scrollbar-thumb { background: #334155; }
            .custom-select-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
        }
    </style>

    <!-- BACKGROUND BASE & BLOBS -->
    <div class="fixed inset-0 z-0 bg-[#F8FAFC] dark:bg-[#0B1120] transition-colors duration-500 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[45vw] h-[45vw] min-w-[500px] min-h-[500px] bg-blue-200/70 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob transition-colors duration-700"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[50vw] h-[50vw] min-w-[500px] min-h-[500px] bg-indigo-200/60 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-2000 transition-colors duration-700"></div>
        <div class="absolute top-[15%] right-[20%] w-[35vw] h-[35vw] min-w-[400px] min-h-[400px] bg-cyan-200/60 dark:bg-cyan-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[90px] animate-blob animation-delay-4000 transition-colors duration-700"></div>
    </div>

    <!-- KONTEN UTAMA DASHBOARD -->
    <div class="relative z-10 py-8 sm:py-10 min-h-screen pb-16">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- 1. HEADER HERO DASHBOARD -->
            <div class="bg-gradient-to-r from-[#3B82F6] to-[#4F46E5] dark:from-[#2563EB] dark:to-[#3730A3] rounded-[2rem] p-8 sm:p-10 shadow-[0_20px_50px_-12px_rgba(59,130,246,0.3)] dark:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.5)] text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden transition-colors duration-500">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 right-32 -mb-10 w-32 h-32 bg-blue-300 opacity-20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-white/10 dark:bg-black/20 border border-white/20 dark:border-white/10 text-blue-50 text-[10px] font-extrabold mb-4 tracking-widest uppercase backdrop-blur-sm shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse mr-2"></span>
                        Command Center TAT
                    </div>
                    <h2 class="font-black text-3xl sm:text-4xl tracking-tight mb-2 leading-tight">Dashboard Analisis BNN</h2>
                    <p class="text-blue-100 dark:text-blue-200 text-sm sm:text-base max-w-2xl leading-relaxed font-medium">
                        Selamat datang, <strong>{{ explode(' ', Auth::user()->name)[0] }}</strong>. Berikut adalah ringkasan performa Tim Asesmen Terpadu dan tren peredaran narkotika untuk membantu pengambilan keputusan strategis.
                    </p>
                </div>
            </div>

            <!-- 2. KPI CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Klien -->
                <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 rounded-[2rem] border border-white/50 dark:border-slate-700/50 shadow-xl relative overflow-hidden group hover:border-[#3B82F6] dark:hover:border-blue-500/50 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 dark:bg-blue-500/10 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Klien Terdaftar</p>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalKlien) }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-500/20 text-[#3B82F6] dark:text-blue-400 rounded-2xl border border-blue-100 dark:border-blue-500/30">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Kasus Menunggu TAT -->
                <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 rounded-[2rem] border border-white/50 dark:border-slate-700/50 shadow-xl relative overflow-hidden group hover:border-[#FFBD2E] dark:hover:border-amber-500/50 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 dark:bg-amber-500/10 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Menunggu Sidang TAT</p>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($menungguTAT) }}</h3>
                            <p class="text-[9px] text-amber-600 dark:text-[#FFBD2E] font-extrabold mt-1 bg-amber-50 dark:bg-amber-500/20 border border-amber-100 dark:border-amber-500/30 inline-block px-2 py-0.5 rounded uppercase tracking-wider">Butuh Tindakan</p>
                        </div>
                        <div class="p-3 bg-amber-50 dark:bg-amber-500/20 text-amber-500 dark:text-[#FFBD2E] rounded-2xl border border-amber-100 dark:border-amber-500/30">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Kasus Selesai -->
                <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 rounded-[2rem] border border-white/50 dark:border-slate-700/50 shadow-xl relative overflow-hidden group hover:border-[#27C93F] dark:hover:border-emerald-500/50 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 dark:bg-emerald-500/10 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Selesai Diasesmen</p>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($selesaiTAT) }}</h3>
                        </div>
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-500/20 text-emerald-500 dark:text-[#27C93F] rounded-2xl border border-emerald-100 dark:border-emerald-500/30">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Barang Bukti -->
                <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 rounded-[2rem] border border-white/50 dark:border-slate-700/50 shadow-xl relative overflow-hidden group hover:border-[#FF5F56] dark:hover:border-rose-500/50 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-50 dark:bg-rose-500/10 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Bukti Disita</p>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalBB, 1, ',', '.') }} <span class="text-base font-bold text-slate-400">Gram</span></h3>
                        </div>
                        <div class="p-3 bg-rose-50 dark:bg-rose-500/20 text-rose-500 dark:text-[#FF5F56] rounded-2xl border border-rose-100 dark:border-rose-500/30">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. TOP 5 PEKERJAAN & AKTIVITAS TERBARU -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Top 5 Pekerjaan -->
                <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-xl p-6 sm:p-8 relative overflow-hidden flex flex-col h-full transition-colors duration-500">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#FF5F56]"></div>
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/50 pb-5 mb-5 mt-1">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-rose-50 dark:bg-rose-500/10 text-[#FF5F56] border border-rose-100 dark:border-rose-500/20 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 dark:text-white text-lg tracking-tight">Top 5 Pekerjaan Klien</h3>
                                <p class="text-[12px] font-medium text-slate-500 dark:text-slate-400">Profil pekerjaan terbanyak pada data TAT</p>
                            </div>
                        </div>
                        <button type="button" onclick="openModal('modalDetailPekerjaan')" class="text-[9px] uppercase tracking-widest font-extrabold text-[#FF5F56] dark:text-rose-400 bg-rose-50 dark:bg-[#0F172A] border border-rose-100 dark:border-rose-500/20 hover:bg-[#FF5F56] hover:text-white dark:hover:bg-rose-500/20 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Lihat Detail &rarr;
                        </button>
                    </div>

                    <div class="flex-1 space-y-4">
                        @forelse($topPekerjaan as $index => $pekerjaan)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center justify-center w-7 h-7 rounded-full {{ $index == 0 ? 'bg-[#FF5F56] text-white shadow-md shadow-rose-500/30' : 'bg-slate-100 dark:bg-[#0F172A] text-slate-500 dark:text-slate-400 border border-transparent dark:border-slate-700/50 group-hover:bg-rose-50 dark:group-hover:bg-rose-500/10 group-hover:text-[#FF5F56] transition-colors' }} text-xs font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-sm group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $pekerjaan->pekerjaan_input }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-24 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden hidden sm:block">
                                        @php
                                            $highestTotal = $topPekerjaan->first()->total > 0 ? $topPekerjaan->first()->total : 1;
                                            $percentage = ($pekerjaan->total / $highestTotal) * 100;
                                        @endphp
                                        <div class="h-full bg-[#FF5F56] rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="font-black text-slate-800 dark:text-white text-sm w-12 text-right">{{ $pekerjaan->total }} <span class="text-[10px] text-slate-400 font-normal">Org</span></span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-slate-50 dark:bg-[#0F172A] rounded-full flex items-center justify-center mb-3 border-2 border-dashed border-slate-200 dark:border-slate-700">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium italic">Belum ada data pekerjaan klien yang tersimpan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Kolom Kanan: Kasus To-Do List / Terbaru -->
                <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-xl overflow-hidden flex flex-col h-full relative transition-colors duration-500">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6]"></div>
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-transparent flex justify-between items-start mt-1">
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-lg tracking-tight">Aktivitas Asesmen Terbaru</h3>
                            @if($tanggalTerakhir)
                                <p class="text-[12px] text-slate-500 dark:text-slate-400 mt-1">
                                    Menampilkan <span class="font-bold text-[#3B82F6]">{{ $aktivitasTerbaru->count() }} klien</span> dari batch
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalTerakhir)->translatedFormat('d F Y') }}</span>.
                                </p>
                            @else
                                <p class="text-[12px] text-slate-500 dark:text-slate-400 mt-1">Belum ada data klien terdaftar.</p>
                            @endif
                        </div>
                        <a href="{{ route('asesmen.index') }}" class="text-[10px] uppercase tracking-widest font-extrabold text-[#3B82F6] dark:text-blue-400 bg-white dark:bg-[#0F172A] border border-blue-100 dark:border-blue-500/20 px-4 py-2 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-500/10 shadow-sm transition-colors whitespace-nowrap">Lihat Semua &rarr;</a>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 max-h-[350px] space-y-3 custom-select-scroll">
                        @forelse($aktivitasTerbaru as $item)
                            <div class="flex items-center justify-between p-4 bg-white dark:bg-[#0F172A]/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 hover:border-blue-200 dark:hover:border-blue-500/30 hover:shadow-md dark:hover:shadow-none transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-[#3B82F6] dark:text-blue-400 font-bold flex items-center justify-center border border-blue-100 dark:border-blue-500/20 shrink-0 text-lg shadow-sm group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($item->nama_lengkap ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#3B82F6] dark:group-hover:text-blue-400 transition-colors">{{ $item->nama_lengkap }}</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">No. Reg: <span class="font-semibold">{{ $item->no_register ?? '-' }}</span></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    @if($item->pelaksanaan === 'YA')
                                        <span class="hidden sm:inline-flex px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-[#27C93F] dark:text-emerald-400 text-[9px] font-black rounded uppercase tracking-widest border border-emerald-200/50 dark:border-emerald-500/20">Selesai TAT</span>
                                    @else
                                        <span class="hidden sm:inline-flex px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 text-[#FFBD2E] dark:text-amber-400 text-[9px] font-black rounded uppercase tracking-widest border border-amber-200/50 dark:border-amber-500/20">Menunggu TAT</span>
                                    @endif

                                    <a href="{{ route('asesmen.show', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-50 dark:bg-[#1E293B] border border-slate-200 dark:border-slate-500 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-white dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white transition-all shadow-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500 dark:text-slate-400 text-sm font-medium">
                                Belum ada aktivitas yang dapat ditampilkan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 4. GENDER & DEMOGRAFI USIA KLIEN -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Demografi Gender -->
                <div class="xl:col-span-1 bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] relative overflow-hidden h-full flex flex-col transition-colors duration-500">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6]"></div>
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 dark:border-slate-700/50 pb-4 mt-2">
                        <h3 class="font-black text-slate-800 dark:text-white text-[15px] tracking-tight">Demografi Gender Klien</h3>
                        <button type="button" onclick="openModal('modalDetailGender')" class="text-[9px] uppercase tracking-widest font-extrabold text-[#3B82F6] dark:text-blue-400 bg-blue-50 dark:bg-[#0F172A] border border-blue-100 dark:border-blue-500/20 hover:bg-[#3B82F6] hover:text-white dark:hover:bg-blue-500/20 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Lihat Detail &rarr;
                        </button>
                    </div>

                    <div class="space-y-5 flex-1 justify-start flex flex-col mt-2">
                        @foreach($demografiGender as $gender)
                            <div>
                                <div class="flex justify-between text-sm mb-1.5 font-bold text-slate-700 dark:text-slate-300">
                                    <span>{{ $gender->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                                    <span>{{ $gender->total }} Orang</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3">
                                    <div class="bg-{{ $gender->jenis_kelamin == 'L' ? 'blue' : 'rose' }}-500 h-3 rounded-full shadow-sm" style="width: {{ ($totalKlien > 0) ? ($gender->total / $totalKlien) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Kolom Kanan: Demografi Usia Klien -->
                <div class="xl:col-span-2 bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] h-full flex flex-col relative overflow-hidden transition-colors duration-500">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-slate-700/50 pb-4 mb-6 gap-4 mt-1">
                        <div class="flex items-center gap-3">
                            <h3 class="text-[15px] font-black text-slate-800 dark:text-white tracking-tight">Demografi Usia Klien</h3>
                            <button type="button" onclick="openModal('modalDetailUsia')" class="text-[9px] uppercase tracking-widest font-extrabold text-[#3B82F6] dark:text-blue-400 bg-blue-50 dark:bg-[#0F172A] border border-blue-100 dark:border-blue-500/20 hover:bg-[#3B82F6] hover:text-white dark:hover:bg-blue-500/20 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                Lihat Detail &rarr;
                            </button>
                        </div>

                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-[#0F172A] px-2 py-1.5 rounded-[1rem] border border-slate-200 dark:border-slate-700/50 shadow-sm w-full sm:w-auto mt-2 sm:mt-0">
                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest pl-2 whitespace-nowrap">Rentang Usia:</label>

                            <div x-data="{ open: false, val: '10' }" class="relative">
                                <select id="intervalPilihan" class="hidden">
                                    <option value="2">Per 2 Tahun</option>
                                    <option value="5">Per 5 Tahun</option>
                                    <option value="10" selected>Per 10 Tahun</option>
                                    <option value="15">Per 15 Tahun</option>
                                    <option value="20">Per 20 Tahun</option>
                                </select>
                                <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between gap-2 px-3 py-1 bg-white dark:bg-[#1E293B] rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 text-xs font-black text-[#3B82F6] dark:text-blue-400 transition-colors focus:outline-none min-w-[120px]">
                                    <span x-text="'Per ' + val + ' Tahun'">Per 10 Tahun</span>
                                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" style="display: none;"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                     class="absolute z-50 top-full right-0 mt-2 w-36 rounded-2xl bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] p-1.5">
                                    <template x-for="item in ['2', '5', '10', '15', '20']">
                                        <button type="button"
                                                @click="val = item; document.getElementById('intervalPilihan').value = item; olahDataUsia(); open = false;"
                                                :class="val == item ? 'bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A]'"
                                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all mt-0.5">
                                            <span x-text="'Per ' + item + ' Tahun'"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="wadahGrafikUsia" class="space-y-6 flex-1 flex flex-col justify-start mt-4"></div>
                </div>
            </div>

            <!-- 5. KARTU DEMOGRAFI WILAYAH (TOP 5 & PENCARIAN) -->
            <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] relative overflow-hidden transition-colors duration-500">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-[#27C93F]"></div>
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center border-b border-slate-100 dark:border-slate-700/50 pb-4 mb-6 mt-1 gap-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-[15px] font-black text-slate-800 dark:text-white whitespace-nowrap tracking-tight">Demografi Wilayah Klien (Top 5)</h3>
                        <button type="button" onclick="bukaModalWilayah()" class="text-[9px] uppercase tracking-widest font-extrabold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500/20 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Lihat Detail &rarr;
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                        <!-- FILTER BULAN & TAHUN WILAYAH -->
                        @php
                            $bulanList = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                            $currentBulan = request('bulan_wilayah');
                            $labelBulan = $currentBulan ? $bulanList[$currentBulan] : 'Semua Bulan';

                            $currentTahun = request('tahun_wilayah');
                            $labelTahun = $currentTahun ? $currentTahun : 'Semua Tahun';
                        @endphp
                        <form id="filterWilayahForm" action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">

                            <div class="flex items-center bg-white dark:bg-[#0F172A] rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm p-1.5">
                                <div class="pl-2 pr-1 text-slate-400 dark:text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>

                                <!-- Custom Dropdown Bulan -->
                                <div x-data="{ open: false }" class="relative">
                                    <select name="bulan_wilayah" id="bulan_select" class="hidden">
                                        <option value="">Semua Bulan</option>
                                        @foreach($bulanList as $num => $name)
                                            <option value="{{ $num }}" {{ request('bulan_wilayah') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between gap-2 px-3 py-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors focus:outline-none min-w-[110px]">
                                        <span>{{ $labelBulan }}</span>
                                        <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                         class="absolute z-50 top-full left-0 mt-2 w-40 rounded-2xl bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] p-1.5 max-h-60 overflow-y-auto custom-select-scroll" style="display: none;">

                                        <button type="button" @click="document.getElementById('bulan_select').value = ''; document.getElementById('filterWilayahForm').submit();"
                                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request('bulan_wilayah') == '' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">
                                            Semua Bulan
                                        </button>
                                        @foreach($bulanList as $num => $name)
                                            <button type="button" @click="document.getElementById('bulan_select').value = '{{ $num }}'; document.getElementById('filterWilayahForm').submit();"
                                                    class="mt-0.5 w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request('bulan_wilayah') == $num ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">
                                                {{ $name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="w-px h-5 bg-slate-200 dark:bg-slate-700 mx-1"></div>

                                <!-- Custom Dropdown Tahun -->
                                <div x-data="{ open: false }" class="relative">
                                    <select name="tahun_wilayah" id="tahun_select" class="hidden">
                                        <option value="">Semua Tahun</option>
                                        @foreach($tahunTersedia as $thn)
                                            <option value="{{ $thn }}" {{ request('tahun_wilayah') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between gap-2 px-3 py-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors focus:outline-none min-w-[110px]">
                                        <span>{{ $labelTahun }}</span>
                                        <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                         class="absolute z-50 top-full right-0 mt-2 w-36 rounded-2xl bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] p-1.5 max-h-60 overflow-y-auto custom-select-scroll" style="display: none;">

                                        <button type="button" @click="document.getElementById('tahun_select').value = ''; document.getElementById('filterWilayahForm').submit();"
                                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request('tahun_wilayah') == '' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">
                                            Semua Tahun
                                        </button>
                                        @foreach($tahunTersedia as $thn)
                                            <button type="button" @click="document.getElementById('tahun_select').value = '{{ $thn }}'; document.getElementById('filterWilayahForm').submit();"
                                                    class="mt-0.5 w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request('tahun_wilayah') == $thn ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white' }}">
                                                {{ $thn }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if(request('bulan_wilayah') || request('tahun_wilayah'))
                                <a href="{{ url()->current() }}" class="p-2 text-rose-500 hover:text-rose-600 bg-white hover:bg-rose-50 dark:bg-[#0F172A] dark:hover:bg-rose-500/10 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm transition-colors" title="Reset Filter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </form>

                        <div class="flex bg-slate-100 dark:bg-[#0F172A] p-1 rounded-xl w-full sm:w-auto shadow-inner border border-transparent dark:border-slate-700/50">
                            <button id="btnTabKtp" onclick="switchWilayah('ktp')" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg bg-white dark:bg-[#1E293B] shadow-sm text-indigo-700 dark:text-indigo-400 transition-all border border-transparent dark:border-slate-700/50">Sesuai KTP</button>
                            <button id="btnTabDomisili" onclick="switchWilayah('domisili')" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-all border border-transparent">Domisili</button>
                        </div>
                        <div class="relative w-full sm:w-56">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="searchWilayah" onkeyup="cariWilayah()" class="block w-full pl-9 pr-3 py-2 border border-slate-200 dark:border-slate-700/50 rounded-xl bg-slate-50 dark:bg-[#0F172A] text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6] transition-all placeholder:text-slate-400" placeholder="Cari Desa/Kel/Kec...">
                        </div>
                    </div>
                </div>

                <div id="hasilSearchWilayah" class="hidden bg-indigo-50/50 dark:bg-[#0F172A]/50 border border-indigo-100 dark:border-slate-700/50 rounded-2xl p-4 mb-6 space-y-2 max-h-48 overflow-y-auto shadow-inner custom-select-scroll"></div>

                <div id="wadahTopKtp" class="grid grid-cols-1 md:grid-cols-3 gap-8 transition-opacity duration-300">
                    @foreach(['Kecamatan' => $dataKtp['topKecamatan'], 'Desa' => $dataKtp['topDesa'], 'Kelurahan' => $dataKtp['topKelurahan']] as $tipe => $topData)
                    <div>
                        <h4 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Top 5 {{ $tipe }}</h4>
                        <div class="space-y-4">
                            @forelse($topData as $nama => $jumlah)
                                @php
                                    $persen = round(($jumlah / max(1, $dataKtp['total'])) * 100, 1);
                                    $warnaTema = $tipe == 'Kecamatan' ? 'bg-indigo-500 text-indigo-600 dark:text-indigo-400' : ($tipe == 'Desa' ? 'bg-emerald-500 text-emerald-600 dark:text-emerald-400' : 'bg-[#FFBD2E] text-amber-600 dark:text-[#FFBD2E]');
                                    $parts = explode(' ', $warnaTema);
                                    $bgBar = $parts[0];
                                    unset($parts[0]);
                                    $textClass = implode(' ', $parts);
                                @endphp
                                <div class="relative pt-1">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 truncate w-3/4">{{ $nama }}</span>
                                        <span class="text-[12px] font-bold {{ $textClass }}">{{ $jumlah }} <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">({{ $persen }}%)</span></span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2"><div class="{{ $bgBar }} h-2 rounded-full" style="width: {{ $persen }}%"></div></div>
                                </div>
                            @empty
                                <p class="text-[13px] text-slate-400 dark:text-slate-500 italic">Data kosong</p>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="wadahTopDomisili" class="hidden grid-cols-1 md:grid-cols-3 gap-8 transition-opacity duration-300">
                    @foreach(['Kecamatan' => $dataDomisili['topKecamatan'], 'Desa' => $dataDomisili['topDesa'], 'Kelurahan' => $dataDomisili['topKelurahan']] as $tipe => $topData)
                    <div>
                        <h4 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Top 5 {{ $tipe }}</h4>
                        <div class="space-y-4">
                            @forelse($topData as $nama => $jumlah)
                                @php
                                    $persen = round(($jumlah / max(1, $dataDomisili['total'])) * 100, 1);
                                    $warnaTema = $tipe == 'Kecamatan' ? 'bg-indigo-500 text-indigo-600 dark:text-indigo-400' : ($tipe == 'Desa' ? 'bg-emerald-500 text-emerald-600 dark:text-emerald-400' : 'bg-[#FFBD2E] text-amber-600 dark:text-[#FFBD2E]');
                                    $parts = explode(' ', $warnaTema);
                                    $bgBar = $parts[0];
                                    unset($parts[0]);
                                    $textClass = implode(' ', $parts);
                                @endphp
                                <div class="relative pt-1">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 truncate w-3/4">{{ $nama }}</span>
                                        <span class="text-[12px] font-bold {{ $textClass }}">{{ $jumlah }} <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">({{ $persen }}%)</span></span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2"><div class="{{ $bgBar }} h-2 rounded-full" style="width: {{ $persen }}%"></div></div>
                                </div>
                            @empty
                                <p class="text-[13px] text-slate-400 dark:text-slate-500 italic">Data kosong</p>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 6. DEMOGRAFI PENDIDIKAN                    -->
            <!-- ========================================== -->
            <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] mt-6 relative overflow-hidden transition-colors duration-500">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-[#3B82F6]"></div>
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 dark:border-slate-700/50 pb-4 mt-1">
                    <h3 class="text-[15px] font-black text-slate-800 dark:text-white flex items-center gap-2 tracking-tight">
                        <svg class="w-5 h-5 text-[#3B82F6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422M12 14v7m0-7l-6.16-3.422"></path></svg>
                        Demografi Tingkat Pendidikan Klien
                    </h3>
                    <button type="button" onclick="openModal('modalDetailPendidikan')" class="text-[9px] uppercase tracking-widest font-extrabold text-[#3B82F6] dark:text-blue-400 bg-blue-50 dark:bg-[#0F172A] border border-blue-100 dark:border-blue-500/20 hover:bg-[#3B82F6] hover:text-white dark:hover:bg-blue-500/20 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                        Lihat Detail &rarr;
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <div class="lg:col-span-2 space-y-5">
                        @forelse($demografiPendidikan as $index => $item)
                            @php
                                $persen = $totalPendidikan > 0 ? round(($item->total / $totalPendidikan) * 100, 1) : 0;
                                $warnaArray = ['bg-blue-500', 'bg-indigo-500', 'bg-cyan-500', 'bg-emerald-500', 'bg-sky-500'];
                                $warnaBg = $warnaArray[$index % count($warnaArray)];
                                $warnaTeks = str_replace('bg-', 'text-', $warnaBg) . ' dark:text-' . str_replace('bg-', '', $warnaBg);
                            @endphp
                            <div class="relative pt-1">
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">{{ $item->nama }}</span>
                                    <span class="text-[13px] font-extrabold {{ $warnaTeks }}">{{ $item->total }} Klien <span class="text-[11px] text-slate-400 font-medium ml-1">({{ $persen }}%)</span></span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5">
                                    <div class="{{ $warnaBg }} h-2.5 rounded-full transition-all duration-700 ease-out shadow-sm" style="width: {{ $persen }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 bg-slate-50 dark:bg-[#0F172A] border border-slate-100 dark:border-slate-700/50 rounded-2xl">
                                <p class="text-sm text-slate-400 font-medium italic">Data demografi pendidikan klien belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-500/20 rounded-[2rem] p-6 flex flex-col justify-center h-full shadow-inner">
                        <div class="mb-4">
                            <span class="text-[10px] font-extrabold text-[#3B82F6] dark:text-blue-400 uppercase tracking-widest bg-white dark:bg-[#0F172A] shadow-sm border border-blue-100 dark:border-blue-800/50 px-3 py-1.5 rounded-lg">Total Data Masuk</span>
                        </div>
                        <div class="text-4xl font-black text-slate-800 dark:text-white mb-2">{{ $totalPendidikan }} <span class="text-lg text-slate-500 dark:text-slate-400 font-bold">Klien</span></div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 leading-relaxed mt-2 pt-4 border-t border-blue-100/60 dark:border-blue-900/30">
                            Distribusi latar belakang ini dapat membantu Tim Hukum & Medis dalam merancang penyampaian komunikasi serta pendekatan rehabilitasi yang paling sesuai.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 7. STATISTIK REKOMENDASI REHABILITASI      -->
            <!-- ========================================== -->
            <div class="bg-white/90 dark:bg-[#1E293B]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] border border-white/50 dark:border-slate-800 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] mt-6 mb-10 relative overflow-hidden transition-colors duration-500">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-[#27C93F]"></div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-slate-100 dark:border-slate-700/50 pb-4 mt-1 gap-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-[15px] font-black text-slate-800 dark:text-white flex items-center gap-2 tracking-tight">
                            <svg class="w-5 h-5 text-[#27C93F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Statistik Hasil Rekomendasi TAT
                        </h3>
                        <button type="button" onclick="openModal('modalDetailRekomendasi')" class="text-[9px] uppercase tracking-widest font-extrabold text-[#27C93F] dark:text-emerald-400 bg-emerald-50 dark:bg-[#0F172A] border border-emerald-100 dark:border-emerald-500/20 hover:bg-[#27C93F] hover:text-white dark:hover:bg-emerald-500/20 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Lihat Detail &rarr;
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Tahun Berjalan ({{ $tahunIni }}).</p>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-lg border border-emerald-100 dark:border-emerald-800/50 shadow-sm">
                            <span class="text-[11px] font-black text-emerald-700 dark:text-[#27C93F]">{{ $totalRekomendasiTahunIni }} Klien Total</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    @php
                        $warnaRek = [
                            'Rawat Jalan' => 'bg-emerald-500 text-emerald-600 dark:text-emerald-400',
                            'Rawat Inap' => 'bg-blue-500 text-blue-600 dark:text-blue-400',
                            'Rehab di Lapas / Rutan' => 'bg-[#FFBD2E] text-amber-600 dark:text-[#FFBD2E]',
                            'Tidak Rehab (Proses Hukum)' => 'bg-[#FF5F56] text-rose-600 dark:text-[#FF5F56]',
                            'Belum Ada Keterangan' => 'bg-slate-400 text-slate-500 dark:text-slate-400',
                        ];
                    @endphp

                    @foreach($statRekomendasi as $label => $jumlah)
                        @php
                            $persen = $persenRekomendasi[$label];
                            $w = $warnaRek[$label];
                            $parts = explode(' ', $w);
                            $bgClass = $parts[0];
                            unset($parts[0]);
                            $textClass = implode(' ', $parts);
                        @endphp
                        <div class="relative pt-1">
                            <div class="flex justify-between items-end mb-1.5">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span>
                                <span class="text-[13px] font-extrabold {{ $textClass }}">{{ $jumlah }} Klien <span class="text-[11px] text-slate-400 font-medium ml-1">({{ $persen }}%)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $bgClass }} h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div> <!-- End Base Wrapper -->

    <!-- ============================================================== -->
    <!-- KUMPULAN MODAL POPUP (MAC SOFT UI)                             -->
    <!-- ============================================================== -->

    <!-- MODAL 5: DETAIL REKOMENDASI KLIEN -->
    <div id="modalDetailRekomendasi" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="modal-overlay absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeModal('modalDetailRekomendasi')"></div>

        <div class="modal-content relative w-full max-w-5xl bg-white dark:bg-[#1E293B] rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">Daftar Klien Berdasarkan Rekomendasi TAT</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Menampilkan data klien pada tahun berjalan ({{ $tahunIni ?? date('Y') }}).</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetailRekomendasi')" class="p-2 text-slate-400 hover:text-[#27C93F] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50 space-y-6">
                @php
                    $warnaRekModal = [
                        'Rawat Jalan' => ['emerald', 'bg-emerald-500', 'text-emerald-600 dark:text-emerald-400', 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-100 dark:border-emerald-800/50', 'hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800/50', 'group-hover:text-emerald-600 dark:group-hover:text-emerald-400'],
                        'Rawat Inap' => ['blue', 'bg-blue-500', 'text-blue-600 dark:text-blue-400', 'bg-blue-50 dark:bg-blue-900/30 border-blue-100 dark:border-blue-800/50', 'hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-200 dark:hover:border-blue-800/50', 'group-hover:text-blue-600 dark:group-hover:text-blue-400'],
                        'Rehab di Lapas / Rutan' => ['amber', 'bg-[#FFBD2E]', 'text-amber-600 dark:text-amber-500', 'bg-amber-50 dark:bg-amber-900/30 border-amber-100 dark:border-amber-800/50', 'hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:border-amber-200 dark:hover:border-amber-800/50', 'group-hover:text-amber-600 dark:group-hover:text-amber-500'],
                        'Tidak Rehab (Proses Hukum)' => ['rose', 'bg-[#FF5F56]', 'text-rose-600 dark:text-rose-400', 'bg-rose-50 dark:bg-rose-900/30 border-rose-100 dark:border-rose-800/50', 'hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:border-rose-200 dark:hover:border-rose-800/50', 'group-hover:text-rose-600 dark:group-hover:text-rose-400'],
                        'Belum Ada Keterangan' => ['slate', 'bg-slate-400', 'text-slate-600 dark:text-slate-400', 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700', 'hover:bg-slate-200 dark:hover:bg-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600', 'group-hover:text-slate-800 dark:group-hover:text-slate-200'],
                    ];
                @endphp

                @forelse($rekomendasiKliens as $kategori => $kliens)
                    @if(count($kliens) > 0)
                        @php $w = $warnaRekModal[$kategori]; @endphp
                        <div class="bg-white dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex items-center justify-between pb-3 border-b border-{{$w[0]}}-100 dark:border-{{$w[0]}}-900/30 mb-4">
                                <span class="text-sm font-black {{ $w[2] }} flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $w[1] }}"></span>
                                    {{ $kategori }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $w[3] }} {{ $w[2] }}">
                                    {{ count($kliens) }} Klien
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] border border-slate-100 dark:border-slate-800 rounded-xl transition-colors group {{ $w[4] }}">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate transition-colors {{ $w[5] }}">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-sm text-slate-400 italic text-center p-8 bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800">
                        Belum ada data rekomendasi klien pada tahun ini.
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 bg-white dark:bg-[#1E293B] border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailRekomendasi')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL 0: DETAIL PEKERJAAN KLIEN -->
    <div id="modalDetailPekerjaan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="modal-overlay absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeModal('modalDetailPekerjaan')"></div>

        <div class="modal-content relative w-full max-w-5xl bg-white dark:bg-[#1E293B] rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">Daftar Klien Berdasarkan Pekerjaan (Top 5)</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Klik pada nama klien untuk melihat rincian data lengkap.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetailPekerjaan')" class="p-2 text-slate-400 hover:text-[#FF5F56] hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50 space-y-6">
                @forelse($topPekerjaanKliens as $namaPekerjaan => $kliens)
                    <div class="bg-white dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-rose-100 dark:border-rose-900/30 mb-4">
                            <span class="text-sm font-black text-[#FF5F56] flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F56]"></span>
                                {{ $namaPekerjaan }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/50">
                                {{ count($kliens) }} Klien
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($kliens as $klien)
                                <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-rose-200 dark:hover:border-rose-800/50 transition-colors group">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-[#FF5F56] dark:group-hover:text-rose-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-400 italic text-center p-8 bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800">
                        Belum ada data klien yang memiliki rincian pekerjaan.
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 bg-white dark:bg-[#1E293B] border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailPekerjaan')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL 1: DETAIL GENDER KLIEN -->
    <div id="modalDetailGender" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <!-- Background Overlay Terpisah untuk Transisi Fade -->
        <div class="modal-overlay absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeModal('modalDetailGender')"></div>

        <!-- Modal Content wrapper -->
        <div class="modal-content relative w-full max-w-4xl bg-white dark:bg-[#1E293B] rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">Daftar Klien Berdasarkan Gender</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Klik pada nama klien untuk melihat rincian data lengkap.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetailGender')" class="p-2 text-slate-400 hover:text-[#FF5F56] hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Kolom Laki-Laki -->
                    <div class="bg-white dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-blue-100 dark:border-blue-900/30 mb-4">
                            <span class="text-sm font-black text-[#3B82F6] flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>
                                Laki-Laki
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 dark:bg-blue-900/30 text-[#3B82F6] dark:text-blue-400 border border-blue-100 dark:border-blue-800/50">
                                {{ $listKlienGender->get('L', collect())->count() }} Klien
                            </span>
                        </div>
                        <div class="space-y-2 max-h-[50vh] overflow-y-auto custom-select-scroll pr-1">
                            @forelse($listKlienGender->get('L', []) as $klien)
                                <a href="{{ route('asesmen.show', $klien->id) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-800/50 transition-colors group">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-[#3B82F6] dark:group-hover:text-blue-400">{{ $klien->nama_lengkap }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5">{{ $klien->no_register ?? 'No Reg: -' }}</div>
                                </a>
                            @empty
                                <div class="text-xs text-slate-400 italic text-center p-4 bg-slate-50 dark:bg-[#0F172A] rounded-xl border border-slate-100 dark:border-slate-800">Belum ada data klien Laki-laki.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Kolom Perempuan -->
                    <div class="bg-white dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-rose-100 dark:border-rose-900/30 mb-4">
                            <span class="text-sm font-black text-[#FF5F56] flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F56]"></span>
                                Perempuan
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/50">
                                {{ $listKlienGender->get('P', collect())->count() }} Klien
                            </span>
                        </div>
                        <div class="space-y-2 max-h-[50vh] overflow-y-auto custom-select-scroll pr-1">
                            @forelse($listKlienGender->get('P', []) as $klien)
                                <a href="{{ route('asesmen.show', $klien->id) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-rose-200 dark:hover:border-rose-800/50 transition-colors group">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-[#FF5F56] dark:group-hover:text-rose-400">{{ $klien->nama_lengkap }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5">{{ $klien->no_register ?? 'No Reg: -' }}</div>
                                </a>
                            @empty
                                <div class="text-xs text-slate-400 italic text-center p-4 bg-slate-50 dark:bg-[#0F172A] rounded-xl border border-slate-100 dark:border-slate-800">Belum ada data klien Perempuan.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            <div class="px-6 py-4 bg-white dark:bg-[#1E293B] border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailGender')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: DETAIL PENDIDIKAN KLIEN -->
    <div id="modalDetailPendidikan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="modal-overlay absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeModal('modalDetailPendidikan')"></div>

        <div class="modal-content relative w-full max-w-5xl bg-white dark:bg-[#1E293B] rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">Daftar Klien Berdasarkan Tingkat Pendidikan</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Klik pada nama klien untuk melihat rincian data lengkap.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetailPendidikan')" class="p-2 text-slate-400 hover:text-[#FF5F56] hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50 space-y-6">
                @forelse($listKlienPendidikan as $namaPendidikan => $kliens)
                    <div class="bg-white dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-indigo-100 dark:border-indigo-900/30 mb-4">
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                {{ $namaPendidikan }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50">
                                {{ $kliens->count() }} Klien
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($kliens as $klien)
                                <a href="{{ route('asesmen.show', $klien->id) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-colors group">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">{{ $klien->nama_lengkap }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien->no_register ?? 'No Reg: -' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-400 italic text-center p-8 bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800">
                        Belum ada data klien yang memiliki rincian pendidikan.
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 bg-white dark:bg-[#1E293B] border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailPendidikan')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: DETAIL USIA KLIEN -->
    <div id="modalDetailUsia" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="modal-overlay absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeModal('modalDetailUsia')"></div>

        <div class="modal-content relative w-full max-w-5xl bg-white dark:bg-[#1E293B] rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">Daftar Klien Berdasarkan Rentang Usia</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Kelompok usia mengikuti pilihan interval yang sedang aktif.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetailUsia')" class="p-2 text-slate-400 hover:text-[#FF5F56] hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div id="kontenModalUsia" class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50 space-y-6">
                <!-- Javascript merender daftar klien berdasarkan usia di sini -->
            </div>

            <div class="px-6 py-4 bg-white dark:bg-[#1E293B] border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailUsia')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL 4: DETAIL WILAYAH TOP 5 -->
    <div id="modalDetailWilayah" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="modal-overlay absolute inset-0 bg-slate-900/70 dark:bg-[#0B1120]/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeModal('modalDetailWilayah')"></div>

        <div class="modal-content relative w-full max-w-5xl bg-white dark:bg-[#1E293B] rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 dark:border-slate-700/50 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-[#0F172A]/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div>
                        <h3 id="modalWilayahTitle" class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">Daftar Klien Wilayah Top 5</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Rincian data klien pada 5 besar Kecamatan, Desa, dan Kelurahan.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetailWilayah')" class="p-2 text-slate-400 hover:text-[#FF5F56] hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Konten Modal Wilayah KTP -->
            <div id="kontenModalWilayahKtp" class="p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50 space-y-8">
                <!-- Top 5 Kecamatan KTP -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/50 px-3 py-2 rounded-xl">
                        Top 5 Kecamatan (KTP)
                    </h4>
                    @forelse($dataKtp['topKecamatanKliens'] as $kec => $kliens)
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="font-extrabold text-slate-800 dark:text-white text-sm">Kecamatan {{ $kec }}</span>
                                <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-400 text-[10px] font-black rounded-full border border-indigo-200/50 dark:border-indigo-800/50">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-colors group">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white dark:bg-[#1E293B] rounded-xl border border-slate-100 dark:border-slate-800">Data kecamatan kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Desa KTP -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 px-3 py-2 rounded-xl">
                        Top 5 Desa (KTP)
                    </h4>
                    @forelse($dataKtp['topDesaKliens'] as $desa => $kliens)
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="font-extrabold text-slate-800 dark:text-white text-sm">Desa {{ $desa }}</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-400 text-[10px] font-black rounded-full border border-emerald-200/50 dark:border-emerald-800/50">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors group">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white dark:bg-[#1E293B] rounded-xl border border-slate-100 dark:border-slate-800">Data desa kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Kelurahan KTP -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-amber-700 dark:text-amber-500 uppercase tracking-widest bg-amber-50 dark:bg-amber-900/30 border border-amber-100 dark:border-amber-800/50 px-3 py-2 rounded-xl">
                        Top 5 Kelurahan (KTP)
                    </h4>
                    @forelse($dataKtp['topKelurahanKliens'] as $kel => $kliens)
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="font-extrabold text-slate-800 dark:text-white text-sm">Kelurahan {{ $kel }}</span>
                                <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-500 text-[10px] font-black rounded-full border border-amber-200/50 dark:border-amber-800/50">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-amber-200 dark:hover:border-amber-800/50 transition-colors group">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-amber-600 dark:group-hover:text-amber-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white dark:bg-[#1E293B] rounded-xl border border-slate-100 dark:border-slate-800">Data kelurahan kosong.</div>
                    @endforelse
                </div>
            </div>

            <!-- Konten Modal Wilayah Domisili -->
            <div id="kontenModalWilayahDomisili" class="hidden p-6 overflow-y-auto custom-select-scroll flex-1 bg-slate-50/50 dark:bg-[#0F172A]/50 space-y-8">
                <!-- Top 5 Kecamatan Domisili -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/50 px-3 py-2 rounded-xl">
                        Top 5 Kecamatan (Domisili Saat Ini)
                    </h4>
                    @forelse($dataDomisili['topKecamatanKliens'] as $kec => $kliens)
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="font-extrabold text-slate-800 dark:text-white text-sm">Kecamatan {{ $kec }}</span>
                                <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-400 text-[10px] font-black rounded-full border border-indigo-200/50 dark:border-indigo-800/50">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-colors group">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white dark:bg-[#1E293B] rounded-xl border border-slate-100 dark:border-slate-800">Data kecamatan kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Desa Domisili -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 px-3 py-2 rounded-xl">
                        Top 5 Desa (Domisili Saat Ini)
                    </h4>
                    @forelse($dataDomisili['topDesaKliens'] as $desa => $kliens)
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="font-extrabold text-slate-800 dark:text-white text-sm">Desa {{ $desa }}</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-400 text-[10px] font-black rounded-full border border-emerald-200/50 dark:border-emerald-800/50">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors group">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white dark:bg-[#1E293B] rounded-xl border border-slate-100 dark:border-slate-800">Data desa kosong.</div>
                    @endforelse
                </div>

                <!-- Top 5 Kelurahan Domisili -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-amber-700 dark:text-amber-500 uppercase tracking-widest bg-amber-50 dark:bg-amber-900/30 border border-amber-100 dark:border-amber-800/50 px-3 py-2 rounded-xl">
                        Top 5 Kelurahan (Domisili Saat Ini)
                    </h4>
                    @forelse($dataDomisili['topKelurahanKliens'] as $kel => $kliens)
                        <div class="bg-white dark:bg-[#1E293B] p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="font-extrabold text-slate-800 dark:text-white text-sm">Kelurahan {{ $kel }}</span>
                                <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-500 text-[10px] font-black rounded-full border border-amber-200/50 dark:border-amber-800/50">{{ count($kliens) }} Klien</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($kliens as $klien)
                                    <a href="{{ route('asesmen.show', $klien['id']) }}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-amber-200 dark:hover:border-amber-800/50 transition-colors group">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-amber-600 dark:group-hover:text-amber-400 truncate">{{ $klien['nama_lengkap'] }}</div>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate">{{ $klien['no_register'] ?? 'No Reg: -' }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic text-center py-4 bg-white dark:bg-[#1E293B] rounded-xl border border-slate-100 dark:border-slate-800">Data kelurahan kosong.</div>
                    @endforelse
                </div>
            </div>

            <div class="px-6 py-4 bg-white dark:bg-[#1E293B] border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button" onclick="closeModal('modalDetailWilayah')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script Manajemen Modal Universal dengan Animasi -->
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                // Tampilkan container terlebih dahulu
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Trigger reflow untuk memastikan display block teraplikasikan
                void modal.offsetWidth;

                // Temukan elemen overlay & content
                const overlay = modal.querySelector('.modal-overlay');
                const content = modal.querySelector('.modal-content');

                if(overlay && content) {
                    // Animasi masuk
                    overlay.classList.remove('opacity-0');
                    content.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
                }

                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                const overlay = modal.querySelector('.modal-overlay');
                const content = modal.querySelector('.modal-content');

                if(overlay && content) {
                    // Animasi keluar
                    overlay.classList.add('opacity-0');
                    content.classList.add('opacity-0', 'scale-95', 'translate-y-4');

                    // Tunggu animasi selesai sebelum menghidden parent
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = '';
                    }, 300); // 300ms sesuai durasi Tailwind class duration-300
                }
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
    </script>

    <!-- Script Grafik Demografi Usia -->
    <script>
        const dataUsiaMentah = @json($dataKlienUsia ?? []);
        let grupUsiaTerbaru = {};

        function olahDataUsia() {
            const interval = parseInt(document.getElementById('intervalPilihan').value);
            grupUsiaTerbaru = {};

            if (dataUsiaMentah.length === 0) {
                document.getElementById('wadahGrafikUsia').innerHTML = '<div class="py-8 flex flex-col items-center justify-center text-center"><div class="w-16 h-16 bg-slate-50 dark:bg-[#0F172A] rounded-full flex items-center justify-center mb-3 border-2 border-dashed border-slate-200 dark:border-slate-700/50"><svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><p class="text-sm text-slate-500 dark:text-slate-400 font-medium italic">Belum ada data usia klien.</p></div>';
                document.getElementById('kontenModalUsia').innerHTML = '<div class="text-sm text-slate-400 italic text-center p-8 bg-white dark:bg-[#1E293B] border border-slate-200 dark:border-slate-800 rounded-2xl">Belum ada data klien yang dapat ditampilkan.</div>';
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
            const warnaWarni = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-400', 'bg-rose-500', 'bg-cyan-500', 'bg-purple-500', 'bg-blue-500'];

            kunciUrut.forEach((label, index) => {
                let jumlah = grupUsiaTerbaru[label].hitung;
                let calcPersen = (jumlah / totalData) * 100;
                let persentase = calcPersen % 1 === 0 ? calcPersen : calcPersen.toFixed(1);
                let warna = warnaWarni[index % warnaWarni.length];
                let warnaText = warna.replace('bg-', 'text-');

                if(warna === 'bg-amber-400') warnaText = 'text-amber-600 dark:text-amber-500';
                if(warna === 'bg-rose-500') warnaText = 'text-rose-600 dark:text-rose-500';
                if(!warnaText.includes('dark:')) warnaText = warnaText + ' dark:' + warnaText;

                let htmlBar = `
                    <div class="relative pt-1">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-extrabold text-slate-700 dark:text-slate-300">
                                ${label}
                            </span>
                            <span class="text-[13px] font-bold ${warnaText}">${jumlah} Klien <span class="text-[11px] font-medium text-slate-400 ml-1 tracking-wider">(${persentase}%)</span></span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                            <div class="${warna} h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 0%;" data-target-width="${persentase}%"></div>
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
                        <a href="/asesmen/${klien.id}" class="block p-3 bg-slate-50/50 dark:bg-[#0F172A] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors group">
                            <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate">${klien.nama_lengkap}</div>
                            <div class="flex justify-between items-center mt-1">
                                <div class="text-[11px] font-semibold text-slate-500 truncate w-3/4">${noReg}</div>
                                <div class="text-[10px] font-extrabold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-2 py-0.5 rounded border border-emerald-200/50 dark:border-emerald-800/50 shadow-sm">${klien.usia} Thn</div>
                            </div>
                        </a>
                    `;
                });

                let blockHtml = `
                    <div class="bg-white dark:bg-[#1E293B] p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-emerald-100 dark:border-emerald-900/30 mb-4">
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Usia ${label}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 shadow-sm border border-emerald-200/50 dark:border-emerald-800/50">
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
                document.getElementById('btnTabKtp').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg bg-white dark:bg-[#1E293B] shadow-sm text-indigo-700 dark:text-indigo-400 transition-all border border-transparent dark:border-slate-700/50";
                document.getElementById('btnTabDomisili').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-all border border-transparent";

                document.getElementById('wadahTopKtp').classList.replace('hidden', 'grid');
                document.getElementById('wadahTopDomisili').classList.replace('grid', 'hidden');
            } else {
                document.getElementById('btnTabDomisili').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg bg-white dark:bg-[#1E293B] shadow-sm text-indigo-700 dark:text-indigo-400 transition-all border border-transparent dark:border-slate-700/50";
                document.getElementById('btnTabKtp').className = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-all border border-transparent";

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
                    htmlKeluaran += cetakBarHasil('Kec', item.nama, item.count, item.persen, 'text-indigo-700 dark:text-indigo-400', 'bg-indigo-200/50 dark:bg-indigo-900/50 border-indigo-200 dark:border-indigo-800/50');
                    totalDitemukan++;
                }
            });
            currentData.desa.forEach(item => {
                if(item.nama.toLowerCase().includes(kataKunci)) {
                    htmlKeluaran += cetakBarHasil('Desa', item.nama, item.count, item.persen, 'text-emerald-700 dark:text-emerald-400', 'bg-emerald-200/50 dark:bg-emerald-900/50 border-emerald-200 dark:border-emerald-800/50');
                    totalDitemukan++;
                }
            });
            currentData.kelurahan.forEach(item => {
                if(item.nama.toLowerCase().includes(kataKunci)) {
                    htmlKeluaran += cetakBarHasil('Kel', item.nama, item.count, item.persen, 'text-amber-700 dark:text-amber-500', 'bg-amber-200/50 dark:bg-amber-900/50 border-amber-200 dark:border-amber-800/50');
                    totalDitemukan++;
                }
            });

            if(totalDitemukan === 0) {
                divHasil.innerHTML = `<div class="text-[13px] text-[#FF5F56] font-semibold italic py-2 text-center">Wilayah "${document.getElementById('searchWilayah').value}" tidak ditemukan dalam data ${modeWilayahAktif.toUpperCase()}.</div>`;
            } else {
                divHasil.innerHTML = `<div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Ditemukan ${totalDitemukan} Hasil (Data ${modeWilayahAktif.toUpperCase()}):</div>` + htmlKeluaran;
            }
        }

        function cetakBarHasil(tipe, nama, count, persen, textClass, bgClass) {
            return `
                <div class="flex justify-between items-center py-2 border-b border-indigo-100/50 dark:border-slate-700/50 last:border-0 hover:bg-white/50 dark:hover:bg-[#0F172A]/50 px-3 rounded-lg transition-colors">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[9px] font-black ${bgClass} border ${textClass} uppercase w-12 text-center shadow-sm">${tipe}</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">${nama}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-slate-800 dark:text-slate-200">${count} Klien</span>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 ml-1 w-10 inline-block text-right">(${persen}%)</span>
                    </div>
                </div>
            `;
        }
    </script>
</x-app-layout>
