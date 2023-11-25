<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryCheck extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the user that owns the HistoryCheck
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UserApps::class, 'user_id', 'id');
    }

    /**
     * Get the image associated with the HistoryCheck
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(ImageHistory::class, 'history_id', 'id');
    }
}
