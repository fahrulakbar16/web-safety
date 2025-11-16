<?php

namespace App\Actions\Driver;

use App\Models\Driver;
use Illuminate\Support\Facades\Storage;

class DeleteDriverAction
{
    /**
     * Delete a driver.
     */
    public function execute(Driver $driver): void
    {
        // Delete associated files
        if ($driver->foto_ktp && Storage::disk('public')->exists($driver->foto_ktp)) {
            Storage::disk('public')->delete($driver->foto_ktp);
        }

        if ($driver->foto_sim && Storage::disk('public')->exists($driver->foto_sim)) {
            Storage::disk('public')->delete($driver->foto_sim);
        }

        if ($driver->foto_diri && Storage::disk('public')->exists($driver->foto_diri)) {
            Storage::disk('public')->delete($driver->foto_diri);
        }

        $driver->delete();
    }
}

