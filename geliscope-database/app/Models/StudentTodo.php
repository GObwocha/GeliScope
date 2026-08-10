<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTodo extends Model
{
    protected $fillable = [
        'student_id',
        'title',
        'is_completed',
        'exam_analysis_id',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }
}
