<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'estimasi_waktu',
        'harga_min',
        'harga_max',
        'kategori_id',
        'gambar',
        'harga_jasa',
    ];

    /**
     * Relasi: satu layanan milik satu kategori
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_id');
    }

    /**
     * Relasi: satu layanan dapat dipilih oleh banyak pemesanan
     */
    public function pemesananServices()
    {
        return $this->belongsToMany(PemesananServis::class, 'layanan_pemesanan');
    }

    public function barangServis()
    {
        return $this->hasMany(BarangServis::class);
    }
}
