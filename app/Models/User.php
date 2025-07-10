<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
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
        'firstname',
        'lastname',
        'email',
        'dark_theme',
        'language',
    ];

    public function assos(): HasMany
    {
        return $this->hasMany(AssoMember::class);
    }

}
