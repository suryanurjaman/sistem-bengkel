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
        Schema::table('kategori_layanan', function (Blueprint $table) {
            $table->json('kelebihan')->nullable()->after('deskripsi');
            $table->string('estimasi_waktu')->nullable()->after('kelebihan');
            $table->decimal('harga_min', 10, 2)->nullable()->after('estimasi_waktu');
            $table->decimal('harga_max', 10, 2)->nullable()->after('harga_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_layanan', function (Blueprint $table) {
            $table->dropColumn(['kelebihan', 'estimasi_waktu', 'harga_min', 'harga_max']);
        });
    }
};
