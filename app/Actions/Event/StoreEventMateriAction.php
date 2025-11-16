<?php

namespace App\Actions\Event;

use App\Models\EventMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreEventMateriAction
{
    /**
     * Store a new event materi.
     */
    public function execute(Request $request, array $data): EventMateri
    {
        $filePath = null;

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('event_materis', 'public');
        }

        $materi = EventMateri::create([
            'event_id' => $data['event_id'],
            'urutan' => $data['urutan'] ?? 1,
            'name' => $data['name'],
            'file' => $filePath,
            'type' => $data['type'] ?? 'text',
            'description' => $data['description'] ?? null,
            'attributes' => $data['attributes'] ?? null,
        ]);

        return $materi;
    }
}

