<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExamRound;

class ExamRoundController extends Controller
{
    public function index()
    {
        $examRounds = ExamRound::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $examRounds]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'term' => 'required|integer',
            'academic_year' => 'required|string|max:4',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $examRound = ExamRound::create($validated);

        return response()->json(['message' => 'Exam round created successfully', 'data' => $examRound], 201);
    }
}
