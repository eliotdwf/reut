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
        // seed database with rooms
        $rooms = [
            [
                'name' => 'Salle de Réunion 1',
                'number' => 'BFE 104',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'capacity' => 15,
                'color' => '#C1A5E5',
            ],
            [
                'name' => 'Salle de Réunion 2',
                'number' => 'BFE 204',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'capacity' => 15,
                'color' => '#D82008',
            ],
            [
                'name' => 'Salle de réunion PSEC',
                'number' => 'BFE 201A',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#C9913E'
            ],
            [
                'name' => 'Salle de réunion PAE',
                'number' => 'BFE 201B',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#9C30DB'
            ],
            [
                'name' => 'Salle de réunion PVDC',
                'number' => 'BFE 205',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#D17247'
            ],
            [
                'name' => 'Salle de réunion PTE',
                'number' => 'BFE 202',
                'description' => 'A compléter',
                'room_type' => RoomType::MDE->value,
                'access_conditions' => 'A compléter',
                'color' => '#6C15EF'
            ],
            [
                'name' => 'Salle FA106 (Danse)',
                'number' => 'BFA 106',
                'description' => 'A compléter',
                'room_type' => RoomType::DANCE->value,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
                'color' => '#44C582'
            ],
            [
                'name' => 'Salle FA109 (Musique, pratique collective)',
                'number' => 'BFA 109',
                'description' => 'A compléter',
                'room_type' => RoomType::MUSIC->value,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
                'color' => '#C5C244'
            ],
            [
                'name' => 'Salle FA107 (Musique, pratique individuelle)',
                'number' => 'BFA 107',
                'description' => 'A compléter',
                'room_type' => RoomType::MUSIC->value,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
                'color' => '#4CAF50'
            ],
            [
                'name' => 'Salle d\'Art',
                'number' => 'BFE 307',
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
