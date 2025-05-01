<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'name',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function modesOfPayment()
    {
        return $this->hasMany(ModeOfPayment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'family_user');
    }

    public function laterTransaction()
    {
        return $this->hasMany(LaterTransaction::class);
    }
}
