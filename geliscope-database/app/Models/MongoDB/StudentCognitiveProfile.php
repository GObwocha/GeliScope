<?php

namespace App\Models\MongoDB;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;

/**
 * StudentCognitiveProfile MongoDB model for the GeliScope application.
 *
 * Stores a student's aggregated Bloom's Taxonomy cognitive breakdown
 * derived from all their exam analyses.
 *
 * @property string       $_id
 * @property int          $student_id
 * @property array        $average_blooms_taxonomy
 * @property Carbon|null $last_updated
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class StudentCognitiveProfile extends Model
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
    protected $collection = 'student_cognitive_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'average_blooms_taxonomy',
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
            'average_blooms_taxonomy' => ,
            'last_updated' => 'datetime',
        ];
    }
}
