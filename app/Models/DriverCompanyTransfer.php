<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverCompanyTransfer extends Model
{
    protected $fillable = [
        'driver_id',
        'old_company_id',
        'new_company_id',
        'surat_pengunduran_diri',
        'screenshot_quiz',
        'notes',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the driver that owns the transfer.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the old company.
     */
    public function oldCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'old_company_id');
    }

    /**
     * Get the new company.
     */
    public function newCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'new_company_id');
    }

    /**
     * Get the user who approved the transfer.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
