<?php

use App\Http\Controllers\AssignHomeworkToGroupController;
use App\Http\Controllers\AssignQuizToGroupController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\LectureStudentsController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersAttendingQuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TakeAttendanceController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\GroupQuizController;
use Illuminate\Support\Facades\Mail;


// Quiz routes
Route::group([], function () {
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{quizId}/users', [QuizController::class, 'users']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy']);
});


// Lecture students Routes
Route::group([], function () {
    Route::get('/lecture-students/{lectureId}', LectureStudentsController::class);
});

// lecture routes
Route::group([], function () {
    Route::get('/lectures', [LectureController::class, 'index']);
    Route::post('/lectures', [LectureController::class, 'store']);
    Route::get('/lectures/{lecture}', [LectureController::class, 'show']);
    Route::delete('/lectures/{lecture}', [LectureController::class, 'destroy']);
});


//  Homework routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/homeworks', [HomeworkController::class, 'index']);
    Route::post('/homeworks', [HomeworkController::class, 'store']);
    Route::post('/homeworks/{homework}/answer', [HomeworkController::class, 'uploadHomeworkAnswer']);
    Route::get('/homeworks/{homework}', [HomeworkController::class, 'show']);
    Route::delete('/homeworks/{homework}', [HomeworkController::class, 'destroy']);
});

//  Groups routes
Route::group([], function () {
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::put('/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy']);
});

// Group Quiz Routes
Route::group([], function () {
    Route::get('/group-quizzes', [GroupQuizController::class, 'index']);
    Route::post('/group-quizzes', [GroupQuizController::class, 'store']);
    Route::get('/group-quizzes/{groupQuiz}', [GroupQuizController::class, 'show']);
    Route::put('/group-quizzes/{groupQuiz}', [GroupQuizController::class, 'update']);
    Route::delete('/group-quizzes/{groupQuiz}', [GroupQuizController::class, 'destroy']);
});

// Teacher Stuff
Route::prefix('teacher')->group(function () {
    Route::get('/quiz-students/{quizId}', UsersAttendingQuizController::class);
    Route::post('/quiz-assignment', AssignQuizToGroupController::class);
    Route::post('/homework-assignment', AssignHomeworkToGroupController::class);
    Route::post('/attendance/{lecture}', TakeAttendanceController::class);
});


Route::get('/migration', [MigrationController::class, 'runMigrationsAndSeeders']);


Route::put('/users/update', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('email', function (){

        Mail::raw('message', function ($message) {
            $message->to('moaadbn3@gmail.com')
                    ->subject('Job Offer');
        });

        return response()->json(['message' => 'Email sent successfully.']);
});
