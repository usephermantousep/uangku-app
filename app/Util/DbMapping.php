<?php

namespace App\Util;

class DbMapping
{
    public static function getSelectIsIncome(): array
    {
        return [
            0 => 'Expense',
            1 => 'Income',
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
