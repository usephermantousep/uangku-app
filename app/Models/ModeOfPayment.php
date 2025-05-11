<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModeOfPayment extends Model
{
    protected $fillable = [
        'family_id',
        'name',
        'description',
        'is_transaction'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('family', function (Builder $query) {
            $user = auth()->user();
            if ($user) {
                $query->whereBelongsTo($user->family);
            }
            $query->orderBy('name');
        });
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function laterTransactions(): HasMany
    {
        return $this->hasMany(LaterTransaction::class);
    }

    public static function initNewFamily($familyId): void
    {
        $modeOfPayments = [
            [
                'name' => 'Tunai',
                'description' => 'Pembayaran langsung dengan uang tunai',
                'family_id' => $familyId,
                'is_transaction' => 1,
            ],
            [
                'name' => 'Transfer Bank',
                'description' => 'Pembayaran melalui rekening bank',
                'family_id' => $familyId,
                'is_transaction' => 1,
            ],
            [
                'name' => 'E-Wallet (GoPay,OVO dll)',
                'description' => 'Pembayaran melalui dompet digital',
                'family_id' => $familyId,
                'is_transaction' => 1,
            ],
            [
                'name' => 'Kartu Kredit',
                'description' => 'Pembayaran menggunakan kartu kredit',
                'family_id' => $familyId,
                'is_transaction' => 0,
            ],
            [
                'name' => 'Kartu Debit',
                'description' => 'Pembayaran menggunakan kartu debit',
                'family_id' => $familyId,
                'is_transaction' => 1,
            ],
            [
                'name' => 'Pay Later',
                'description' => 'Pembayaran menggunakan metode pay later',
                'family_id' => $familyId,
                'is_transaction' => 0,
            ],
            [
                'name' => 'Virtual Account',
                'description' => 'Pembayaran menggunakan virtual account',
                'family_id' => $familyId,
                'is_transaction' => 1,
            ],
        ];
        self::insert($modeOfPayments);
    }
}
