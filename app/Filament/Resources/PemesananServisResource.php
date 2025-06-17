<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananServisResource\Pages;
use App\Models\PemesananServis;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\StatusServis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PemesananServisResource extends Resource
{
    protected static ?string $model = PemesananServis::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Pemesanan Servis';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Pilih Pelanggan
                Forms\Components\Select::make('pelanggan_id')
                    ->label('Pelanggan')
                    ->relationship('pelanggan', 'nama_lengkap')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 2. Pilih Layanan
                Forms\Components\Select::make('layanans')
                    ->label('Layanan')
                    ->relationship('layanans', 'nama_layanan') // gunakan relasi many-to-many
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),


                // 3. Kode Booking (otomatis, disabled)
                Forms\Components\TextInput::make('kode_booking')
                    ->label('Kode Booking')
                    ->disabled()
                    ->placeholder('Ter‐generate otomatis'),

                // 4. Tanggal Dipesan (otomatis, disabled)
                Forms\Components\DateTimePicker::make('tanggal_dipesan')
                    ->label('Tanggal Dipesan')
                    ->disabled(),

                // 5. Pilih Status dari tabel status_servis
                Forms\Components\Select::make('status_id')
                    ->label('Status')
                    ->relationship('statusServis', 'nama_status')
                    //->searchable()  tambah searchable jika ingin cari─cari
                    ->preload()
                    ->required()
                    ->reactive(), // WAJIB untuk reaktif ke perubahan live

                // 6. Tampilkan keterangan (deskripsi) dari status yang dipilih
                Forms\Components\Placeholder::make('keterangan_status')
                    ->label('Deskripsi Status')
                    ->content(function (callable $get) {
                        // $get('status_id') akan mengembalikan ID status yang dipilih
                        $id = $get('status_id');
                        if (! $id) {
                            return '- Pilih status terlebih dahulu -';
                        }
                        $status = StatusServis::find($id);
                        return $status
                            ? $status->keterangan
                            : '- Status tidak ditemukan -';
                    }),

                // 7. Keterangan dari pelanggan (readonly)
                Forms\Components\Textarea::make('keterangan')
                    ->label('Catatan Pelanggan')
                    ->disabled()
                    ->columnSpanFull(),

                // 8. Keterangan Admin
                Forms\Components\Textarea::make('keterangan_admin')
                    ->label('Catatan Admin')
                    ->nullable()
                    ->columnSpanFull(),


                Forms\Components\TextInput::make('total_harga')
                    ->label('Harga Final (Manual)')
                    ->numeric()
                    ->prefix('Rp')
                    ->visible(fn(callable $get) => in_array((int) $get('status_id'), [3, 4]))
                    ->required(fn(callable $get) => in_array((int) $get('status_id'), [3, 4]))
                    ->reactive(), // agar ikut berubah saat status diganti

                Forms\Components\Placeholder::make('harga_range')
                    ->label('Total Estimasi Harga')
                    ->content(function (callable $get) {
                        $ids = $get('layanans');
                        if (!$ids || !is_array($ids)) return '- Pilih layanan terlebih dahulu -';

                        $layanan = \App\Models\Layanan::whereIn('id', $ids);
                        $min = $layanan->sum('harga_min');
                        $max = $layanan->sum('harga_max');

                        return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('kode_booking')->label('Booking'),

                // Tampilkan nama pelanggan via relasi
                Tables\Columns\TextColumn::make('pelanggan.nama_lengkap')->label('Pelanggan'),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Catatan Pelanggan')
                    ->limit(30),

                Tables\Columns\TextColumn::make('keterangan_admin')
                    ->label('Catatan Admin')
                    ->limit(30),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Harga Final')
                    ->money('IDR', true)
                    ->alignEnd()
                    ->sortable(),

                // Tampilkan nama layanan via relasi
                Tables\Columns\TextColumn::make('layanans.nama_layanan')
                    ->label('Layanan')
                    ->badge()
                    ->separator(', '),
                Tables\Columns\TextColumn::make('range_harga')
                    ->label('Harga')
                    ->sortable(false),


                // Tampilkan nama status via relasi
                Tables\Columns\TextColumn::make('statusServis.nama_status')->label('Status'),

                Tables\Columns\TextColumn::make('tanggal_dipesan')
                    ->label('Tanggal Dipesan')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPemesananServis::route('/'),
            'create' => Pages\CreatePemesananServis::route('/create'),
            'edit'   => Pages\EditPemesananServis::route('/{record}/edit'),
        ];
    }
}
