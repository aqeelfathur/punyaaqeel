<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::get('/community', function () {
    return view('community');
})->name('community');

Route::get('/add-exercise', function () {
    return view('add-exercise');
})->name('add-exercise');

Route::get('/add-programs', function () {
    return view('add-programs');
})->name('add-programs');

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');

Route::get('/workout-programs', function () {
    return view('workout-programs');
})->name('workout-programs');

Route::get('/load', function () {
    return view('load');
})->name('load');

Route::get('/list', function () {
    return view('list');
})->name('list');

Route::get('/customworkout', function () {
    return view('customworkout');
})->name('customworkout');

Route::get('/add-programs', function () {
    return view('add-programs');
})->name('add-programs');

Route::get('/add-exercises', function () {
    return view('add-exercises');
})->name('add-exercises');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');


use App\Http\Controllers\Auth\RegisterController;

// Routes untuk registrasi
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

use App\Http\Controllers\Auth\LoginController;
// Routes untuk login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile routes
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
Route::get('/settings', [App\Http\Controllers\ProfileController::class, 'settings'])->name('settings');
Route::put('/settings', [App\Http\Controllers\ProfileController::class, 'updateSettings'])->name('settings.update');

// Stats di home routes
use App\Http\Controllers\HomeController;
Route::get('/', [HomeController::class, 'index'])->name('home');

// Workout Programs Routes
use App\Http\Controllers\WorkoutProgramController;
Route::get('/workout-programs', [WorkoutProgramController::class, 'index'])->name('workout.programs');
// Route untuk menambahkan program ke workout user (dilindungi middleware auth)
Route::post('/workout-programs/add-to-workout', [WorkoutProgramController::class, 'addToWorkout'])
    ->name('workout.addToWorkout')
    ->middleware('auth');