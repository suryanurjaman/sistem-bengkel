<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusServisTable extends Migration
{
    public function up()
    {
        Schema::create('status_servis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_status');    // Contoh: Menunggu Konfirmasi, Sedang Diproses, Selesai
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Lalu tambahkan kolom status_id di pemesanan_servis
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->dropColumn('status');    // Hapus enum sebelumnya
            $table->foreignId('status_id')
                  ->after('tanggal_dipesan')
                  ->constrained('status_servis')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->enum('status', ['Menunggu Konfirmasi', 'Sedang Diproses', 'Selesai', 'Dibatalkan'])
                  ->default('Menunggu Konfirmasi');
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
        Schema::dropIfExists('status_servis');
    }
}
