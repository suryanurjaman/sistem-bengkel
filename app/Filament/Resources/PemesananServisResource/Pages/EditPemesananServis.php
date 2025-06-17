<?php

namespace App\Filament\Resources\PemesananServisResource\Pages;

use App\Filament\Resources\PemesananServisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemesananServis extends EditRecord
{
    protected static string $resource = PemesananServisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
