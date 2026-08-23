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
        $teachers = \App\Models\Teacher::factory(5)->create();
        $parents = \App\Models\ParentProfile::factory(5)->create();

        // Link Parents to Students
        foreach ($parents as $parent) {
            $parent->students()->attach($students->random(5)->pluck('id')->toArray());
        }

        // Seed Class Groups
        $classes = \App\Models\ClassGroup::factory(5)->create();

        // Seed Exam Rounds
        $examRounds = \App\Models\ExamRound::factory(5)->create();

        // Seed Exams
        $exams = \App\Models\Exam::factory(5)->create();

        // Seed Exam Results
        foreach ($exams as $exam) {
            foreach ($students->random(5) as $student) {
                \App\Models\ExamResult::factory()->create([
                    'exam_id' => $exam->id,
                    'student_id' => $student->id,
                ]);
            }
        }

        // Seed Question Scores for Exam Results
        $examResults = \App\Models\ExamResult::all();
        foreach ($examResults as $result) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\QuestionScore::create([
                    'exam_result_id' => $result->id,
                    'question_number' => $i,
                    'score' => rand(1, 10),
                    'max_score' => 10,
                ]);
            }
        }

        // Link Teachers to Classes (teacher_classes)
        foreach ($teachers as $teacher) {
            $teacherClasses = $classes->random(5);
            foreach ($teacherClasses as $class) {
                $teacher->classes()->attach($class->id, [
                    'subject_id' => $subjects->random()->id
                ]);
            }
        }
        
        // Link Students to Classes (student_classes)
        foreach ($students as $student) {
            $studentClasses = $classes->random(5);
            foreach ($studentClasses as $class) {
                $student->classes()->attach($class->id, [
                    'academic_year' => '2025'
                ]);
            }
        }

        // Link Students to Skills (student_skill)
        $skills = \App\Models\Skill::all();
        // Ensure there are at least 5 skills to attach
        if ($skills->count() >= 5) {
            foreach ($students as $student) {
                $student->skills()->attach($skills->random(5)->pluck('id')->toArray());
            }
        }

        // Leaderboard entries
        foreach ($students as $student) {
            \App\Models\LeaderboardEntry::create([
                'student_id' => $student->id,
                'subject_id' => $subjects->random()->id,
                'academic_year' => '2025',
                'term' => 1,
                'total_score' => rand(50, 100),
                'rank' => rand(1, 15),
            ]);
        }

        // Call Mongo Seeder for MongoDB Collections
        $this->call([
            MongoSeeder::class,
        ]);
    }
}
