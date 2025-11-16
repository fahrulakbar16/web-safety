<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateEventAction
{
    /**
     * Update an event.
     */
    public function execute(Event $event, Request $request, array $data): Event
    {
        $imagePath = $event->image;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }

            $file = $request->file('image');
            $imagePath = $file->store('events', 'public');
        }
        // If no file uploaded, keep existing image (don't update image field)

        $event->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $imagePath,
            'status' => $data['status'] ?? 'active',
        ]);

        return $event->fresh();
    }
}

