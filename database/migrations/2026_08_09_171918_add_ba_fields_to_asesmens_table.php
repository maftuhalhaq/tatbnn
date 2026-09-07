<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            // Header Berita Acara
            $table->string('no_ba')->nullable();
            $table->date('tgl_ba')->nullable();
            $table->string('ketua_tat_nama')->nullable();
            $table->string('ketua_tat_nrp')->nullable();
            $table->string('no_kep_tim')->nullable();
            $table->date('tgl_kep_tim')->nullable();

            // Narasi Hasil Pemeriksaan Panjang
            $table->longText('narasi_medis')->nullable();
            $table->longText('narasi_hukum')->nullable();

            // Alat Bukti (Surat Keterangan)
            $table->string('alat_bukti_no_sk')->nullable();
            $table->date('alat_bukti_tgl_sk')->nullable();
            $table->string('alat_bukti_dokter')->nullable();
            $table->string('alat_bukti_hasil')->nullable();

            // Kesimpulan & Rekomendasi Khusus BA
            $table->string('kesimpulan_jenis_zat')->nullable();
            $table->string('kesimpulan_pola_pakai')->nullable();
            $table->string('kesimpulan_kategori')->nullable();
            $table->string('rekomendasi_tempat_rehab')->nullable();
            $table->string('rekomendasi_durasi')->nullable();
            $table->text('rekomendasi_keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            $table->dropColumn([
                'no_ba',
                'tgl_ba',
                'ketua_tat_nama',
                'ketua_tat_nrp',
                'no_kep_tim',
                'tgl_kep_tim',
                'narasi_medis',
                'narasi_hukum',
                'alat_bukti_no_sk',
                'alat_bukti_tgl_sk',
                'alat_bukti_dokter',
                'alat_bukti_hasil',
                'kesimpulan_jenis_zat',
                'kesimpulan_pola_pakai',
                'kesimpulan_kategori',
                'rekomendasi_tempat_rehab',
                'rekomendasi_durasi',
                'rekomendasi_keterangan'
            ]);
        });
    }
};