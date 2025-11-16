<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'urutan',
        'soal',
    ];

    /**
     * Get the exam that owns the exam question.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the exam question options for the exam question.
     */
    public function examQuestionOptions(): HasMany
    {
        return $this->hasMany(ExamQuestionOption::class);
    }

    /**
     * Get the exam answers for the exam question.
     */
    public function examAnswers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
