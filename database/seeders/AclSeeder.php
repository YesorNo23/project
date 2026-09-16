<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AclSeeder extends Seeder
{
    public function run()
    {
        $acls = [
            ['id' => 1, 'ugid' => 1, 'appid' => 1, 'acclevel' => 2, 'status' => null],
            ['id' => 2, 'ugid' => 1, 'appid' => 2, 'acclevel' => 2, 'status' => null],
            ['id' => 3, 'ugid' => 1, 'appid' => 3, 'acclevel' => 2, 'status' => null],
            ['id' => 5, 'ugid' => 1, 'appid' => 5, 'acclevel' => 2, 'status' => null],
            ['id' => 8, 'ugid' => 1, 'appid' => 4, 'acclevel' => 2, 'status' => null],
        ];

        foreach ($acls as $acl) {
            // ใช้ updateOrInsert เพื่อเช็คว่าถ้ามี id นี้แล้วให้อัปเดต ถ้ายังไม่มีให้สร้างใหม่ (ป้องกันข้อมูลซ้ำ)
            DB::table('acl')->updateOrInsert(
                ['id' => $acl['id']], // ค้นหาจากเงื่อนไขนี้
                $acl // ข้อมูลที่จะบันทึก
            );
        }
    }
}