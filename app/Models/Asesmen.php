<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Asesmen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asesmens';

    protected $fillable = [
        'nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir',
        'usia', 
        'kewarganegaraan', 'agama', 'pendidikan_id', 'pekerjaan_id',
        'rekomendasi_id', 'narkotika_id', 'alamat_ktp', 'alamat_domisili',
        'no_register', 'no_bln', 'asal_pengajuan', 'no_surat_pengajuan',
        'no_lkn', 'tgl_surat', 'tgl_berkas', 'tgl_pelaksanaan', 'tgl_tangkap',
        'berat_bb', 'pasal_sangkaan', 'deskripsi_bb', 'hasil_asesmen_hukum',
        'hasil_asesmen_medis', 'pelaksanaan', 'penghasilan_rata_rata',
        'status_hukum', 'keterlibatan_jaringan', 'cara_mendapatkan',
        'dapat_dari_siapa', 'kesehatan_fisik', 'psikologi', 'tes_urine',
        'alasan_penggunaan', 'kondisi_keluarga', 'tingkat_ketergantungan',
        'pola_pemakaian', 'kondisi_lingkungan', 'keterangan_tambahan',
        'saran_1', 'saran_2', 'saran_3', 'saran_4', 'aspek_hukum', 'aspek_medis'
    ];

    // Mengizinkan semua kolom diisi secara massal kecuali 'id'
    //

    // =========================================================================
    // RELASI DATABASE
    // =========================================================================

    /**
     * Relasi ke tabel Master Pendidikan
     */
    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    /**
     * Relasi ke tabel Master Pekerjaan
     */
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id');
    }

    /**
     * Relasi ke tabel Master Narkotika
     */
    public function narkotika()
    {
        return $this->belongsTo(Narkotika::class, 'narkotika_id');
    }

    /**
     * Relasi ke tabel Master Rekomendasi
     */
    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class, 'rekomendasi_id');
    }

    /**
     * Relasi pivot (many-to-many) ke tabel Master Anggota (Tim TAT)
     */
    /**
     * Relasi pivot (many-to-many) ke tabel Master Anggota (Tim TAT)
     */
    public function anggotaTim()
    {
        // Ubah 'anggota_id' menjadi 'master_anggota_id'
        return $this->belongsToMany(MasterAnggota::class, 'asesmen_anggota', 'asesmen_id', 'master_anggota_id');
    }

    public function anggota()
    {
        // Ubah 'anggota_id' menjadi 'master_anggota_id'
        return $this->belongsToMany(MasterAnggota::class, 'asesmen_anggota', 'asesmen_id', 'master_anggota_id');
    }
}
