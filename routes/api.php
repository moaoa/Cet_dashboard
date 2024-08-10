<?php

use App\Http\Controllers\AssignHomeworkToGroupController;
use App\Http\Controllers\AssignQuizToGroupController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\LectureStudentsController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\UserLectures;
use App\Http\Controllers\UsersAttendingQuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TakeAttendanceController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\GroupQuizController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\StudentLecturesController;

// API routes for attendance
Route::post('/attendance/{lecture}', TakeAttendanceController::class)->name('attendance.take');



// QUIZ QUESTIONS
Route::post('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])->name('quiz-questions.store');
Route::get('/quiz-questions/{id}', [QuizQuestionController::class, 'show'])->name('quiz-questions.show');
Route::put('/quiz-questions/{id}', [QuizQuestionController::class, 'update'])->name('quiz-questions.update');
Route::delete('/quiz-questions/{id}', [QuizQuestionController::class, 'destroy'])->name('quiz-questions.destroy');


// Quiz routes
Route::group([], function () {
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quizId}/users', [QuizController::class, 'users'])->name('quizzes.index');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
});





// User Lecture Routes
Route::group([], function () {
    Route::get('/user-lectures/{userId}', [UserLectures::class, 'index'])->name('user-lectures.index');
});

// Lecture students Routes
Route::group([], function () {
    Route::get('/lecture-students/{lectureId}', LectureStudentsController::class);
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

// Group Quiz Routes
Route::group([], function () {
    Route::get('/group-quizzes', [GroupQuizController::class, 'index'])->name('group-quizzes.index');
    Route::post('/group-quizzes', [GroupQuizController::class, 'store'])->name('group-quizzes.store');
    Route::get('/group-quizzes/{groupQuiz}', [GroupQuizController::class, 'show'])->name('group-quizzes.show');
    Route::put('/group-quizzes/{groupQuiz}', [GroupQuizController::class, 'update'])->name('group-quizzes.update');
    Route::delete('/group-quizzes/{groupQuiz}', [GroupQuizController::class, 'destroy'])->name('group-quizzes.destroy');
});

// User Answer Routes
Route::group([], function () {
    Route::get('/user-answers/{userId}', [UserAnswerController::class, 'index'])->name('user-answers.index');
    Route::post('/user-answers', [UserAnswerController::class, 'store'])->name('user-answers.store');
    Route::get('/user-answers/{userAnswer}', [UserAnswerController::class, 'show'])->name('user-answers.show');
    Route::put('/user-answers/{userAnswer}', [UserAnswerController::class, 'update'])->name('user-answers.update');
    Route::delete('/user-answers/{userAnswer}', [UserAnswerController::class, 'destroy'])->name('user-answers.destroy');
});

// Teacher Stuff
Route::get('/quiz-students/{quizId}', UsersAttendingQuizController::class)->name('quiz-users');
Route::post('/quiz-assignment', AssignQuizToGroupController::class)->name('assign-quiz');
Route::post('/homework-assignment', AssignHomeworkToGroupController::class)->name('assign-homework');

// Student Stuff
Route::prefix('student')->group(function () {
    Route::get('/lectures', [StudentLecturesController::class, 'index'])->name('student-lectures.index');
});
