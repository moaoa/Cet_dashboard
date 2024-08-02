<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\QuizQuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TakeAttendanceController;
use App\Http\Controllers\QuizController;

// API routes for attendance
Route::post('/lectures/{lecture}/attendance', TakeAttendanceController::class)->name('attendance.take');



// QUIZ QUESTIONS
Route::post('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])->name('quiz-questions.store');
Route::get('/quiz-questions/{id}', [QuizQuestionController::class, 'show'])->name('quiz-questions.show');
Route::put('/quiz-questions/{id}', [QuizQuestionController::class, 'update'])->name('quiz-questions.update');
Route::delete('/quiz-questions/{id}', [QuizQuestionController::class, 'destroy'])->name('quiz-questions.destroy');


// Quiz routes
Route::group([], function () {
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
});

// lecture routes
Route::group([], function () {
    Route::get('/lectures', [LectureController::class, 'index'])->name('lectures.index');
    Route::post('/lectures', [LectureController::class, 'store'])->name('lectures.store');
    Route::get('/lectures/{lecture}', [LectureController::class, 'show'])->name('lectures.show');
    Route::put('/lectures/{lecture}', [LectureController::class, 'update'])->name('lectures.update');
    Route::delete('/lectures/{lecture}', [LectureController::class, 'destroy'])->name('lectures.destroy');
});


//  Homework routes
Route::group([], function () {
    Route::get('/homeworks', [HomeworkController::class, 'index'])->name('homeworks.index');
    Route::post('/homeworks', [HomeworkController::class, 'store'])->name('homeworks.store');
    Route::get('/homeworks/{homework}', [HomeworkController::class, 'show'])->name('homeworks.show');
    Route::put('/homeworks/{homework}', [HomeworkController::class, 'update'])->name('homeworks.update');
    Route::delete('/homeworks/{homework}', [HomeworkController::class, 'destroy'])->name('homeworks.destroy');
});

//  Groups routes
Route::group([], function () {
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
});
