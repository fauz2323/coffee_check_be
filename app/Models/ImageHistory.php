<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageHistory extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the history that owns the ImageHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function history()
    {
        return $this->belongsTo(HistoryCheck::class, 'history_id', 'id');
    }
}
