<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'description',
        'leader_id',
    ];

    /**
     * Get the leader of the division
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get all members in this division
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'division_id');
    }
}
