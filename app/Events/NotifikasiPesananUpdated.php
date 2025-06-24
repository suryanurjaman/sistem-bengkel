<?php

namespace App\Events;

use App\Models\PemesananServis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NotifikasiPesananUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $pesanan;

    public function __construct(PemesananServis $pesanan)
    {
        $this->pesanan = $pesanan->load('statusServis');
    }

    public function broadcastOn()
    {
        return new Channel('pelanggan.' . $this->pesanan->pelanggan_id);
    }

    public function broadcastWith()
    {
        return [
            'kode_booking' => $this->pesanan->kode_booking,
            'status' => $this->pesanan->statusServis->nama_status ?? '-',
            'tanggal' => $this->pesanan->tanggal_dipesan->format('d M Y'),
            'keterangan_admin' => $this->pesanan->keterangan_admin,
            'catatan_mekanik' => $this->pesanan->catatan_mekanik,
        ];
    }
}
