<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventMateri extends Model
{
    protected $fillable = [
        'event_id',
        'urutan',
        'name',
        'file',
        'type',
        'description',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    /**
     * Get the event that owns the event materi.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user materis for the event materi.
     */
    public function userMateris(): HasMany
    {
        return $this->hasMany(UserMateri::class);
    }
}
