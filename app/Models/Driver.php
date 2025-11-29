<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'alamat',
        'no_hp',
        'no_ktp',
        'no_sim',
        'foto_ktp',
        'foto_sim',
        'foto_diri',
        'status',
    ];

    /**
     * Get the user that owns the driver.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that owns the driver.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the company transfers for the driver.
     */
    public function companyTransfers(): HasMany
    {
        return $this->hasMany(DriverCompanyTransfer::class);
    }
}
