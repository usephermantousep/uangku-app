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
        $userAdmin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin'),
        ]);

        $userAdmin->assignRole('Admin');
        $userAdmin->family()->attach(1);

        $usera = User::factory()->create([
            'name' => 'user A',
            'email' => 'usera@test.com',
            'password' => bcrypt('usera'),
        ]);
        $usera->assignRole('Head of Family');
        $usera->family()->attach(1);


        $usera1 = User::factory()->create([
            'name' => 'user A1',
            'email' => 'usera1@test.com',
            'password' => bcrypt('usera1'),
        ]);
        $usera1->assignRole('Member');
        $usera1->family()->attach(1);

        $userb = User::factory()->create([
            'name' => 'user B',
            'email' => 'userb@test.com',
            'password' => bcrypt('userb'),
        ]);
        $userb->assignRole('Head Of Family');
        $userb->family()->attach(2);

        $userb1 = User::factory()->create([
            'name' => 'user B1',
            'email' => 'userb1@test.com',
            'password' => bcrypt('userb1'),
        ]);
        $userb1->assignRole('Member');
        $userb1->family()->attach(2);
    }
}
