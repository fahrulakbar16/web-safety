<?php

namespace App\Actions\Event;

use App\Models\Exam;

class UpdateExamAction
{
    /**
     * Update an exam.
     */
    public function execute(Exam $exam, array $data): Exam
    {
        $exam->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'durasi' => $data['durasi'] ?? 0,
            'jumlah_soal' => $data['jumlah_soal'] ?? 0,
            'minimal_score' => $data['minimal_score'] ?? 0,
        ]);

        return $exam->fresh();
    }
}

