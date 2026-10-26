<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            // Menambahkan 4 kolom saran yang boleh kosong (nullable)
            $table->text('saran_1')->nullable();
            $table->text('saran_2')->nullable();
            $table->text('saran_3')->nullable();
            $table->text('saran_4')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            $table->dropColumn(['saran_1', 'saran_2', 'saran_3', 'saran_4']);
        });
    }
};
