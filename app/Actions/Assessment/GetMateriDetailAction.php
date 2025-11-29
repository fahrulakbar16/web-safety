<?php

namespace App\Actions\Assessment;

use App\Models\EventMateri;
use App\Models\UserMateri;
use Illuminate\Support\Facades\Auth;

class GetMateriDetailAction
{
    /**
     * Get material detail with access check.
     */
    public function execute(EventMateri $eventMateri): array
    {
        $user = Auth::user();
        $eventMateri->load('event');

        // Check if previous materials are completed
        $previousMateris = EventMateri::where('event_id', $eventMateri->event_id)
            ->where('urutan', '<', $eventMateri->urutan)
            ->orderBy('urutan', 'asc')
            ->get();

        $canAccess = true;
        $blockingMateri = null;

        foreach ($previousMateris as $prevMateri) {
            $userMateri = UserMateri::where('user_id', $user->id)
                ->where('event_materi_id', $prevMateri->id)
                ->where('is_completed', true)
                ->first();

            if (!$userMateri) {
                $canAccess = false;
                $blockingMateri = $prevMateri;
                break;
            }
        }

        $userMateri = UserMateri::where('user_id', $user->id)
            ->where('event_materi_id', $eventMateri->id)
            ->first();

        return [
            'eventMateri' => $eventMateri,
            'canAccess' => $canAccess,
            'blockingMateri' => $blockingMateri,
            'isCompleted' => $userMateri?->is_completed ?? false,
        ];
    }
}

