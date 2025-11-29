<?php

namespace App\Actions\Assessment;

use App\Models\Event;
use App\Models\EventMateri;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\UserMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmitExamAction
{
    /**
     * Submit exam answers and calculate score.
     */
    public function execute(Request $request, Event $event, Exam $exam): array
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

        $answers = $request->input('answers', []);

        // Create exam attempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        // Load exam questions with correct answers
        $exam->load('examQuestions.examQuestionOptions');
        $totalQuestions = $exam->examQuestions->count();
        $correctAnswers = 0;

        // Process answers
        foreach ($exam->examQuestions as $question) {
            $selectedOptionId = $answers[$question->id] ?? null;

            if ($selectedOptionId) {
                $selectedOption = $question->examQuestionOptions->firstWhere('id', $selectedOptionId);

                if ($selectedOption) {
                    ExamAnswer::create([
                        'exam_attempt_id' => $attempt->id,
                        'exam_question_id' => $question->id,
                        'exam_question_option_id' => $selectedOptionId,
                        'is_correct' => $selectedOption->is_correct,
                    ]);

                    if ($selectedOption->is_correct) {
                        $correctAnswers++;
                    }
                }
            }
        }

        // Calculate score
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        $isPassed = $score >= $exam->minimal_score;

        // Update attempt with score
        $attempt->update([
            'score' => $score,
        ]);

        // Reload attempts to include the new one
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
            'examResult' => [
                'score' => $score,
                'is_passed' => $isPassed,
            ],
        ];
    }
}

