<nav x-data="{ open: false }" class="bg-white/45 dark:bg-[#0F172A]/45 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/80 sticky top-0 z-50 transition-colors duration-500">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            <!-- Sisi Kiri: Mac Controls & Logo Brand -->
            <div class="flex items-center">
                <!-- Mac Window Controls (Hanya di Desktop) -->
                <div class="hidden sm:flex items-center gap-2 mr-6">
                    <div class="w-3 h-3 rounded-full bg-rose-500 shadow-sm"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400 shadow-sm"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm"></div>
                </div>

                <!-- Logo & Menu Utama -->
                <div class="shrink-0 flex items-center gap-3 border-l border-transparent sm:border-slate-200 dark:sm:border-slate-700 sm:pl-6 transition-colors duration-500">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo-bnn.jpg') }}" alt="Logo BNN" class="object-contain drop-shadow-sm rounded-full group-hover:scale-105 transition-transform" style="width: 32px; height: 32px;">
                        <div class="flex flex-col justify-center">
                            <div class="flex items-center gap-1.5">
                                <h1 class="font-black text-[14px] tracking-tight text-slate-900 dark:text-white leading-none">BNN</h1>
                                <span class="font-bold text-[14px] tracking-tight text-blue-600 dark:text-blue-400 leading-none hidden sm:inline-block">SistemTAT</span>
                                <span class="text-[9px] font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 px-1.5 py-0.5 rounded-md ml-1 border border-blue-200 dark:border-blue-800/50 hidden md:inline-block">v2.4</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Sisi Tengah: Navigation Menu (Pills Modern) -->
            <div class="hidden sm:flex items-center justify-center flex-1 px-8 mt-1">
                <div class="flex items-center gap-2 bg-slate-100/50 dark:bg-slate-800/50 p-1.5 rounded-full border border-slate-200/50 dark:border-slate-700/50 shadow-inner">
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-2 rounded-full text-xs transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:bg-white dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white hover:shadow-sm' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('asesmen.index') }}"
                        class="px-5 py-2 rounded-full text-xs transition-all duration-200 {{ request()->routeIs('asesmen.*') ? 'bg-blue-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:bg-white dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white hover:shadow-sm' }}">
                        Data Asesmen
                    </a>

                    @can('is-admin')
                    <a href="{{ route('users.index') }}"
                        class="px-5 py-2 rounded-full text-xs transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:bg-white dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white hover:shadow-sm' }}">
                        Manajemen Pengguna
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Sisi Kanan: Profil & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <!-- PERBAIKAN TOMBOL USER: Dibuat sangat soft, transparan, tanpa border kasar -->
                        <button class="inline-flex items-center gap-2.5 p-1 pr-4 bg-white/60 dark:bg-slate-800/60 hover:bg-white dark:hover:bg-slate-800 text-sm font-bold rounded-full text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)] dark:shadow-[0_2px_10px_-3px_rgba(0,0,0,0.3)] border border-slate-100 dark:border-slate-700/50 backdrop-blur-md">

                            <!-- Avatar Inisial User (Warna lebih soft dengan gradient) -->
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 text-white flex items-center justify-center font-black text-xs shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <!-- Nama Panggilan -->
                            <div class="hidden lg:block text-[11px] tracking-wide font-extrabold">{{ explode(' ', Auth::user()->name)[0] }}</div>

                            <!-- Panah Bawah -->
                            <svg class="fill-current h-3.5 w-3.5 text-slate-400 ml-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <!-- Isi Modal Dropdown -->
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700/50">
                            <p class="text-[9px] uppercase tracking-widest font-black text-slate-400 dark:text-slate-500 leading-none">Login sebagai</p>
                            <p class="text-xs font-bold text-slate-900 dark:text-white mt-1.5 truncate">{{ Auth::user()->email }}</p>

                            <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest
                                {{ Auth::user()->role === 'admin' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-800' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200/50 dark:border-slate-700' }}">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </span>
                        </div>

                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-slate-800/50 flex items-center gap-2 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ __('Profile Saya') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-xs font-bold text-rose-500 dark:text-rose-400 hover:text-rose-600 dark:hover:text-rose-300 hover:bg-rose-50/50 dark:hover:bg-slate-800/50 flex items-center gap-2 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Keluar (Log Out)') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out border border-transparent">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile Menu Expanded) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 dark:bg-[#0F172A]/95 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 shadow-lg absolute w-full left-0 z-40 transition-all duration-300">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 rounded-lg text-sm font-bold transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Dashboard
            </a>
            <a href="{{ route('asesmen.index') }}" class="block px-4 py-2.5 rounded-lg text-sm font-bold transition-colors {{ request()->routeIs('asesmen.*') ? 'bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Data Asesmen
            </a>

            @can('is-admin')
            <a href="{{ route('users.index') }}" class="block px-4 py-2.5 rounded-lg text-sm font-bold transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Manajemen Pengguna
            </a>
            @endcan
        </div>

        <div class="pt-4 pb-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-[#0B1120]/50">
            <div class="px-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 text-white flex items-center justify-center text-base font-bold shadow-md">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-black text-sm text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-[11px] text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-1 px-4">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ __('Profile Saya') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-rose-500 dark:text-rose-400 hover:bg-white dark:hover:bg-slate-800 hover:text-rose-600 dark:hover:text-rose-300 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        {{ __('Keluar (Log Out)') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
