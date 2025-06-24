<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananResource\Pages;
use App\Models\Layanan;
use App\Models\KategoriLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationLabel = 'Layanan';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tambahkan dropdown kategori
                Forms\Components\Select::make('kategori_id')
                    ->label('Kategori')
                    ->options(
                        KategoriLayanan::all()->pluck('nama_kategori', 'id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama_layanan')
                    ->label('Nama Layanan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable(),

                Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->directory('layanan-images') // folder di storage/app/public
                    ->imagePreviewHeight('200')   // tampilan preview
                    ->maxSize(5048)               // maksimal 5MB
                    ->nullable(),

                Forms\Components\TextInput::make('estimasi_waktu')
                    ->label('Estimasi Waktu')
                    ->placeholder('Contoh: 1 jam, 30 menit')
                    ->required(),

                Forms\Components\TextInput::make('harga_min')
                    ->label('Harga Minimum')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('harga_max')
                    ->label('Harga Maksimum')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('harga_jasa')
                    ->label('Harga Jasa Tetap')
                    ->numeric()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),

                // Tampilkan nama kategori di tabel
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_layanan')
                    ->label('Layanan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimasi_waktu')
                    ->label('Estimasi'),

                Tables\Columns\TextColumn::make('harga_min')
                    ->label('Harga Min')
                    ->money('idr', true),

                Tables\Columns\TextColumn::make('harga_max')
                    ->label('Harga Max')
                    ->money('idr', true),

                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                // Contoh filter berdasarkan kategori
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Filter Kategori')
                    ->relationship('kategori', 'nama_kategori'),
            ])
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
            'index'  => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'edit'   => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }
}
