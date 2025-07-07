<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            [
                'name' => 'admin',
                'label' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'user',
                'label' => 'Pengguna Biasa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
