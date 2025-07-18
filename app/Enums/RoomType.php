<?php

namespace App\Enums;

enum RoomType: string
{

    case MDE = 'MDE';
    case MUSIC = 'Musique';
    case DANCE = 'Danse';

    public function bookingPersoAllowed(): bool
    {
        return match ($this) {
            self::MDE => false,
            self::MUSIC, self::DANCE => true,
        };
    }

    public function fullLabel() : string
    {
        return $this->value." (Réservation individuelle " . ($this->bookingPersoAllowed() ? 'autorisée)' : 'NON autorisée)');
    }

    public static function values(): array
    {
        return array_map(fn($type) => $type->value, self::cases());
    }

    /**
     * Return the list the enums values that allow Personal Booking.
     * @return array
     */
    public static function bookingPersoAllowedValues(): array
    {
        return array_filter(
            self::values(),
            fn($value) => self::from($value)->bookingPersoAllowed()
        );
    }

    /**
     * Return the list the enums values that DOES NOT allow Personal Booking.
     * @return array
     */
    public static function bookingPersoNotAllowedValues(): array
    {
        return array_filter(
            self::values(),
            fn($value) => !self::from($value)->bookingPersoAllowed()
        );
    }

    public static function casesAsKeyValueArray(bool $withBookingPersoInfo = false): array
    {
        return collect(self::cases())
            ->mapWithKeys(function (self $case) use ($withBookingPersoInfo) {
                $key = $case->value;
                $value = $case->value;
                if($withBookingPersoInfo) {
                    $value = $case->fullLabel();
                }
                return [$key => $value];
            })
            ->toArray();
    }

}
