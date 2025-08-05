<?php

namespace App\Models;

use App\Enums\Permission as PermissionEnum;
use Carbon\Carbon;
use DateTime;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable implements FilamentUser, HasName
{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // Use string for UUID
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'dark_theme',
        'language',
    ];

    public function assosMemberships(): HasMany
    {
        return $this->hasMany(AssoMember::class);
    }

    public function assos(): BelongsToMany
    {
        return $this->belongsToMany(Asso::class, 'asso_members', 'user_id', 'asso_id')
            ->withPivot('role_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Checks if the user has a specific permission.
     * @param string $permission the permission to check
     * @return bool true if the user has the permission, false otherwise
     */
    public function hasPermission(string $permission): bool
    {
        return $this->hasPermissions([$permission]);
    }

    /**
     * Checks if the user has a list of permissions.
     * @param array $requiredPermissions an array of permissions to check against the user's permissions
     * @param bool $requireAll indicates if all permissions are required (true) or if any permission is sufficient (false)
     * @return bool true if the user has the permission, false otherwise
     */
    public function hasPermissions(array $requiredPermissions, bool $requireAll = true): bool
    {

        if (empty($requiredPermissions)) {
            return true; // If no permissions are required, return true
        }

        // Retrieve the user's permissions from the database
        $userPermissions = $this->permissions->pluck('value')->toArray();

        // If the user has the ALL permission, no need to check specific permissions
        if (in_array(PermissionEnum::ALL->value, $userPermissions)) {
            return true;
        }

        if ($requireAll) {
            // Check if all required permissions are present
            if (empty(array_diff($requiredPermissions, $userPermissions))) {
                return true;
            }
        } else {
            // If any of the specified permissions are present, return true
            if (!empty(array_intersect($requiredPermissions, $userPermissions))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Updates the user's permissions based on their associations and roles.
     * @return void
     */
    public function updatePermissions(): void
    {
        Log::debug('Removing all permissions for user ID: ' . $this->id);
        $this->permissions()->detach();

        Log::debug("Collecting permissions for user ID: " . $this->id);
        $userPermissions = [];

        foreach ($this->assosMemberships()->get() as $assoMember) {
            $assoId = $assoMember->asso->id;
            $roleId = $assoMember->role_id;

            $userPermissions = array_merge(
                $userPermissions,
                config("access.allowed.$assoId.$roleId", []),   // Permissions for the association and role
                config("access.allowed.$assoId.*", []),         // Default permissions for the association
                config("access.allowed.*.$roleId", []),         // Default permissions for the role
                config("access.allowed.*.*", [])                // Global default permissions
            );
        }

        foreach (PermissionEnum::fromStrings(array_unique($userPermissions)) as $permission) {
            Log::debug("Registering permission: " . $permission->name . " for user ID: " . $this->id);
            if ($id = Permission::where('key', $permission->name)->value('id')) {
                $this->permissions()->attach($id);
            }
        }
    }

    /**
     * Checks if the user can access the Filament panel.
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Returns true if the user can book a room for himself and not for an association.
     * This is based on whether the user has already reached the maximum time of personal bookings for the current week
     * @return bool
     */
    public function canMakePersoBooking(DateTime $starts_at, DateTime $ends_at): bool
    {

        //return false; // test the validation of the booking time

        // calculate time in minutes between the start and end of the booking
        $bookingTime = Carbon::instance($starts_at)->diffInMinutes(Carbon::instance($ends_at));

        // Get the current week start and end dates
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        // Count the time of personal bookings made by the user in the current week
        $personalBookingsCount = $this->bookings()
            ->where('booking_perso', true)
            ->whereBetween('starts_at', [$weekStart, $weekEnd]);

        foreach ($personalBookingsCount->get() as $booking) {
            $bookingTime += $booking->ends_at->diffInMinutes($booking->starts_at);
        }

        return $bookingTime < env('MAX_MINUTES_BOOKING_PERSO_PER_WEEK', 180); // 3 hours maximum personal booking time
    }

}
