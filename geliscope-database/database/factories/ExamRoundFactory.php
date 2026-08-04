<?php

namespace Database\Factories;

use App\Models\ExamRound;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamRound>
 */
class ExamRoundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Mid-Term 1', 'End Term 1', 'Mid-Term 2', 'End Term 2', 'Mid-Term 3', 'Final Exam']),
            'term' => fake()->numberBetween(1, 3),
            'academic_year' => fake()->randomElement(['2024/2025', '2025/2026']),
        ];
    }
}
