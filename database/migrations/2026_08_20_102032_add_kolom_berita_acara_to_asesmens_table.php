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
            // Cek dan tambah hanya jika kolom BELUM ada di database

            if (!Schema::hasColumn('asesmens', 'no_ba')) { $table->string('no_ba')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'tgl_ba')) { $table->date('tgl_ba')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'ketua_tat_nama')) { $table->string('ketua_tat_nama')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'ketua_tat_nrp')) { $table->string('ketua_tat_nrp')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'no_kep_tim')) { $table->string('no_kep_tim')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'tgl_kep_tim')) { $table->date('tgl_kep_tim')->nullable(); }

            if (!Schema::hasColumn('asesmens', 'alat_bukti_no_sk')) { $table->string('alat_bukti_no_sk')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'alat_bukti_tgl_sk')) { $table->date('alat_bukti_tgl_sk')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'alat_bukti_dokter')) { $table->string('alat_bukti_dokter')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'alat_bukti_hasil')) { $table->string('alat_bukti_hasil')->nullable(); }

            // Kolom yang dipastikan hilang pada error sebelumnya
            if (!Schema::hasColumn('asesmens', 'status_klien')) { $table->string('status_klien')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'kesimpulan_jenis_zat')) { $table->string('kesimpulan_jenis_zat')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'kesimpulan_pola_pakai')) { $table->string('kesimpulan_pola_pakai')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'kesimpulan_kategori')) { $table->string('kesimpulan_kategori')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'diagnosis_medis')) { $table->string('diagnosis_medis')->nullable(); }

            if (!Schema::hasColumn('asesmens', 'rekomendasi_tempat_rehab')) { $table->string('rekomendasi_tempat_rehab')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'rekomendasi_durasi')) { $table->string('rekomendasi_durasi')->nullable(); }
            if (!Schema::hasColumn('asesmens', 'rekomendasi_keterangan')) { $table->text('rekomendasi_keterangan')->nullable(); }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dikosongkan agar aman jika di-rollback
    }
};
