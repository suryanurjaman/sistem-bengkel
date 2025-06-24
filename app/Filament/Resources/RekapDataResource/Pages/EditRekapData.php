<?php

namespace App\Filament\Resources\RekapDataResource\Pages;

use App\Filament\Resources\RekapDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapData extends EditRecord
{
    protected static string $resource = RekapDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
