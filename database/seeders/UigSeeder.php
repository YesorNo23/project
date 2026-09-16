<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('uig')->insert([
            [
                'id' => 1,
                'uid' => 1,
                'ugid' => 1,
                'status' => null,
            ],
            [
                'id' => 2,
                'uid' => 2,
                'ugid' => 2,
                'status' => null,
            ],
        ]);
    }
}