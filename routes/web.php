<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PelangganAuth\Auth\AuthenticatedSessionController as PelangganLoginController;
use App\Http\Controllers\PelangganAuth\Auth\RegisteredUserController   as PelangganRegisterController;
use App\Http\Controllers\PelangganAuth\Auth\PasswordResetLinkController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\KategoriLayananController;
use App\Http\Controllers\StatusServisController;
use App\Models\KategoriLayanan;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Halaman beranda, layanan, status servis, kontak
Route::get('/', [BerandaController::class, 'index'])->name('home');
Route::get('/layanan', [KategoriLayananController::class, 'index'])->name('layanan');
Route::get('/status-servis', [StatusServisController::class, 'index'])->name('status.servis');
Route::post('/status-servis/cek', [StatusServisController::class, 'cek'])->name('status.servis.cek');
Route::view('/kontak','kontak')->name('kontak');
Route::get('/layanan/{kategori}', [LayananController::class, 'byKategori'])
    ->name('layanan.byKategori');

Route::get('/api/kategori/{id}/layanans', function ($id) {
    return KategoriLayanan::findOrFail($id)
        ->layanans()->select('id', 'nama_layanan', 'harga_min', 'harga_max')->get();
});


/*
|--------------------------------------------------------------------------
| Auth Pelanggan (Public)
|--------------------------------------------------------------------------
*/

// Form pendaftaran pelanggan
Route::get('/pelanggan/register', [PelangganRegisterController::class, 'create'])
    ->name('pelanggan.register');
// Proses pendaftaran pelanggan
Route::post('/pelanggan/register', [PelangganRegisterController::class, 'store'])
    ->name('pelanggan.register.store');

// Form login pelanggan
Route::get('/pelanggan/login', [PelangganLoginController::class, 'create'])
    ->name('pelanggan.login');
// Proses login pelanggan
Route::post('/pelanggan/login', [PelangganLoginController::class, 'store'])
    ->name('pelanggan.login.store');

// Logout pelanggan
Route::post('/pelanggan/logout', [PelangganLoginController::class, 'destroy'])
    ->name('pelanggan.logout');

// Form “Lupa Kata Sandi” pelanggan
Route::get('/pelanggan/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest:pelanggan')
    ->name('pelanggan.password.request');

// Proses kirim link reset
Route::post('/pelanggan/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest:pelanggan')
    ->name('pelanggan.password.email');

/*
|--------------------------------------------------------------------------
| Booking Servis
|--------------------------------------------------------------------------
*/

// Siapa saja boleh lihat form booking (GET)
Route::get('/booking', [BookingController::class, 'create'])
    ->name('booking.create');

// Hanya pelanggan login yang boleh kirim form (POST)
Route::post('/booking', [BookingController::class, 'store'])
    ->middleware('auth:pelanggan')
    ->name('booking.store');
