<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamResult>
 */
class ExamResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $score = fake()->numberBetween(35, 95);
        $grade = match(true) {
            $score >= 80 => 'A',
            $score >= 70 => 'B',
            $score >= 60 => 'C',
            $score >= 50 => 'D',
            default => 'E',
        };

        return [
            'exam_id' => Exam::factory(),
            'student_id' => Student::factory(),
            'total_score' => $score,
            'max_score' => 100.00,
            'grade' => $grade,
            'mongo_analysis_id' => null,
        ];
    }
}
