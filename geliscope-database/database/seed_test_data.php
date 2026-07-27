echo "Starting data seeding...\n";

// 1. Ensure we have at least 10 students
$currentStudentsCount = \App\Models\Student::count();
$studentsNeeded = max(0, 10 - $currentStudentsCount);

$schoolId = \App\Models\School::first()->id ?? null;
$classId = \App\Models\ClassGroup::first()->id ?? null;

if (!$schoolId || !$classId) {
    echo "Error: Need at least one School and one ClassGroup in the DB.\n";
    exit(1);
}

for ($i = 0; $i < $studentsNeeded; $i++) {
    $user = \App\Models\User::create([
        'first_name' => 'Test',
        'last_name' => 'Student ' . ($currentStudentsCount + $i + 1),
        'role' => 'student',
        'email' => 'teststudent' . ($currentStudentsCount + $i + 1) . '@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
    ]);

    \App\Models\Student::create([
        'user_id' => $user->id,
        'admission_number' => 'ADM/' . date('Y') . '/' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
        'school_id' => $schoolId,
        'current_class_id' => $classId,
        'date_of_birth' => \Illuminate\Support\Carbon::now()->subYears(rand(14, 18)),
    ]);
}

$students = \App\Models\Student::with('user')->get();
echo "Students seeded. Total: " . $students->count() . "\n";

// 2. Ensure we have 5 exams
$currentExamsCount = \App\Models\Exam::count();
$examsNeeded = max(0, 5 - $currentExamsCount);
$subjectId = \App\Models\Subject::first()->id ?? null;
$teacherId = \App\Models\Teacher::first()->id ?? null;

if (!$subjectId || !$teacherId) {
    echo "Error: Need at least one Subject and one Teacher in the DB.\n";
    exit(1);
}

for ($i = 0; $i < $examsNeeded; $i++) {
    \App\Models\Exam::create([
        'exam_code' => 'EXAM-' . rand(1000, 9999),
        'name' => 'Mock Exam ' . ($currentExamsCount + $i + 1),
        'subject_id' => $subjectId,
        'class_id' => $classId,
        'exam_date' => \Illuminate\Support\Carbon::now()->subDays(rand(1, 30)),
        'term' => rand(1, 3),
        'academic_year' => '2026',
        'created_by' => $teacherId,
    ]);
}

$exams = \App\Models\Exam::orderBy('exam_date', 'asc')->get();
echo "Exams seeded. Total: " . $exams->count() . "\n";

// 3. Generate ExamResults and MongoDB Analyses
$resultsCount = 0;
$mongoCount = 0;

foreach ($students as $student) {
    foreach ($exams as $exam) {
        $result = \App\Models\ExamResult::where('student_id', $student->id)->where('exam_id', $exam->id)->first();
        
        if (!$result) {
            $maxScore = 100;
            $totalScore = rand(45, 98); // Random score between 45 and 98
            
            $result = \App\Models\ExamResult::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'total_score' => $totalScore,
                'max_score' => $maxScore,
                'grade' => $totalScore >= 80 ? 'A' : ($totalScore >= 60 ? 'B' : 'C'),
            ]);
            $resultsCount++;
            
            // Create MongoDB Analysis
            $mongoAnalysis = \App\Models\MongoDB\ExamAnalysis::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'exam_result_id' => $result->id,
                'blooms_taxonomy' => [
                    'knowledge' => rand(5, 10),
                    'comprehension' => rand(5, 10),
                    'application' => rand(5, 10),
                    'analysis' => rand(5, 10),
                    'synthesis' => rand(5, 10),
                    'evaluation' => rand(5, 10),
                ],
                'suggestions' => [
                    "Review chapter " . rand(1, 5) . " on basic concepts.",
                    "Practice more application-level questions.",
                ],
                'overall_summary' => "The student demonstrated a solid understanding but struggled slightly with synthesis tasks.",
                'generated_at' => \Illuminate\Support\Carbon::now(),
            ]);
            
            $result->update(['mongo_analysis_id' => $mongoAnalysis->_id]);
            $mongoCount++;
        }
    }
}

echo "Created $resultsCount new Exam Results in MySQL.\n";
echo "Created $mongoCount new Exam Analyses in MongoDB.\n";
echo "Done!\n";

