<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'family_id',
        'description',
        'is_income'
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function laterTransaction()
    {
        return $this->hasMany(LaterTransaction::class);
    }
}
