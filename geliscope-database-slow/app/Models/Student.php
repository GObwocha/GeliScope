<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Student model for the GeliScope application.
 *
 * Represents a student enrolled in a school, linked to a user account.
 * Tracks class enrolments, exam results, parent relationships, and leaderboard standings.
 *
 * @property int         $id
 * @property int         $user_id
 * @property string      $admission_number
 * @property int         $school_id
 * @property int         $current_class_id
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'admission_number',
        'school_id',
        'current_class_id',
        'date_of_birth',
        'personal_motto',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the user account that owns this student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school the student is enrolled in.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student's current class.
     */
    public function currentClass(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class, 'current_class_id');
    }

    /**
     * Get all classes the student has been enrolled in.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassGroup::class, 'student_classes', 'student_id', 'class_id')
            ->withPivot('academic_year')
            ->withTimestamps();
    }

    /**
     * Get all exam results for this student.
     */
    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    /**
     * Get the skills associated with this student.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'student_skill');
    }

    /**
     * Get the parents associated with this student.
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'parent_students', 'student_id', 'parent_id');
    }

    /**
     * Get all leaderboard entries for this student.
     */
    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class);
    }
}
