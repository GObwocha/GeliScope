<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaderboardEntry>
 */
class LeaderboardEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'academic_year' => '2025/2026',
            'term' => fake()->numberBetween(1, 3),
            'total_score' => fake()->randomFloat(2, 50, 400),
            'rank' => fake()->numberBetween(1, 100),
        ];
    }
}
