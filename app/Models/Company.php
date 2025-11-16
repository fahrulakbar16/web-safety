<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'alamat',
        'logo',
        'status',
    ];

    /**
     * Get the drivers for the company.
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }
}
