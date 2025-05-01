<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'family_id',
        'user_id',
        'category_id',
        'description',
        'amount',
        'mode_of_payment_id',
        'transaction_date',
        'type',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
