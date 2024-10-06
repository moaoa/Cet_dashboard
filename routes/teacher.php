<?php

use App\Http\Controllers\TakeAttendanceController;
use App\Http\Controllers\Teacher\AuthController;
use App\Http\Controllers\Teacher\GroupsOfSubjectController;
use App\Http\Controllers\Teacher\HomeworkController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\UsersAttendingQuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\LecturesController;
use App\Http\Controllers\Teacher\SubjectsController;
use App\Http\Controllers\AvailableClassRoomsController;

Route::middleware('auth:teacher')->group(function () {
     Route::get('/lectures', [LecturesController::class, 'index']);
     Route::post('/lectures', [LecturesController::class, 'store']);

     Route::get('/subjects', [SubjectsController::class, 'index']);
     Route::get('/subjects/{subject}/groups', [GroupsOfSubjectController::class, 'index']);

     Route::post('/attendance/{lecture}', TakeAttendanceController::class);

     Route::get('/available-classrooms', AvailableClassRoomsController::class);

     Route::get('/quizzes', [QuizController::class, 'index']);
     Route::post('/quizzes', [QuizController::class, 'store']);
     Route::get('/quiz-results/{quiz}', [QuizController::class, 'quizResults']);
     Route::get('/quiz/{quiz}/users/{user}/result', [QuizController::class, 'getStudentResult']);


     Route::get('/homeworks', [HomeworkController::class, 'index']);
     Route::post('/homeworks', [HomeworkController::class, 'store']);
     Route::get('/groups/{group}/subjects/{subject}/homeworks', [HomeworkController::class, 'getHomeworkForGroupAndSubject']);
     Route::get('/homeworks/{homework}', [HomeworkController::class, 'show']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//jRoute::get('/student/subject', [UserSubjectController::class, 'index'])->middleware('auth:sanctum');
