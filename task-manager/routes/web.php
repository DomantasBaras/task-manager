<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Default route redirects to the dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard route
Route::get('/dashboard', function () {
    return Inertia::render('Tasks');
})->middleware(['auth', 'verified'])->name('dashboard');

// Task details route (requires authentication)
Route::middleware('auth')->get('tasks/{task}', function ($id) {
    return Inertia::render('TaskDetails', [
        'id' => $id,
        'auth' => [
            'user' => Auth::user(),
        ]
    ]);
})->name('task.details');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Logout route that redirects to the dashboard after logging out
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Include authentication routes (register, login, etc.)
require __DIR__ . '/auth.php';