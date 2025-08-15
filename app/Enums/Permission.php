<?php

namespace App\Enums;

use function Symfony\Component\String\s;

enum Permission: string
{
    case ALL='*'; // allow all permissions

    case VIEW_ASSOS = 'view.assos'; // allow viewing associations

    case CREATE_BOOKINGS_ASSOS_PAE = 'create.bookings.assos.pae'; // allow booking a room for any asso related to the PAE

    case CREATE_BOOKINGS_MUSIC_DANCE_ROOMS_ASSO = 'create.bookings.music-dance.rooms.asso'; // allow booking dance and music rooms for the association

    case CREATE_MULTIPLE_DAYS_BOOKINGS = 'create.multiple.days.bookings'; // allow creating bookings that span multiple days

    case CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE = "create.bookings.over-two-weeks-before"; // allow booking rooms over two weeks before the event

    case UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS = "update-delete.bookings.music-dance.rooms"; // allow updating and deleting bookings for music and dance rooms

    case UPDATE_DELETE_BOOKINGS_MDE_ROOMS = "update-delete.bookings.mde.rooms"; // allow updating and deleting bookings for MDE rooms

    case MANAGE_MDE_ROOMS = "manage.mde.rooms"; // allow creating, reading, updating and deleting MDE rooms
    case MANAGE_MUSIC_DANCE_ROOMS = "manage.music-dance.rooms"; // allow creating, reading, updating and deleting Music-Dance rooms

    public static function fromStrings(array $perms): array
    {
        $out = [];
        foreach ($perms as $perm) {
            $out[] = Permission::from($perm);
        }
        return $out;
    }

    public static function asListOfString(...$perms): array
    {
        $out = [];
        foreach ($perms as $perm) {
            if ($perm instanceof Permission) {
                $out[] = $perm->value;
            } else if (is_string($perm)) {
                $out[] = $perm;
            } else if (is_array($perm)) {
                $out = array_merge($out, $perm);
            }
        }
        return $out;
    }
}
