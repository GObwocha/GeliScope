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
            'exam_round_id' => 'required|string',
            'student_id' => 'nullable|exists:students,id', // required if exam_round_id is 'all'
        ]);

        $examRoundId = $request->exam_round_id;
        $studentId = $request->student_id;

        if ($examRoundId === 'all') {
            $results = Cache::remember('leaderboard_all_rounds_student_' . $studentId, 600, function () use ($studentId) {
                // Fetch all exam rounds the student has participated in
                $studentRoundIds = \App\Models\ExamResult::where('student_id', $studentId)
                    ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
                    ->pluck('exams.exam_round_id')
                    ->unique();
                
                if ($studentRoundIds->isEmpty()) {
                    return collect([]);
                }

                // Get all results for exams in these rounds for all students
                return \App\Models\ExamResult::join('exams', 'exam_results.exam_id', '=', 'exams.id')
                    ->whereIn('exams.exam_round_id', $studentRoundIds)
                    ->with(['student.user', 'student.school.subCounty.county'])
                    ->select('exam_results.*') // avoid column conflicts
                    ->get()
                    ->groupBy('student_id')
                    ->map(function ($studentResults) {
                        // calculate average over all exams in all rounds
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
            // Fetch leaderboard for a specific exam round
            $results = Cache::rememberForever('leaderboard_round_' . $examRoundId, function () use ($examRoundId) {
                return \App\Models\ExamResult::join('exams', 'exam_results.exam_id', '=', 'exams.id')
                    ->where('exams.exam_round_id', $examRoundId)
                    ->with(['student.user', 'student.school.subCounty.county'])
                    ->select('exam_results.*')
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
        }
    }
}
