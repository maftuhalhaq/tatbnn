<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            $table->string('no_surat_rekomendasi')->nullable();
            $table->date('tgl_rekomendasi')->nullable();
            $table->string('kepada_yth')->nullable();
            $table->string('no_keputusan')->nullable();
            $table->date('tgl_keputusan')->nullable();
            $table->string('tentang_permohonan')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('nama_narkotika_medis')->nullable();
            $table->string('lama_perawatan')->nullable();
            $table->text('keterangan_diagnosis')->nullable();
        });
    }

    public function down(): void
    {
        // ... (Kode rollback opsional)
    }
};
