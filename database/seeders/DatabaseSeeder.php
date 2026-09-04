<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. ระบุตำแหน่งไฟล์ sql ในโปรเจกต์
        $sqlPath = database_path('project.sql');

        if (File::exists($sqlPath)) {
            // 2. ดึงข้อความคำสั่ง SQL ทั้งหมดออกมา
            $sql = File::get($sqlPath);

            // 3. ยิงคำสั่งทั้งหมดเข้าฐานข้อมูลคลาวด์ Aiven ในครั้งเดียว
            DB::unprepared($sql);
            
            $this->command->info('Database imported successfully from SQL file!');
        } else {
            $this->command->error('SQL file not found.');
        }
    }
}
