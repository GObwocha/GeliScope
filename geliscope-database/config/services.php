<?php

return [

    'emailjs' => [
        'public_key' => env('EMAILJS_PUBLIC_KEY'),
        'private_key' => env('EMAILJS_PRIVATE_KEY'),
    ],

    'emailjs_otp' => [
        'service_id' => env('OTP_EMAILJS_SERVICE_ID'),
        'template_id' => env('OTP_EMAILJS_TEMPLATE_ID'),
    ],

    'emailjs_ver' => [
        'service_id' => env('VER_EMAILJS_SERVICE_ID'),
        'template_id' => env('VER_EMAILJS_TEMPLATE_ID'),
    ],

    'africastalking' => [
        'username' => env('AFRICASTALKING_USERNAME'),
        'api_key' => env('AFRICASTALKING_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

    'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'), // e.g., http://localhost:8000/api/auth/google/callback
    ],

];
