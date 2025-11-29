<?php

namespace App\Actions\Assessment;

use App\Models\Event;
use App\Models\ExamAttempt;
use App\Models\UserMateri;
use Illuminate\Support\Facades\Auth;

class GetAssessmentDataAction
{
    /**
     * Get assessment data with events, materials, and progress.
     */
    public function execute(): array
    {
        $user = Auth::user();

        // Get all active events
        $events = Event::where('status', 'active')
            ->with(['eventMateris' => function ($query) {
                $query->orderBy('urutan', 'asc');
            }])
            ->get();

        // Get user's completed materials
        $completedMateris = UserMateri::where('user_id', $user->id)
            ->where('is_completed', true)
            ->pluck('event_materi_id')
            ->toArray();

        // Process events with progress
        $eventsWithProgress = $events->map(function ($event) use ($user, $completedMateris) {
            $materis = $event->eventMateris->map(function ($materi) use ($completedMateris, $user) {
                $isCompleted = in_array($materi->id, $completedMateris);
                $userMateri = UserMateri::where('user_id', $user->id)
                    ->where('event_materi_id', $materi->id)
                    ->first();

                return [
                    'id' => $materi->id,
                    'name' => $materi->name,
                    'description' => $materi->description,
                    'type' => $materi->type,
                    'file' => $materi->file,
                    'urutan' => $materi->urutan,
                    'is_completed' => $isCompleted,
                    'completed_at' => $userMateri?->completed_at,
                ];
            });

            // Check if all materials are completed
            $allMaterisCompleted = $materis->every(function ($materi) {
                return $materi['is_completed'];
            });

            // Get exams for this event
            $exams = $event->exams->map(function ($exam) use ($user) {
                $attempts = ExamAttempt::where('user_id', $user->id)
                    ->where('exam_id', $exam->id)
                    ->get();

                return [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'description' => $exam->description,
                    'durasi' => $exam->durasi,
                    'jumlah_soal' => $exam->jumlah_soal,
                    'minimal_score' => $exam->minimal_score,
                    'attempts' => $attempts->map(function ($attempt) use ($exam) {
                        return [
                            'id' => $attempt->id,
                            'score' => $attempt->score,
                            'is_passed' => $attempt->score >= $exam->minimal_score,
                            'finished_at' => $attempt->finished_at,
                        ];
                    }),
                ];
            });

            return [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $event->description,
                'image' => $event->image,
                'materis' => $materis,
                'exams' => $exams,
                'all_materis_completed' => $allMaterisCompleted,
                'total_materis' => $materis->count(),
                'completed_materis' => $materis->where('is_completed', true)->count(),
            ];
        });

        return $eventsWithProgress->toArray();
    }
}

