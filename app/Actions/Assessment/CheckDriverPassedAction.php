<?php

namespace App\Actions\Assessment;

use App\Models\Event;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;

class CheckDriverPassedAction
{
    /**
     * Check if driver has passed all exams for any active event.
     *
     * @return array{is_passed: bool, passed_event: array|null}
     */
    public function execute(): array
    {
        $user = Auth::user();

        // Get all active events
        $events = Event::where('status', 'active')
            ->with(['exams'])
            ->get();

        foreach ($events as $event) {
            // Check if event has exams
            if ($event->exams->isEmpty()) {
                continue;
            }

            // Check if all exams for this event have been passed
            $allExamsPassed = true;
            foreach ($event->exams as $exam) {
                $passedAttempt = ExamAttempt::where('user_id', $user->id)
                    ->where('exam_id', $exam->id)
                    ->where('score', '>=', $exam->minimal_score)
                    ->first();

                if (!$passedAttempt) {
                    $allExamsPassed = false;
                    break;
                }
            }

            if ($allExamsPassed) {
                return [
                    'is_passed' => true,
                    'passed_event' => [
                        'id' => $event->id,
                        'name' => $event->name,
                        'description' => $event->description,
                        'image' => $event->image,
                    ],
                ];
            }
        }

        return [
            'is_passed' => false,
            'passed_event' => null,
        ];
    }
}

