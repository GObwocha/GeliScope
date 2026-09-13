<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::with(['subcounty.county'])->withCount(['classes', 'students', 'teachers'])->get();
        return response()->json($schools);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subcounty_id' => 'required|exists:subcounties,id',
            'school_type' => 'required|in:primary,secondary,tertiary',
        ]);

        $school = School::create($validated);

        return response()->json($school, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $school = School::with(['subcounty.county', 'classes', 'students.user', 'teachers.user'])->findOrFail($id);
        return response()->json($school);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'subcounty_id' => 'sometimes|required|exists:subcounties,id',
            'school_type' => 'sometimes|required|in:primary,secondary,tertiary',
        ]);

        $school->update($validated);

        return response()->json($school);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();

        return response()->json(['message' => 'School deleted successfully'], 200);
    }

    /**
     * List classes for a specific school.
     */
    public function classes($id)
    {
        $school = School::findOrFail($id);
        $classes = $school->classes()->withCount(['students', 'teachers'])->get();

        return response()->json($classes);
    }

    /**
     * Store a class in a specific school.
     */
    public function storeClass(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:50',
        ]);

        $classGroup = $school->classes()->create($validated);

        return response()->json($classGroup, 201);
    }
}
