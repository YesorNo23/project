<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatDetailSeeder extends Seeder
{
    public function run()
    {
        $catDetails = [
            ['id' => 22, 'category_id' => 1, 'name' => 'Delta Wave(0.5-4 Hz)', 'status' => 1],
            ['id' => 23, 'category_id' => 1, 'name' => 'Theta Wave(4-8 Hz)', 'status' => 1],
            ['id' => 24, 'category_id' => 1, 'name' => 'Alpla Wave(8-12 Hz)', 'status' => 1], // สะกดตามต้นฉบับ SQL 
            ['id' => 25, 'category_id' => 1, 'name' => 'Beta Wave(12-30 Hz)', 'status' => 1],
            ['id' => 26, 'category_id' => 2, 'name' => 'นอนหลับ', 'status' => 1],
            ['id' => 27, 'category_id' => 2, 'name' => 'เพิ่มสมาธิและโฟกัส', 'status' => 1],
            ['id' => 28, 'category_id' => 1, 'name' => 'Gamma Wave(30-100 Hz)', 'status' => 1],
            ['id' => 29, 'category_id' => 2, 'name' => 'ปรับอารมณ์ให้ดีขึ้น', 'status' => 1],
            ['id' => 30, 'category_id' => 2, 'name' => 'ผ่อนคลายทั่วไป', 'status' => 1],
            ['id' => 31, 'category_id' => 2, 'name' => 'ลดความเครียด', 'status' => 1],
        ];

        foreach ($catDetails as $detail) {
            DB::table('cat_detail')->updateOrInsert(
                ['id' => $detail['id']],
                $detail
            );
        }
    }
}