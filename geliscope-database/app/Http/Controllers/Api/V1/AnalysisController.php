<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\Exam;
use App\Models\MongoDB\ExamAnalysis;
use App\Models\MongoDB\StudentCognitiveProfile;
use App\Models\MongoDB\ClassAnalysis;
use App\Models\MongoDB\TeacherMarkingConfig;
use App\Models\MongoDB\ExamArchive;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\ExamRound;
use App\Models\StudentTodo;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AnalysisController extends Controller
{
    /**
     * Get exam analysis for a student and exam.
     */
    public function examAnalysis($studentId, $examId)
    {
        $analysis = ExamAnalysis::where('student_id', (int) $studentId)
            ->where('exam_id', (int) $examId)
            ->first();

        if (!$analysis) {
            return response()->json(['message' => 'Exam analysis not found'], 404);
        }

        return response()->json($analysis);
    }

    /**
     * Store or update exam analysis document.
     */
    public function storeExamAnalysis(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'exam_id' => 'required|integer',
            'exam_result_id' => 'nullable|integer',
            'blooms_taxonomy' => 'required|array',
            'suggestions' => 'nullable|array',
            'overall_summary' => 'nullable|string',
            'question_breakdown' => 'nullable|array',
        ]);

        $validated['student_id'] = (int) $validated['student_id'];
        $validated['exam_id'] = (int) $validated['exam_id'];
        if (isset($validated['exam_result_id'])) {
            $validated['exam_result_id'] = (int) $validated['exam_result_id'];
        }

        $validated['generated_at'] = Carbon::now();

        $examResultId = $validated['exam_result_id'] ?? null;
        $examResult = null;

        if (!empty($validated['question_breakdown'])) {
            $totalScore = 0;
            $maxScore = 0;
            foreach ($validated['question_breakdown'] as $q) {
                $totalScore += (float) ($q['score'] ?? 0);
                $maxScore += (float) ($q['max'] ?? 0);
            }

            $examResult = ExamResult::updateOrCreate(
                [
                    'student_id' => (int) $validated['student_id'],
                    'exam_id' => (int) $validated['exam_id'],
                ],
                [
                    'total_score' => $totalScore,
                    'max_score' => $maxScore,
                ]
            );
            
            $examResultId = $examResult->id;
            
            foreach ($validated['question_breakdown'] as $q) {
                if (isset($q['q'])) {
                    $examResult->questionScores()->updateOrCreate(
                        ['question_number' => (int) $q['q']],
                        [
                            'score' => (float) ($q['score'] ?? 0),
                            'max_score' => (float) ($q['max'] ?? 0),
                        ]
                    );
                }
            }
        }
        
        // Strategy 1: Clear the real-time cache for the leaderboard
        Cache::forget('leaderboard_exam_' . $validated['exam_id']);
        Cache::forget('leaderboard_all_student_' . $validated['student_id']);

        $validated['exam_result_id'] = $examResultId;

        $analysis = ExamAnalysis::updateOrCreate(
            [
                'student_id' => (int) $validated['student_id'],
                'exam_id' => (int) $validated['exam_id'],
            ],
            $validated
        );

        if ($examResult) {
            $examResult->update(['mongo_analysis_id' => $analysis->_id]);
        }
        
        // Auto-generate To-Do items from AI suggestions
        if (!empty($validated['suggestions'])) {
            foreach ($validated['suggestions'] as $suggestion) {
                $title = is_array($suggestion) ? ($suggestion['title'] ?? $suggestion['text'] ?? json_encode($suggestion)) : (string) $suggestion;
                
                StudentTodo::firstOrCreate([
                    'student_id' => (int) $validated['student_id'],
                    'exam_analysis_id' => (string) $analysis->_id,
                    'title' => substr($title, 0, 255), // Enforce max length
                ]);
            }
        }
        
        // Recalculate Bloom's Taxonomy Cognitive Profile Averages
        $allAnalyses = ExamAnalysis::where('student_id', (int) $validated['student_id'])->get();
        $bloomSums = [
            'remembering' => 0,
            'understanding' => 0,
            'applying' => 0,
            'analyzing' => 0,
            'evaluating' => 0,
        ];
        $count = 0;
        
        foreach ($allAnalyses as $ana) {
            if (!empty($ana->blooms_taxonomy)) {
                $bloomSums['remembering'] += (float)($ana->blooms_taxonomy['remembering'] ?? 0);
                $bloomSums['understanding'] += (float)($ana->blooms_taxonomy['understanding'] ?? 0);
                $bloomSums['applying'] += (float)($ana->blooms_taxonomy['applying'] ?? 0);
                $bloomSums['analyzing'] += (float)($ana->blooms_taxonomy['analyzing'] ?? 0);
                $bloomSums['evaluating'] += (float)($ana->blooms_taxonomy['evaluating'] ?? 0);
                $count++;
            }
        }
        
        if ($count > 0) {
            $averages = [
                'remembering' => round($bloomSums['remembering'] / $count, 2),
                'understanding' => round($bloomSums['understanding'] / $count, 2),
                'applying' => round($bloomSums['applying'] / $count, 2),
                'analyzing' => round($bloomSums['analyzing'] / $count, 2),
                'evaluating' => round($bloomSums['evaluating'] / $count, 2),
            ];
            
            StudentCognitiveProfile::updateOrCreate(
                ['student_id' => (int) $validated['student_id']],
                [
                    'average_blooms_taxonomy' => $averages,
                    'last_updated' => Carbon::now(),
                ]
            );
        }

        return response()->json($analysis, 201);
    }

    // /**
    //  * Get student skill profile.
    //  */
    // public function skillProfile($studentId)
    // {
    //     $profile = StudentSkillProfile::where('student_id', (int) $studentId)->first();

    //     if (!$profile) {
    //         return response()->json(['message' => 'Skill profile not found'], 404);
    //     }

    //     return response()->json($profile);
    // }

    // /**
    //  * Store or update student skill profile.
    //  */
    // public function storeSkillProfile(Request $request)
    // {
    //     $validated = $request->validate([
    //         'student_id' => 'required|integer',
    //         'skill_sets' => 'required|array',
    //         'suggested_careers' => 'nullable|array',
    //     ]);

    //     $validated['last_updated'] = Carbon::now();

    //     $profile = StudentSkillProfile::updateOrCreate(
    //         ['student_id' => (int) $validated['student_id']],
    //         $validated
    //     );

    //     return response()->json($profile, 201);
    // }

    /**
     * Get student cognitive profile (Bloom's Taxonomy averages).
     */
    public function cognitiveProfile(int $studentId)
    {
        $profile = StudentCognitiveProfile::where('student_id', (int) $studentId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Cognitive profile not found'], 404);
        }

        return response()->json($profile);
    }

    /**
     * Get class analysis for class and exam.
     */
    public function classAnalysis($classId, $examId)
    {
        $analysis = ClassAnalysis::where('class_id', (int) $classId)
            ->where('exam_id', (int) $examId)
            ->first();

        if (!$analysis) {
            return response()->json(['message' => 'Class analysis not found'], 404);
        }

        return response()->json($analysis);
    }

    /**
     * Store class analysis.
     */
    public function storeClassAnalysis(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer',
            'class_id' => 'required|integer',
            'exam_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'question_analyses' => 'nullable|array',
            'class_overview' => 'nullable|array',
            'suggestions' => 'nullable|array',
        ]);

        $validated['teacher_id'] = (int) $validated['teacher_id'];
        $validated['class_id'] = (int) $validated['class_id'];
        $validated['exam_id'] = (int) $validated['exam_id'];
        $validated['subject_id'] = (int) $validated['subject_id'];

        $validated['generated_at'] = Carbon::now();

        $analysis = ClassAnalysis::updateOrCreate(
            [
                'class_id' => (int) $validated['class_id'],
                'exam_id' => (int) $validated['exam_id'],
            ],
            $validated
        );

        return response()->json($analysis, 201);
    }

    /**
     * Get marking config for an exam.
     */
    public function markingConfig($examId)
    {
        $config = TeacherMarkingConfig::where('exam_id', (int) $examId)->first();

        if (!$config) {
            return response()->json(['message' => 'Marking config not found'], 404);
        }

        return response()->json($config);
    }

    public function storeMarkingConfig(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer',
            'exam_id' => 'required|integer',
            'instructions' => 'required|array',
        ]);

        $validated['teacher_id'] = (int) $validated['teacher_id'];
        $validated['exam_id'] = (int) $validated['exam_id'];

        $config = TeacherMarkingConfig::updateOrCreate(
            ['exam_id' => (int) $validated['exam_id']],
            $validated
        );

        return response()->json($config, 201);
    }

    /**
     * Process exam script images via Gemini Vision AI.
     */
    public function processVision(Request $request)
    {
        $ip = $request->ip();

        // 15 requests per minute
        if (RateLimiter::tooManyAttempts('gemini-vision-min:' . $ip, 15)) {
            return response()->json(['message' => 'Minute API quota exceeded. Please try again later.'], 429);
        }

        // 1500 requests per day
        if (RateLimiter::tooManyAttempts('gemini-vision-day:' . $ip, 1500)) {
            return response()->json(['message' => 'Daily API quota exceeded. Please try again tomorrow.'], 429);
        }

        RateLimiter::hit('gemini-vision-min:' . $ip, 60);
        RateLimiter::hit('gemini-vision-day:' . $ip, 86400);

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,pdf|max:16384',
            'student_id' => 'nullable|integer',
            'exam_id' => 'nullable|integer',
            'action' => 'nullable|string|in:mark_and_analyze,analyze_only',
            'marking_instructions' => 'nullable|string'
        ]);

        $action = $request->input('action', 'analyze_only');
        $markingInstructions = $request->input('marking_instructions', '');

        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json(['message' => 'Gemini API key is missing.'], 500);
        }

        $prompt = 'You are the GELISCOPE VISION & OCR ENGINE. Evaluate the provided exam script(s). Return ONLY valid JSON in the following format, with no markdown code blocks formatting, just the raw JSON: { "student_id": 1, "exam_id": 1, "ocr_raw_text": "[Extracted and structured text]", "blooms_taxonomy": { "remembering": 95, "understanding": 88, "applying": 82, "analyzing": 60, "evaluating": 45 }, "question_breakdown": [ { "q": 1, "topic": "Algebra", "score": 3, "max": 3, "status": "Perfect", "comment": "Excellent" } ], "overall_summary": "Summary text", "suggestions": [ "Suggestion 1" ] }';

        if ($action === 'mark_and_analyze') {
            $prompt .= ' SPECIAL INSTRUCTION: The teacher has requested you to strictly MARK this paper and provide an analysis. Use the following marking instructions when generating the question_breakdown scores and comments: "' . $markingInstructions . '"';
        }

        $parts = [];
        $parts[] = [
            'text' => $prompt
        ];

        foreach ($request->file('files') as $file) {
            $mime = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            
            if ($request->student_id && $request->exam_id) {
                ExamArchive::create([
                    'student_id' => (int) $request->student_id,
                    'exam_id' => (int) $request->exam_id,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $mime,
                    'file_data' => $base64,
                ]);
            }

            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mime,
                    'data' => $base64
                ]
            ];
        }

        $payload = [
            'contents' => [
                [
                    'parts' => $parts
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ];

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . $apiKey, $payload);

        if ($response->failed()) {
            Log::error('Gemini API Error: ' . $response->body());
            return response()->json(['message' => 'Failed to process image with Vision AI.', 'error' => $response->body()], 500);
        }

        $result = $response->json();
        $generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        
        $generatedText = preg_replace('/```json\s+/', '', $generatedText);
        $generatedText = preg_replace('/```/', '', $generatedText);

        $data = json_decode(trim($generatedText), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Failed to parse Vision AI JSON response.'], 500);
        }

        $data['student_id'] = $request->input('student_id', $data['student_id'] ?? null);
        $data['exam_id'] = $request->input('exam_id', $data['exam_id'] ?? null);

        return response()->json($data);
    }

    /**
     * Use AI to determine the 5 most probable careers based on skills.
     */
    public function suggestCareers(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id'
        ]);

        $studentId = $request->student_id;
        $student = Student::with('skills')->findOrFail($studentId);

        if ($student->skills->isEmpty()) {
            return response()->json(['careers' => []]);
        }

        $skillNames = $student->skills->pluck('name')->implode(', ');
        $cacheKey = 'career_suggestions_student_' . $studentId;

        // Cache for 24 hours to prevent API spam
        $careers = Cache::remember($cacheKey, 86400, function () use ($skillNames) {
            $apiKey = config('services.gemini.key');
            if (!$apiKey) {
                return ['Gemini API key is missing.'];
            }

            $prompt = 'You are a career counselor. A student has the following natural aptitudes and skills: ' . $skillNames . '. ' .
                      'Determine the 5 most probable careers the student can excel in. Return ONLY a JSON array of strings (e.g. ["Career 1", "Career 2"]). No other text, no markdown.';

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ];

            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . $apiKey, $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error (Careers): ' . $response->body());
                return ['Error fetching careers from AI'];
            }

            $result = $response->json();
            $generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
            
            $generatedText = preg_replace('/```json\s+/', '', $generatedText);
            $generatedText = preg_replace('/```/', '', $generatedText);

            $data = json_decode(trim($generatedText), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                return ['Failed to parse AI response'];
            }

            return array_slice($data, 0, 5);
        });

        return response()->json(['careers' => $careers]);
    }

    /**
     * Get lightweight list of exam archives for a student.
     */
    public function getArchives($studentId)
    {
        $archives = ExamArchive::where('student_id', (int) $studentId)
            ->select('_id', 'student_id', 'exam_id', 'file_name', 'mime_type', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($archives);
    }

    /**
     * Download or view an exam archive full file payload.
     */
    public function downloadArchive($archiveId)
    {
        $archive = ExamArchive::find($archiveId);
        
        if (!$archive) {
            return response()->json(['message' => 'Archive not found'], 404);
        }
        
        return response()->json($archive);
    }

    /**
     * Get teacher classes and subjects
     */
    public function teacherClasses($teacherId)
    {
        $teacher = Teacher::with('classes')->find((int) $teacherId);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
        
        $classes = [];
        $subjectIds = [];
        foreach ($teacher->classes as $class) {
            if ($class->school_id !== $teacher->school_id) continue;
            $subjectIds[] = $class->pivot->subject_id;
        }
        
        $subjects = Subject::whereIn('id', $subjectIds)->get()->keyBy('id');
        
        foreach ($teacher->classes as $class) {
            if ($class->school_id !== $teacher->school_id) continue;
            
            $subId = $class->pivot->subject_id;
            $exams = Exam::where('class_id', $class->id)
                ->where('subject_id', $subId)
                ->select('id', 'name', 'exam_round_id')
                ->get();

            $classes[] = [
                'id' => $class->id,
                'name' => $class->name,
                'subject_id' => $subId,
                'subject_name' => isset($subjects[$subId]) ? $subjects[$subId]->name : 'Unknown',
                'exams' => $exams,
            ];
        }
        
        return response()->json($classes);
    }

    /**
     * Get unique students for all classes a teacher teaches
     */
    public function teacherStudents(Request $request, $teacherId)
    {
        $teacher = Teacher::with(['classes.currentStudents.user', 'classes.currentStudents.parents.user'])->find((int) $teacherId);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $classId = $request->query('class_id');
        $students = collect();

        foreach ($teacher->classes as $class) {
            if ($class->school_id !== $teacher->school_id) continue;
            if ($classId && $class->id != $classId) continue;

            foreach ($class->currentStudents as $student) {
                if ($student->school_id !== $teacher->school_id) continue;

                if (!$students->contains('id', $student->id)) {
                    $parents = $student->parents->map(function ($parent) {
                        return [
                            'id' => $parent->id,
                            'phone_number' => $parent->phone_number,
                            'name' => $parent->user->first_name . ' ' . $parent->user->last_name,
                        ];
                    });

                    $students->push([
                        'id' => $student->id,
                        'admission_number' => $student->admission_number,
                        'user' => [
                            'id' => $student->user->id,
                            'first_name' => $student->user->first_name,
                            'last_name' => $student->user->last_name,
                        ],
                        'parents' => $parents
                    ]);
                }
            }
        }

        return response()->json($students->values()->all());
    }

    /**
     * Get teacher dashboard stats
     */
    public function teacherDashboard($teacherId)
    {
        $teacher = Teacher::with('classes')->find((int) $teacherId);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $classIds = $teacher->classes->pluck('id')->toArray();

        // 1. Stream Bottleneck (Top failure point)
        $topError = "No diagnostic data available yet.";
        $latestClassAnalysis = ClassAnalysis::whereIn('class_id', $classIds)->orderBy('generated_at', 'desc')->first();
        if ($latestClassAnalysis && !empty($latestClassAnalysis->question_analyses)) {
            $lowestRatio = 1.1;
            foreach ($latestClassAnalysis->question_analyses as $qa) {
                if (isset($qa['avg_score']) && isset($qa['max_score']) && $qa['max_score'] > 0) {
                    $ratio = $qa['avg_score'] / $qa['max_score'];
                    if ($ratio < $lowestRatio) {
                        $lowestRatio = $ratio;
                        $topError = $qa['common_error'] ?? ($qa['topic'] ?? 'Unknown Error');
                    }
                }
            }
        }
        
        // 2. Class mean of the latest exam round
        $latestRound = ExamRound::orderBy('start_date', 'desc')->first();
        $classMean = 0;
        $examName = "No Exams Found";
        $dateRecorded = "N/A";
        
        if ($latestRound) {
            $examName = $latestRound->name;
            $dateRecorded = $latestRound->end_date ? $latestRound->end_date->format('Y-m-d') : 'Ongoing';
            
            $examIds = [];
            foreach ($teacher->classes as $cls) {
                $eIds = Exam::where('exam_round_id', $latestRound->id)
                    ->where('class_id', $cls->id)
                    ->where('subject_id', $cls->pivot->subject_id)
                    ->pluck('id')->toArray();
                $examIds = array_merge($examIds, $eIds);
            }
            
            if (count($examIds) > 0) {
                $avg = ExamResult::whereIn('exam_id', $examIds)
                    ->selectRaw('SUM(total_score) as total_sum, SUM(max_score) as max_sum')
                    ->first();
                if ($avg && $avg->max_sum > 0) {
                    $classMean = round(($avg->total_sum / $avg->max_sum) * 100, 1);
                }
            }
        }

        return response()->json([
            'stream_bottleneck' => $topError,
            'class_mean' => $classMean,
            'exam_name' => $examName,
            'date_recorded' => $dateRecorded,
        ]);
    }

    /**
     * Generate class diagnosis using AI
     */
    public function generateClassDiagnosis(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer',
            'class_id' => 'required|integer',
            'exam_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        $classId = (int) $validated['class_id'];
        $examId = (int) $validated['exam_id'];
        $subjectId = (int) $validated['subject_id'];
        $teacherId = (int) $validated['teacher_id'];

        $classGroup = ClassGroup::with('currentStudents')->find($classId);
        if (!$classGroup) {
            return response()->json(['message' => 'Class not found'], 404);
        }
        $studentIds = $classGroup->currentStudents->pluck('id')->toArray();

        $analyses = ExamAnalysis::whereIn('student_id', $studentIds)
            ->where('exam_id', (int) $examId)
            ->get();

        if ($analyses->isEmpty()) {
            return response()->json(['message' => 'No student analysis data found for this exam. AI cannot generate a class diagnosis.'], 400);
        }

        $bloomSums = [
            'remembering' => 0,
            'understanding' => 0,
            'applying' => 0,
            'analyzing' => 0,
            'evaluating' => 0,
        ];
        $count = 0;

        foreach ($analyses as $ana) {
            if (!empty($ana->blooms_taxonomy)) {
                $bloomSums['remembering'] += (float)($ana->blooms_taxonomy['remembering']['score'] ?? 0);
                $bloomSums['understanding'] += (float)($ana->blooms_taxonomy['understanding']['score'] ?? 0);
                $bloomSums['applying'] += (float)($ana->blooms_taxonomy['applying']['score'] ?? 0);
                $bloomSums['analyzing'] += (float)($ana->blooms_taxonomy['analyzing']['score'] ?? 0);
                $bloomSums['evaluating'] += (float)($ana->blooms_taxonomy['evaluating']['score'] ?? 0);
                $count++;
            }
        }

        if ($count === 0) {
            return response()->json(['message' => 'No valid Bloom\'s taxonomy data found in the student analyses.'], 400);
        }

        $averages = [
            'remembering' => round($bloomSums['remembering'] / $count, 2),
            'understanding' => round($bloomSums['understanding'] / $count, 2),
            'applying' => round($bloomSums['applying'] / $count, 2),
            'analyzing' => round($bloomSums['analyzing'] / $count, 2),
            'evaluating' => round($bloomSums['evaluating'] / $count, 2),
        ];

        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json(['message' => 'Gemini API key is missing.'], 500);
        }

        $prompt = 'You are the GELISCOPE AI CLASS ANALYZER. Evaluate the performance of the class based on the average Bloom\'s Taxonomy scores out of 20 for each category: ' . json_encode($averages) . '. Return ONLY valid JSON in the following format: { "class_overview": { "class_average_percentage": 75, "highest_performing_area": "Remembering", "lowest_performing_area": "Evaluating" }, "suggestions": [ "Suggestion 1 for the teacher", "Suggestion 2" ] }. Do not include markdown code blocks, just raw JSON.';

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ];

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . $apiKey, $payload);

        if ($response->failed()) {
            Log::error('Gemini API Error (Class Diagnosis): ' . $response->body());
            return response()->json(['message' => 'Failed to generate class diagnosis with AI.', 'error' => $response->body()], 500);
        }

        $result = $response->json();
        $generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        
        $generatedText = preg_replace('/```json\s+/', '', $generatedText);
        $generatedText = preg_replace('/```/', '', $generatedText);

        $aiData = json_decode(trim($generatedText), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Failed to parse AI response.', 'raw' => $generatedText], 500);
        }

        $analysis = ClassAnalysis::updateOrCreate(
            [
                'class_id' => (int) $classId,
                'exam_id' => (int) $examId,
            ],
            [
                'teacher_id' => (int) $teacherId,
                'subject_id' => (int) $subjectId,
                'class_overview' => $aiData['class_overview'] ?? null,
                'suggestions' => $aiData['suggestions'] ?? [],
                'generated_at' => Carbon::now(),
            ]
        );

        return response()->json($analysis, 201);
    }

    /**
     * Get class performance history for a teacher (grouped by subject and ordered by exam rounds).
     */
    public function teacherClassPerformance(Request $request, $teacherId)
    {
        $classId = $request->query('class_id');
        
        if (!$classId) {
            return response()->json(['message' => 'class_id is required'], 400);
        }

        // Fetch unique rounds and subjects directly from the database for this class
        $rounds = ExamRound::whereHas('exams', function($query) use ($classId) {
            $query->where('class_id', $classId);
        })->orderBy('start_date')->get();

        $subjects = Subject::whereHas('exams', function($query) use ($classId) {
            $query->where('class_id', $classId)->whereNotNull('exam_round_id');
        })->get();

        $roundNames = $rounds->pluck('name')->toArray();
        $roundIds = $rounds->pluck('id')->toArray();

        // Perform the aggregation entirely in SQL using Laravel's Query Builder
        $aggregatedResults = \Illuminate\Support\Facades\DB::table('exam_results')
            ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
            ->where('exams.class_id', $classId)
            ->whereNotNull('exams.exam_round_id')
            ->selectRaw('
                exams.subject_id,
                exams.exam_round_id,
                AVG((exam_results.total_score / exam_results.max_score) * 100) as avg_score
            ')
            ->groupBy('exams.subject_id', 'exams.exam_round_id')
            ->get();

        $subjectData = [];

        foreach ($subjects as $subject) {
            $data = [];
            foreach ($roundIds as $roundId) {
                // Find the aggregated result for this subject and round
                $result = $aggregatedResults->first(function ($r) use ($subject, $roundId) {
                    return $r->subject_id == $subject->id && $r->exam_round_id == $roundId;
                });

                if ($result) {
                    $data[] = round($result->avg_score, 2);
                } else {
                    $data[] = null;
                }
            }
            
            $subjectData[] = [
                'name' => $subject->name,
                'data' => $data,
            ];
        }

        return response()->json([
            'rounds' => $roundNames,
            'subjects' => $subjectData
        ]);
    }

    /**
     * Get comprehensive profile and AI diagnostic performance for a student.
     */
    public function teacherStudentComprehensiveProfile(Request $request, $teacherId, $studentId)
    {
        $student = Student::with(['user', 'currentClass'])->find($studentId);
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $examResults = ExamResult::where('student_id', $student->id)
            ->with(['exam.subject', 'exam.examRound'])
            ->get();

        $performance = [];

        foreach ($examResults as $result) {
            $analysis = ExamAnalysis::where('student_id', (int) $student->id)
                ->where('exam_id', (int) $result->exam_id)
                ->first();

            $failurePoints = [];
            if ($analysis) {
                if (is_array($analysis->suggestions) && !empty($analysis->suggestions)) {
                    $failurePoints = $analysis->suggestions;
                } else if ($analysis->overall_summary) {
                    $failurePoints = [$analysis->overall_summary];
                }
            }

            $performance[] = [
                'exam_name' => $result->exam->name ?? 'Unknown Exam',
                'subject' => $result->exam->subject->name ?? 'Unknown Subject',
                'round' => $result->exam->examRound->name ?? 'Unknown Round',
                'score' => $result->total_score,
                'max_score' => $result->max_score,
                'percentage' => $result->max_score > 0 ? round(((float)$result->total_score / (float)$result->max_score) * 100) : 0,
                'failure_points' => $failurePoints,
                'date' => $result->created_at
            ];
        }

        return response()->json([
            'profile' => [
                'first_name' => $student->user->first_name,
                'last_name' => $student->user->last_name,
                'email' => $student->user->email,
                'admission_number' => $student->admission_number,
                'class_name' => $student->classGroup ? $student->classGroup->name : 'N/A'
            ],
            'performance' => $performance
        ]);
    }
}
