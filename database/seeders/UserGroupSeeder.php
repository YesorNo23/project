<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usergroup')->insert([
            [
                'id' => 1,
                'name' => 'SuperAdmin',
                'detail' => 'ทำงาน',
                'status' => 1,
            ],
            [
                'id' => 2,
                'name' => 'user',
                'detail' => 'สมาชิกในระบบ',
                'status' => 1,
            ],
        ]);
    }
}