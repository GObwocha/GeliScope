<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Skill;
use App\Models\Student;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['name' => 'Analytical Thinking', 'description' => 'Ability to analyze information and solve complex problems.'],
            ['name' => 'Creative Writing', 'description' => 'Ability to express ideas imaginatively through written words.'],
            ['name' => 'Public Speaking', 'description' => 'Ability to communicate effectively in front of an audience.'],
            ['name' => 'Logical Reasoning', 'description' => 'Ability to think in a disciplined and systematic way.'],
            ['name' => 'Empathy', 'description' => 'Ability to understand and share the feelings of another.'],
            ['name' => 'Visual Arts', 'description' => 'Ability to create visual expressions of art.'],
        ];

        foreach ($skills as $skillData) {
            Skill::firstOrCreate(['name' => $skillData['name']], $skillData);
        }

        // Attach random skills to all existing students
        $allSkills = Skill::all();
        $students = Student::all();

        foreach ($students as $student) {
            // Attach 2 to 4 random skills to each student
            $randomSkills = $allSkills->random(rand(2, 4))->pluck('id');
            $student->skills()->syncWithoutDetaching($randomSkills);
        }
    }
}
