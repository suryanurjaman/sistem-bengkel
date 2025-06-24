<?php

namespace App\Filament\Resources\PemesananServisResource\Pages;

use App\Filament\Resources\PemesananServisResource;
use App\Models\BarangServis;
use App\Models\Layanan;
use Filament\Resources\Pages\EditRecord;
use App\Events\NotifikasiPesananUpdated;
use App\Models\NotifikasiPelanggan;
use App\Models\PemesananServis;
use Illuminate\Support\Facades\Log;


class EditPemesananServis extends EditRecord
{
    protected static string $resource = PemesananServisResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['layanans'] = $this->record->layanans()->pluck('layanan_id')->toArray();

        $pivotBarang = $this->record->barangServis()->pluck('barang_servis_id')->toArray();

        $barangPerLayanan = [];
        foreach ($this->record->layanans as $layanan) {
            $barangId = BarangServis::where('layanan_id', $layanan->id)
                ->whereIn('id', $pivotBarang)
                ->value('id');
            if ($barangId) {
                $barangPerLayanan[$layanan->id] = $barangId;
            }
        }

        $data['barang_per_layanan'] = $barangPerLayanan;

        return $data;
    }

    protected function beforeSave(): void
    {
        $this->data['total_harga'] = $this->hitungTotalHarga();
    }

    protected function afterSave(): void
    {
        // Sync layanan & barang
        $this->record->layanans()->sync($this->data['layanans'] ?? []);
        $this->record->barangServis()->sync(array_values($this->data['barang_per_layanan'] ?? []));

        // Ambil ulang dengan relasi
        $pesanan = PemesananServis::with('statusServis')->find($this->record->id);

        // 1) Status berubah?
        if ($this->record->wasChanged('status_id')) {
            Log::info('[EditPel] Status changed, kirim notif');
            NotifikasiPelanggan::tambahNotifBaru([
                'pelanggan_id' => $pesanan->pelanggan_id,
                'judul'        => 'Status Servis Diperbarui',
                'pesan'        => 'Status Anda: ' . ($pesanan->statusServis->nama_status ?? '-'),
                'kode_booking' => $pesanan->kode_booking,
                'status'       => $pesanan->statusServis->nama_status ?? '-',
                'kategori'     => 'status',
                'tipe'         => 'status',
            ]);
        }

        // 2) Catatan Admin berubah?
        if ($this->record->wasChanged('keterangan_admin') && $pesanan->keterangan_admin) {
            Log::info('[EditPel] Catatan admin changed, kirim notif');
            NotifikasiPelanggan::tambahNotifBaru([
                'pelanggan_id' => $pesanan->pelanggan_id,
                'judul'        => 'Catatan dari Admin',
                'pesan'        => $pesanan->keterangan_admin,
                'kode_booking' => $pesanan->kode_booking,
                'status'       => $pesanan->statusServis->nama_status ?? '-',
                'kategori'     => 'admin',
                'tipe'         => 'catatan',
            ]);
        }

        // 3) Catatan Mekanik berubah?
        if ($this->record->wasChanged('catatan_mekanik') && $pesanan->catatan_mekanik) {
            Log::info('[EditPel] Catatan mekanik changed, kirim notif');
            NotifikasiPelanggan::tambahNotifBaru([
                'pelanggan_id' => $pesanan->pelanggan_id,
                'judul'        => 'Catatan dari Mekanik',
                'pesan'        => $pesanan->catatan_mekanik,
                'kode_booking' => $pesanan->kode_booking,
                'status'       => $pesanan->statusServis->nama_status ?? '-',
                'kategori'     => 'mekanik',
                'tipe'         => 'catatan',
            ]);
        }

        // Broadcast realtime
        event(new NotifikasiPesananUpdated($pesanan));
    }



    private function hitungTotalHarga(): int
    {
        $layanans = Layanan::whereIn('id', $this->data['layanans'] ?? [])->get();
        $barangIds = collect($this->data['barang_per_layanan'] ?? [])->flatten()->filter()->unique();
        $barangList = BarangServis::whereIn('id', $barangIds)->get();

        return $layanans->sum('harga_jasa') + $barangList->sum('harga');
    }
}
