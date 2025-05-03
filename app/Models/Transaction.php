<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'family_id',
        'category_id',
        'description',
        'amount',
        'mode_of_payment_id',
        'transaction_date',
        'type',
        'created_by',
        'updated_by'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('family', function (Builder $query) {
            $user = auth()->user();
            if ($user) {
                $query->where('family_id', Filament::getTenant()->id);
            }
        });
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function modeOfPayment(): BelongsTo
    {
        return $this->belongsTo(ModeOfPayment::class);
    }
}
