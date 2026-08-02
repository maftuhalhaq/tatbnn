<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $table = 'm_pekerjaan';
    protected $fillable = ['nama_pekerjaan'];

    public function asesmens()
    {
        return $this->hasMany(Asesmen::class, 'pekerjaan_id');
    }
}