<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * ParentReport MongoDB model for the GeliScope application.
 *
 * Stores AI-generated reports for parents, summarising their child's
 * performance over a specific period with actionable improvement steps.
 *
 * @property string       $_id
 * @property int          $parent_id
 * @property int          $student_id
 * @property array        $report_period
 * @property array        $performance_summary
 * @property array        $improvement_steps
 * @property \Illuminate\Support\Carbon|null $generated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ParentReport extends Model
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
    protected $collection = 'parent_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'student_id',
        'report_period',
        'performance_summary',
        'improvement_steps',
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
            'report_period' => 'array',
            'performance_summary' => 'array',
            'improvement_steps' => 'array',
            'generated_at' => 'datetime',
        ];
    }
}
