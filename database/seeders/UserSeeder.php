<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'id' => 1,
                'password' => '$2y$12$4cBsaMVQDMLWGj7bSgE3AOuIVahY50x3iRaITw1mvigprAjcVkMYS',
                'name' => 'SuperAdmin',
                'surname' => 'pichan',
                'email' => '665041901208@mail.rmutk.ac.th',
                'birthdate' => '2026-04-09',
                'gender' => 'male',
                'usertype' => 'SuperAdmin',
                'status' => 1,
            ],
        ]);
    }
}