<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessibleTime extends Model
{
    //
    public $timestamps = false;

    protected $fillable = [
        'weekday',
        'opens_at',
        'closes_at',
        'room_id',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

}
