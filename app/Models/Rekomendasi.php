<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'm_rekomendasi';
    protected $fillable = ['tempat_rehabilitasi'];

    public function asesmens()
    {
        return $this->hasMany(Asesmen::class, 'rekomendasi_id');
    }
}