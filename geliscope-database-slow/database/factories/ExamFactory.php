<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\ClassGroup;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_round_id' => \App\Models\ExamRound::factory(),
            'exam_code' => strtoupper(fake()->unique()->bothify('EX-####-????')),
            'name' => fake()->randomElement(['Mathematics Paper 1', 'Physics Paper 2', 'Chemistry Practical', 'Biology Theory']),
            'subject_id' => \App\Models\Subject::inRandomOrder()->first()->id ?? Subject::factory(),
            'class_id' => ClassGroup::factory(),
            'exam_date' => fake()->date('Y-m-d', 'now'),
            'term' => fake()->numberBetween(1, 3),
            'academic_year' => '2025/2026',
            'created_by' => Teacher::factory(),
        ];
    }
}
