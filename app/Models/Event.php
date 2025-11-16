<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];

    /**
     * Get the event materis for the event.
     */
    public function eventMateris(): HasMany
    {
        return $this->hasMany(EventMateri::class);
    }

    /**
     * Get the exams for the event.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
