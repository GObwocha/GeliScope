<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardEntry;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Display a listing of leaderboard entries.
     */
    public function index(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'nullable|string',
            'term' => 'nullable|integer|between:1,3',
        ]);

        $query = LeaderboardEntry::with(['student.user', 'subject'])
            ->where('subject_id', $request->subject_id);

        if ($request->has('academic_year') && !empty($request->academic_year)) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->has('term') && !empty($request->term)) {
            $query->where('term', $request->term);
        }

        $entries = $query->orderBy('rank', 'asc')->paginate(20);

        return response()->json($entries);
    }
}
