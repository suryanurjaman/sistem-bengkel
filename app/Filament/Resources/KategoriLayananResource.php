<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriLayananResource\Pages;
use App\Models\KategoriLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KategoriLayananResource extends Resource
{
    protected static ?string $model = KategoriLayanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Kategori Layanan';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable(),

                Forms\Components\Repeater::make('kelebihan')
                    ->label('Kelebihan Layanan')
                    ->schema([
                        Forms\Components\TextInput::make('value')->label('Kelebihan')->required(),
                    ])
                    ->default([])
                    ->collapsible(),

                Forms\Components\TextInput::make('estimasi_waktu')
                    ->label('Estimasi Waktu Pengerjaan')
                    ->placeholder('Contoh: 8 menit - 4 hari')
                    ->maxLength(50)
                    ->nullable(),

                Forms\Components\TextInput::make('harga_min')
                    ->label('Harga Minimum')
                    ->numeric()
                    ->prefix('Rp')
                    ->nullable(),

                Forms\Components\TextInput::make('harga_max')
                    ->label('Harga Maksimum')
                    ->numeric()
                    ->prefix('Rp')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('nama_kategori')->label('Kategori'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKategoriLayanans::route('/'),
            'create' => Pages\CreateKategoriLayanan::route('/create'),
            'edit'   => Pages\EditKategoriLayanan::route('/{record}/edit'),
        ];
    }
}
