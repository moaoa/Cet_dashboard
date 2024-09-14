<?php
use App\Http\Controllers\Teacher\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\LecturesController;

Route::middleware('auth:teacher')->group(function () {
    Route::get('/lectures', [LecturesController::class, 'index']);

// Route::prefix('teacher')->group(function () {
//     Route::get('/quiz-students/{quizId}', UsersAttendingQuizController::class);
//     Route::post('/quiz-assignment', AssignQuizToGroupController::class);
//     Route::post('/homework-assignment', AssignHomeworkToGroupController::class);
//     Route::post('/attendance/{lecture}', TakeAttendanceController::class);
// });
    //Route::get('/lectures', [StudentLectures::class, 'index']);

    // Route::get('/subject/{subject}/homeworks', [StudentHomeworksController::class, 'index']);
    // Route::get('/quizzes', [QuizController::class, 'studentQuizzes']);
    // Route::post('/quizzes/{id}/answer', [QuizController::class, 'answerQuiz']);
    // Route::get('/quizzes/{id}/result', [QuizController::class, 'quizResult']);
    // Route::post('/homeworks/{id}/comment', [StudentHomeworksController::class, 'addComment']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//jRoute::get('/student/subject', [UserSubjectController::class, 'index'])->middleware('auth:sanctum');