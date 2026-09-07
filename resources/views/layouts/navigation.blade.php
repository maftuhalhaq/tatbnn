<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Kiri: Logo & Menu Utama -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('asesmen.index') }}" class="flex items-center gap-2 group">
                        <!-- Jika Anda punya logo gambar, taruh di dalam komponen ini -->
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-extrabold text-sm shadow-md group-hover:scale-105 transition-transform">
                            BNN
                        </div>
                        <span class="font-extrabold text-lg text-slate-900 tracking-tight hidden sm:block">Sistem<span class="text-indigo-600">TAT</span></span>
                    </a>
                </div>

                <!-- Navigation Links (Desain Pill/Tombol Modern) -->
                <div class="hidden sm:flex items-center space-x-2 mt-1">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900 font-bold shadow-sm border border-slate-200/60' : 'text-slate-500 font-medium hover:bg-slate-50 hover:text-slate-800' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('asesmen.index') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('asesmen.*') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-sm border border-indigo-100/60' : 'text-slate-500 font-medium hover:bg-slate-50 hover:text-slate-800' }}">
                        Data Asesmen
                    </a>

                    <!-- MENU MANAJEMEN PENGGUNA (KHUSUS ADMIN) -->
                    @can('is-admin')
                    <a href="{{ route('users.index') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-sm border border-indigo-100/60' : 'text-slate-500 font-medium hover:bg-slate-50 hover:text-slate-800' }}">
                        Manajemen Pengguna
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Kanan: Profil & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 p-1.5 pr-3 rounded-full bg-white border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100 shadow-sm">
                            
                            <!-- Avatar Inisial User -->
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-inner">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            
                            <!-- Nama Panggilan (Kata Pertama) -->
                            <span class="text-sm font-bold text-slate-700">
                                {{ explode(' ', Auth::user()->name)[0] }}
                            </span>

                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm text-slate-500 leading-none">Login sebagai</p>
                            <p class="text-sm font-bold text-slate-900 mt-1 truncate">{{ Auth::user()->email }}</p>
                            
                            <!-- Tampilkan Label Role di Dropdown -->
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                {{ Auth::user()->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </span>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Profile Saya') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-sm font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:bg-indigo-50 focus:text-indigo-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-100 shadow-lg absolute w-full left-0 z-40">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }}">
                Dashboard
            </a>
            <a href="{{ route('asesmen.index') }}" class="block px-4 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('asesmen.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                Data Asesmen
            </a>
            
            <!-- MENU MANAJEMEN PENGGUNA MOBILE (KHUSUS ADMIN) -->
            @can('is-admin')
            <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                Manajemen Pengguna
            </a>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-slate-100 bg-slate-50">
            <div class="px-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center text-base font-bold shadow-md">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-base text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-slate-500">{{ Auth::user()->email }}</div>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border border-slate-200 text-slate-600 bg-white">
                        Role: {{ str_replace('_', ' ', Auth::user()->role) }}
                    </span>
                </div>
            </div>

            <div class="mt-4 space-y-1 px-4">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-medium text-slate-700 hover:bg-white rounded-lg transition-colors">
                    {{ __('Profile Saya') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm font-medium text-rose-600 hover:bg-white hover:text-rose-700 rounded-lg transition-colors">
                        {{ __('Keluar (Log Out)') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>