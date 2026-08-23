<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ExamResult model for the GeliScope application.
 *
 * Stores the overall result for a single student on a single exam.
 * Links to individual question-level scores and optionally references
 * a MongoDB analysis document via mongo_analysis_id.
 *
 * @property int         $id
 * @property int         $exam_id
 * @property int         $student_id
 * @property float       $total_score
 * @property float       $max_score
 * @property string      $grade
 * @property string|null $mongo_analysis_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ExamResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'total_score',
        'max_score',
        'grade',
        'mongo_analysis_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_score' => 'decimal:2',
            'max_score' => 'decimal:2',
        ];
    }

    /**
     * Get the exam this result belongs to.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the student this result belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the individual question scores for this result.
     */
    public function questionScores(): HasMany
    {
        return $this->hasMany(QuestionScore::class);
    }
}
