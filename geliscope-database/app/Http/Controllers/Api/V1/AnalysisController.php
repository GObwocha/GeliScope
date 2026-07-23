<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MongoDB\ExamAnalysis;
use App\Models\MongoDB\StudentSkillProfile;
use App\Models\MongoDB\ClassAnalysis;
use App\Models\MongoDB\TeacherMarkingConfig;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        ]);

        $validated['generated_at'] = Carbon::now();

        $analysis = ExamAnalysis::updateOrCreate(
            [
                'student_id' => (int) $validated['student_id'],
                'exam_id' => (int) $validated['exam_id'],
            ],
            $validated
        );

        return response()->json($analysis, 201);
    }

    /**
     * Get student skill profile.
     */
    public function skillProfile($studentId)
    {
        $profile = StudentSkillProfile::where('student_id', (int) $studentId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Skill profile not found'], 404);
        }

        return response()->json($profile);
    }

    /**
     * Store or update student skill profile.
     */
    public function storeSkillProfile(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'skill_sets' => 'required|array',
            'suggested_careers' => 'nullable|array',
        ]);

        $validated['last_updated'] = Carbon::now();

        $profile = StudentSkillProfile::updateOrCreate(
            ['student_id' => (int) $validated['student_id']],
            $validated
        );

        return response()->json($profile, 201);
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
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts('gemini-vision-min:' . $ip, 15)) {
            return response()->json(['message' => 'Minute API quota exceeded. Please try again later.'], 429);
        }

        // 1500 requests per day
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts('gemini-vision-day:' . $ip, 1500)) {
            return response()->json(['message' => 'Daily API quota exceeded. Please try again tomorrow.'], 429);
        }

        \Illuminate\Support\Facades\RateLimiter::hit('gemini-vision-min:' . $ip, 60);
        \Illuminate\Support\Facades\RateLimiter::hit('gemini-vision-day:' . $ip, 86400);

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,pdf|max:51200',
            'student_id' => 'nullable|integer',
            'exam_id' => 'nullable|integer',
        ]);

        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json(['message' => 'Gemini API key is missing.'], 500);
        }

        $parts = [];
        $parts[] = [
            'text' => 'You are the GELISCOPE VISION & OCR ENGINE. Evaluate the provided exam script(s). Return ONLY valid JSON in the following format, with no markdown code blocks formatting, just the raw JSON: { "student_id": 1, "exam_id": 1, "ocr_raw_text": "[Extracted and structured text]", "blooms_taxonomy": { "remembering": 95, "understanding": 88, "applying": 82, "analyzing": 60, "evaluating": 45 }, "question_breakdown": [ { "q": 1, "topic": "Algebra", "score": 3, "max": 3, "status": "Perfect", "comment": "Excellent" } ], "overall_summary": "Summary text", "suggestions": [ "Suggestion 1" ] }'
        ];

        foreach ($request->file('files') as $file) {
            $mime = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
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

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, $payload);

        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('Gemini API Error: ' . $response->body());
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
}
