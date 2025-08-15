<?php

use App\Enums\Permission as P;

return [
    // to get the allowed access path id /allowed/<assos_id>/<role_id>
    "allowed" => [
        "*" => [
            "5e03dae0-3af5-11e9-a43d-b3a93bca68c7" => P::asListOfString(P::CREATE_BOOKINGS_MUSIC_DANCE_ROOMS_ASSO), // president
        ],
        "6dff8940-3af5-11e9-a76b-d5944de919ff" => [ // BDE-UTC
            "*" => P::asListOfString(P::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE), // all members
            "5e03dae0-3af5-11e9-a43d-b3a93bca68c7" => P::asListOfString(P::UPDATE_DELETE_BOOKINGS_MDE_ROOMS, P::MANAGE_MDE_ROOMS, P::CREATE_MULTIPLE_DAYS_BOOKINGS, P::VIEW_ASSOS), // president
            "cf2e1161-6aa6-11ee-84cb-00505600cfa6" => P::asListOfString(P::UPDATE_DELETE_BOOKINGS_MDE_ROOMS, P::MANAGE_MDE_ROOMS, P::CREATE_MULTIPLE_DAYS_BOOKINGS, P::VIEW_ASSOS), // responsable locaux
            "cf2e2766-6aa6-11ee-84cb-00505600cfa6" => P::asListOfString(P::UPDATE_DELETE_BOOKINGS_MDE_ROOMS, P::MANAGE_MDE_ROOMS, P::CREATE_MULTIPLE_DAYS_BOOKINGS, P::VIEW_ASSOS) // team locaux
        ],
        "6e105220-3af5-11e9-95ce-1f406c6cfae9" => [ // SiMDE - pour les tests uniquement
            "5e03dae0-3af5-11e9-a43d-b3a93bca68c7" => P::asListOfString(P::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE, P::MANAGE_MDE_ROOMS, P::CREATE_MULTIPLE_DAYS_BOOKINGS, P::VIEW_ASSOS, P::CREATE_BOOKINGS_ASSOS_PAE, P::UPDATE_DELETE_BOOKINGS_MDE_ROOMS), // president
        ],
        "6e1f5580-3af5-11e9-a85f-31a81ca6ffa0" => [ // PAE
            "*" => P::asListOfString(P::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE), // all members
            "5e03dae0-3af5-11e9-a43d-b3a93bca68c7" => P::asListOfString(P::UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS, P::CREATE_BOOKINGS_ASSOS_PAE, P::MANAGE_MUSIC_DANCE_ROOMS), // president
            "cf2e1161-6aa6-11ee-84cb-00505600cfa6" => P::asListOfString(P::UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS, P::MANAGE_MUSIC_DANCE_ROOMS), // responsable locaux
        ],
        "6e2dc2f0-3af5-11e9-aed8-514dc3b25814" => [ // PSEC
            "*" => P::asListOfString(P::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE), // all members
        ],
        "6e3cc240-3af5-11e9-ac2d-2f9c2cef5b73" => [ // PTE
            "*" => P::asListOfString(P::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE), // all members
        ],
        "6e4a9940-3af5-11e9-9498-63b959403b7a" => [ // PVDC
            "*" => P::asListOfString(P::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE), // all members
        ],
    ]
];
