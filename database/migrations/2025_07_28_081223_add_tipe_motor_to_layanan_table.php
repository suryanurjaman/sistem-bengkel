<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->string('tipe_motor')->default('umum')->after('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn('tipe_motor');
        });
    }
};
