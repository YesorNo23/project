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
        $sqlPath = database_path('project.sql');

        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);

            // บรรทัดที่เพิ่มใหม่: สั่งปิดการบังคับ Primary Key ชั่วคราวเฉพาะรอบนี้
            DB::unprepared("SET SESSION sql_require_primary_key = 0;");

            // ยิงคำสั่งทั้งหมดเข้าฐานข้อมูลคลาวด์ Aiven
            DB::unprepared($sql);
            
            $this->command->info('Database imported successfully from SQL file!');
        } else {
            $this->command->error('SQL file not found.');
        }
    }
}
