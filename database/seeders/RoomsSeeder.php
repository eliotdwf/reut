<?php

namespace Database\Seeders;

use App\Enums\RoomType;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // seed database with rooms
        $rooms = [
            [
                'name' => 'Salle de Réunion 1',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'capacity' => 15,
                'color' => '#C1A5E5',
            ],
            [
                'name' => 'Salle de Réunion 2',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'capacity' => 15,
                'color' => '#D82008',
            ],
            [
                'name' => 'Salle de réunion PSEC',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#C9913E'
            ],
            [
                'name' => 'Salle de réunion PAE',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#9C30DB'
            ],
            [
                'name' => 'Salle de réunion PVDC',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#D17247'
            ],
            [
                'name' => 'Salle de réunion PTE',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#6C15EF'
            ],
            [
                'name' => 'Salle BFXXX (Danse)',
                'description' => 'A compléter',
                'room_type' => RoomType::DANCE->value,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
                'color' => '#44C582'
            ],
            [
                'name' => 'Salle BFXXX (Musique)',
                'description' => 'A compléter',
                'room_type' => RoomType::MUSIC->value,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
                'color' => '#C5C244'
                ],
            [
                'name' => "Salle d'Art",
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => "La carte d'accès à la salle doit être récupérée au bureau du BDE en échange d'une caution (carte étudiante par exemple).",
                'color' => '#C54447'
            ],
        ];
        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
