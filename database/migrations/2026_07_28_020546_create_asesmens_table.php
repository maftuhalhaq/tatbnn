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
        Schema::create('asesmens', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Pakai UUID agar lebih aman untuk URL

            // --- BLOK DATA SURAT & REGISTRASI ---
            $table->string('no_register')->nullable();
            $table->string('no_bln')->nullable();
            $table->string('asal_pengajuan')->nullable();
            $table->string('no_surat_pengajuan')->nullable();
            $table->string('no_lkn')->nullable();
            $table->date('tgl_surat')->nullable();
            $table->date('tgl_berkas')->nullable(); // Sesuai "Tanggal Berkas Diterima"
            $table->date('tgl_pelaksanaan')->nullable();
            $table->date('tgl_tangkap')->nullable();

            // --- BLOK IDENTITAS KLIEN ---
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('kewarganegaraan')->nullable(); // Kolom yang ditambahkan sebelumnya
            $table->string('agama')->nullable(); // Dibutuhkan untuk export
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 20)->nullable();

            // Relasi ke Master Data Pendidikan & Pekerjaan
            $table->foreignId('pendidikan_id')->nullable()->constrained('m_pendidikan');
            $table->foreignId('pekerjaan_id')->nullable()->constrained('m_pekerjaan');
            $table->string('penghasilan_rata_rata')->nullable();

            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();

            // --- BLOK KASUS NARKOTIKA (REKAP TAT) ---
            $table->foreignId('narkotika_id')->nullable()->constrained('m_narkotika');
            $table->decimal('berat_bb', 10, 2)->nullable();
            $table->text('deskripsi_bb')->nullable(); // Tambahan untuk "Barang Bukti (Deskripsi)"
            $table->string('pasal_sangkaan')->nullable();

            $table->string('status_hukum')->nullable();
            $table->string('keterlibatan_jaringan')->nullable();
            $table->string('cara_mendapatkan')->nullable();
            $table->string('dapat_dari_siapa')->nullable(); // Disamakan dengan export (sebelumnya dapat_dari)

            $table->string('tes_urine')->nullable();

            // Data Hasil Asesmen Awal (Rekap TAT)
            $table->text('hasil_asesmen_hukum')->nullable();
            $table->text('hasil_asesmen_medis')->nullable();
            $table->foreignId('rekomendasi_id')->nullable()->constrained('m_rekomendasi');
            $table->enum('pelaksanaan', ['YA', 'TIDAK'])->default('TIDAK');
            $table->text('keterangan_tambahan')->nullable(); // Sebelumnya keterangan

            // --- BLOK HASIL CASE CONFERENCE ---
            // Aspek ini dipisah dari hasil_asesmen awal karena Case Conference punya analisis sendiri (di Excel ada merge cell)
            $table->text('aspek_hukum')->nullable();
            $table->text('aspek_medis')->nullable();
            $table->text('kesehatan_fisik')->nullable(); // Disamakan dengan export (sebelumnya kesehatan)
            $table->text('psikologi')->nullable();
            $table->text('alasan_penggunaan')->nullable();
            $table->text('kondisi_keluarga')->nullable();
            $table->string('tingkat_ketergantungan')->nullable();
            $table->string('pola_pemakaian')->nullable();
            $table->text('kondisi_lingkungan')->nullable();
            $table->text('saran_case_conference')->nullable(); // Disamakan dengan export (sebelumnya saran)

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asesmens');
    }
};