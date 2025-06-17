<?php

// database/seeders/StatusServisSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusServisSeeder extends Seeder
{
    public function run()
    {
        DB::table('status_servis')->insert([
            ['id' => 1, 'nama_status' => 'Menunggu Konfirmasi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_status' => 'Dalam Antrian',      'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_status' => 'Sedang Dikerjakan',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_status' => 'Selesai',             'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_status' => 'Batal',               'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
