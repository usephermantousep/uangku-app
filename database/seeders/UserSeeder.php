<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin'),
        ]);

        User::factory()->create([
            'name' => 'user A',
            'email' => 'usera@test.com',
            'password' => bcrypt('usera'),
        ]);
        User::factory()->create([
            'name' => 'user B',
            'email' => 'userb@test.com',
            'password' => bcrypt('userb'),
        ]);
    }
}
