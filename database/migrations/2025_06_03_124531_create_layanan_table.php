<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayananTable extends Migration
{
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan');                // Contoh: Ganti Oli, Servis Rem, dll
            $table->text('deskripsi')->nullable();         // Deskripsi singkat
            $table->decimal('harga', 12, 2)->default(0);    // Harga layanan (dua angka desimal)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('layanan');
    }
}
