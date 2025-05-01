<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaterTransaction extends Model
{
    protected $fillable = [
        'family_id',
        'user_id',
        'category_id',
        'description',
        'amount',
        'mode_of_payment_id',
        'transaction_date',
        'periods',
        'number_period',
        'is_paid',
        'paid_at',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function modeOfPayment()
    {
        return $this->belongsTo(ModeOfPayment::class);
    }
}
