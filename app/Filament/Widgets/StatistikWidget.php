<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Layanan;
use App\Models\PemesananServis;
use Filament\Widgets\Widget;

class StatistikWidget extends Widget
{
    protected static string $view = 'filament.widgets.statistik-widget';
    protected static ?string $heading = 'Statistik Umum';

    public function getData(): array
    {
        return [
            'totalAdmin' => User::count(),
            'totalLayanan' => Layanan::count(),
            'totalPemesanan' => PemesananServis::count(),
            'pemesananSelesai' => PemesananServis::whereHas('statusServis', function ($q) {
                $q->where('nama_status', 'Selesai');
            })->count(),
            'pemesananProses' => PemesananServis::whereHas('statusServis', function ($q) {
                $q->where('nama_status', 'Sedang Diproses');
            })->count(),
        ];
    }
}
