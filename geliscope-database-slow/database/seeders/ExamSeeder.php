<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\QuestionScore;
use App\Models\LeaderboardEntry;
use App\Models\ClassGroup;
use App\Models\Subject;
use App\Models\Teacher;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $c1 = ClassGroup::where('name', 'Form 1 East')->first();
        $c2 = ClassGroup::where('name', 'Form 2 West')->first();
        $math = Subject::where('name', 'Mathematics')->first();
        $english = Subject::where('name', 'English')->first();
        $t1 = Teacher::where('employee_number', 'TSC/10001')->first();
        $t2 = Teacher::where('employee_number', 'TSC/10002')->first();

        if ($c1 && $math && $t1) {
            $e1 = Exam::create([
                'exam_code' => 'EXAM-2025-MATH-F1E',
                'name' => 'Form 1 East Mathematics Mid-Term 1',
                'subject_id' => $math->id,
                'class_id' => $c1->id,
                'exam_date' => '2025-06-15',
                'term' => 1,
                'academic_year' => '2025/2026',
                'created_by' => $t1->id,
            ]);

            $students = $c1->students;
            foreach ($students as $index => $student) {
                $totalScore = $index === 0 ? 84.50 : 72.00;
                $grade = $totalScore >= 80 ? 'A' : 'B';

                $result = ExamResult::create([
                    'exam_id' => $e1->id,
                    'student_id' => $student->id,
                    'total_score' => $totalScore,
                    'max_score' => 100.00,
                    'grade' => $grade,
                    'mongo_analysis_id' => '60d5ec49f1b2c8b1f8e4e1a' . ($index + 1),
                ]);

                // 5 question scores (20 marks each)
                for ($q = 1; $q <= 5; $q++) {
                    QuestionScore::create([
                        'exam_result_id' => $result->id,
                        'question_number' => $q,
                        'score' => round($totalScore / 5, 2),
                        'max_score' => 20.00,
                    ]);
                }

                LeaderboardEntry::create([
                    'student_id' => $student->id,
                    'subject_id' => $math->id,
                    'academic_year' => '2025/2026',
                    'term' => 1,
                    'total_score' => $totalScore,
                    'rank' => $index + 1,
                ]);
            }
        }

        if ($c2 && $english && $t2) {
            $e2 = Exam::create([
                'exam_code' => 'EXAM-2025-ENG-F2W',
                'name' => 'Form 2 West English Mid-Term 1',
                'subject_id' => $english->id,
                'class_id' => $c2->id,
                'exam_date' => '2025-06-16',
                'term' => 1,
                'academic_year' => '2025/2026',
                'created_by' => $t2->id,
            ]);

            $students = $c2->students;
            foreach ($students as $index => $student) {
                $totalScore = 88.00;
                $result = ExamResult::create([
                    'exam_id' => $e2->id,
                    'student_id' => $student->id,
                    'total_score' => $totalScore,
                    'max_score' => 100.00,
                    'grade' => 'A',
                    'mongo_analysis_id' => '60d5ec49f1b2c8b1f8e4e1a3',
                ]);

                for ($q = 1; $q <= 5; $q++) {
                    QuestionScore::create([
                        'exam_result_id' => $result->id,
                        'question_number' => $q,
                        'score' => 17.60,
                        'max_score' => 20.00,
                    ]);
                }

                LeaderboardEntry::create([
                    'student_id' => $student->id,
                    'subject_id' => $english->id,
                    'academic_year' => '2025/2026',
                    'term' => 1,
                    'total_score' => $totalScore,
                    'rank' => 1,
                ]);
            }
        }
    }
}
