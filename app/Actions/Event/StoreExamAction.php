<?php

namespace App\Actions\Event;

use App\Models\Exam;

class StoreExamAction
{
    /**
     * Store a new exam.
     */
    public function execute(array $data): Exam
    {
        $exam = Exam::create([
            'event_id' => $data['event_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'durasi' => $data['durasi'] ?? 0,
            'jumlah_soal' => $data['jumlah_soal'] ?? 0,
            'minimal_score' => $data['minimal_score'] ?? 0,
        ]);

        return $exam;
    }
}

