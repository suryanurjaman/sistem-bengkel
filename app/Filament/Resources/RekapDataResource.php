<?php

namespace App\Filament\Resources;

use App\Models\PemesananServis;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\RekapDataResource\Pages;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;

class RekapDataResource extends Resource
{
    protected static ?string $model = PemesananServis::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Rekap Bengkel';
    protected static ?string $slug = 'rekap-bengkel';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_booking')->label('Kode Booking'),
                Tables\Columns\TextColumn::make('pelanggan.nama_lengkap')->label('Nama Pelanggan'),
                Tables\Columns\TextColumn::make('jenis_motor')->label('Jenis Motor'),
                Tables\Columns\TextColumn::make('tanggal_servis')->label('Tanggal Servis'),
                Tables\Columns\TextColumn::make('statusServis.nama_status')->label('Status'),
                Tables\Columns\TextColumn::make('total_harga')->label('Total Harga')->money('IDR'),
                Tables\Columns\TextColumn::make('layanans')
                    ->label('Layanan')
                    ->html()
                    ->formatStateUsing(fn($record) => $record->layanans->map(function ($layanan) {
                        return "- {$layanan->nama_layanan} (Rp" . number_format($layanan->harga_jasa ?? 0, 0, ',', '.') . ")";
                    })->implode('<br>')),

                Tables\Columns\TextColumn::make('barangServis')
                    ->label('Barang')
                    ->html()
                    ->formatStateUsing(fn($record) => $record->barangServis->map(function ($barang) {
                        return "- {$barang->nama_barang} (Rp" . number_format($barang->harga ?? 0, 0, ',', '.') . ")";
                    })->implode('<br>')),


            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status Servis')
                    ->options(fn() => \App\Models\StatusServis::pluck('nama_status', 'id')),

                Tables\Filters\Filter::make('tanggal_servis')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['from'] && $data['until']) {
                            return $query->whereBetween('tanggal_servis', [
                                Carbon::parse($data['from'])->startOfDay(),
                                Carbon::parse($data['until'])->endOfDay(),
                            ]);
                        }

                        return $query;
                    }),


            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapData::route('/'),
        ];
    }
}
