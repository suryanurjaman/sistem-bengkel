<?php

namespace App\Filament\Resources\BarangServisResource\Pages;

use App\Filament\Resources\BarangServisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangServis extends EditRecord
{
    protected static string $resource = BarangServisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
