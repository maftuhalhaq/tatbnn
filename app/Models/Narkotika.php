<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narkotika extends Model
{
    use HasFactory;

    protected $table = 'm_narkotika';

    // SESUAIKAN: Ubah dari 'nama_narkotika' menjadi 'jenis_narkotika'
    protected $fillable = ['jenis_narkotika'];

    public function asesmens()
    {
        return $this->hasMany(Asesmen::class, 'narkotika_id');
    }
}