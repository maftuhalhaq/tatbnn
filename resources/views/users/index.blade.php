<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 tracking-tight">Manajemen Pengguna</h2>
        <p class="text-sm text-slate-500 mt-1">Setujui pendaftar baru dan atur hak akses (Role) masing-masing akun.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start shadow-sm">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start shadow-sm">
                    <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="px-6 py-4 font-bold">Nama / Email</th>
                                <th class="px-6 py-4 font-bold">Tanggal Daftar</th>
                                <th class="px-6 py-4 font-bold">Pengaturan Akses</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $u)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-sm text-slate-900">{{ $u->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $u->email }}</div>
                                    @if($u->id === auth()->id())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded">AKUN ANDA</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $u->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <!-- Form Update Akses -->
                                        <form action="{{ route('users.update', $u->id) }}" method="POST" class="flex items-center gap-2 m-0">
                                            @csrf @method('PATCH')
                                            
                                            <!-- Pilih Status Persetujuan -->
                                            <select name="is_approved" class="text-xs rounded-lg border-slate-300 font-bold focus:ring-indigo-500 focus:border-indigo-500 {{ $u->is_approved ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                                <option value="1" {{ $u->is_approved ? 'selected' : '' }}>Diizinkan Login</option>
                                                <option value="0" {{ !$u->is_approved ? 'selected' : '' }}>Ditolak / Menunggu</option>
                                            </select>

                                            <!-- Pilih Role (Sudah ditambahkan Penginput Data) -->
                                            <select name="role" class="text-xs rounded-lg border-slate-300 font-bold bg-slate-50 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin (Full Akses)</option>
                                                <option value="penginput_data" {{ $u->role === 'penginput_data' ? 'selected' : '' }}>Penginput Data</option>
                                                <option value="pengedit_ba" {{ $u->role === 'pengedit_ba' ? 'selected' : '' }}>Pengedit B. Acara</option>
                                                <option value="pengedit_rekom" {{ $u->role === 'pengedit_rekom' ? 'selected' : '' }}>Pengedit Rekom</option>
                                                <option value="read_only" {{ $u->role === 'read_only' ? 'selected' : '' }}>Read Only</option>
                                            </select>

                                            <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                                Simpan
                                            </button>
                                        </form>

                                        <!-- Form Hapus Akun (Hanya muncul jika bukan akun admin yang sedang login) -->
                                        @if($u->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="m-0" onsubmit="return confirm('Peringatan: Apakah Anda yakin ingin menghapus pengguna {{ $u->name }} secara permanen? Data yang sudah dihapus tidak dapat dikembalikan.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-white text-rose-600 border border-rose-200 text-xs font-bold rounded-lg hover:bg-rose-50 hover:text-rose-700 transition shadow-sm" title="Hapus Pengguna">
                                                Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>