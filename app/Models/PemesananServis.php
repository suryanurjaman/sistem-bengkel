<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananServis extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_servis';

    protected $fillable = [
        'pelanggan_id',
        'plat_nomor',
        'jenis_motor',
        'alamat',
        'tanggal_servis',
        'status_id',
        'keterangan',
        'keterangan_admin',
        'catatan_mekanik',
        'total_harga_min',
        'total_harga_max',
        'total_harga',
        'is_hidden',
        'tanggal_dipesan',
        'kode_booking',
    ];

    protected $casts = [
        'tanggal_dipesan' => 'datetime',
        'tanggal_servis'  => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (PemesananServis $m) {
            // generate kode_booking
            $today = now()->format('Ymd');
            $last = PemesananServis::whereDate('created_at', now())
                ->latest('id')
                ->value('kode_booking');
            $next = $last
                ? intval(last(explode('-', $last))) + 1
                : 1;
            $m->kode_booking    = 'SV-' . $today . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            $m->tanggal_dipesan = now();
        });
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layanans()
    {
        return $this->belongsToMany(
            Layanan::class,
            'layanan_pemesanan',
            'pemesanan_servis_id',
            'layanan_id'
        );
    }

    public function barangServis()
    {
        return $this->belongsToMany(
            BarangServis::class,
            'barang_pemesanan',
            'pemesanan_servis_id',
            'barang_servis_id'
        );
    }

    public function statusServis()
    {
        return $this->belongsTo(StatusServis::class, 'status_id');
    }
}
