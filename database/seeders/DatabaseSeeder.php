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
        $this->call([
            FamilySeeder::class,
            UserSeeder::class,
            FamilyUserSeeder::class,
            CategorySeeder::class,
            ModeOfPaymentSeeder::class,
            TransactionSeeder::class,
            LaterTransactionSeeder::class
        ]);
    }
}
