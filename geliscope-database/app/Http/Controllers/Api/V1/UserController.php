<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        return response()->json($query->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:student,teacher,parent',
            'profile_photo_url' => 'nullable|string|url',
            // Role specific fields
            'admission_number' => 'required_if:role,student|string|unique:students,admission_number',
            'school_id' => 'required_if:role,student,teacher|exists:schools,id',
            'current_class_id' => 'nullable|exists:classes,id',
            'date_of_birth' => 'nullable|date',
            'employee_number' => 'required_if:role,teacher|string|unique:teachers,employee_number',
            'specialization' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'occupation' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'profile_photo_url' => $validated['profile_photo_url'] ?? null,
            ]);

            if ($validated['role'] === 'student') {
                Student::create([
                    'user_id' => $user->id,
                    'admission_number' => $validated['admission_number'],
                    'school_id' => $validated['school_id'],
                    'current_class_id' => $validated['current_class_id'] ?? null,
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                ]);
            } elseif ($validated['role'] === 'teacher') {
                Teacher::create([
                    'user_id' => $user->id,
                    'employee_number' => $validated['employee_number'],
                    'school_id' => $validated['school_id'],
                    'specialization' => $validated['specialization'] ?? null,
                ]);
            } elseif ($validated['role'] === 'parent') {
                ParentProfile::create([
                    'user_id' => $user->id,
                    'phone_number' => $validated['phone_number'] ?? null,
                    'occupation' => $validated['occupation'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json($user->load(['student.school', 'student.skills', 'teacher.school', 'parentProfile']), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['student.school', 'student.skills', 'teacher.school', 'parentProfile'])->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'profile_photo_url' => 'nullable|string|url',
            'personal_motto' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 7) {
                        $fail('The personal motto must not exceed 7 words.');
                    }
                },
            ],
            'admission_number' => 'sometimes|required|string|unique:students,admission_number,' . optional($user->student)->id,
            'school_id' => 'sometimes|required|exists:schools,id',
            'current_class_id' => 'nullable|exists:classes,id',
            'date_of_birth' => 'nullable|date',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($request->only(['first_name', 'last_name', 'email', 'password', 'profile_photo_url']));

        if ($user->role === 'student' && $user->student) {
            $studentData = $request->only(['admission_number', 'school_id', 'current_class_id', 'date_of_birth', 'personal_motto']);
            if (!empty($studentData)) {
                $user->student->update($studentData);
            }
        }

        return response()->json($user->fresh(['student.school', 'student.skills', 'teacher.school', 'parentProfile']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    /**
     * Upload an avatar for the user.
     */
    public function uploadAvatar(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store the file in the 'public/avatars' directory
            $path = $file->storeAs('avatars', $filename, 'public');
            
            // Generate full URL (requires php artisan storage:link)
            $url = config('app.url') . '/storage/' . $path;

            // Delete old avatar if it exists and is hosted locally
            if ($user->profile_photo_url && str_contains($user->profile_photo_url, '/storage/avatars/')) {
                $oldPath = str_replace(config('app.url') . '/storage/', '', $user->profile_photo_url);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $user->profile_photo_url = $url;
            $user->save();

            return response()->json([
                'message' => 'Avatar uploaded successfully',
                'profile_photo_url' => $url,
                'user' => $user->fresh(['student.school', 'student.skills', 'teacher.school', 'parentProfile'])
            ], 200);
        }

        return response()->json(['message' => 'No file provided'], 400);
    }

    /**
     * Remove the user's avatar.
     */
    public function removeAvatar($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_photo_url) {
            if (str_contains($user->profile_photo_url, '/storage/avatars/')) {
                $oldPath = str_replace(config('app.url') . '/storage/', '', $user->profile_photo_url);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $user->profile_photo_url = null;
            $user->save();
        }

        return response()->json([
            'message' => 'Avatar removed successfully',
            'user' => $user->fresh(['student.school', 'student.skills', 'teacher.school', 'parentProfile'])
        ], 200);
    }
}
