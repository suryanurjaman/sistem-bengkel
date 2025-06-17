<?php

namespace App\Filament\Resources\PemesananServisResource\Pages;

use App\Filament\Resources\PemesananServisResource;
use Filament\Actions;
use App\Models\Layanan;
use Filament\Resources\Pages\CreateRecord;

class CreatePemesananServis extends CreateRecord
{
    protected static string $resource = PemesananServisResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_harga_min'] = Layanan::whereIn('id', $data['layanans'])->sum('harga_min');
        $data['total_harga_max'] = Layanan::whereIn('id', $data['layanans'])->sum('harga_max');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_harga_min'] = Layanan::whereIn('id', $data['layanans'])->sum('harga_min');
        $data['total_harga_max'] = Layanan::whereIn('id', $data['layanans'])->sum('harga_max');

        return $data;
    }
}
