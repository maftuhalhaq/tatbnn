<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan Model User dipanggil

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun Admin otomatis
        User::factory()->create([
            'name' => 'Admin BNN',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'), // Password untuk login nantinya adalah: password
        ]);

        // 2. Memanggil seeder master data Anda agar tabel referensi ikut terisi
        $this->call([
            MasterDataSeeder::class,
        ]);
    }
}
