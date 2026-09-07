<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_opsis', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // Akan berisi: 'zat' atau 'tempat_rehab'
            $table->string('nilai'); // Berisi nama zat atau nama tempat rehab
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_opsis');
    }
};