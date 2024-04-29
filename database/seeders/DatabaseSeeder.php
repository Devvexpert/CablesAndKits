<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $userData = [
            [
                'name' => 'Jane Doe',
                'email' => 'test@example.com',
                'password' => bcrypt('test@123'),
            ],
            [
                'name' => 'John Smith',
                'email' => 'test2@example.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'John',
                'email' => 'test3@example.com',
                'password' => bcrypt('password123'),
            ],
        ];

        foreach ($userData as $user) {
            User::create($user);
        }
    }
}
