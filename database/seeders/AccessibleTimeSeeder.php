<?php

namespace Database\Seeders;

use App\Models\AccessibleTime;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessibleTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accessibleTimes = [
            ['day' => 'Monday', 'start_time' => '08:00:00', 'end_time' => '20:00:00'],
            ['day' => 'Tuesday', 'start_time' => '08:00:00', 'end_time' => '20:00:00'],
            ['day' => 'Wednesday', 'start_time' => '08:00:00', 'end_time' => '20:00:00'],
            ['day' => 'Thursday', 'start_time' => '08:00:00', 'end_time' => '20:00:00'],
            ['day' => 'Friday', 'start_time' => '08:00:00', 'end_time' => '20:00:00'],
            ['day' => 'Saturday', 'start_time' => '09:00:00', 'end_time' => '18:00:00'],
            ['day' => 'Sunday', 'start_time' => null, 'end_time' => null], // Closed on Sundays
        ];
        // retrieve all rooms from database and add a new accessible time for each room
        $rooms = Room::all();
        foreach ($rooms as $room) {
            foreach ($accessibleTimes as $time) {
                AccessibleTime::create([
                    'room_id' => $room->id,
                    'opens_at' => $time['start_time'],
                    'closes_at' => $time['end_time'],
                    'weekday' => $time['day'],
                ]);
            }
        }

    }
}
