<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class DeleteEventAction
{
    /**
     * Delete an event.
     */
    public function execute(Event $event): void
    {
        // Check if event has related data
        if ($event->eventMateris()->count() > 0) {
            throw new \Exception('Event tidak dapat dihapus karena masih memiliki materi terkait.');
        }

        if ($event->exams()->count() > 0) {
            throw new \Exception('Event tidak dapat dihapus karena masih memiliki ujian terkait.');
        }

        // Delete image if exists
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();
    }
}

