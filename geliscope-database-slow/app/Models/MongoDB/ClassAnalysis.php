<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * ClassAnalysis MongoDB model for the GeliScope application.
 *
 * Stores AI-generated class-level analysis for a specific exam and subject,
 * including per-question analysis, class overview statistics, and improvement suggestions.
 *
 * @property string       $_id
 * @property int          $teacher_id
 * @property int          $class_id
 * @property int          $exam_id
 * @property int          $subject_id
 * @property array        $question_analyses
 * @property array        $class_overview
 * @property array        $suggestions
 * @property \Illuminate\Support\Carbon|null $generated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ClassAnalysis extends Model
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
    protected $collection = 'class_analyses';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'teacher_id',
        'class_id',
        'exam_id',
        'subject_id',
        'question_analyses',
        'class_overview',
        'suggestions',
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
            'question_analyses' => 'array',
            'class_overview' => 'array',
            'suggestions' => 'array',
            'generated_at' => 'datetime',
        ];
    }
}
