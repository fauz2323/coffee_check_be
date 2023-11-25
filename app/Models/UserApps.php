<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UserApps extends Model
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password'
    ];

    /**
     * Get all of the comments for the UserApps
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(HistoryCheck::class, 'user_id', 'id');
    }
}
