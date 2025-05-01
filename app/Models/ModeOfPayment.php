<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
