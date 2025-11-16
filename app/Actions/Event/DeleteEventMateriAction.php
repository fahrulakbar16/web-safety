<?php

namespace App\Actions\Event;

use App\Models\EventMateri;
use Illuminate\Support\Facades\Storage;

class DeleteEventMateriAction
{
    /**
     * Delete an event materi.
     */
    public function execute(EventMateri $materi): void
    {
        // Delete file if exists
        if ($materi->file && Storage::disk('public')->exists($materi->file)) {
            Storage::disk('public')->delete($materi->file);
        }

        $materi->delete();
    }
}

