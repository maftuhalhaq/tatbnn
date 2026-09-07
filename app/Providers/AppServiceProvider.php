<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Admin: HANYA role admin yang bisa kelola & setujui User baru
        Gate::define('is-admin', function ($user) {
            return $user->role === 'admin';
        });

        // 2. Manage Data: Bisa Input, Edit, Hapus, Import, Export
        // (Admin, Penginput Data, Pengedit BA, dan Pengedit Rekom bisa melakukan ini)
        Gate::define('manage-data', function ($user) {
            return in_array($user->role, ['admin', 'penginput_data', 'pengedit_ba', 'pengedit_rekom']);
        });

        // 3. Akses Dokumen: Bisa Membuat Surat BA dan Surat Rekomendasi
        // (Admin, Pengedit BA, dan Pengedit Rekom bisa melakukan ini. Penginput Data TIDAK BISA)
        Gate::define('akses-dokumen', function ($user) {
            return in_array($user->role, ['admin', 'pengedit_ba', 'pengedit_rekom']);
        });

        // 4. Read Only: Bisa melihat tabel, detail, detail case conference, dan PDF ringkasan
        // (Semua role yang valid memiliki akses ini)
        Gate::define('read-only', function ($user) {
            return in_array($user->role, ['admin', 'penginput_data', 'pengedit_ba', 'pengedit_rekom', 'read_only']);
        });
    }
}