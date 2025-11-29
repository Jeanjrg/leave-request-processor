<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApplication extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'address_during_leave',
        'emergency_contact',
        'status',
        'leader_approved_at',
        'leader_rejection_note',
        'hrd_approved_at',
        'hrd_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'leader_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the leave application
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
