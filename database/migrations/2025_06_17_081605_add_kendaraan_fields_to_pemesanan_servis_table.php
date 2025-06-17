<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->string('plat_nomor')->nullable()->after('pelanggan_id');
            $table->string('jenis_motor')->nullable()->after('plat_nomor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->dropColumn(['plat_nomor', 'jenis_motor']);
        });
    }
};
