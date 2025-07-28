<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\RoomType;
use Carbon\Carbon;
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

    public function canUserUpdateDelete(User $user): bool
    {
        if(Carbon::parse($this->ends_at)->isPast()) {
            return false; // Cannot update/delete past bookings
        }

        $roomType = $this->room->room_type;
        $isMDERoom = $roomType === RoomType::MDE;
        $isMusicOrDanceRoom = $roomType === RoomType::MUSIC || $roomType === RoomType::DANCE;
        $hasPermissionMusicDance = $user->hasPermission(Permission::UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS->value);
        $hasPermissionMDE = $user->hasPermission(Permission::UPDATE_DELETE_BOOKINGS_MDE_ROOMS->value);
        return ($hasPermissionMDE && $isMDERoom) || ($hasPermissionMusicDance && $isMusicOrDanceRoom)
            || $user->id === $this->user_id;
    }

}
