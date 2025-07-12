<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asso extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // Use string for UUID
    protected $fillable = [
        'id',
        'shortname',
        'login',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(AssoMember::class);
    }

}
