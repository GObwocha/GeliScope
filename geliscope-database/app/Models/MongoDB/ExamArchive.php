<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

class ExamArchive extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'exam_archives';

    protected $fillable = [
        'student_id',
        'exam_id',
        'file_name',
        'mime_type',
        'file_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'student_id' => 'integer',
        'exam_id' => 'integer',
    ];
}
