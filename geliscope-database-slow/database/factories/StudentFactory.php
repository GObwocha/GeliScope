<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\School;
use App\Models\ClassGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'student']),
            'admission_number' => fake()->unique()->bothify('ADM/202#/####'),
            'school_id' => School::factory(),
            'current_class_id' => null,
            'date_of_birth' => fake()->date('Y-m-d', '-13 years'),
        ];
    }
}
