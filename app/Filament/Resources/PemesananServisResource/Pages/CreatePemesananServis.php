<?php

namespace App\Filament\Resources\PemesananServisResource\Pages;

use App\Filament\Resources\PemesananServisResource;
use App\Models\BarangServis;
use App\Models\Layanan;
use Filament\Resources\Pages\CreateRecord;

class CreatePemesananServis extends CreateRecord
{
    protected static string $resource = PemesananServisResource::class;

    protected function beforeCreate(): void
    {
        $this->data['total_harga'] = $this->hitungTotalHarga();
    }

    protected function afterCreate(): void
    {
        $layanans = $this->data['layanans'] ?? [];
        $barangMap = $this->data['barang_per_layanan'] ?? [];

        $this->record->layanans()->sync($layanans);
        $this->record->barangServis()->sync(array_values($barangMap));
    }

    private function hitungTotalHarga(): int
    {
        $layanans = Layanan::whereIn('id', $this->data['layanans'] ?? [])->get();
        $barangIds = collect($this->data['barang_per_layanan'] ?? [])->flatten()->filter()->unique();
        $barangList = BarangServis::whereIn('id', $barangIds)->get();

        return $layanans->sum('harga_jasa') + $barangList->sum('harga');
    }
}
