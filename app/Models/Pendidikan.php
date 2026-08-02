<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    // Beri tahu Laravel nama tabel spesifiknya
    protected $table = 'm_pendidikan';

    // Kolom apa saja yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = ['nama_pendidikan'];

    // Relasi: Satu Pendidikan bisa dimiliki oleh banyak Asesmen (1 to N)
    public function asesmens()
    {
        return $this->hasMany(Asesmen::class, 'pendidikan_id');
    }

}