<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asesmen extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara massal kecuali kolom 'id'
    // Otomatis mengizinkan penyimpanan 'foto_klien'
    protected $guarded = ['id'];

    // Menentukan nama tabel secara eksplisit
    protected $table = 'asesmens';

    // Konfigurasi Primary Key UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Event Booting: Otomatis generate UUID saat data baru dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // --- Relasi BelongsTo ke Tabel Master ---
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id');
    }

    public function narkotika()
    {
        return $this->belongsTo(Narkotika::class, 'narkotika_id');
    }

    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class, 'rekomendasi_id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function getStatusKelengkapanAttribute()
    {
        // 1. Cek Field Utama dari halaman Create/Edit
        $kolomUtama = [
            'nama_lengkap',
            'nik',
            'no_register',
            'no_bln',
            'asal_pengajuan',
            'no_surat_pengajuan',
            'no_lkn',
            'tgl_surat',
            'tgl_berkas',
            'tgl_pelaksanaan',
            'tgl_tangkap',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'kewarganegaraan',
            'agama',
            'alamat_ktp',
            'alamat_domisili',
            'berat_bb',
            'pasal_sangkaan',
            'deskripsi_bb',
            'status_hukum',
            'keterlibatan_jaringan',
            'cara_mendapatkan',
            'dapat_dari_siapa',
            'kesehatan_fisik',
            'psikologi',
            'tes_urine',
            'alasan_penggunaan',
            'kondisi_keluarga',
            'tingkat_ketergantungan',
            'pola_pemakaian',
            'kondisi_lingkungan',
            'saran_case_conference',
            'hasil_asesmen_hukum',
            'hasil_asesmen_medis'
        ];

        foreach ($kolomUtama as $kol) {
            if (is_null($this->$kol) || trim((string) $this->$kol) === '') {
                return 'BELUM LENGKAP';
            }
        }

        // Cek Dropdown Narkotika
        if (empty($this->narkotika_id) && empty($this->narkotika_input))
            return 'BELUM LENGKAP';

        // 2. Cek Berita Acara (Minimal ada No BA & Ketua TAT)
        if (empty($this->no_ba) || empty($this->ketua_tat_nama)) {
            return 'BELUM LENGKAP';
        }

        // 3. Cek Surat Rekomendasi (Minimal ada No Surat & Kepada Yth)
        if (empty($this->no_surat_rekomendasi) || empty($this->kepada_yth)) {
            return 'BELUM LENGKAP';
        }

        return 'DATA LENGKAP';
    }

    // Tambahan fungsi boolean (true/false) agar lebih mudah dipanggil di Blade (HTML)
    public function getIsLengkapAttribute()
    {
        return $this->status_kelengkapan === 'DATA LENGKAP';
    }

    // --- Relasi Many-to-Many ke Tim Medis & Tim Hukum ---
    public function anggotaTim()
    {
        return $this->belongsToMany(MasterAnggota::class, 'asesmen_anggota', 'asesmen_id', 'master_anggota_id');
    }
}