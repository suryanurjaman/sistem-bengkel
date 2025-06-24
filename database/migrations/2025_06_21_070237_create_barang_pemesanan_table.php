<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang_pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_servis_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_servis_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_pemesanan');
    }
};
