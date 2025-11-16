<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMateri extends Model
{
    protected $fillable = [
        'user_id',
        'event_materi_id',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the user materi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event materi that owns the user materi.
     */
    public function eventMateri(): BelongsTo
    {
        return $this->belongsTo(EventMateri::class);
    }
}
