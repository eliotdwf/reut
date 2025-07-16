<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'title',
        'starts_at',
        'ends_at',
        'open_to_others',
        'booking_perso',
        'room_id',
        'user_id',
        'asso_id'
    ];

    /**
     * Get the room associated with the booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function asso(): BelongsTo
    {
        return $this->belongsTo(Asso::class);
    }
}
