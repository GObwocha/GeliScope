<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SchoolController;
use App\Http\Controllers\Api\V1\ExamController;
use App\Http\Controllers\Api\V1\AnalysisController;
use App\Http\Controllers\Api\V1\LeaderboardController;
use App\Http\Controllers\Api\V1\ParentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\StudentTodoController;

Route::prefix('v1')->group(function () {
    // Unprotected Routes (Login and Registration)
    Route::post('auth/check-email', [AuthController::class, 'checkEmail']);
    Route::post('auth/login', [AuthController::class, 'login']);
    //Google OAuth
    Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect']);
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

    Route::post('register-user', [UserController::class, 'store']); // Registration
    Route::get('schools', [SchoolController::class, 'index']); // For registration
    Route::get('schools/{id}/classes', [SchoolController::class, 'classes']); // For registration

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        //Users
        Route::apiResource('users', UserController::class)->except(['store']);
        //Subjects
        Route::get('subjects', [\App\Http\Controllers\Api\V1\SubjectController::class, 'index']);
        //Schools
        Route::apiResource('schools', SchoolController::class)->except(['index']);
        Route::post('schools/{id}/classes', [SchoolController::class, 'storeClass']);
        //Exam Rounds
        Route::get('exam-rounds', [\App\Http\Controllers\Api\V1\ExamRoundController::class, 'index']);
        //Exams
        Route::apiResource('exams', ExamController::class);
        Route::post('exams/{examId}/results', [ExamController::class, 'storeResult']);
        Route::post('exam-results/{resultId}/question-scores', [ExamController::class, 'storeQuestionScores']);
        Route::get('students/{studentId}/exam-results', [ExamController::class, 'studentResults']);
        //Analysis
        Route::get('analysis/exam/{studentId}/{examId}', [AnalysisController::class, 'examAnalysis']);
        Route::post('analysis/exam', [AnalysisController::class, 'storeExamAnalysis']);
        Route::post('analysis/process-vision', [AnalysisController::class, 'processVision']);
        Route::get('analysis/skills/{studentId}', [AnalysisController::class, 'skillProfile']);
        Route::post('analysis/skills', [AnalysisController::class, 'storeSkillProfile']);
        Route::get('analysis/cognitive-profile/{studentId}', [AnalysisController::class, 'cognitiveProfile']);
        Route::get('analysis/class/{classId}/{examId}', [AnalysisController::class, 'classAnalysis']);
        Route::post('analysis/class', [AnalysisController::class, 'storeClassAnalysis']);
        Route::get('analysis/marking-config/{examId}', [AnalysisController::class, 'markingConfig']);
        Route::post('analysis/marking-config', [AnalysisController::class, 'storeMarkingConfig']);
        Route::post('analysis/careers/suggest', [AnalysisController::class, 'suggestCareers']);
        Route::get('analysis/teacher/{teacherId}/dashboard', [AnalysisController::class, 'teacherDashboard']);
        Route::get('analysis/teacher/{teacherId}/classes', [AnalysisController::class, 'teacherClasses']);
        Route::get('analysis/teacher/{teacherId}/students', [AnalysisController::class, 'teacherStudents']);
        Route::get('analysis/teacher/{teacherId}/student/{studentId}/comprehensive-profile', [AnalysisController::class, 'teacherStudentComprehensiveProfile']);
        Route::get('analysis/teacher/{teacherId}/class-performance', [AnalysisController::class, 'teacherClassPerformance']);
        Route::post('analysis/class/generate-diagnosis', [AnalysisController::class, 'generateClassDiagnosis']);
        //Archives
        Route::get('archives/student/{studentId}', [AnalysisController::class, 'getArchives']);
        Route::get('archives/{archiveId}/download', [AnalysisController::class, 'downloadArchive']);
        //Leaderboard
        Route::get('leaderboard', [LeaderboardController::class, 'index']);
        //Parents
        Route::get('parents/{parentId}', [ParentController::class, 'show']);
        Route::post('parents/{parentId}/students', [ParentController::class, 'linkStudent']);
        Route::delete('parents/{parentId}/students/{studentId}', [ParentController::class, 'unlinkStudent']);
        Route::get('parents/{parentId}/students/{studentId}/report', [ParentController::class, 'studentReport']);
        Route::post('parents/reports', [ParentController::class, 'storeStudentReport']);
        Route::get('parents/{parentId}/students/{studentId}/teachers', [ParentController::class, 'childTeachers']);

        // Student Todos
        Route::get('students/{studentId}/todos', [StudentTodoController::class, 'index']);
        Route::post('students/{studentId}/todos', [StudentTodoController::class, 'store']);
        Route::put('todos/{id}', [StudentTodoController::class, 'update']);
        Route::delete('todos/{id}', [StudentTodoController::class, 'destroy']);
    });
});
