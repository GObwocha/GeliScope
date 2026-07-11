<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * TeacherMarkingConfig MongoDB model for the GeliScope application.
 *
 * Stores teacher-defined marking instructions for a specific exam,
 * used to guide the AI-powered marking/analysis pipeline.
 *
 * @property string $_id
 * @property int    $teacher_id
 * @property int    $exam_id
 * @property array  $instructions
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class TeacherMarkingConfig extends Model
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
    protected $collection = 'teacher_marking_configs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'teacher_id',
        'exam_id',
        'instructions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'instructions' => 'array',
        ];
    }
}
