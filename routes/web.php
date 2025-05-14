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

Route::get('/calender', function () {
    return view('calender');
})->name('calender');

Route::get('/workout-programs', function () {
    return view('workout-programs');
})->name('workout-programs');