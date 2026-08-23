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
                $studentRoundIds = \Illuminate\Support\Facades\DB::table('exam_results')
                    ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
                    ->where('exam_results.student_id', $studentId)
                    ->pluck('exams.exam_round_id')
                    ->unique()
                    ->filter();
                
                if ($studentRoundIds->isEmpty()) {
                    return collect([]);
                }

                // Get aggregated results for all students in those rounds directly in SQL
                $rawResults = \Illuminate\Support\Facades\DB::table('exam_results')
                    ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
                    ->join('students', 'exam_results.student_id', '=', 'students.id')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->leftJoin('schools', 'students.school_id', '=', 'schools.id')
                    ->leftJoin('subcounties', 'schools.subcounty_id', '=', 'subcounties.id')
                    ->leftJoin('counties', 'subcounties.county_id', '=', 'counties.id')
                    ->whereIn('exams.exam_round_id', $studentRoundIds)
                    ->selectRaw('
                        students.id as id,
                        users.first_name,
                        users.last_name,
                        schools.name as school_name,
                        counties.name as county_name,
                        AVG((exam_results.total_score / exam_results.max_score) * 100) as raw_score
                    ')
                    ->groupBy(
                        'students.id',
                        'users.first_name',
                        'users.last_name',
                        'schools.name',
                        'counties.name'
                    )
                    ->orderByDesc('raw_score')
                    ->get();
                    
                return $rawResults->map(function ($item, $index) {
                    return [
                        'id' => $item->id,
                        'name' => $item->first_name . ' ' . $item->last_name,
                        'score' => round($item->raw_score),
                        'school' => $item->school_name ?? 'N/A',
                        'county' => $item->county_name ?? 'N/A',
                        'rank' => $index + 1,
                    ];
                });
            });
                
            return response()->json($results);
        } else {
            // Fetch leaderboard for a specific exam round directly in SQL
            $results = Cache::rememberForever('leaderboard_round_' . $examRoundId, function () use ($examRoundId) {
                $rawResults = \Illuminate\Support\Facades\DB::table('exam_results')
                    ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
                    ->join('students', 'exam_results.student_id', '=', 'students.id')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->leftJoin('schools', 'students.school_id', '=', 'schools.id')
                    ->leftJoin('subcounties', 'schools.subcounty_id', '=', 'subcounties.id')
                    ->leftJoin('counties', 'subcounties.county_id', '=', 'counties.id')
                    ->where('exams.exam_round_id', $examRoundId)
                    ->selectRaw('
                        students.id as id,
                        users.first_name,
                        users.last_name,
                        schools.name as school_name,
                        counties.name as county_name,
                        AVG((exam_results.total_score / exam_results.max_score) * 100) as raw_score
                    ')
                    ->groupBy(
                        'students.id',
                        'users.first_name',
                        'users.last_name',
                        'schools.name',
                        'counties.name'
                    )
                    ->orderByDesc('raw_score')
                    ->get();
                    
                return $rawResults->map(function ($item, $index) {
                    return [
                        'id' => $item->id,
                        'name' => $item->first_name . ' ' . $item->last_name,
                        'score' => round($item->raw_score),
                        'school' => $item->school_name ?? 'N/A',
                        'county' => $item->county_name ?? 'N/A',
                        'rank' => $index + 1,
                    ];
                });
            });

            return response()->json($results);
        }
    }
}
