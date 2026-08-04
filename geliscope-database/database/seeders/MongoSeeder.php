<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Teacher;
use App\Models\ClassGroup;
use App\Models\Subject;
use App\Models\ParentProfile;
use App\Models\MongoDB\ExamAnalysis;
use App\Models\MongoDB\StudentSkillProfile;
use App\Models\MongoDB\ClassAnalysis;
use App\Models\MongoDB\ParentReport;
use App\Models\MongoDB\TeacherMarkingConfig;
use App\Models\MongoDB\ExamPaperScan;
use Carbon\Carbon;

class MongoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            ExamAnalysis::truncate();
            StudentCognitiveProfile::truncate();
            ClassAnalysis::truncate();
            ParentReport::truncate();
            TeacherMarkingConfig::truncate();
            ExamPaperScan::truncate();

            $students = Student::all();
            $exams = Exam::all();
            $teachers = Teacher::all();
            $classes = ClassGroup::all();
            $subjects = Subject::all();
            $parents = ParentProfile::all();

            if ($students->isEmpty() || $exams->isEmpty()) {
                return;
            }

            foreach ($students as $student) {
                $exam = $exams->random();
                $result = ExamResult::where('student_id', $student->id)->where('exam_id', $exam->id)->first();
                $teacher = $teachers->random();
                $class = $classes->random();
                $subject = $subjects->random();
                $parent = $student->parents()->first() ?? $parents->random();

                // 1. Exam Analysis
                ExamAnalysis::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'exam_result_id' => $result ? $result->id : rand(1, 100),
                    'blooms_taxonomy' => [
                        'remembering' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'understanding' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'applying' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'analyzing' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'evaluating' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                    ],
                    'suggestions' => [
                        'Review quadratic formulas under word problems.',
                        'Practice step-by-step substitution in algebra.',
                        'Focus on showing full working to earn method marks.'
                    ],
                    'overall_summary' => $student->user->first_name . ' showed excellent command of fundamentals.',
                    'generated_at' => Carbon::now(),
                ]);

                // 2. Student Cognitive Profile (Replaced StudentSkillProfile)
                StudentCognitiveProfile::create([
                    'student_id' => $student->id,
                    'blooms_taxonomy' => [
                        'remembering' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'understanding' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'applying' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'analyzing' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                        'evaluating' => ['score' => rand(10, 20), 'max' => 20, 'percentage' => rand(50, 100)],
                    ],
                    'last_updated' => Carbon::now(),
                ]);

                // 3. Class Analysis
                if ($class && $subject && $teacher) {
                    ClassAnalysis::create([
                        'teacher_id' => $teacher->id,
                        'class_id' => $class->id,
                        'exam_id' => $exam->id,
                        'subject_id' => $subject->id,
                        'question_analyses' => [
                            ['question' => 1, 'topic' => 'Algebraic Expressions', 'avg_score' => 17.5, 'max_score' => 20, 'common_error' => 'Sign errors when factoring negatives.'],
                            ['question' => 2, 'topic' => 'Linear Equations', 'avg_score' => 18.2, 'max_score' => 20, 'common_error' => 'None.'],
                            ['question' => 3, 'topic' => 'Geometry & Angles', 'avg_score' => 14.0, 'max_score' => 20, 'common_error' => 'Confusing corresponding angles with alternate angles.']
                        ],
                        'class_overview' => [
                            'class_average' => 78.25,
                            'highest_score' => 84.50,
                            'lowest_score' => 72.00,
                            'pass_rate_percentage' => 100
                        ],
                        'suggestions' => [
                            'Schedule a 30-minute revision session on parallel line angle properties.',
                            'Provide a quick worksheet focusing on factoring negative binomials.'
                        ],
                        'generated_at' => Carbon::now(),
                    ]);
                }

                // 4. Parent Report
                if ($parent) {
                    ParentReport::create([
                        'parent_id' => $parent->id,
                        'student_id' => $student->id,
                        'report_period' => ['term' => 1, 'academic_year' => '2025/2026'],
                        'performance_summary' => [
                            'key_strengths' => 'Exceptional ability to solve complex equations quickly.',
                            'areas_for_growth' => 'Ensure careful review of word problems before answering.',
                            'attendance_and_participation' => 'Exemplary participation during math labs.'
                        ],
                        'improvement_steps' => [
                            'Encourage 20 minutes of daily practice using interactive math quizzes.',
                            'Review marked exam papers together at home using GeliScope explanations.'
                        ],
                        'generated_at' => Carbon::now(),
                    ]);
                }

                // 5. Teacher Marking Config
                if ($teacher) {
                    TeacherMarkingConfig::create([
                        'teacher_id' => $teacher->id,
                        'exam_id' => $exam->id,
                        'instructions' => [
                            'rubric_items' => [
                                ['question' => 1, 'required_keywords' => ['factorize', 'common term'], 'partial_credit' => true],
                                ['question' => 2, 'required_keywords' => ['substitution', 'slope'], 'partial_credit' => true]
                            ],
                            'strictness_level' => 'Moderate',
                            'award_method_marks' => true
                        ],
                    ]);
                }

                // 6. Exam Paper Scan
                ExamPaperScan::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'original_image_urls' => [
                        'https://storage.geliscope.com/scans/2025/adm_001_page1.jpg',
                        'https://storage.geliscope.com/scans/2025/adm_001_page2.jpg'
                    ],
                    'ocr_raw_text' => 'Question 1: 2x + 4 = 10 => 2x = 6 => x = 3. Correct working shown.',
                    'scan_metadata' => [
                        'scanner_model' => 'GeliScope AI Vision Engine v2',
                        'scan_timestamp' => Carbon::now()->toIso8601String(),
                        'page_count' => 2
                    ]
                ]);
            }

        } catch (\Throwable $e) {
            Log::warning('MongoSeeder skipped: MongoDB extension or connection not active: ' . $e->getMessage());
        }
    }
}
