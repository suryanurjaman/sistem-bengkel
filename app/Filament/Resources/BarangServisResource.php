<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangServisResource\Pages;
use App\Models\BarangServis;
use App\Models\Layanan;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class BarangServisResource extends Resource
{
    protected static ?string $model = BarangServis::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Barang Servis';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('layanan_id')
                ->label('Layanan')
                ->options(Layanan::all()->pluck('nama_layanan', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required(),

            Forms\Components\TextInput::make('harga')
                ->label('Harga')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('layanan.nama_layanan')->label('Layanan'),
            Tables\Columns\TextColumn::make('nama_barang'),
            Tables\Columns\TextColumn::make('harga')->money('idr', true),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i'),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangServis::route('/'),
            'create' => Pages\CreateBarangServis::route('/create'),
            'edit' => Pages\EditBarangServis::route('/{record}/edit'),
        ];
    }
}
