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
use App\Http\Controllers\KontakController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PelangganAuth\Auth\NewPasswordController;
use App\Http\Controllers\RekapController;
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


Route::view('/kontak', 'kontak')->name('kontak');
Route::post('/kontak/kirim', [KontakController::class, 'store'])->name('kontak.kirim');
// Halaman 

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

Route::get('/pelanggan/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest:pelanggan')
    ->name('pelanggan.password.reset');

Route::post('/pelanggan/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest:pelanggan')
    ->name('pelanggan.password.update');

/*
|--------------------------------------------------------------------------
| Booking Servis
|--------------------------------------------------------------------------
*/
// List semua bookingan milik pelanggan
Route::get('/booking', [BookingController::class, 'index'])
    ->middleware('auth:pelanggan')
    ->name('booking.index');

// Form untuk tambah bookingan baru
Route::get('/booking/create', [BookingController::class, 'create'])
    ->middleware('auth:pelanggan')
    ->name('booking.create');

// Simpan data booking baru
Route::post('/booking', [BookingController::class, 'store'])
    ->middleware('auth:pelanggan')
    ->name('booking.store');

// Form edit bookingan
Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])
    ->middleware('auth:pelanggan')
    ->name('booking.edit');

// Update bookingan yang sudah ada
Route::put('/booking/{id}', [BookingController::class, 'update'])
    ->middleware('auth:pelanggan')
    ->name('booking.update');

Route::put('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

Route::get('/booking/{kode}/invoice', [\App\Http\Controllers\BookingController::class, 'downloadInvoice'])->name('booking.invoice');



// Sembunyikan bookingan
Route::post('/booking/{id}/hide', [BookingController::class, 'hide'])
    ->middleware('auth:pelanggan')
    ->name('booking.hide');


// routes/web.php
Route::get('/test-broadcast', function () {
    $pesanan = \App\Models\PemesananServis::latest()->first();
    event(new \App\Events\NotifikasiPesananUpdated($pesanan));
    return 'Broadcast dikirim';
});


Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');
Route::post('/notifikasi/clear-all', [NotifikasiController::class, 'clearAll'])->name('notifikasi.clearAll');


// 🔄 Ambil notifikasi pelanggan (dipanggil dari JS saat broadcast masuk)
Route::get('/notifikasi-pelanggan/json', function () {
    $id = auth('pelanggan')->id();

    $notifs = \App\Models\NotifikasiPelanggan::where('pelanggan_id', $id)
        ->orderByDesc('created_at')
        ->take(10)
        ->get();

    return response()->json($notifs);
})->middleware('auth:pelanggan');

// Tandai satu notifikasi dibaca
Route::post('/notifikasi/{id}/read', function ($id) {
    $notif = \App\Models\NotifikasiPelanggan::findOrFail($id);
    if ($notif->pelanggan_id !== auth('pelanggan')->id()) abort(403);
    $notif->is_read = true;
    $notif->save();

    return response()->json(['success' => true]);
})->middleware('auth:pelanggan');

// Hapus satu notifikasi
Route::delete('/notifikasi/{id}', function ($id) {
    $notif = \App\Models\NotifikasiPelanggan::findOrFail($id);
    if ($notif->pelanggan_id !== auth('pelanggan')->id()) abort(403);
    $notif->delete();

    return response()->json(['success' => true]);
})->middleware('auth:pelanggan');


Route::get('/rekap-bengkel/export-pdf', [RekapController::class, 'exportPdf'])
    ->name('rekap.export.pdf')
    ->middleware(['auth']); // cukup pakai auth aja

// Cetak invoice via panel admin Filament
Route::get('/admin/invoice/{pemesananServis}/cetak', [RekapController::class, 'exportSingleInvoice'])
    ->name('invoice.cetak')
    ->middleware(['auth']);
