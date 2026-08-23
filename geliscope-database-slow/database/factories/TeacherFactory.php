<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'teacher']),
            'employee_number' => 'TSC/' . fake()->unique()->numberBetween(10000, 99999),
            'school_id' => School::factory(),
            'specialization' => fake()->randomElement(['Mathematics', 'English', 'Sciences', 'Humanities', 'Languages']),
        ];
    }
}
