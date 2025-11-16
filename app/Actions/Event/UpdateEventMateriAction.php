<?php

namespace App\Actions\Event;

use App\Models\EventMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateEventMateriAction
{
    /**
     * Update an event materi.
     */
    public function execute(EventMateri $materi, Request $request, array $data): EventMateri
    {
        $filePath = $materi->file;

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($materi->file && Storage::disk('public')->exists($materi->file)) {
                Storage::disk('public')->delete($materi->file);
            }

            $file = $request->file('file');
            $filePath = $file->store('event_materis', 'public');
        }

        $materi->update([
            'urutan' => $data['urutan'] ?? $materi->urutan,
            'name' => $data['name'],
            'file' => $filePath,
            'type' => $data['type'] ?? $materi->type,
            'description' => $data['description'] ?? null,
            'attributes' => $data['attributes'] ?? null,
        ]);

        return $materi->fresh();
    }
}

