<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * ExamAnalysis MongoDB model for the GeliScope application.
 *
 * Stores AI-generated analysis of a student's exam performance,
 * including Bloom's taxonomy breakdown, suggestions, and an overall summary.
 *
 * @property string       $_id
 * @property int          $student_id
 * @property int          $exam_id
 * @property int          $exam_result_id
 * @property array        $blooms_taxonomy
 * @property array        $suggestions
 * @property string|null  $overall_summary
 * @property \Illuminate\Support\Carbon|null $generated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ExamAnalysis extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'exam_analyses';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'exam_id',
        'exam_result_id',
        'blooms_taxonomy',
        'suggestions',
        'overall_summary',
        'generated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'student_id' => 'integer',
            'exam_id' => 'integer',
            'exam_result_id' => 'integer',
            'blooms_taxonomy' => ,
            'suggestions' => ,
            'generated_at' => 'datetime',
        ];
    }
}
