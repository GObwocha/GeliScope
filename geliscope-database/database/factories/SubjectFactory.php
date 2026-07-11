<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'Mathematics' => 'Sciences',
            'English' => 'Languages',
            'Kiswahili' => 'Languages',
            'Physics' => 'Sciences',
            'Chemistry' => 'Sciences',
            'Biology' => 'Sciences',
            'History' => 'Humanities',
            'Geography' => 'Humanities',
            'Computer Studies' => 'Technical',
        ];

        $name = fake()->unique()->randomElement(array_keys($subjects));

        return [
            'name' => $name,
            'category' => $subjects[$name] ?? 'General',
        ];
    }
}
