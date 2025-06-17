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
        Schema::table('layanan', function (Blueprint $table) {
            $table->decimal('harga_min', 12, 2)->default(0)->after('harga');
            $table->decimal('harga_max', 12, 2)->default(0)->after('harga_min');
            $table->dropColumn('harga');
            $table->string('estimasi_waktu')->nullable()->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn(['harga_min', 'harga_max', 'estimasi_waktu']);
            $table->decimal('harga', 12, 2)->default(0);
        });
    }
};
