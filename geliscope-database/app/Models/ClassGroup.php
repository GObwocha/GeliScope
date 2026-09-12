<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ClassGroup model for the GeliScope application.
 *
 * Represents a class/form within a school (e.g. "Form 1A, 2025").
 * Maps to the 'classes' table. Uses the name "ClassGroup" to avoid
 * conflict with PHP's reserved 'class' keyword.
 *
 * @property int    $id
 * @property int    $school_id
 * @property string $name
 * @property string $academic_year
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ClassGroup extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'academic_year',
    ];

    /**
     * Get the school this class belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the students currently assigned to this class.
     */
    public function currentStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'current_class_id');
    }

    /**
     * Get the students enrolled in this class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_classes', 'class_id', 'student_id')
            ->withPivot('academic_year')
            ->withTimestamps();
    }

    /**
     * Get the teachers assigned to this class.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_classes', 'class_id', 'teacher_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    /**
     * Get all exams for this class.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id');
    }
}
