<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananServisTable extends Migration
{
    public function up()
    {
        Schema::create('pemesanan_servis', function (Blueprint $table) {
            $table->id();
            // Relasi ke user (pelanggan)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Relasi ke layanan: satu pemesanan bisa memilih satu layanan (atau jika ingin multiple, nanti pakai pivot)
            $table->foreignId('layanan_id')
                  ->constrained('layanan')
                  ->onDelete('cascade');

            $table->string('kode_booking')->unique();     // Contoh: “SV-20250603-0001”
            $table->dateTime('tanggal_dipesan')->nullable(); // Waktu pemesanan dibuat

            $table->enum('status', ['Menunggu Konfirmasi', 'Sedang Diproses', 'Selesai', 'Dibatalkan'])
                  ->default('Menunggu Konfirmasi');
            $table->text('keterangan')->nullable();       // Catatan tambahan dari user
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanan_servis');
    }
}
