<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontakMasukResource\Pages;
use App\Models\KontakMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KontakMasukResource extends Resource
{
    protected static ?string $model = KontakMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Pesan Kontak';
    protected static ?string $pluralLabel = 'Pesan Kontak';
    protected static ?string $modelLabel = 'Pesan Kontak';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')->label('Nama')->disabled(),
            Forms\Components\TextInput::make('email')->label('Email')->disabled(),
            Forms\Components\Textarea::make('pesan')->label('Isi Pesan')->rows(10)->disabled(),
            Forms\Components\DateTimePicker::make('created_at')->label('Dikirim')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pesan')->label('Isi Pesan')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Dikirim')->dateTime('d M Y H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKontakMasuks::route('/'),
            'view' => Pages\ViewKontakMasuk::route('/{record}'),
        ];
    }

    // Nonaktifkan tombol tambah dan edit
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
