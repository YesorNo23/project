<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Master Tables (ไม่มี Foreign Key)
        $this->call([
            UserSeeder::class,
            UsergroupSeeder::class,
            ApplicationSeeder::class,
            CategorySeeder::class,
            MusicSeeder::class,
            SessionsSeeder::class, // (ถ้าต้องการ Seed)
        ]);

        // 2. Child Tables (มี Foreign Key)
        $this->call([
            UigSeeder::class,
            CatDetailSeeder::class,
            CatMusicSeeder::class,

            AclSeeder::class, 
        ]);
    }
}