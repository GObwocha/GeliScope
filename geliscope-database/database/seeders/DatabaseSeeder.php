<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountySeeder::class,
            SkillSeeder::class,
        ]);

        // Seed 10 schools
        $schools = \App\Models\School::factory(10)->create();

        // Seed subjects using seeder
        $this->call(SubjectSeeder::class);
        $subjects = \App\Models\Subject::all();

        // Seed Users (Students, Teachers, Parents)
        $students = \App\Models\Student::factory(15)->create();
        $teachers = \App\Models\Teacher::factory(10)->create();
        $parents = \App\Models\ParentProfile::factory(10)->create();

        // Link Parents to Students
        foreach ($parents as $parent) {
            $parent->students()->attach($students->random(rand(1, 3))->pluck('id')->toArray());
        }

        // Seed Class Groups
        $classes = \App\Models\ClassGroup::factory(10)->create();

        // Seed Exam Rounds
        $examRounds = \App\Models\ExamRound::factory(5)->create();

        // Seed Exams
        $exams = \App\Models\Exam::factory(10)->create();

        // Seed Exam Results
        foreach ($exams as $exam) {
            foreach ($students->random(5) as $student) {
                \App\Models\ExamResult::factory()->create([
                    'exam_id' => $exam->id,
                    'student_id' => $student->id,
                ]);
            }
        }

        // Call Mongo Seeder for MongoDB Collections
        $this->call([
            MongoSeeder::class,
        ]);
    }
}
