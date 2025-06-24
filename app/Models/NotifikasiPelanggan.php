<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class NotifikasiPelanggan extends Model
{
    protected $table = 'notifikasi_pelanggan';

    protected $fillable = [
        'pelanggan_id',
        'judul',
        'pesan',
        'kode_booking',
        'status',
        'kategori',
        'tipe',
        'is_read',
    ];

    public static function tambahNotifBaru(array $data)
    {
        Log::info('[NotifPel] tambahNotifBaru dipanggil', $data);

        return self::updateOrCreate(
            [
                'pelanggan_id' => $data['pelanggan_id'],
                'kode_booking' => $data['kode_booking'],
                'kategori'     => $data['kategori'],
                'tipe'         => $data['tipe'],
            ],
            [
                'judul'    => $data['judul'],
                'pesan'    => $data['pesan'],
                'status'   => $data['status']   ?? null,
                'is_read'  => false,
            ]
        );
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
