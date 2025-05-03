<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LaterTransaction;
use App\Models\ModeOfPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;

class LaterTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker\Factory::create();
        $categories = Category::where('is_income', 0)->get();
        $modeOfPayments = ModeOfPayment::where('is_transaction', 0)->get();
        $startDate = now()->startOfDay()->startOfMonth();
        $endDate = now()->endOfDay()->endOfMonth();
        $transactions = [];

        for ($i = 0; $i < 100; $i++) {
            $user = $faker->numberBetween(2, 3);
            $familyId = $user == 2 ? 1 : 2;
            $category = $categories->where('family_id', $familyId)->random();
            $modeOfPayment = $modeOfPayments->where('family_id', $familyId)->random();
            $transactions[] = [
                'family_id' => $familyId,
                'category_id' => $category->id,
                'mode_of_payment_id' => $modeOfPayment->id,
                'amount' => $faker->numberBetween(10000, 1000000),
                'description' => $faker->sentence(3),
                'transaction_date' => $faker->dateTimeBetween($startDate, $endDate),
                'periods' => $faker->numberBetween(1, 12),
                'number_period' => $faker->numberBetween(1, 12),
                'is_paid' => $faker->boolean(50),
                'paid_at' => $faker->boolean(50) ? $faker->dateTimeBetween($startDate, $endDate) : null,
                'created_by' => $user,
                'updated_by' => $user,
            ];
        }
        LaterTransaction::insert($transactions);
    }
}
