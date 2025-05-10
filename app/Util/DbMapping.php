<?php

namespace App\Util;

class DbMapping
{
    public static function getSelectIsIncome($locale): array
    {
        return [
            0 => $locale == 'en' ? 'Expense' : "Pengeluaran",
            1 => $locale == 'en' ? 'Income' : "Pendapatan",
        ];
    }

    public static function getIsTransactionOrLater(): array
    {
        return [
            0 => 'Later',
            1 => 'Transaction',
        ];
    }
}
