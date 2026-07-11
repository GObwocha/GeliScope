<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * StudentSkillProfile MongoDB model for the GeliScope application.
 *
 * Stores a student's aggregated skill profile derived from exam analyses,
 * including skill sets and suggested career paths.
 *
 * @property string       $_id
 * @property int          $student_id
 * @property array        $skill_sets
 * @property array        $suggested_careers
 * @property \Illuminate\Support\Carbon|null $last_updated
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class StudentSkillProfile extends Model
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
    protected $collection = 'student_skill_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'skill_sets',
        'suggested_careers',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'skill_sets' => 'array',
            'suggested_careers' => 'array',
            'last_updated' => 'datetime',
        ];
    }
}
