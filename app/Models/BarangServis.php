<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangServis extends Model
{
    use HasFactory;

    protected $table = 'barang_servis';

    protected $fillable = ['layanan_id', 'nama_barang', 'harga'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function pemesananServis()
    {
        return $this->belongsToMany(
            PemesananServis::class,
            'barang_pemesanan',
            'barang_servis_id',
            'pemesanan_servis_id'
        );
    }
}
