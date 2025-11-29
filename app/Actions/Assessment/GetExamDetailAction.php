<?php

namespace App\Actions\Assessment;

use App\Models\Event;
use App\Models\EventMateri;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\UserMateri;
use Illuminate\Support\Facades\Auth;

class GetExamDetailAction
{
    /**
     * Get exam detail for driver.
     */
    public function execute(Event $event, Exam $exam): array
    {
        $user = Auth::user();

        // Check if all materials are completed
        $eventMateris = EventMateri::where('event_id', $event->id)
            ->orderBy('urutan', 'asc')
            ->get();

        $allCompleted = true;
        foreach ($eventMateris as $materi) {
            $userMateri = UserMateri::where('user_id', $user->id)
                ->where('event_materi_id', $materi->id)
                ->where('is_completed', true)
                ->first();

            if (!$userMateri) {
                $allCompleted = false;
                break;
            }
        }

        if (!$allCompleted) {
            throw new \Exception('Anda harus menyelesaikan semua materi terlebih dahulu sebelum mengikuti ujian.');
        }

        $exam->load(['event', 'examQuestions.examQuestionOptions']);

        // Get user's previous attempts
        $attempts = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
            ],
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'description' => $exam->description,
                'durasi' => $exam->durasi,
                'jumlah_soal' => $exam->jumlah_soal,
                'minimal_score' => $exam->minimal_score,
                'exam_questions' => $exam->examQuestions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'urutan' => $question->urutan,
                        'soal' => $question->soal,
                        'exam_question_options' => $question->examQuestionOptions->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'opsi' => $option->opsi,
                                'jawaban' => $option->jawaban,
                                // Don't expose is_correct to prevent cheating
                            ];
                        }),
                    ];
                })->sortBy('urutan')->values(),
            ],
            'attempts' => $attempts->map(function ($attempt) use ($exam) {
                return [
                    'id' => $attempt->id,
                    'score' => $attempt->score,
                    'is_passed' => $attempt->score >= $exam->minimal_score,
                    'started_at' => $attempt->started_at,
                    'finished_at' => $attempt->finished_at,
                ];
            }),
        ];
    }
}

