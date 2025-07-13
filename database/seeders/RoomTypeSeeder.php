<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed room types
        $roomTypes = [
            [
                'label' => 'MDE',
                'booking_perso_allowed' => false,
            ],
            [
                'label' => 'Musique',
                'booking_perso_allowed' => true,
            ],
            [
                'label' => 'Danse',
                'booking_perso_allowed' => true,
            ],
            [
                'label' => 'Art',
                'booking_perso_allowed' => false,
            ],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}
