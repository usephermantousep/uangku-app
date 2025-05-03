<?php

namespace App\Models;

use App\Observers\ModeOfPaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ModeOfPaymentObserver::class])]
class ModeOfPayment extends Model
{
    protected $fillable = [
        'family_id',
        'name',
        'description',
        'is_transaction'
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function laterTransactions()
    {
        return $this->hasMany(LaterTransaction::class);
    }
}
