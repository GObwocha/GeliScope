<?php

namespace Database\Factories;

use App\Models\ExamResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionScore>
 */
class QuestionScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $max = fake()->randomElement([5, 10, 20]);
        $score = fake()->randomFloat(2, 0, $max);

        return [
            'exam_result_id' => ExamResult::factory(),
            'question_number' => fake()->numberBetween(1, 10),
            'score' => $score,
            'max_score' => $max,
        ];
    }
}
