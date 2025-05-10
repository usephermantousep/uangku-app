<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', "guard_name" => "web"],
            ['name' => 'Head of Family', "guard_name" => "web"],
            ['name' => 'Member', "guard_name" => "web"],
        ];

        Role::insert($roles);
    }
}
