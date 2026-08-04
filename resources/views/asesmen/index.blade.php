<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Data Asesmen & Case Conference</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola data klien, status hukum, dan hasil rekomendasi TAT.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <!-- Tombol Download Template -->
                <a href="{{ route('asesmen.downloadTemplate') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-lg font-semibold text-xs text-emerald-700 uppercase tracking-wider hover:bg-emerald-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template Excel
                </a>
                <!-- Tombol Tambah Manual -->
                <a href="{{ route('asesmen.create') }}" style="background-color: #3890f5;" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-wider hover:opacity-90 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Data
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form Import Excel -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-slate-600">
                    <span class="font-bold text-slate-900">Import Data Massal:</span> Unggah file Excel (.xlsx) yang sudah diisi sesuai template.
                </div>
                <form action="{{ route('asesmen.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 w-full md:w-auto">
                    @csrf
                    <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-xs font-bold uppercase rounded-lg hover:bg-slate-700 transition shadow-sm">Import</button>
                </form>
            </div>

            <!-- Filter & Pencarian -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                <form action="{{ route('asesmen.index') }}" method="GET" class="space-y-4">
                    
                    <!-- Baris 1: Search Utama -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Klien, NIK, No. Register, atau LKN..." class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg text-sm bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <!-- Baris 2: Filter Lanjutan (Dropdowns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        
                        <!-- Filter Bulan -->
                        <select name="bulan" class="w-full border-slate-300 rounded-lg text-sm text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Bulan --</option>
                            @php
                                $bulans = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach($bulans as $num => $name)
                                <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        <!-- Filter Tahun -->
                        <select name="tahun" class="w-full border-slate-300 rounded-lg text-sm text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($daftarTahun as $thn)
                                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>

                        <!-- Filter Jenis Narkotika -->
                        <select name="narkotika" class="w-full border-slate-300 rounded-lg text-sm text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Jenis Narkotika --</option>
                            @foreach($masterNarkotika as $n)
                                <option value="{{ $n->id }}" {{ request('narkotika') == $n->id ? 'selected' : '' }}>{{ $n->jenis_narkotika }}</option>
                            @endforeach
                        </select>

                        <!-- Filter Pelaksanaan -->
                        <select name="status" class="w-full border-slate-300 rounded-lg text-sm text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Status Pelaksanaan --</option>
                            <option value="YA" {{ request('status') == 'YA' ? 'selected' : '' }}>Sudah Dilaksanakan (YA)</option>
                            <option value="TIDAK" {{ request('status') == 'TIDAK' ? 'selected' : '' }}>Belum Dilaksanakan (TIDAK)</option>
                        </select>

                        <!-- Sorting -->
                        <select name="sort" class="w-full border-slate-300 rounded-lg text-sm text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Urutkan: Terlama</option>
                            <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Urutkan: Nama A-Z</option>
                            <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Urutkan: Nama Z-A</option>
                        </select>
                    </div>

                    <!-- Baris 3: Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row justify-between items-center border-t border-slate-100 pt-4 mt-2 gap-3 sm:gap-0">
                        
                        <!-- Kiri: Tombol Export Excel -->
                        <div class="w-full sm:w-auto">
                            <a href="{{ route('asesmen.export-excel', request()->all()) }}" class="inline-flex justify-center items-center w-full sm:w-auto px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 shadow-sm transition">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Excel
                            </a>
                        </div>

                        <!-- Kanan: Reset & Terapkan Filter -->
                        <div class="flex gap-2 w-full sm:w-auto">
                            @if(request()->hasAny(['search', 'bulan', 'tahun', 'narkotika', 'status']) && count(request()->except('page')) > 0)
                                <a href="{{ route('asesmen.index') }}" class="inline-flex justify-center items-center flex-1 sm:flex-none px-4 py-2 border border-slate-300 text-sm font-semibold rounded-lg text-slate-600 bg-white hover:bg-slate-50 transition">
                                    Reset Filter
                                </a>
                            @endif
                            <button type="submit" style="background-color: #3890f5;" class="inline-flex justify-center items-center flex-1 sm:flex-none px-6 py-2 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white hover:opacity-90 transition">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white overflow-hidden shadow-xs rounded-xl border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="px-6 py-4 font-bold">No</th>
                                <th class="px-6 py-4 font-bold">Identitas Klien</th>
                                <th class="px-6 py-4 font-bold">Perkara & Hukum</th>
                                <th class="px-6 py-4 font-bold">Medis & Rekomendasi</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($asesmens as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $asesmens->firstItem() + $index }}
                                    </td>
                                    
                                    <!-- Kolom Identitas -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 text-sm">{{ $item->nama_lengkap }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">NIK: {{ $item->nik ?? '-' }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">Reg: {{ $item->no_register ?? '-' }}</div>
                                    </td>

                                    <!-- Kolom Perkara & Hukum -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-800">
                                            {{ $item->narkotika->jenis_narkotika ?? 'Belum diset' }}
                                            @if($item->berat_bb)
                                                <span class="text-xs text-slate-500">({{ $item->berat_bb }} gr)</span>
                                            @endif
                                        </div>
                                        @if($item->status_hukum)
                                            <div class="mt-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">
                                                    {{ $item->status_hukum }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="text-xs text-slate-500 mt-1 truncate max-w-[200px]" title="{{ $item->pasal_sangkaan }}">
                                            {{ $item->pasal_sangkaan ?? '-' }}
                                        </div>
                                    </td>

                                    <!-- Kolom Medis & Rekomendasi -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="text-xs text-slate-500">Urine:</span>
                                            @if($item->tes_urine == 'Positif')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 uppercase">Positif</span>
                                            @elseif($item->tes_urine == 'Negatif')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase">Negatif</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">N/A</span>
                                            @endif
                                        </div>
                                        <div class="text-sm font-semibold text-blue-700 truncate max-w-[200px]" title="{{ $item->rekomendasi->tempat_rehabilitasi ?? 'Belum ada rekomendasi' }}">
                                            {{ $item->rekomendasi->tempat_rehabilitasi ?? 'Belum ada rekomendasi' }}
                                        </div>
                                    </td>

                                    <!-- Kolom Aksi -->
                                    <td class="px-4 py-3 text-sm text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            
                                            <!-- 1. BARU: Tombol Berita Acara (Hijau) -->
                                            <a href="{{ route('asesmen.berita-acara', $item->id) }}" class="p-1.5 bg-green-50 text-green-600 rounded-md hover:bg-green-100 transition-colors" title="Buat Berita Acara">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </a>

                                            <!-- 2. BARU: Tombol Rekomendasi (Ungu) -->
                                            <a href="{{ route('asesmen.rekomendasi', $item->id) }}" class="p-1.5 bg-purple-50 text-purple-600 rounded-md hover:bg-purple-100 transition-colors" title="Buat Rekomendasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                                                </svg>
                                            </a>

                                            <!-- 3. LAMA: Tombol Detail (Biru) -->
                                            <a href="{{ route('asesmen.show', $item->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors" title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>

                                            <!-- 4. LAMA: Tombol Edit (Kuning) -->
                                            <a href="{{ route('asesmen.edit', $item->id) }}" class="p-1.5 bg-yellow-50 text-yellow-600 rounded-md hover:bg-yellow-100 transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>

                                            <!-- 5. LAMA: Tombol Print/PDF (Merah Muda) -->
                                            <a href="{{ route('asesmen.pdf', $item->id) }}" class="p-1.5 bg-red-50 text-red-500 rounded-md hover:bg-red-100 transition-colors" title="Cetak PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.728 12.32a.75.75 0 01.75-.75h9.044a.75.75 0 01.75.75v5.436a.75.75 0 01-.75.75H7.478a.75.75 0 01-.75-.75v-5.436zM3 13.5v-3a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 10.5v3a2.25 2.25 0 01-2.25 2.25h-.75v3.75a2.25 2.25 0 01-2.25 2.25H8.25A2.25 2.25 0 016 19.5v-3.75h-.75A2.25 2.25 0 013 13.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 8.25V5.25a2.25 2.25 0 012.25-2.25h3a2.25 2.25 0 012.25 2.25v3" />
                                                </svg>
                                            </a>

                                            <!-- 6. LAMA: Tombol Hapus (Abu-abu) -->
                                            <form action="{{ route('asesmen.destroy', $item->id) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-gray-50 text-gray-500 rounded-md hover:bg-gray-200 transition-colors" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-base font-medium text-slate-900">Belum ada data Asesmen</p>
                                        <p class="text-sm mt-1">Silakan tambah data secara manual atau import melalui Excel.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($asesmens->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-white">
                        {{ $asesmens->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>