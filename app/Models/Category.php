<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'family_id',
        'description',
        'is_income'
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

    public function laterTransaction(): HasMany
    {
        return $this->hasMany(LaterTransaction::class);
    }

    public static function initNewFamily($familyId): void
    {
        $categories = [
            [
                'name' => 'Gaji',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Bonus',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Penjualan Barang',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Investasi',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Hadiah',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Pendapatan Sampingan',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Pengembalian Dana',
                'family_id' => $familyId,
                'is_income' => 1,
            ],
            [
                'name' => 'Lain-lain',
                'family_id' => $familyId,
                'is_income' => 1,
            ],

            // Pengeluaran
            [
                'name' => 'Transportasi',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Tagihan Rumah Tangga',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Makanan & Minuman',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Kesehatan',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Pendidikan',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Hiburan',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Pakaian & Aksesori',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Perawatan Pribadi',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Kewajiban Agama',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Perbaikan & Pemeliharaan',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Utang & Cicilan',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
            [
                'name' => 'Lain-lain',
                'family_id' => $familyId,
                'is_income' => 0,
            ],
        ];

        self::insert($categories);
    }
}
