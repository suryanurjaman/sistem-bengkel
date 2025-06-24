<?php

namespace App\Filament\Resources\KontakMasukResource\Pages;

use App\Filament\Resources\KontakMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKontakMasuk extends EditRecord
{
    protected static string $resource = KontakMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
