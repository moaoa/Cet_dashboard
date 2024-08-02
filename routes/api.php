<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TakeAttendanceController;

// API routes for attendance
Route::post('/lectures/{lecture}/attendance', TakeAttendanceController::class)->name('attendance.take');

Route::get('/test', function() {
    return 'hi';
});
