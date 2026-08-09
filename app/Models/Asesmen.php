<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asesmen extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara massal kecuali kolom 'id'
    protected $guarded = ['id'];

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
        // Ambil semua kolom yang ada di tabel asesmens untuk data ini
        $attributes = $this->getAttributes();

        // Daftar kolom sistem yang tidak perlu dicek kelengkapannya
        $exclude = ['id', 'created_at', 'updated_at', 'created_by'];

        foreach ($attributes as $key => $value) {
            if (!in_array($key, $exclude)) {
                // Jika ditemukan satu saja nilai yang NULL atau string kosong ('')
                // (Angka 0 tetap akan terhitung sebagai data yang sudah diisi)
                if (is_null($value) || trim($value) === '') {
                    return 'Data Belum Lengkap';
                }
            }
        }

        // Jika seluruh loop selesai dan tidak ada yang kosong
        return 'Data Lengkap';
    }
}
