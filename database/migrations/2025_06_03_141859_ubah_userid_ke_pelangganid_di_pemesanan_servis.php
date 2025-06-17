<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UbahUseridKePelangganidDiPemesananServis extends Migration
{
    public function up()
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            // 1. Hapus foreign key dan kolom user_id (jika sudah ada constraint ke users)
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // 2. Tambahkan kolom pelanggan_id yang merefer ke tabel pelanggans
            $table->foreignId('pelanggan_id')
                  ->after('id')
                  ->constrained('pelanggans')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            // 1. Hapus dulu kolom pelanggan_id
            $table->dropForeign(['pelanggan_id']);
            $table->dropColumn('pelanggan_id');

            // 2. Kembalikan kolom user_id yang merujuk ke tabel users
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }
}
