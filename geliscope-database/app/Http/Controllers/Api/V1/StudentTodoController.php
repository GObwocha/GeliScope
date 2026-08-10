<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StudentTodo;
use Illuminate\Http\Request;

class StudentTodoController extends Controller
{
    public function index($studentId)
    {
        $todos = StudentTodo::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($todos);
    }

    public function store(Request $request, $studentId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_completed' => 'boolean',
            'exam_analysis_id' => 'nullable|string',
        ]);

        $todo = StudentTodo::create([
            'student_id' => $studentId,
            'title' => $request->title,
            'is_completed' => $request->is_completed ?? false,
            'exam_analysis_id' => $request->exam_analysis_id,
        ]);

        return response()->json($todo, 201);
    }

    public function update(Request $request, $id)
    {
        $todo = StudentTodo::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        if ($request->has('title')) {
            $todo->title = $request->title;
        }
        
        if ($request->has('is_completed')) {
            $todo->is_completed = $request->is_completed;
        }

        $todo->save();

        return response()->json($todo);
    }

    public function destroy($id)
    {
        $todo = StudentTodo::findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully']);
    }
}
