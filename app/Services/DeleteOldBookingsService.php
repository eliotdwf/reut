<?php

namespace App\Services;

class DeleteOldBookingsService
{

    public function run()
    {
        // Fetch bookings that finished over a year ago
        $oldBookings = \App\Models\Booking::where('ends_at', '<', now()->subYear())
            ->get();

        foreach ($oldBookings as $booking) {
            info("DeleteOldBookingsService: Deleting booking {$booking->id} that ended on {$booking->ends_at}");
            $booking->delete();
        }

        info("DeleteOldBookingsService: Deleted " . count($oldBookings) . " old bookings");
        return count($oldBookings);
    }
}
