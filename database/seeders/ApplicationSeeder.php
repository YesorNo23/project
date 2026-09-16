<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run()
    {
        $applications = [
            ['id' => 1, 'name' => 'การจัดการผู้ใช้งานในระบบ', 'dir' => 'UserManagement', 'detail' => null, 'status' => 1],
            ['id' => 2, 'name' => 'การจัดการกลุ่มผู้ใช้', 'dir' => 'GroupManagement', 'detail' => null, 'status' => 1],
            ['id' => 3, 'name' => 'การจัดการแอปพิเคชั่น', 'dir' => 'AppManagement', 'detail' => null, 'status' => 1],
            ['id' => 4, 'name' => 'การจัดการคลื่นเสียง', 'dir' => 'MusicManagement', 'detail' => null, 'status' => 1],
            ['id' => 5, 'name' => 'การจัดการตัวกรอง', 'dir' => 'FilterManagement', 'detail' => null, 'status' => 1],
        ];

        foreach ($applications as $app) {
            // ใช้ updateOrInsert เพื่อป้องกันข้อมูลซ้ำซ้อน หากรัน Seeder หลายครั้ง
            DB::table('application')->updateOrInsert(
                ['id' => $app['id']], // เช็คจาก id
                $app // ข้อมูลที่ต้องการอัปเดตหรือสร้างใหม่
            );
        }
    }
}