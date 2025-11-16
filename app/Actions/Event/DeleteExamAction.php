<?php

namespace App\Actions\Event;

use App\Models\Exam;

class DeleteExamAction
{
    /**
     * Delete an exam.
     */
    public function execute(Exam $exam): void
    {
        // Check if exam has related data
        if ($exam->examQuestions()->count() > 0) {
            throw new \Exception('Ujian tidak dapat dihapus karena masih memiliki soal terkait.');
        }

        if ($exam->examAttempts()->count() > 0) {
            throw new \Exception('Ujian tidak dapat dihapus karena masih memiliki attempt terkait.');
        }

        $exam->delete();
    }
}

