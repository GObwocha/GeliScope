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
     * Step 2: Login with password (Sends OTP)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // 1. Send via EmailJS (or log if credentials missing)
        if (config('services.emailjs.service_id') && config('services.emailjs.template_id') && config('services.emailjs.public_key')) {
            try {
                \Illuminate\Support\Facades\Http::post('https://api.emailjs.com/api/v1.0/email/send', [
                    'service_id' => config('services.emailjs.service_id'),
                    'template_id' => config('services.emailjs.template_id'),
                    'user_id' => config('services.emailjs.public_key'),
                    'template_params' => [
                        'to_email' => $user->email,
                        'otp_code' => $otp,
                        'message' => "Your GeliScope login OTP is: {$otp}"
                    ]
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('EmailJS failed: ' . $e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::info("EmailJS credentials not set. Simulated Email to {$user->email}: Your GeliScope login OTP is: {$otp}");
        }

        // 2. Send via Africa's Talking (SMS)
        if ($user->phone_number && config('services.africastalking.username') && config('services.africastalking.api_key')) {
            try {
                \Illuminate\Support\Facades\Http::asForm()
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'apiKey' => config('services.africastalking.api_key'),
                    ])
                    ->post('https://api.africastalking.com/version1/messaging', [
                        'username' => config('services.africastalking.username'),
                        'to' => $user->phone_number,
                        'message' => "Your GeliScope login OTP is: {$otp}",
                    ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('AfricasTalking SMS failed: ' . $e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::info("AfricasTalking credentials not set. Simulated SMS to {$user->phone_number}: Your GeliScope login OTP is: {$otp}");
        }

        return response()->json([
            'requires_otp' => true,
            'message' => 'OTP sent to your email and phone number.',
        ], 200);
    }

    /**
     * Step 3: Verify OTP and complete login
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'otp' => 'required|string|size:6'
        ]);

        $user = User::with(['student.school', 'student.skills', 'teacher.school', 'parentProfile.students.user'])
                    ->where('email', $request->email)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->otp_code !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp' => ['The OTP is invalid or has expired.'],
            ]);
        }

        // Clear OTP
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Delete all previous tokens (optional)
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
     * Verify User Email
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = User::where('email_verification_token', $request->token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired verification token.'], 400);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        // Optional: auto-login after verification
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => $user->load(['student.school', 'student.skills', 'teacher.school', 'parentProfile.students.user']),
            'token' => $token,
        ], 200);
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
