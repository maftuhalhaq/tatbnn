<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role pengguna, default kita buat read_only
            $table->enum('role', ['admin', 'pengedit_ba', 'pengedit_rekom', 'read_only'])->default('read_only');
            // Status persetujuan, default false (belum disetujui)
            $table->boolean('is_approved')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_approved']);
        });
    }
};