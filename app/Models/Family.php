<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    protected $fillable = [
        'name',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
    public function modeOfPayments(): HasMany
    {
        return $this->hasMany(ModeOfPayment::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'family_user');
    }

    public function laterTransaction(): HasMany
    {
        return $this->hasMany(LaterTransaction::class);
    }
}
