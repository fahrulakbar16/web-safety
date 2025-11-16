<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreEventAction
{
    /**
     * Store a new event.
     */
    public function execute(Request $request, array $data): Event
    {
        $imagePath = null;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imagePath = $file->store('events', 'public');
        }

        $event = Event::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $imagePath,
            'status' => $data['status'] ?? 'active',
        ]);

        return $event;
    }
}

