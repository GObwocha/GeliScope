<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * LeaderboardEntry model for the GeliScope application.
 *
 * Tracks a student's ranking for a specific subject in a given
 * academic year and term.
 *
 * @property int    $id
 * @property int    $student_id
 * @property int    $subject_id
 * @property string $academic_year
 * @property string $term
 * @property float  $total_score
 * @property int    $rank
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class LeaderboardEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_year',
        'term',
        'total_score',
        'rank',
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
        ];
    }

    /**
     * Get the student this leaderboard entry belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject this leaderboard entry is for.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
