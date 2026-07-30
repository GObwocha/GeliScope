<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    /**
     * Display a listing of leaderboard entries based on exam.
     */
    public function index(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|string',
            'student_id' => 'nullable|exists:students,id', // required if exam_id is 'all'
        ]);

        $examId = $request->exam_id;
        $studentId = $request->student_id;

        if ($examId === 'all') {
            $results = Cache::remember('leaderboard_all_student_' . $studentId, 600, function () use ($studentId) {
                // Fetch all exam IDs the student has taken
                $studentExamIds = \App\Models\ExamResult::where('student_id', $studentId)->pluck('exam_id');
                
                if ($studentExamIds->isEmpty()) {
                    return collect([]);
                }

                // Get all results for these exams for all students
                return \App\Models\ExamResult::whereIn('exam_id', $studentExamIds)
                    ->with(['student.user', 'student.school.subCounty.county'])
                    ->get()
                    ->groupBy('student_id')
                    ->map(function ($studentResults) {
                        $totalPercentage = $studentResults->sum(fn($res) => ($res->total_score / $res->max_score) * 100);
                        $averagePercentage = $totalPercentage / $studentResults->count();
                        $firstResult = $studentResults->first();
                        
                        return [
                            'id' => $firstResult->student_id,
                            'name' => $firstResult->student->user->first_name . ' ' . $firstResult->student->user->last_name,
                            'score' => round($averagePercentage),
                            'school' => $firstResult->student->school->name ?? 'N/A',
                            'county' => $firstResult->student->school->subCounty->county->name ?? 'N/A',
                        ];
                    })
                    ->sortByDesc('score')
                    ->values()
                    ->map(function ($item, $index) {
                        $item['rank'] = $index + 1;
                        return $item;
                    });
            });
                
            return response()->json($results);
        } else {
            // Fetch leaderboard for a specific exam
            $results = Cache::rememberForever('leaderboard_exam_' . $examId, function () use ($examId) {
                return \App\Models\ExamResult::where('exam_id', $examId)
                    ->with(['student.user', 'student.school.subCounty.county'])
                    ->get()
                    ->map(function ($result) {
                        return [
                            'id' => $result->student_id,
                            'name' => $result->student->user->first_name . ' ' . $result->student->user->last_name,
                            'score' => round(($result->total_score / $result->max_score) * 100),
                            'school' => $result->student->school->name ?? 'N/A',
                            'county' => $result->student->school->subCounty->county->name ?? 'N/A',
                        ];
                    })
                    ->sortByDesc('score')
                    ->values()
                    ->map(function ($item, $index) {
                        $item['rank'] = $index + 1;
                        return $item;
                    });
            });

            return response()->json($results);
        }
    }
}
