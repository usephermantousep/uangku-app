<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyUser extends Model
{
    protected $table = 'family_user';

    protected $fillable = [
        'user_id',
        'family_id',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
