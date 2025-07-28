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
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Notifications\Notification;


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
        $this->record->barangServis()->sync(
            collect($this->data['barang_per_layanan'] ?? [])
                ->filter(fn($id) => filled($id) && is_numeric($id))
                ->values()
                ->all()
        );


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

    public function getHeaderActions(): array
    {
        return [
            Action::make('batalkan')
                ->label('Batalkan Pemesanan')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('pesan')
                        ->label('Pesan ke Pelanggan')
                        ->default(
                            "Halo, kami dari *Bengkel Sahabat Motor Paijo* ingin menginformasikan bahwa pemesanan servis Anda dengan kode booking *{$this->record->kode_booking}* pada tanggal *" . $this->record->tanggal_servis->format('d M Y') . "* telah *dibatalkan*.\n\nAlasan pembatalan:\n- Mohon maaf, ... (isi alasan di sini)\n\nJika Anda ingin menjadwalkan ulang, silakan hubungi kami kembali melalui WhatsApp ini.\n\nTerima kasih atas pengertiannya 🙏"
                        )
                        ->rows(10)

                        ->required(),
                ])
                ->visible(fn() => $this->record->status_id !== 5)
                ->action(function (array $data) {
                    $record = $this->record;

                    // Ubah status jadi Dibatalkan (ID 5)
                    $dibatalkanId = 5;
                    $record->update([
                        'status_id'        => $dibatalkanId,
                        'keterangan_admin' => 'Dibatalkan: ' . $data['pesan'],
                    ]);

                    // Kirim notifikasi ke pelanggan
                    \App\Models\NotifikasiPelanggan::tambahNotifBaru([
                        'pelanggan_id' => $record->pelanggan_id,
                        'judul'        => 'Pemesanan Dibatalkan',
                        'pesan'        => $data['pesan'],
                        'kode_booking' => $record->kode_booking,
                        'status'       => 'Dibatalkan',
                        'kategori'     => 'admin',
                        'tipe'         => 'pembatalan',
                    ]);

                    // Arahkan ke WhatsApp
                    $noHp = preg_replace('/[^0-9]/', '', $record->pelanggan->no_hp);
                    if (str_starts_with($noHp, '0')) {
                        $noHp = '62' . substr($noHp, 1);
                    }
                    $pesan = urlencode($data['pesan']);
                    $waUrl = "https://wa.me/{$noHp}?text={$pesan}";

                    Notification::make()
                        ->title('Pemesanan dibatalkan')
                        ->success()
                        ->body('Pelanggan akan dihubungi via WhatsApp.')
                        ->send();

                    return redirect()->away($waUrl);
                }),
        ];
    }
}
