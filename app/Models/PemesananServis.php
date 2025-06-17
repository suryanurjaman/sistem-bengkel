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
        'kode_booking',
        'tanggal_dipesan',
        'tanggal_servis',
        'status_id',
        'keterangan',
        'keterangan_admin',
        'total_harga_min',
        'total_harga_max',
        'total_harga',
    ];

    protected $casts = [
        'tanggal_dipesan' => 'datetime',
        'tanggal_servis' => 'date',
    ];


    protected static function booted()
    {
        static::creating(function ($model) {
            // 1. Generate kode_booking:
            $today   = now()->format('Ymd'); // misal 20250603
            $lastNomor = PemesananServis::whereDate('created_at', now()->toDateString())
                ->latest('id')
                ->value('kode_booking');
            if (! $lastNomor) {
                $urut = 1;
            } else {
                // asumsi format "SV-20250603-0001"
                $parts = explode('-', $lastNomor);
                $urut  = intval(last($parts)) + 1;
            }
            $model->kode_booking = 'SV-' . $today . '-' . str_pad($urut, 4, '0', STR_PAD_LEFT);
            // 2. Set tanggal_dipesan
            $model->tanggal_dipesan = now();
        });
    }


    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layanans()
    {
        return $this->belongsToMany(Layanan::class, 'layanan_pemesanan');
    }


    public function statusServis()
    {
        return $this->belongsTo(StatusServis::class, 'status_id');
    }

    public function getRangeHargaAttribute(): string
    {
        $min = $this->layanans->sum('harga_min');
        $max = $this->layanans->sum('harga_max');

        return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
    }
}
