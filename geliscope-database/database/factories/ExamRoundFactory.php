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
            'name' => 'Round ' . $this->faker->unique()->numberBetween(1, 100),
            'term' => $this->faker->numberBetween(1, 3),
            'academic_year' => $this->faker->year(),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ];
    }
}
