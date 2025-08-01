<?php

namespace App\Filament\Resources\RekapDataResource\Pages;

use App\Filament\Resources\RekapDataResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

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
                    // Ambil semua filter aktif dari tabel
                    $filters = $this->getTable()->getFilters();

                    // Buat array query dengan format 'tableFilters[nama_filter]=nilai'
                    $query = [];
                    foreach ($filters as $filter) {
                        $query["tableFilters[{$filter->getName()}]"] = $filter->getState();
                    }

                    // Gabungkan query string dengan route
                    return route('rekap.export.pdf', $query);
                })
                ->openUrlInNewTab(),
        ];
    }
}
