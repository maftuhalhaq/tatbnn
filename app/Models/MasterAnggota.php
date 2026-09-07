<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAnggota extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi Many-to-Many ke tabel asesmens
    public function asesmens()
    {
        return $this->belongsToMany(Asesmen::class, 'asesmen_anggota', 'master_anggota_id', 'asesmen_id');
    }
}