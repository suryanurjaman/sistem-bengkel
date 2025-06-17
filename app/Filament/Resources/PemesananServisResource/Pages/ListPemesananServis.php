<?php

namespace App\Filament\Resources\PemesananServisResource\Pages;

use App\Filament\Resources\PemesananServisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemesananServis extends ListRecords
{
    protected static string $resource = PemesananServisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
