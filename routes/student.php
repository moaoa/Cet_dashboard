<?php
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentHomeworksController;
use App\Http\Controllers\StudentLectures;
use App\Http\Controllers\UserSubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('student')->group(function () {
    Route::get('/lectures', [StudentLectures::class, 'index'])->name('user-lectures');
    Route::get('/subject/{subject}/homeworks', [StudentHomeworksController::class, 'index'])->name('student-homeworks');
    Route::get('/quizzes', [QuizController::class, 'studentQuizzes'])->name('student-homeworks.index');
    Route::post('/quizzes/{id}/answer', [QuizController::class, 'answerQuiz'])->name('student-quiz-answer');
    Route::get('/quizzes/{id}/result', [QuizController::class, 'quizResult'])->name('student-quiz-result');
    Route::post('/homeworks/{id}/comment', [StudentHomeworksController::class, 'addComment'])->name('student-add-comment');
});

Route::get('/student/subject', [UserSubjectController::class, 'index'])->middleware('auth:sanctum')->name('student-subjects');
