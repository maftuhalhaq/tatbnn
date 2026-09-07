<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            // Menambahkan kolom-kolom untuk kebutuhan Surat Rekomendasi
            $table->string('no_surat_rekomendasi')->nullable();
            $table->date('tgl_rekomendasi')->nullable();
            $table->string('kepada_yth')->nullable();
            $table->string('no_keputusan')->nullable();
            $table->date('tgl_keputusan')->nullable();
            $table->string('tentang_permohonan')->nullable();
            $table->string('nama_narkotika_medis')->nullable();
            $table->string('lama_perawatan')->nullable();
            $table->text('keterangan_diagnosis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn([
                'no_surat_rekomendasi',
                'tgl_rekomendasi',
                'kepada_yth',
                'no_keputusan',
                'tgl_keputusan',
                'tentang_permohonan',
                'nama_narkotika_medis',
                'lama_perawatan',
                'keterangan_diagnosis'
            ]);
        });
    }
};
