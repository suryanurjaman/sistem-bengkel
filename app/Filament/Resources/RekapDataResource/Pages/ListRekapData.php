<?php

namespace App\Filament\Resources\RekapDataResource\Pages;

use App\Filament\Resources\RekapDataResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListRekapData extends ListRecords
{
    protected static string $resource = RekapDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->url(function () {
                    $query = request()->query();
                    return route('rekap.export.pdf', $query);
                })
                ->openUrlInNewTab(),
        ];
    }
}
