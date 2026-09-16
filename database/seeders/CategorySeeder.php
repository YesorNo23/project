<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 1, 'name' => 'ประเภทคลื่น', 'status' => 1],
            ['id' => 2, 'name' => 'อารมณ์', 'status' => 1],
        ];

        foreach ($categories as $category) {
            // ใช้ updateOrInsert เพื่อป้องกันข้อมูลซ้ำ
            DB::table('category')->updateOrInsert(
                ['id' => $category['id']],
                $category
            );
        }
    }
}