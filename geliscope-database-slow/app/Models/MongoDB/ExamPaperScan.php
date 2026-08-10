<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * ExamPaperScan MongoDB model for the GeliScope application.
 *
 * Stores scanned exam paper data including original image URLs,
 * raw OCR text, and scan metadata in the 'exam_paper_scans' collection.
 *
 * @property string       $_id
 * @property int          $student_id
 * @property int          $exam_id
 * @property array        $original_image_urls
 * @property string|null  $ocr_raw_text
 * @property array        $scan_metadata
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ExamPaperScan extends Model
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
    protected $collection = 'exam_paper_scans';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'exam_id',
        'original_image_urls',
        'ocr_raw_text',
        'scan_metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'original_image_urls' => 'array',
            'scan_metadata' => 'array',
        ];
    }
}
