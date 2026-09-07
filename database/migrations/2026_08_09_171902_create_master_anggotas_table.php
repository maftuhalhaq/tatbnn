<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip_nrp_sip')->nullable(); // NIP untuk dokter/jaksa, NRP untuk polisi, SIP untuk praktik
            $table->string('pangkat')->nullable(); // Penata / IIIc, Ipda, Jaksa Muda
            $table->string('jabatan')->nullable();
            $table->enum('kategori', ['medis', 'hukum']); // Membedakan Tim Medis dan Tim Hukum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_anggotas');
    }
};