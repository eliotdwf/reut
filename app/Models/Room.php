<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'room_type',
        'capacity',
        'color',
        'access_conditions'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function accessibleTimes(): HasMany
    {
        return $this->hasMany(AccessibleTime::class);
    }

    /**
     * Check if the booking start/end time is valid based on the room's accessible times.
     * @param $weekday
     * @param $bookingTime start/end time of the booking to check
     * @return string|null the error message if the booking time is invalid, null if valid
     */
    public function checkBookingTimeValid($weekday, $bookingTime): string | null
    {
        $accessibleTimes = $this->accessibleTimes->firstWhere('weekday', $weekday);
        $opensAt = Carbon::parse($accessibleTimes->opens_at)->format('H:i');
        $closesAt = Carbon::parse($accessibleTimes->closes_at)->format('H:i');

        info("Checking booking time: {$bookingTime} between {$opensAt} and {$closesAt} on {$weekday}");

        if ($bookingTime < $opensAt || $bookingTime > $closesAt) {
            return 'La salle n\'est accessible qu\'entre ' . $opensAt . ' et ' . $closesAt . ' le ' . $weekday . '.';
        }

        return null;
    }

    /**
     * Check if a room is open on a specific weekday
     * @param $weekday ('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
     * @return bool
     */
    public function isRoomOpenByWeekday($weekday): bool
    {
        $accessibleTimes = $this->accessibleTimes->firstWhere('weekday', $weekday);
        if(!$accessibleTimes->opens_at || !$accessibleTimes->closes_at) {
            return false;
        }
        return true;
    }

    /**
     * Check if the room is already booked for the given time slot.
     * @param $weekday
     * @param $bookingStartsAt
     * @param $bookingEndsAt
     * @return bool
     */
    public function isAlreadyBooked($bookingStartsAt, $bookingEndsAt): bool
    {
        // datenewbookingdebut > datefinbooking OU datenewbookingfin < datedebutbooking => newBooking valide
        // bookingStartsAt <= ends_at && bookingEndsAt >= starts_at => newBooking invalide
        // I search for any booking that have a start time before or during the new booking's end time
        // and an end time after or during the new booking's start time
        $overlappingBookings = $this->bookings()
            ->where('starts_at', '<=', $bookingEndsAt)
            ->where('ends_at', '>=', $bookingStartsAt)
            ->get();

        return $overlappingBookings->isNotEmpty();
    }
}
