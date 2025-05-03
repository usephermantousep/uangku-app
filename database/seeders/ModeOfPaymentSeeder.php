<?php

namespace Database\Seeders;

use App\Models\ModeOfPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModeOfPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $modeOfPayments = [
                [
                    'name' => 'Tunai',
                    'description' => 'Pembayaran langsung dengan uang tunai',
                    'family_id' => $i,
                    'is_transaction' => 1,
                ],
                [
                    'name' => 'Transfer Bank',
                    'description' => 'Pembayaran melalui rekening bank',
                    'family_id' => $i,
                    'is_transaction' => 1,
                ],
                [
                    'name' => 'E-Wallet (OVO, GoPay, dll)',
                    'description' => 'Pembayaran melalui dompet digital',
                    'family_id' => $i,
                    'is_transaction' => 1,
                ],
                [
                    'name' => 'Kartu Kredit',
                    'description' => 'Pembayaran menggunakan kartu kredit',
                    'family_id' => $i,
                    'is_transaction' => 0,
                ],
                [
                    'name' => 'Kartu Debit',
                    'description' => 'Pembayaran menggunakan kartu debit',
                    'family_id' => $i,
                    'is_transaction' => 1,
                ],
                [
                    'name' => 'Pay Later',
                    'description' => 'Pembayaran menggunakan metode pay later',
                    'family_id' => $i,
                    'is_transaction' => 0,
                ],
                [
                    'name' => 'Virtual Account',
                    'description' => 'Pembayaran menggunakan virtual account',
                    'family_id' => $i,
                    'is_transaction' => 1,
                ],
            ];
            ModeOfPayment::insert($modeOfPayments);
        }
    }
}
