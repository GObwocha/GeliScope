<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * School model for the GeliScope application.
 *
 * Represents an educational institution. A school contains classes, students,
 * and teachers.
 *
 * @property int    $id
 * @property string $name
 * @property string $county
 * @property string $sub_county
 * @property string $school_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'subcounty_id',
        'school_type',
    ];

    /**
     * Get the subcounty the school belongs to.
     */
    public function subcounty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subcounty::class);
    }

    /**
     * Get all classes belonging to this school.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassGroup::class);
    }

    /**
     * Get all students enrolled in this school.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all teachers employed at this school.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
