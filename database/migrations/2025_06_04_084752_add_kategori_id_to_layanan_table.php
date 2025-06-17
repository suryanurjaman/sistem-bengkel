<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            // Tambahkan kolom kategori_id yang merujuk ke tabel kategori_layanan
            $table->foreignId('kategori_id')
                  ->nullable()   // boleh null jika belum diisi
                  ->after('id')
                  ->constrained('kategori_layanan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};
