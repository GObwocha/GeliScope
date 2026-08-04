<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRound extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'term',
        'academic_year',
    ];

    /**
     * Get the exams associated with this round.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
