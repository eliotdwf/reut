<?php

namespace Database\Seeders;

use App\Models\Asso;
use App\Models\AssoMember;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // For testing purpose only !!!
        $fakeUsers = [
            [
                "id" => Str::uuid(),
                "first_name" => "Fake",
                "last_name" => "User",
                "email" => "fake.user@mail.com",
                "last_login_at" => now(),
            ],
            [
                "id" => Str::uuid(),
                "first_name" => "Inactive",
                "last_name" => "User",
                "email" => "inactive.user@mail.com",
                "last_login_at" => now()->subYears(2),
            ]
        ];

        $assoId = Str::uuid();
        $fakeAsso = [
            "id" => $assoId,
            "shortname" => "Fake Association",
            "login" => "fakeasso",
        ];
        Asso::create($fakeAsso);

        foreach ($fakeUsers as $userData) {
            $user = User::create($userData);
            // associate the user to the fake association
            AssoMember::create(
                [
                    'user_id' => $user->id,
                    'asso_id' => $assoId,
                    'role_id' => Str::uuid(),
                ]
            );
            // create a booking for the user that ended over a year ago
            Booking::create([
                "title" => "Fake Booking". " for " . $user->first_name . " " . $user->last_name,
                "user_id" => $user->id,
                "room_id" => 7,
                "starts_at" => now()->addDays(4),
                "ends_at" => now()->addDays(4)->addHour(),
            ]);
        }
    }
}
