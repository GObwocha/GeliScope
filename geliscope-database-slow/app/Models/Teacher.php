<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Teacher model for the GeliScope application.
 *
 * Represents a teacher employed at a school, linked to a user account.
 * Manages class assignments (with subject pivots) and exam creation.
 *
 * @property int         $id
 * @property int         $user_id
 * @property string      $employee_number
 * @property int         $school_id
 * @property string|null $specialization
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'employee_number',
        'school_id',
        'specialization',
    ];

    /**
     * Get the user account that owns this teacher profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school this teacher belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the classes assigned to this teacher.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassGroup::class, 'teacher_classes', 'teacher_id', 'class_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    /**
     * Get all exams created by this teacher.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'created_by');
    }
}
