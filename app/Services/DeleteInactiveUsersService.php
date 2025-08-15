<?php

namespace App\Services;

class DeleteInactiveUsersService
{

    public function run()
    {
        // Fetch users who have not logged in for over a year
        $inactiveUsers = \App\Models\User::where('last_login_at', '<', now()->subYear())
            ->get();

        foreach ($inactiveUsers as $user) {
            info("DeleteInactiveUsersService: Deleting user {$user->id} ({$user->first_name} {$user->last_name}) due to inactivity");
            $user->delete();
        }

        info("DeleteInactiveUsersService: Deleted " . count($inactiveUsers) . " inactive users");
        return count($inactiveUsers);
    }
}
