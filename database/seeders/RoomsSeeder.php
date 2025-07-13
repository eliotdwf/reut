<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'room_type_id' => 1,
                'access_conditions' => 'A compléter',
                'capacity' => 15,
            ],
            [
                'name' => 'Salle de Réunion 2',
                'description' => 'A compléter',
                'room_type_id' => 1,
                'access_conditions' => 'A compléter',
                'capacity' => 15,
            ],
            [
                'name' => 'Salle de réunion PSEC',
                'description' => 'A compléter',
                'room_type_id' => 1,
                'access_conditions' => 'A compléter',
            ],
            [
                'name' => 'Salle de réunion PAE',
                'description' => 'A compléter',
                'room_type_id' => 1,
                'access_conditions' => 'A compléter',
            ],
            [
                'name' => 'Salle de réunion PVDC',
                'description' => 'A compléter',
                'room_type_id' => 1,
                'access_conditions' => 'A compléter',
            ],
            [
                'name' => 'Salle de réunion PTE',
                'description' => 'A compléter',
                'room_type_id' => 1,
                'access_conditions' => 'A compléter',
            ],
            [
                'name' => 'Salle BFXXX (Danse)',
                'description' => 'A compléter',
                'room_type_id' => 3,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
            ],
            [
                'name' => 'Salle BFXXX (Musique)',
                'description' => 'A compléter',
                'room_type_id' => 2,
                'access_conditions' => 'Max 3h par semaine par personne pour les réservation individuelles, sans limite pour les réservations pour les assos',
            ],
            [
                'name' => "Salle d'Art",
                'description' => 'A compléter',
                'room_type_id' => 4,
                'access_conditions' => "La carte d'accès à la salle doit être récupérée au bureau du BDE en échange d'une caution (carte étudiante par exemple).",
            ],
        ];
        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
