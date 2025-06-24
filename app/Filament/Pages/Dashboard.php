<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\PemesananServis;
use App\Models\Layanan;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard';

    public $totalAdmin;
    public $totalLayanan;
    public $totalPemesanan;
    public $pemesananSelesai;

    public function mount(): void
    {
        $this->totalAdmin = User::count();
        $this->totalLayanan = Layanan::count();
        $this->totalPemesanan = PemesananServis::count();
        $this->pemesananSelesai = PemesananServis::whereHas('statusServis', function ($query) {
            $query->where('nama_status', 'selesai');
        })->count();
    }
}
