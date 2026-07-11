<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * QuestionScore model for the GeliScope application.
 *
 * Stores the score for an individual question within an exam result.
 *
 * @property int   $id
 * @property int   $exam_result_id
 * @property int   $question_number
 * @property float $score
 * @property float $max_score
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class QuestionScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'exam_result_id',
        'question_number',
        'score',
        'max_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'max_score' => 'decimal:2',
        ];
    }

    /**
     * Get the exam result this question score belongs to.
     */
    public function examResult(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class);
    }
}
