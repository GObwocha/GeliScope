<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\MongoDB\ParentReport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ParentController extends Controller
{
    /**
     * Search students for linking.
     */
    public function searchStudents(Request $request)
    {
        $query = Student::with('user');
        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $query->where('admission_number', 'LIKE', "%{$q}%")
                  ->orWhereHas('user', function($u) use ($q) {
                      $u->where('first_name', 'LIKE', "%{$q}%")
                        ->orWhere('last_name', 'LIKE', "%{$q}%");
                  });
        }
        return response()->json($query->take(20)->get());
    }

    /**
     * Display the specified parent profile with linked students.
     */
    public function show($parentId)
    {
        $parent = ParentProfile::with(['user', 'students.user', 'students.school', 'students.currentClass'])->findOrFail($parentId);
        return response()->json($parent);
    }

    /**
     * Link a student to a parent.
     */
    public function linkStudent(Request $request, $parentId)
    {
        $parent = ParentProfile::findOrFail($parentId);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $parent->students()->syncWithoutDetaching([$validated['student_id']]);

        return response()->json($parent->load('students.user'), 200);
    }

    /**
     * Unlink a student from a parent.
     */
    public function unlinkStudent($parentId, $studentId)
    {
        $parent = ParentProfile::findOrFail($parentId);
        $parent->students()->detach($studentId);

        return response()->json(['message' => 'Student unlinked successfully'], 200);
    }

    /**
     * Get parent report from MongoDB for a specific child.
     */
    public function studentReport($parentId, $studentId)
    {
        // Ensure parent has access to this student
        $parent = ParentProfile::findOrFail($parentId);
        if (!$parent->students()->where('students.id', $studentId)->exists()) {
            return response()->json(['message' => 'Unauthorized access to student report'], 403);
        }

        $report = ParentReport::where('parent_id', (int) $parentId)
            ->where('student_id', (int) $studentId)
            ->first();

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        return response()->json($report);
    }

    /**
     * Store or update parent report document.
     */
    public function storeStudentReport(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|integer',
            'student_id' => 'required|integer',
            'report_period' => 'required|array',
            'performance_summary' => 'nullable|array',
            'improvement_steps' => 'nullable|array',
        ]);

        $validated['parent_id'] = (int) $validated['parent_id'];
        $validated['student_id'] = (int) $validated['student_id'];

        $validated['generated_at'] = Carbon::now();

        $report = ParentReport::updateOrCreate(
            [
                'parent_id' => (int) $validated['parent_id'],
                'student_id' => (int) $validated['student_id'],
            ],
            $validated
        );

        return response()->json($report, 201);
    }

    /**
     * Get teachers associated with a specific student through their classes.
     */
    public function childTeachers($parentId, $studentId)
    {
        $parent = ParentProfile::findOrFail($parentId);
        if (!$parent->students()->where('students.id', $studentId)->exists()) {
            return response()->json(['message' => 'Unauthorized access to student teachers'], 403);
        }

        $student = Student::with('classes.teachers.user')->findOrFail($studentId);

        // Collect unique teachers from the student's enrolled classes
        $teachers = collect();
        foreach ($student->classes as $classGroup) {
            foreach ($classGroup->teachers as $teacher) {
                if (!$teachers->contains('id', $teacher->id)) {
                    $teachers->push($teacher);
                }
            }
        }

        return response()->json($teachers->values());
    }
}
