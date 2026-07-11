<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Exam model for the GeliScope application.
 *
 * Represents an examination event for a specific subject and class,
 * created by a teacher.
 *
 * @property int    $id
 * @property string $name
 * @property int    $subject_id
 * @property int    $class_id
 * @property \Illuminate\Support\Carbon|null $exam_date
 * @property string $term
 * @property string $academic_year
 * @property int    $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Exam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'subject_id',
        'class_id',
        'exam_date',
        'term',
        'academic_year',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
        ];
    }

    /**
     * Get the subject this exam covers.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the class group this exam is for.
     */
    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class, 'class_id');
    }

    /**
     * Get the teacher who created this exam.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'created_by');
    }

    /**
     * Get all results for this exam.
     */
    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}
