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
}
