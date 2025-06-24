<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananServisResource\Pages;
use App\Models\PemesananServis;
use App\Models\StatusServis;
use App\Models\Layanan;
use App\Models\BarangServis;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;


class PemesananServisResource extends Resource
{
    protected static ?string $model = PemesananServis::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Pemesanan Servis';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Form Pemesanan')
                ->tabs([

                    // TAB UTAMA FORMULIR
                    Tab::make('Formulir')
                        ->schema([

                            Forms\Components\Select::make('pelanggan_id')
                                ->label('Pelanggan')
                                ->relationship('pelanggan', 'nama_lengkap')
                                ->searchable()
                                ->preload()
                                ->disabled(fn($state, $context) => $context !== 'create')
                                ->required(),

                            Forms\Components\TextInput::make('plat_nomor')
                                ->label('Plat Nomor')
                                ->disabled(fn($state, $context) => $context !== 'create'),

                            Forms\Components\TextInput::make('jenis_motor')
                                ->label('Jenis Motor')
                                ->disabled(fn($state, $context) => $context !== 'create'),

                            Forms\Components\Textarea::make('alamat')
                                ->label('Alamat')
                                ->disabled(fn($state, $context) => $context !== 'create')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('layanans')
                                ->label('Layanan')
                                ->multiple()
                                ->options(
                                    \App\Models\Layanan::all()->mapWithKeys(function ($layanan) {
                                        return [
                                            $layanan->id => "{$layanan->nama_layanan} - Rp " . number_format($layanan->harga_jasa),
                                        ];
                                    })
                                )
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $currentBarang = $get('barang_per_layanan') ?? [];
                                    $cleaned = collect($currentBarang)
                                        ->only($state)
                                        ->toArray();

                                    $set('barang_per_layanan', $cleaned);

                                    foreach (array_keys($currentBarang) as $layananId) {
                                        if (! in_array($layananId, $state)) {
                                            $set("barang_per_layanan.{$layananId}", null);
                                        }
                                    }
                                }),


                            Forms\Components\Fieldset::make('Barang per Layanan')
                                ->schema(
                                    fn(callable $get) =>
                                    collect($get('layanans') ?? [])
                                        ->map(function ($layananId) {
                                            return Forms\Components\Select::make("barang_per_layanan.{$layananId}")
                                                ->label("Barang untuk: " . \App\Models\Layanan::find($layananId)?->nama_layanan)
                                                ->options(\App\Models\BarangServis::where('layanan_id', $layananId)->pluck('nama_barang', 'id'))
                                                ->required()
                                                ->reactive();
                                        })->toArray()
                                )
                                ->columns(1)
                                ->visible(fn(callable $get) => filled($get('layanans'))),




                            Forms\Components\TextInput::make('kode_booking')
                                ->label('Kode Booking')
                                ->disabled()
                                ->placeholder('Ter‐generate otomatis'),

                            Forms\Components\DatePicker::make('tanggal_dipesan')
                                ->label('Tanggal Dipesan')
                                ->required()
                                ->disabled(fn($state, $context) => $context !== 'create'),

                            Forms\Components\DatePicker::make('tanggal_servis')
                                ->label('Tanggal Servis')
                                ->required()
                                ->disabled(fn($state, $context) => $context !== 'create'),

                            Forms\Components\Select::make('status_id')
                                ->label('Status')
                                ->relationship('statusServis', 'nama_status')
                                ->preload()
                                ->required()
                                ->reactive(),

                            Forms\Components\Placeholder::make('keterangan_status')
                                ->label('Deskripsi Status')
                                ->content(function (callable $get) {
                                    $id = $get('status_id');
                                    if (! $id) return '- Pilih status terlebih dahulu -';
                                    $status = StatusServis::find($id);
                                    return $status?->keterangan ?? '- Status tidak ditemukan -';
                                }),

                            Forms\Components\Textarea::make('keterangan')
                                ->label('Catatan Pelanggan')
                                ->disabled()
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('keterangan_admin')
                                ->label('Catatan Admin')
                                ->nullable()
                                ->disabled(fn() => Filament::auth()->user()?->hasRole('mekanik'))
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('catatan_mekanik')
                                ->label('Catatan Mekanik')
                                ->nullable()
                                ->visible(fn() => Filament::auth()->user()?->hasRole('mekanik'))
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->disabled()
                                ->numeric()
                                ->prefix('Rp')
                                ->dehydrated(),

                        ]),

                    // TAB RINCIAN HARGA
                    Tab::make('Rincian Harga')
                        ->schema([
                            Forms\Components\Placeholder::make('rincian_harga')
                                ->label('Rincian Harga')
                                ->content(function (callable $get) {
                                    $layanans = \App\Models\Layanan::whereIn('id', $get('layanans') ?? [])->get();
                                    $barangIds = collect($get('barang_per_layanan') ?? [])->flatten()->filter()->unique();
                                    $barangList = \App\Models\BarangServis::whereIn('id', $barangIds)->get();

                                    return new \Illuminate\Support\HtmlString(
                                        view('filament.partials.rincian-harga', [
                                            'layanans'   => $layanans,
                                            'barangList' => $barangList,
                                        ])->render()
                                    );
                                })
                                ->columnSpanFull()
                                ->visible(fn(callable $get) => filled($get('layanans')) || filled($get('barang_per_layanan'))),

                        ])
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('kode_booking')->label('Kode Booking')->searchable(),
                Tables\Columns\TextColumn::make('pelanggan.nama_lengkap')->label('Pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('plat_nomor')->label('Plat Nomor')->searchable(),
                Tables\Columns\TextColumn::make('jenis_motor')->label('Jenis Motor')->searchable(),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat')->limit(25)->tooltip(fn($record) => $record->alamat),
                Tables\Columns\TextColumn::make('tanggal_dipesan')->label('Dipesan')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_servis')->label('Tanggal Servis')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('layanans.nama_layanan')->label('Layanan')->badge()->separator(', '),
                Tables\Columns\TextColumn::make('total_harga')->label('Total Harga')->money('IDR', true)->alignEnd()->sortable(),
                Tables\Columns\TextColumn::make('keterangan')->label('Catatan Pelanggan')->limit(30)->tooltip(fn($record) => $record->keterangan),
                Tables\Columns\TextColumn::make('keterangan_admin')->label('Catatan Admin')->limit(30)->tooltip(fn($record) => $record->keterangan_admin),
                Tables\Columns\TextColumn::make('catatan_mekanik')->label('Catatan Mekanik')->limit(30)->tooltip(fn($record) => $record->catatan_mekanik),
                Tables\Columns\TextColumn::make('statusServis.nama_status')->label('Status')->badge()->colors([
                    'gray'    => 'Menunggu',
                    'warning' => 'Diproses',
                    'success' => 'Selesai',
                    'danger'  => 'Dibatalkan',
                ])->icons([
                    'heroicon-o-clock'    => 'Menunggu',
                    'heroicon-o-wrench'   => 'Diproses',
                    'heroicon-o-check'    => 'Selesai',
                    'heroicon-o-x-circle' => 'Dibatalkan',
                ])->sortable(),
            ])
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
