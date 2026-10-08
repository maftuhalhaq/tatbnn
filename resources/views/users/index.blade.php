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

    <x-slot name="header">
        <div class="relative z-10 max-w-[1400px] mx-auto w-full">
            <h2 class="font-black text-2xl text-slate-900 dark:text-white tracking-tight">Manajemen Pengguna</h2>
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Setujui pendaftar baru dan atur hak akses (Role) masing-masing akun.</p>
        </div>
    </x-slot>

    <!-- Konten Utama di atas Background -->
    <div class="relative z-10 py-8 min-h-screen">
        <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ALERT MESSAGES -->
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

            <!-- KONTAINER TABEL UTAMA -->
            <div class="bg-white dark:bg-[#1E293B] shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.3)] rounded-[2.5rem] border border-slate-100 dark:border-slate-800 transition-colors duration-300 relative z-20 isolate transform-gpu pb-24">
                <div class="overflow-x-visible w-full rounded-[2.5rem]">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white dark:bg-[#0F172A] text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-black border-b border-slate-100 dark:border-slate-800">
                                <th class="px-6 md:px-8 py-5 rounded-tl-[2.5rem]">Nama / Email</th>
                                <th class="px-6 py-5">Tanggal Daftar</th>
                                <th class="px-6 md:px-8 py-5 rounded-tr-[2.5rem]">Pengaturan Akses</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 bg-white dark:bg-[#1E293B]">

                            <!-- BYPASS BLADE COMPILER MENGGUNAKAN NATIVE PHP -->
                            <?php foreach($users as$user): ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-[#0F172A]/50 transition-colors duration-200 group">
                                <td class="px-6 md:px-8 py-6 align-top">
                                    <div class="font-black text-sm text-slate-900 dark:text-white tracking-tight group-hover:text-[#3B82F6] transition-colors">{{ $user->name }}</div>
                                    <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ $user->email }}</div>

                                    <div class="mt-2.5 flex items-center gap-2">
                                        @if($user->id === auth()->id())
                                            <span class="inline-flex px-2 py-0.5 bg-blue-50 dark:bg-blue-500/10 text-[#3B82F6] dark:text-blue-400 border border-blue-200/50 dark:border-blue-500/20 text-[9px] font-black uppercase tracking-widest rounded-md shadow-sm">AKUN ANDA</span>
                                        @endif

                                        <!-- MENGGUNAKAN 'or' AGAR TIDAK BENTROK DENGAN SIMBOL MATEMATIKA LATEX -->
                                        @if($user->id === 1 or$user->email === 'admin@admin.com')
                                            <span class="inline-flex px-2 py-0.5 bg-rose-50 dark:bg-rose-500/10 text-[#FF5F56] dark:text-rose-400 border border-rose-200/50 dark:border-rose-500/20 text-[9px] font-black uppercase tracking-widest rounded-md shadow-sm">SUPER ADMIN</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-6 align-top">
                                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $user->created_at->format('d M Y, H:i') }}
                                    </div>
                                </td>

                                <td class="px-6 md:px-8 py-6 align-top">
                                    <div class="flex flex-col gap-2.5 min-w-[340px]">

                                        <!-- BARIS 1: Form Update Akses (Status & Role) -->
                                        <form action="{{ route('users.update', $user->id) }}" method="POST" class="flex items-center gap-2 m-0 w-full">
                                            @csrf
                                            @method('PATCH')

                                            <!-- Pilih Status Persetujuan -->
                                            <div class="flex-1">
                                                <select name="is_approved" class="w-full text-[11px] py-2 px-3 rounded-xl border-slate-200 dark:border-slate-700 font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm appearance-none cursor-pointer {{ $user->is_approved ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400' }}">
                                                    <option value="1" {{ $user->is_approved ? 'selected' : '' }} class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300">Diizinkan Login</option>
                                                    <option value="0" {{ !$user->is_approved ? 'selected' : '' }} class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300">Ditolak / Menunggu</option>
                                                </select>
                                            </div>

                                            <!-- Pilih Role -->
                                            <div class="flex-1">
                                                <select name="role" class="w-full text-[11px] py-2 px-3 rounded-xl border-slate-200 dark:border-slate-700 font-bold bg-slate-50 dark:bg-[#0F172A] text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm appearance-none cursor-pointer">
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Full Akses)</option>
                                                    <option value="penginput_data" {{ $user->role === 'penginput_data' ? 'selected' : '' }}>Penginput Data</option>
                                                    <option value="pengedit_ba" {{ $user->role === 'pengedit_ba' ? 'selected' : '' }}>Pengedit B. Acara</option>
                                                    <option value="pengedit_rekom" {{ $user->role === 'pengedit_rekom' ? 'selected' : '' }}>Pengedit Rekom</option>
                                                    <option value="read_only" {{ $user->role === 'read_only' ? 'selected' : '' }}>Read Only</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="px-4 py-2 bg-[#3B82F6] hover:bg-blue-600 text-white text-[10px] uppercase tracking-widest font-black rounded-xl transition-all shadow-md transform-gpu hover:-translate-y-0.5">
                                                Simpan
                                            </button>
                                        </form>

                                        <!-- BARIS 2: Tombol Edit & Hapus -->
                                        <div class="flex items-center gap-2">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-[#FFBD2E] text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors shadow-sm transform-gpu">
                                                Edit
                                            </a>

                                            <!-- Tombol Hapus / Gembok Permanen -->
                                            <!-- MENGGUNAKAN 'and' AGAR TIDAK BENTROK DENGAN SIMBOL MATEMATIKA -->
                                            @if($user->id !== auth()->id() and $user->id !== 1 and$user->email !== 'admin@admin.com')
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 flex" onsubmit="return confirm('Peringatan: Apakah Anda yakin ingin menghapus pengguna {{ $user->name }} secara permanen? Data yang sudah dihapus tidak dapat dikembalikan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-[#FF5F56] text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors shadow-sm transform-gpu" title="Hapus Pengguna">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @elseif($user->id === 1 or$user->email === 'admin@admin.com')
                                                <!-- Badge Gembok untuk Super Admin -->
                                                <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-slate-50 dark:bg-[#0F172A] text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700/50 text-[10px] uppercase tracking-widest font-black rounded-xl cursor-not-allowed shadow-sm" title="Akun Super Admin tidak bisa dihapus">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                                                    Permanen
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Mesin Custom Dropdown -->
    <script>
        function makeCustomSelect(select) {
            if (!select || select.classList.contains('no-custom') || select.classList.contains('hidden')) return;

            let isRendering = false;
            let renderTimeout;

            function render() {
                if (isRendering) return;
                isRendering = true;

                if (select.nextElementSibling && select.nextElementSibling.classList.contains('mac-select-wrapper')) {
                    select.nextElementSibling.remove();
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'mac-select-wrapper relative w-full flex-1';
                wrapper.style.zIndex = '1';

                const btn = document.createElement('button');
                btn.type = 'button';

                // Cek kelas asli select (untuk styling khusus seperti bg-emerald atau bg-rose)
                const isEmerald = select.classList.contains('bg-emerald-50') || select.classList.contains('text-emerald-700');
                const isRose = select.classList.contains('bg-rose-50') || select.classList.contains('text-rose-700');

                let btnBaseClass = 'flex justify-between items-center w-full px-4 py-2 rounded-xl border transition-all focus:outline-none focus:ring-2 shadow-sm text-[11px] font-bold text-left ';

                if (select.disabled) {
                    btn.className = btnBaseClass + 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed dark:bg-slate-800/50 dark:text-slate-500';
                    btn.disabled = true;
                } else if (isEmerald) {
                    btn.className = btnBaseClass + 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300 focus:ring-emerald-500/20 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400';
                } else if (isRose) {
                    btn.className = btnBaseClass + 'bg-rose-50 border-rose-100 text-rose-700 hover:border-rose-300 focus:ring-rose-500/20 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400';
                } else {
                    btn.className = btnBaseClass + 'bg-slate-50 border-slate-200 text-slate-700 hover:border-[#3B82F6] focus:ring-blue-500/20 dark:bg-[#0F172A] dark:border-slate-700 dark:text-slate-300 dark:hover:border-blue-500';
                }

                const span = document.createElement('span');
                span.className = 'truncate pointer-events-none flex-1';

                let selectedText = '-- Pilih --';
                if (select.selectedIndex >= 0 && select.options[select.selectedIndex]) {
                    selectedText = select.options[select.selectedIndex].text;
                } else if (select.options.length > 0) {
                    selectedText = select.options[0].text;
                }
                span.innerText = selectedText;

                const icon = document.createElement('div');
                icon.className = 'shrink-0 ml-2 transition-transform duration-200 pointer-events-none flex items-center justify-center';

                let iconColor = 'text-slate-400';
                if (isEmerald) iconColor = 'text-emerald-500 dark:text-emerald-400';
                else if (isRose) iconColor = 'text-rose-500 dark:text-rose-400';

                icon.innerHTML = `<svg class="w-3.5 h-3.5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`;

                btn.appendChild(span);
                btn.appendChild(icon);

                const listWrapper = document.createElement('div');
                listWrapper.className = 'mac-select-list absolute z-[90] top-full left-0 mt-2 w-full min-w-[200px] bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-xl border border-slate-100 dark:border-slate-700 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] dark:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] rounded-2xl py-2 max-h-48 overflow-y-auto hidden custom-select-scroll transition-all opacity-0 translate-y-2';

                let hasVisibleOptions = false;
                Array.from(select.options).forEach((opt, index) => {
                    if(opt.style.display === 'none') return;
                    hasVisibleOptions = true;

                    const item = document.createElement('div');
                    item.className = 'px-4 py-2.5 mx-1.5 mt-0.5 rounded-xl cursor-pointer text-[11px] font-bold transition-colors ';

                    if (opt.disabled && opt.value === '') {
                        item.className += 'text-slate-400 opacity-70 cursor-default pointer-events-none';
                    } else if (opt.disabled) {
                        item.className += 'text-slate-400 cursor-not-allowed bg-slate-50 pointer-events-none dark:bg-slate-800';
                    } else if (opt.selected && opt.value !== '') {
                        item.className += 'bg-blue-50 text-[#3B82F6] dark:bg-blue-900/30 dark:text-blue-400';
                    } else {
                        item.className += 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#0F172A] hover:text-slate-900 dark:hover:text-white';
                    }

                    item.innerText = opt.text;

                    if (!opt.disabled) {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            select.selectedIndex = index;
                            span.innerText = opt.text;
                            closeAllMacSelects();

                            // Re-render class jika status persetujuan berubah
                            if(select.name === 'is_approved') {
                                if(opt.value == "1") {
                                    btn.className = btnBaseClass + 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300 focus:ring-emerald-500/20 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400';
                                    icon.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`;
                                } else {
                                    btn.className = btnBaseClass + 'bg-rose-50 border-rose-100 text-rose-700 hover:border-rose-300 focus:ring-rose-500/20 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400';
                                    icon.innerHTML = `<svg class="w-3.5 h-3.5 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>`;
                                }
                            }

                            select.dispatchEvent(new Event('change', {bubbles: true}));
                        });
                    }
                    listWrapper.appendChild(item);
                });

                if(!hasVisibleOptions) {
                    const empty = document.createElement('div');
                    empty.className = 'px-4 py-2 text-[11px] text-slate-400 italic text-center';
                    empty.innerText = 'Tidak ada pilihan';
                    listWrapper.appendChild(empty);
                }

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if(select.disabled) return;

                    const isHidden = listWrapper.classList.contains('hidden');
                    closeAllMacSelects();

                    if (isHidden) {
                        wrapper.style.zIndex = '90';
                        const tr = wrapper.closest('tr');
                        if (tr) {
                            tr.style.zIndex = '50';
                            tr.style.position = 'relative';
                        }

                        if (listWrapper.closeTimeout) clearTimeout(listWrapper.closeTimeout);
                        listWrapper.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            listWrapper.classList.remove('opacity-0', 'translate-y-2');
                            icon.classList.add('rotate-180');
                        });
                    }
                });

                wrapper.appendChild(btn);
                wrapper.appendChild(listWrapper);

                select.style.display = 'none';
                select.parentNode.insertBefore(wrapper, select.nextSibling);

                isRendering = false;
            }

            function triggerRender() {
                clearTimeout(renderTimeout);
                renderTimeout = setTimeout(render, 50);
            }

            triggerRender();
        }

        function closeAllMacSelects() {
            document.querySelectorAll('.mac-select-wrapper').forEach(wrapper => {
                wrapper.style.zIndex = '1';

                const tr = wrapper.closest('tr');
                if(tr) {
                    tr.style.zIndex = '';
                    tr.style.position = '';
                }

                const list = wrapper.querySelector('.mac-select-list');
                const icon = wrapper.querySelector('button > div');
                if(list && !list.classList.contains('hidden')) {
                    list.classList.add('opacity-0', 'translate-y-2');
                    if(icon) icon.classList.remove('rotate-180');
                    if (list.closeTimeout) clearTimeout(list.closeTimeout);
                    list.closeTimeout = setTimeout(() => {
                        list.classList.add('hidden');
                    }, 200);
                }
            });
        }

        document.addEventListener('click', closeAllMacSelects);

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select').forEach(select => {
                makeCustomSelect(select);
            });
        });
    </script>
</x-app-layout>
