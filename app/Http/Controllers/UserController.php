<?php

namespace App\Http\Controllers;

use App\Models\Asso;
use App\Models\AssoMember;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function createOrUpdateUser($userDetails, $currentAssociations): User {
        $user = User::updateOrCreate(
            ['id' => $userDetails['uuid']],
            [
                'first_name' => $userDetails['firstName'],
                'last_name' => $userDetails['lastName'],
                'email' => $userDetails['email'],
            ]
        );

        // delete all associations of the user
        info("Deleting all associations of the user: ".$user->id);
        AssoMember::where('user_id', $user->id)->delete();

        foreach ($currentAssociations as $currentAsso) {
            $asso = Asso::where('id', $currentAsso['id'])->first();
            // Check if the association already exists in the database based on its uuid
            if (!$asso) {
                // If it does not exist, create a new association
                info("Creating new association: ".$currentAsso['shortname']);
                $asso = Asso::create([
                    'id' => $currentAsso['id'],
                    'shortname' => $currentAsso['shortname'],
                    'login' => $currentAsso['login'],
                ]);
            }
            // Check if the user is already a member of the association - should never happen since we delete all user memberships to associations
            if(AssoMember::where('user_id', $user->id)
                ->where('asso_id', $asso->id)
                ->where('role_id', $currentAsso['user_role']['id'])
                ->exists()) {
                info("User is already a member of the association: ".$asso->shortname);
            }
            else {
                info("Adding user ".$user->id." to the association: ".$asso->shortname." with role: ".$currentAsso['user_role']['id']);
                AssoMember::create(
                    [
                        'user_id' => $user->id,
                        'asso_id' => $asso->id,
                        'role_id' => $currentAsso['user_role']['id'],
                    ]
                );
            }
        }
        return $user;
    }
}
