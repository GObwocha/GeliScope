<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SchoolController;
use App\Http\Controllers\Api\V1\ExamController;
use App\Http\Controllers\Api\V1\AnalysisController;
use App\Http\Controllers\Api\V1\LeaderboardController;
use App\Http\Controllers\Api\V1\ParentController;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    // Unprotected Routes (Login and Registration)
    Route::post('auth/check-email', [AuthController::class, 'checkEmail']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('register-user', [UserController::class, 'store']); // Registration
    Route::get('schools', [SchoolController::class, 'index']); // For registration
    Route::get('schools/{id}/classes', [SchoolController::class, 'classes']); // For registration

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('users', UserController::class)->except(['store']);

        Route::apiResource('schools', SchoolController::class)->except(['index']);
        Route::post('schools/{id}/classes', [SchoolController::class, 'storeClass']);

        Route::apiResource('exams', ExamController::class);
        Route::post('exams/{examId}/results', [ExamController::class, 'storeResult']);
        Route::post('exam-results/{resultId}/question-scores', [ExamController::class, 'storeQuestionScores']);

        Route::get('analysis/exam/{studentId}/{examId}', [AnalysisController::class, 'examAnalysis']);
        Route::post('analysis/exam', [AnalysisController::class, 'storeExamAnalysis']);
        Route::post('analysis/process-vision', [AnalysisController::class, 'processVision']);
        Route::get('analysis/skills/{studentId}', [AnalysisController::class, 'skillProfile']);
        Route::post('analysis/skills', [AnalysisController::class, 'storeSkillProfile']);
        Route::get('analysis/class/{classId}/{examId}', [AnalysisController::class, 'classAnalysis']);
        Route::post('analysis/class', [AnalysisController::class, 'storeClassAnalysis']);
        Route::get('analysis/marking-config/{examId}', [AnalysisController::class, 'markingConfig']);
        Route::post('analysis/marking-config', [AnalysisController::class, 'storeMarkingConfig']);

        Route::get('leaderboard', [LeaderboardController::class, 'index']);

        Route::get('parents/{parentId}', [ParentController::class, 'show']);
        Route::post('parents/{parentId}/students', [ParentController::class, 'linkStudent']);
        Route::delete('parents/{parentId}/students/{studentId}', [ParentController::class, 'unlinkStudent']);
        Route::get('parents/{parentId}/students/{studentId}/report', [ParentController::class, 'studentReport']);
        Route::post('parents/reports', [ParentController::class, 'storeStudentReport']);
        Route::get('parents/{parentId}/students/{studentId}/teachers', [ParentController::class, 'childTeachers']);
    });
});
