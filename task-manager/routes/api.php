<?php
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
// Task routes
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{task}', [TaskController::class, 'show']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{task}', [TaskController::class, 'update']);
//Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
Route::patch('/tasks/{task}/status', [TaskController::class, 'changeStatus']);

// Comment routes
Route::get('/tasks/{task}/comments', [CommentController::class, 'index']);
Route::post('/tasks/{task}/comments', [CommentController::class, 'store']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
Route::patch('/tasks/{task}/type', [TaskController::class, 'changeType']);
Route::get('/tasks/{task}/logs', [TaskController::class, 'getLogs']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
Route::post('/register', [RegisteredUserController::class, 'store']);

