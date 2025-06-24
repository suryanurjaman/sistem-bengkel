<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('jenis_motor');
        });
    }

    public function down()
    {
        Schema::table('pemesanan_servis', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};
