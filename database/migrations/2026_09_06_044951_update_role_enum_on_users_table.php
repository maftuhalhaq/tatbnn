<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Menambahkan 'penginput_data' ke dalam opsi ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'penginput_data', 'pengedit_ba', 'pengedit_rekom', 'read_only') DEFAULT 'read_only'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengedit_ba', 'pengedit_rekom', 'read_only') DEFAULT 'read_only'");
    }
};