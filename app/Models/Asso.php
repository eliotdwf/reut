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
        'parent_id'
    ];

    public function members(): HasMany
    {
        return $this->hasMany(AssoMember::class);
    }

    public static function assosPAE()
    {
        return self::where('parent_id', '6e1f5580-3af5-11e9-a85f-31a81ca6ffa0')->get();
    }

}
