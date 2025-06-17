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
            $table->date('tanggal_servis')->after('tanggal_dipesan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->dropColumn('tanggal_servis');
        });
    }
};
