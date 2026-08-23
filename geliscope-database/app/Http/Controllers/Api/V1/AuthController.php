<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Socialite;

class AuthController extends Controller
{
    /**
     * Step 1: Check if email exists
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role' => $user->role,
            'profile_photo_url' => $user->profile_photo_url,
        ], 200);
    }

    /**
     * Step 2: Login with password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::with(['student.school', 'student.skills', 'teacher.school', 'parentProfile.students.user'])->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete all previous tokens (optional, but good for single-device sessions or general cleanup)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    // Step 1: Send the Google login URL to Vue
    public function googleRedirect()
    {
        $driver = Socialite::driver('google');
        $url = $driver->stateless()->redirect()->getTargetUrl();
        return response()->json(['url' => $url]);
    }

    // Step 2: Handle the return from Google
    public function googleCallback()
    {
        // 1. Get the user data from Google
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::with(['student.school', 'student.skills', 'teacher.school', 'parentProfile.students.user'])->where('email', $googleUser->email)->first();

        if (!$user ){
            return redirect()->away(config('app.frontend_url') . '/login?error=user_not_found');
        }

        if (!$user->profile_photo_url && $googleUser->avatar) {
            $user->profile_photo_url = $googleUser->avatar;
            $user->save();
        }

        // Delete all previous tokens (optional, but good for single-device sessions or general cleanup)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Redirect back to your Vue frontend, passing the token in the URL
        $frontendUrl = config('app.frontend_url') . '/google-callback?token=' . $token;
        
        return redirect()->away($frontendUrl);
    }

    /**
     * Get the authenticated User
     */
    public function me(Request $request)
    {
        $user = $request->user()->load(['student.school', 'student.skills', 'teacher.school', 'parentProfile.students.user']);
        return response()->json($user, 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
