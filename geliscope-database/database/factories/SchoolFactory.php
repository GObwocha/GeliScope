<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $counties = [
            'Nairobi' => ['Westlands', 'Dagoretti', 'Langata', 'Kasarani', 'Starehe'],
            'Kiambu' => ['Thika Town', 'Ruiru', 'Juja', 'Kiambu Town', 'Limuru'],
            'Mombasa' => ['Nyali', 'Mvita', 'Kisauni', 'Likoni', 'Changamwe'],
            'Kisumu' => ['Kisumu Central', 'Kisumu East', 'Kisumu West', 'Nyando'],
            'Nakuru' => ['Nakuru Town East', 'Nakuru Town West', 'Naivasha', 'Molo']
        ];

        $county = fake()->randomElement(array_keys($counties));
        $subCounty = fake()->randomElement($counties[$county]);

        return [
            'name' => fake()->company() . ' Academy',
            'county' => $county,
            'sub_county' => $subCounty,
            'school_type' => fake()->randomElement(['primary', 'secondary', 'tertiary']),
        ];
    }
}
