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
            ['weekday' => 'Monday', 'opens_at' => '08:00:00', 'closes_at' => '20:00:00'],
            ['weekday' => 'Tuesday', 'opens_at' => '08:00:00', 'closes_at' => '20:00:00'],
            ['weekday' => 'Wednesday', 'opens_at' => '08:00:00', 'closes_at' => '20:00:00'],
            ['weekday' => 'Thursday', 'opens_at' => '08:00:00', 'closes_at' => '20:00:00'],
            ['weekday' => 'Friday', 'opens_at' => '08:00:00', 'closes_at' => '20:00:00'],
            ['weekday' => 'Saturday', 'opens_at' => '09:00:00', 'closes_at' => '18:00:00'],
            ['weekday' => 'Sunday', 'opens_at' => null, 'closes_at' => null], // Closed on Sundays
        ];
        // retrieve all rooms from database and add a new accessible time for each room
        $rooms = Room::all();
        foreach ($rooms as $room) {
            foreach ($accessibleTimes as $time) {
                AccessibleTime::create([
                    'room_id' => $room->id,
                    'opens_at' => $time['opens_at'],
                    'closes_at' => $time['closes_at'],
                    'weekday' => $time['weekday'],
                ]);
            }
        }

    }
}
