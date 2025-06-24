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
        Schema::table('notifikasi_pelanggan', function (Blueprint $table) {
            $table->string('status')->nullable()->after('kode_booking');
            $table->string('kategori')->nullable()->after('status'); // admin/mekanik/status
            $table->string('tipe')->nullable()->after('kategori'); // misal "catatan", "status"
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi_pelanggan', function (Blueprint $table) {
            $table->dropColumn(['status', 'kategori', 'tipe']);
        });
    }
};
