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

    /**
     * Store marking config.
     */
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
}
