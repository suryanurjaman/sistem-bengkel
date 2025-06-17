<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLayanan extends Model
{
    use HasFactory;

    protected $table = 'kategori_layanan';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'kelebihan',
        'estimasi_waktu',
        'harga_min',
        'harga_max',
    ];

    protected $casts = [
        'kelebihan' => 'array', // karena json
    ];

    /**
     * Relasi: satu kategori bisa punya banyak layanan
     */
    public function layanans()
    {
        return $this->hasMany(Layanan::class, 'kategori_id');
    }
}
