<?php

namespace Database\Seeders;

use App\Models\Asso;
use App\Models\AssoMember;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoomsSeeder::class,
            AccessibleTimeSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
