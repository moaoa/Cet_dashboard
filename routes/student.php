<?php
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentHomeworksController;
use App\Http\Controllers\StudentLectures;
use App\Http\Controllers\UserSubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('student')->group(function () {
    Route::get('/lectures', [StudentLectures::class, 'index']);
    Route::get('/subject/{subject}/homeworks', [StudentHomeworksController::class, 'index']);
    Route::get('/quizzes', [QuizController::class, 'studentQuizzes']);
    Route::post('/quizzes/{id}/answer', [QuizController::class, 'answerQuiz']);
    Route::get('/quizzes/{id}/result', [QuizController::class, 'quizResult']);
    Route::post('/homeworks/{id}/comment', [StudentHomeworksController::class, 'addComment']);
});

Route::get('/student/subject', [UserSubjectController::class, 'index'])->middleware('auth:sanctum');
