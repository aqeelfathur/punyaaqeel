<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-page');
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