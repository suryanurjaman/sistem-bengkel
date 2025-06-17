<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            // Hapus foreign key dan kolom layanan_id
            if (Schema::hasColumn('pemesanan_servis', 'layanan_id')) {
                $table->dropForeign(['layanan_id']);
                $table->dropColumn('layanan_id');
            }

            // Tambahkan total_harga
            if (!Schema::hasColumn('pemesanan_servis', 'total_harga')) {
                $table->decimal('total_harga', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            // Balikkan perubahan jika dibatalkan
            $table->unsignedBigInteger('layanan_id')->nullable();
            $table->foreign('layanan_id')->references('id')->on('layanan')->onDelete('set null');

            $table->dropColumn('total_harga');
        });
    }
};
