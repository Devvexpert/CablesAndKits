<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AddUserData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the user records into the users table

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'test',
                'email' =>'test@gmail.com',
                'password' => Hash::make('test@123'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'test',
                'email' =>'test1@gmail.com',
                'password' => Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'test',
                'email' =>'test2@gmail.com',
                'password' => Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
