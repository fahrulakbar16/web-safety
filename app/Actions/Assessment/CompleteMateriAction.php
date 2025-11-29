<?php

namespace App\Actions\Assessment;

use App\Models\EventMateri;
use App\Models\UserMateri;
use Illuminate\Support\Facades\Auth;

class CompleteMateriAction
{
    /**
     * Mark a material as completed.
     */
    public function execute(EventMateri $eventMateri): void
    {
        $user = Auth::user();

        // Check if previous materials are completed (sequential requirement)
        $previousMateris = EventMateri::where('event_id', $eventMateri->event_id)
            ->where('urutan', '<', $eventMateri->urutan)
            ->orderBy('urutan', 'asc')
            ->get();

        foreach ($previousMateris as $prevMateri) {
            $userMateri = UserMateri::where('user_id', $user->id)
                ->where('event_materi_id', $prevMateri->id)
                ->where('is_completed', true)
                ->first();

            if (!$userMateri) {
                throw new \Exception('Anda harus menyelesaikan materi sebelumnya terlebih dahulu.');
            }
        }

        // Mark as completed
        UserMateri::updateOrCreate(
            [
                'user_id' => $user->id,
                'event_materi_id' => $eventMateri->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );
    }
}

