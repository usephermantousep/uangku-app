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
}
