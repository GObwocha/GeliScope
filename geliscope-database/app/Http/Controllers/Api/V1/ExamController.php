<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\QuestionScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Exam::with(['subject', 'classGroup', 'teacher.user']);

        if ($request->has('class_id') && !empty($request->class_id)) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->has('subject_id') && !empty($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->has('academic_year') && !empty($request->academic_year)) {
            $query->where('academic_year', $request->academic_year);
        }

        return response()->json($query->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_code' => 'nullable|string|unique:exams,exam_code|max:100',
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_date' => 'required|date',
            'term' => 'required|integer|between:1,3',
            'academic_year' => 'required|string|max:50',
            'created_by' => 'required|exists:teachers,id',
        ]);

        $exam = Exam::create($validated);

        return response()->json($exam->load(['subject', 'classGroup', 'teacher.user']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $exam = Exam::with(['subject', 'classGroup', 'teacher.user', 'results.student.user'])->findOrFail($id);
        return response()->json($exam);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'exam_code' => 'sometimes|nullable|string|unique:exams,exam_code,' . $id . '|max:100',
            'name' => 'sometimes|required|string|max:255',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'class_id' => 'sometimes|required|exists:classes,id',
            'exam_date' => 'sometimes|required|date',
            'term' => 'sometimes|required|integer|between:1,3',
            'academic_year' => 'sometimes|required|string|max:50',
            'created_by' => 'sometimes|required|exists:teachers,id',
        ]);

        $exam->update($validated);

        return response()->json($exam->load(['subject', 'classGroup', 'teacher.user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['message' => 'Exam deleted successfully'], 200);
    }

    /**
     * Store an exam result for a student.
     */
    public function storeResult(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'total_score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:2',
            'mongo_analysis_id' => 'nullable|string',
        ]);

        $result = $exam->results()->updateOrCreate(
            ['student_id' => $validated['student_id']],
            $validated
        );

        return response()->json($result->load('student.user'), 201);
    }

    /**
     * Bulk store question scores for an exam result.
     */
    public function storeQuestionScores(Request $request, $resultId)
    {
        $result = ExamResult::findOrFail($resultId);

        $validated = $request->validate([
            'scores' => 'required|array|min:1',
            'scores.*.question_number' => 'required|integer|min:1',
            'scores.*.score' => 'required|numeric|min:0',
            'scores.*.max_score' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['scores'] as $item) {
                $result->questionScores()->updateOrCreate(
                    ['question_number' => $item['question_number']],
                    [
                        'score' => $item['score'],
                        'max_score' => $item['max_score']
                    ]
                );
            }

            DB::commit();

            return response()->json($result->load('questionScores'), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to save question scores', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch exam results for a specific student, sorted chronologically.
     */
    public function studentResults(Request $request, $studentId)
    {
        $subjectId = $request->query('subject_id');

        $query = ExamResult::with('exam.examRound')
            ->where('exam_results.student_id', $studentId)
            ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
            ->join('exam_rounds', 'exams.exam_round_id', '=', 'exam_rounds.id')
            ->select('exam_results.*', 'exam_rounds.name as round_name', 'exam_rounds.id as round_id')
            ->orderBy('exam_rounds.id', 'asc');

        if ($subjectId) {
            $query->where('exams.subject_id', $subjectId);
        }

        $results = $query->get();

        $grouped = $results->groupBy('round_id');

        $formattedResults = $grouped->map(function ($roundResults) use ($subjectId) {
            $first = $roundResults->first();
            $roundId = $first->round_id;
            
            $studentTotal = $roundResults->sum('total_score');
            $studentMax = $roundResults->sum('max_score');

            $streamQuery = ExamResult::join('exams', 'exam_results.exam_id', '=', 'exams.id')
                ->where('exams.exam_round_id', $roundId);
                
            if ($subjectId) {
                $streamQuery->where('exams.subject_id', $subjectId);
            }

            $aggregates = $streamQuery->selectRaw('SUM(total_score) as sum_total, SUM(max_score) as sum_max')->first();
            
            $streamAvg = 0;
            if ($aggregates && $aggregates->sum_max > 0) {
                $streamAvg = ($aggregates->sum_total / $aggregates->sum_max) * 100;
            }

            return [
                'id' => $roundId,
                'exam_id' => $first->exam_id,
                'student_id' => $first->student_id,
                'total_score' => $studentTotal,
                'max_score' => $studentMax,
                'exam' => [
                    'id' => $roundId,
                    'name' => $first->round_name,
                ],
                'stream_avg' => round($streamAvg, 1),
            ];
        })->values();

        return response()->json($formattedResults);
    }
}
