<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Data Asesmen & Case Conference</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola data klien, status hukum, dan hasil rekomendasi TAT.</p>
            </div>
            
            <!-- HANYA MANAGE-DATA YANG BISA TAMBAH DATA -->
            @can('manage-data')
            <a href="{{ route('asesmen.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data Baru
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. ALERT MESSAGES -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="ml-3 text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="ml-3 text-sm font-medium text-rose-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- 2. BLOK MANAJEMEN DATA MASSAL -->
            @can('manage-data')
            <div class="bg-gradient-to-br from-slate-50 to-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Manajemen Data Massal Excel</h3>
                            <p class="text-sm text-slate-500 mt-1 max-w-xl">Unduh template kosong untuk diisi secara luring (offline), lalu unggah kembali ke sistem untuk mempercepat proses input data banyak klien sekaligus.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto bg-white p-2 rounded-xl border border-slate-100 shadow-sm">
                        <a href="{{ route('asesmen.downloadTemplate') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg font-semibold text-xs text-slate-700 hover:bg-slate-50 hover:text-emerald-600 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh Template
                        </a>
                        <div class="hidden sm:block w-px h-8 bg-slate-200"></div>
                        <form action="{{ route('asesmen.import') }}" method="POST" enctype="multipart/form-data" class="w-full sm:w-auto flex items-center gap-2">
                            @csrf
                            <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="block w-full text-sm text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg hover:bg-slate-700 transition">Import</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan

            <!-- HEADER DAFTAR KLIEN & BLOK PENCARIAN -->
            <div class="flex flex-col lg:flex-row justify-between items-start gap-4 mb-6 mt-4">
                <div class="shrink-0 lg:mt-3">
                    <h2 class="text-xl font-bold text-slate-800">Daftar Klien TAT</h2>
                    <p class="text-sm text-slate-500 mt-1">Total data ditemukan: <span class="font-bold text-indigo-600">{{ $asesmens->total() }}</span> klien</p>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm w-full lg:max-w-2xl xl:max-w-3xl">
                    <form action="{{ route('asesmen.index') }}" method="GET" id="filterForm">
                        @if(request()->has('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg></div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Klien, NIK, atau No. Register..." class="block w-full pl-11 pr-3 py-3 border border-slate-300 rounded-xl text-sm bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all shadow-sm">
                            </div>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm">Cari</button>
                            <button type="button" onclick="toggleFilter()" class="px-5 py-3 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                                Filter
                                <svg id="filterIcon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </div>
                        <div id="filterPanel" class="overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0">
                            <div class="pt-5 mt-5 border-t border-slate-100">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <select name="bulan" class="w-full border-slate-300 rounded-xl text-sm text-slate-600 focus:border-indigo-500 bg-slate-50">
                                        <option value="">-- Bulan --</option>
                                        @php $bulans = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember']; @endphp
                                        @foreach($bulans as $num => $name) <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option> @endforeach
                                    </select>
                                    <select name="tahun" class="w-full border-slate-300 rounded-xl text-sm text-slate-600 focus:border-indigo-500 bg-slate-50">
                                        <option value="">-- Tahun --</option>
                                        @foreach($daftarTahun ?? [] as $thn) <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option> @endforeach
                                    </select>
                                    <select name="narkotika" class="w-full border-slate-300 rounded-xl text-sm text-slate-600 focus:border-indigo-500 bg-slate-50">
                                        <option value="">-- Jenis Zat/Narkotika --</option>
                                        @foreach($masterNarkotika ?? [] as $n) <option value="{{ $n->id }}" {{ request('narkotika') == $n->id ? 'selected' : '' }}>{{ $n->jenis_narkotika }}</option> @endforeach
                                    </select>
                                    <select name="status" class="w-full border-slate-300 rounded-xl text-sm text-slate-600 focus:border-indigo-500 bg-slate-50">
                                        <option value="">-- Status TAT --</option>
                                        <option value="YA" {{ request('status') == 'YA' ? 'selected' : '' }}>Selesai</option>
                                        <option value="TIDAK" {{ request('status') == 'TIDAK' ? 'selected' : '' }}>Menunggu</option>
                                    </select>
                                </div>
                                <div class="flex justify-end gap-3 mt-4">
                                    @if(request()->hasAny(['bulan', 'tahun', 'narkotika', 'status', 'search'])) <a href="{{ route('asesmen.index', ['sort' => request('sort')]) }}" class="px-5 py-2 text-sm font-bold text-slate-500 hover:text-slate-800">Bersihkan Filter</a> @endif
                                    <button type="submit" class="px-5 py-2 bg-indigo-100 text-indigo-700 text-sm font-bold rounded-lg hover:bg-indigo-200">Terapkan Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. TABEL DATA (DENGAN 4 TOMBOL EXPORT EXCEL) -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200">
                <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Tabel Data Klien</h3>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                        <form action="{{ route('asesmen.index') }}" method="GET" class="flex-1 sm:flex-none">
                            @foreach(request()->except('sort', 'page') as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                            <select name="sort" onchange="this.form.submit()" class="w-full sm:w-48 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-lg py-2 shadow-sm">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama Ditambahkan</option>
                                <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Abjad (A - Z)</option>
                                <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Abjad (Z - A)</option>
                            </select>
                        </form>

                        @can('manage-data')
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <!-- EXPORT LAMA (Fungsi Original) -->
                            <a href="{{ route('asesmen.export-excel', request()->query()) }}" class="inline-flex items-center px-3 py-2 border border-slate-300 text-xs font-bold rounded-lg shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition-all">
                                Export (Filter)
                            </a>
                            <a href="{{ route('asesmen.export-excel', ['sort' => request('sort', 'terbaru')]) }}" class="inline-flex items-center px-3 py-2 border border-slate-300 text-xs font-bold rounded-lg shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition-all">
                                Export (Sorting)
                            </a>
                            
                            <div class="w-px h-6 bg-slate-300 my-auto hidden sm:block"></div>

                            <!-- EXPORT TEMPLATE BARU -->
                            <a href="{{ route('asesmen.export-excel', array_merge(request()->query(), ['type' => 'rekap'])) }}" class="inline-flex items-center px-3 py-2 border border-emerald-400 text-xs font-bold rounded-lg shadow-sm text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition-all" title="Unduh dengan Format Rekap TAT BNNP">
                                <svg class="h-4 w-4 mr-1.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Rekap TAT
                            </a>
                            <a href="{{ route('asesmen.export-excel', array_merge(request()->query(), ['type' => 'cc'])) }}" class="inline-flex items-center px-3 py-2 border border-sky-400 text-xs font-bold rounded-lg shadow-sm text-sky-800 bg-sky-50 hover:bg-sky-100 transition-all" title="Unduh dengan Format Case Conference">
                                <svg class="h-4 w-4 mr-1.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Case Conf.
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

                <div class="overflow-x-auto pb-4">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="px-6 py-4 font-bold w-16">No</th>
                                <th class="px-6 py-4 font-bold">Identitas Klien</th>
                                <th class="px-6 py-4 font-bold">Perkara Hukum</th>
                                <th class="px-6 py-4 font-bold">Medis & Rekomendasi</th>
                                <th class="px-6 py-4 font-bold text-right pr-8">Tindakan & Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($asesmens as $index => $item)
                                @php
                                    $usiaKlien = '-';
                                    if (!empty($item->tgl_lahir)) {
                                        try { $usiaKlien = \Carbon\Carbon::parse($item->tgl_lahir)->age . ' Tahun'; } catch (\Exception $e) {}
                                    }
                                    $bb_gabungan = ($item->berat_bb ? $item->berat_bb . ' gr' : '') . ($item->berat_bb && $item->deskripsi_bb ? ' - ' : '') . ($item->deskripsi_bb ?? '');
                                @endphp

                                <tr class="hover:bg-slate-50/70 transition-colors duration-200">
                                    <td class="px-6 py-5 text-sm text-slate-500 align-top">{{ $asesmens->firstItem() + $index }}</td>

                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 mt-0.5 relative">
                                                @if ($item->foto_klien) <img class="w-full h-full rounded-full object-cover border border-slate-200 shadow-sm" src="{{ asset('storage/' . $item->foto_klien) }}">
                                                @else <div class="w-full h-full rounded-full bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-lg border border-indigo-100 shadow-sm">{{ strtoupper(substr($item->nama_lengkap ?? 'A', 0, 1)) }}</div> @endif
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-sm text-left">{{ $item->nama_lengkap }}</div>
                                                <div class="text-xs text-slate-500 mt-1">NIK: <span class="font-medium text-slate-700">{{ $item->nik ?? '-' }}</span></div>
                                                <div class="text-xs text-slate-500 mt-0.5">Reg: <span class="font-medium text-slate-700">{{ $item->no_register ?? '-' }}</span></div>
                                                <div class="mt-2.5 flex flex-col gap-2">
                                                    <div>
                                                        @if($item->status_kelengkapan === 'Data Lengkap') <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">DATA LENGKAP</span>
                                                        @else <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">BELUM LENGKAP</span> @endif
                                                    </div>
                                                    <div class="border-t border-slate-100 pt-2 mt-1">
                                                        <div id="text-tanggal-{{ $item->id }}" class="flex items-center gap-1.5">
                                                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Ditambahkan:</span>
                                                            <span class="text-[11px] font-bold text-slate-700">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</span>
                                                            @can('manage-data')
                                                            <button type="button" onclick="toggleEditTanggal('{{ $item->id }}', true)" class="text-indigo-400 hover:text-indigo-600 transition-colors"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                                            @endcan
                                                        </div>
                                                        @can('manage-data')
                                                        <form id="form-tanggal-{{ $item->id }}" action="{{ route('asesmen.update-tanggal', $item->id) }}" method="POST" class="hidden items-center gap-1.5">
                                                            @csrf @method('PATCH')
                                                            <input type="date" name="tanggal_ditambahkan" value="{{ $item->created_at ? $item->created_at->format('Y-m-d') : date('Y-m-d') }}" class="text-[11px] border-slate-300 rounded py-0.5 px-1.5 focus:ring-indigo-500 h-6">
                                                            <button type="submit" class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded hover:bg-emerald-200 h-6">Simpan</button>
                                                            <button type="button" onclick="toggleEditTanggal('{{ $item->id }}', false)" class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded hover:bg-slate-200 h-6">Batal</button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <div class="text-sm font-bold text-slate-800">
                                            {{ $item->narkotika->jenis_narkotika ?? 'Belum diset' }}
                                            @if($item->berat_bb) <span class="text-xs text-slate-500 font-normal ml-1">({{ $item->berat_bb }} gr)</span> @endif
                                        </div>
                                        @if($item->status_hukum) <div class="mt-1.5"><span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase border border-slate-200">{{ $item->status_hukum }}</span></div> @endif
                                        <div class="text-xs text-slate-500 mt-2 line-clamp-2 max-w-[220px]" title="{{ $item->pasal_sangkaan }}">{{ $item->pasal_sangkaan ?? 'Pasal belum diinput' }}</div>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xs font-medium text-slate-500">Urine:</span>
                                            @php $urineFirstWord = strtoupper(strtok($item->tes_urine ?? '', " :,-")); @endphp
                                            @if($urineFirstWord === 'POSITIF') <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">POSITIF</span>
                                            @elseif($urineFirstWord === 'NEGATIF') <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">NEGATIF</span>
                                            @else <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">N/A</span> @endif
                                        </div>
                                        <div class="text-sm font-bold text-indigo-700 line-clamp-2 max-w-[220px]">
                                            {{ $item->rekomendasi_input ?? $item->rekomendasi->tempat_rehabilitasi ?? 'Belum ada rekomendasi' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-col gap-2 min-w-[175px] float-right">
                                            <!-- BARIS 1: Detail, Edit, Hapus -->
                                            <div class="inline-flex rounded-lg shadow-sm w-full" role="group">
                                                <a href="{{ route('asesmen.show', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-slate-600 bg-white border border-slate-200 {{ Gate::allows('manage-data') ? 'rounded-l-lg' : 'rounded-lg' }} hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    Detail
                                                </a>
                                                @can('manage-data')
                                                <a href="{{ route('asesmen.edit', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-slate-600 bg-white border-t border-b border-slate-200 hover:bg-slate-50 hover:text-amber-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('asesmen.destroy', $item->id) }}" method="POST" class="m-0 flex">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center px-2 py-1.5 text-slate-400 bg-white border border-slate-200 rounded-r-lg hover:bg-rose-50 hover:text-rose-600 transition-colors" onclick="return confirm('Hapus klien permanen?')">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>

                                            <!-- BARIS 2: B. Acara & Rekom -->
                                            @can('akses-dokumen')
                                            <div class="flex gap-1.5 w-full mt-1">
                                                <a href="{{ route('asesmen.berita-acara', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md hover:bg-emerald-100 transition-colors text-[10px] font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    B. Acara
                                                </a>
                                                <a href="{{ route('asesmen.rekomendasi', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 bg-purple-50 border border-purple-200 text-purple-700 rounded-md hover:bg-purple-100 transition-colors text-[10px] font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                                    Rekom
                                                </a>
                                            </div>
                                            @endcan

                                            <!-- BARIS 3 & 4: Detail Tambahan & PDF -->
                                            <button type="button" onclick="toggleExpand('{{ $item->id }}')" class="inline-flex items-center justify-between px-3 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-md hover:bg-indigo-100 transition-colors text-[11px] font-bold w-full shadow-sm group">
                                                <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>Case Conference</div>
                                                <svg id="icon-expand-{{ $item->id }}" class="w-3.5 h-3.5 text-indigo-400 group-hover:text-indigo-700 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                            </button>
                                            <a href="{{ route('asesmen.cetakPdf', $item->id) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-600 rounded-md hover:bg-rose-100 transition-colors text-[11px] font-bold w-full">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                                Unduh PDF Ringkasan
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- EXPANDABLE ROW -->
                                <tr id="expand-row-{{ $item->id }}" class="hidden bg-slate-50 border-b-2 border-indigo-200 shadow-inner">
                                    <td colspan="5" class="p-6">
                                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 md:p-7 space-y-6">
                                            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest">Detail Case Conference: <span class="text-indigo-600">{{ $item->nama_lengkap }}</span></h4>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                                <div class="space-y-3">
                                                    <h5 class="text-[11px] font-bold text-slate-400 border-b border-slate-100 pb-1 mb-2">IDENTITAS</h5>
                                                    <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Usia</span><p class="font-bold text-sm text-slate-800">{{ $usiaKlien }}</p></div>
                                                    <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">P / L (Jenis Kelamin)</span><p class="font-bold text-sm text-slate-800">{{ $item->jenis_kelamin == 'L' ? 'Laki-Laki' : ($item->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p></div>
                                                    <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Pekerjaan</span><p class="font-bold text-sm text-slate-800">{{ $item->pekerjaan->nama_pekerjaan ?? '-' }}</p></div>
                                                    <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Pendidikan Terakhir</span><p class="font-bold text-sm text-slate-800">{{ $item->pendidikan->nama_pendidikan ?? '-' }}</p></div>
                                                    <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Domisili</span><p class="font-bold text-sm text-slate-800 leading-snug">{{ $item->alamat_domisili ?? '-' }}</p></div>
                                                </div>
                                                <div class="space-y-4">
                                                    <div class="space-y-3">
                                                        <h5 class="text-[11px] font-bold text-amber-500 border-b border-amber-100 pb-1 mb-2">HUKUM</h5>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Status Hukum</span><p class="font-bold text-sm text-slate-800">{{ $item->status_hukum ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Keterlibatan Jaringan</span><p class="font-bold text-sm text-rose-600 uppercase">{{ $item->keterlibatan_jaringan ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Barang Bukti</span><p class="font-bold text-sm text-slate-800">{{ $bb_gabungan ?: '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Cara Mendapatkan</span><p class="font-bold text-sm text-slate-800">{{ $item->cara_mendapatkan ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Dapat Dari</span><p class="font-bold text-sm text-slate-800">{{ $item->dapat_dari_siapa ?? '-' }}</p></div>
                                                    </div>
                                                    <div class="space-y-3 pt-3">
                                                        <h5 class="text-[11px] font-bold text-blue-500 border-b border-blue-100 pb-1 mb-2">MEDIS</h5>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Kesehatan</span><p class="font-bold text-sm text-slate-800 whitespace-pre-line">{{ $item->kesehatan_fisik ?? $item->aspek_medis ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Psikologi</span><p class="font-bold text-sm text-slate-800 whitespace-pre-line">{{ $item->psikologi ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Hasil Tes Urine</span><p class="font-bold text-sm text-slate-800">{{ $item->tes_urine ?? '-' }}</p></div>
                                                    </div>
                                                </div>
                                                <div class="space-y-4">
                                                    <div class="space-y-3">
                                                        <h5 class="text-[11px] font-bold text-slate-400 border-b border-slate-100 pb-1 mb-2">KONDISI & RIWAYAT</h5>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Alasan Penggunaan</span><p class="font-bold text-sm text-slate-800 whitespace-pre-line">{{ $item->alasan_penggunaan ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Kondisi Keluarga</span><p class="font-bold text-sm text-slate-800 whitespace-pre-line">{{ $item->kondisi_keluarga ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Tingkat Ketergantungan</span><p class="font-bold text-sm text-slate-800">{{ $item->tingkat_ketergantungan ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Pola Pemakaian</span><p class="font-bold text-sm text-slate-800">{{ $item->pola_pemakaian ?? '-' }}</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Kondisi Lingkungan</span><p class="font-bold text-sm text-slate-800 whitespace-pre-line">{{ $item->kondisi_lingkungan ?? '-' }}</p></div>
                                                    </div>
                                                    <div class="space-y-3 pt-3 border-t border-slate-100">
                                                        <div>
                                                            <span class="block text-[10px] font-semibold text-emerald-600 uppercase">Rekomendasi (Instansi)</span>
                                                            <p class="font-extrabold text-sm text-emerald-800 bg-emerald-50 px-2 py-1 rounded inline-block mt-1">{{ $item->rekomendasi_input ?? $item->rekomendasi->tempat_rehabilitasi ?? '-' }}</p>
                                                        </div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Keterangan Hukum</span><p class="font-semibold text-sm text-slate-700 italic">"{{ $item->rekomendasi_keterangan ?? $item->keterangan_tambahan ?? '-' }}"</p></div>
                                                        <div><span class="block text-[10px] font-semibold text-slate-400 uppercase">Saran Sidang</span><p class="font-bold text-sm text-slate-800 bg-slate-50 p-2 rounded-lg border border-slate-100 whitespace-pre-line">{{ $item->saran_case_conference ?? '-' }}</p></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-t border-slate-100 pt-4 flex justify-end">
                                                <button onclick="toggleExpand('{{ $item->id }}')" class="px-5 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition-colors shadow-sm">Tutup Detail</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="mx-auto w-24 h-24 mb-5 bg-slate-50 rounded-full flex items-center justify-center border-2 border-dashed border-slate-200">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada data klien</h3>
                                        <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">Mulai dengan menambahkan data klien secara manual, atau import langsung melalui template Excel yang disediakan.</p>
                                        @can('manage-data')
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('asesmen.create') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-sm hover:bg-indigo-700 transition">+ Tambah Data Klien</a>
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($asesmens->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-white">
                        {{ $asesmens->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Script Animasi Filter & Expand -->
    <script>
        function toggleFilter() {
            const panel = document.getElementById('filterPanel');
            const icon = document.getElementById('filterIcon');
            if (panel.classList.contains('max-h-0')) {
                panel.classList.remove('max-h-0', 'opacity-0');
                panel.classList.add('max-h-[500px]', 'opacity-100');
                icon.classList.add('rotate-180');
            } else {
                panel.classList.add('max-h-0', 'opacity-0');
                panel.classList.remove('max-h-[500px]', 'opacity-100');
                icon.classList.remove('rotate-180');
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('bulan') || urlParams.has('tahun') || urlParams.has('narkotika') || urlParams.has('status')) {
                const hasValue = ['bulan', 'tahun', 'narkotika', 'status'].some(param => urlParams.get(param) !== '' && urlParams.get(param) !== null);
                if (hasValue) toggleFilter();
            }
        });
        function toggleExpand(id) {
            const expandRow = document.getElementById('expand-row-' + id);
            const icon = document.getElementById('icon-expand-' + id);
            if (expandRow.classList.contains('hidden')) {
                expandRow.classList.remove('hidden');
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