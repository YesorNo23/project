<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $migrations = [
            [
                'id' => 1,
                'migration' => '0001_01_01_000000_create_users_table',
                'batch' => 1
            ],
        ];

        // Insert ข้อมูลลงตาราง migrations
        DB::table('migrations')->insert($migrations);
    }
}