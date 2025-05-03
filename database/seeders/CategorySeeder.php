<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $categories = [
                // Pemasukan
                [
                    'name' => 'Gaji',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Bonus',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Freelance',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Penjualan Barang',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Investasi',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Hadiah',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Pendapatan Sampingan',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Pengembalian Dana',
                    'family_id' => $i,
                    'is_income' => 1,
                ],
                [
                    'name' => 'Lain-lain',
                    'family_id' => $i,
                    'is_income' => 1,
                ],

                // Pengeluaran
                [
                    'name' => 'Transportasi',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Tagihan Rumah Tangga',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Makanan & Minuman',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Kesehatan',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Pendidikan',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Hiburan',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Pakaian & Aksesori',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Perawatan Pribadi',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Kewajiban Agama',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Perbaikan & Pemeliharaan',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Utang & Cicilan',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
                [
                    'name' => 'Lain-lain',
                    'family_id' => $i,
                    'is_income' => 0,
                ],
            ];
            Category::insert($categories);
        }
    }
}
