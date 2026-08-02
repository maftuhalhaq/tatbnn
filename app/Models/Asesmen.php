<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asesmen extends Model
{
    use HasFactory;

    // Cukup gunakan ini agar semua kolom di tabel asesmens bisa diisi secara massal
    protected $guarded = ['id'];

    protected $table = 'asesmens';

    // Konfigurasi Primary Key UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Event Booting: Otomatis generate UUID
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
}
