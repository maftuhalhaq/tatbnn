<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pendidikan');
            $table->timestamps();
        });

        Schema::create('m_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pekerjaan');
            $table->timestamps();
        });

        Schema::create('m_narkotika', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_narkotika');
            $table->timestamps();
        });

        Schema::create('m_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->string('tempat_rehabilitasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_rekomendasi');
        Schema::dropIfExists('m_narkotika');
        Schema::dropIfExists('m_pekerjaan');
        Schema::dropIfExists('m_pendidikan');
    }
};