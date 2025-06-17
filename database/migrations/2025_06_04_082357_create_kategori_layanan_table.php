<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique(); // Contoh: "Layanan Dasar", "Layanan Sistem Rem", dll.
            $table->text('deskripsi')->nullable();     // Deskripsi ringkas jika diperlukan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_layanan');
    }
};
