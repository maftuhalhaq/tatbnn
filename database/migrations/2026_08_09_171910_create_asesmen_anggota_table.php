<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asesmen_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('asesmen_id')->constrained('asesmens')->cascadeOnDelete();
            $table->foreignId('master_anggota_id')->constrained('master_anggotas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asesmen_anggota');
    }
};