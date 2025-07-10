<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssoMember extends Model
{
    protected $fillable = [
        'asso_id',
        'user_id',
        'role_id',
    ];
    public $timestamps = false;

    public function asso(): BelongsTo
    {
        return $this->belongsTo(Asso::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
