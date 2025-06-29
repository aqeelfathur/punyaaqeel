<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\WorkoutCalendarController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', fn() => view('about-us'))->name('about-us');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Community Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('community')->name('community.')->group(function () {
    Route::get('/', [CommunityController::class, 'index'])->name('index');
    Route::post('/post', [CommunityController::class, 'storePost'])->name('post');
    Route::post('/{postId}/comment', [CommunityController::class, 'storeComment'])->name('comment');
    Route::post('/{postId}/like', [CommunityController::class, 'toggleLike'])->name('like');
});

/*
|--------------------------------------------------------------------------
| Workout Routes
|--------------------------------------------------------------------------
*/
Route::get('/workout-programs', [WorkoutController::class, 'programs'])->name('workout.programs');

Route::middleware(['auth'])->prefix('workout')->name('workout.')->group(function () {
    Route::post('/add-to-workout', [WorkoutController::class, 'addToWorkout'])->name('addToWorkout');
    Route::post('/remove-from-workout', [WorkoutController::class, 'removeFromWorkout'])->name('removeFromWorkout');
});

/*
|--------------------------------------------------------------------------
| Load + Program Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('load')->name('load.')->group(function () {
    Route::get('/', [LoadController::class, 'index'])->name('index'); // Akses via route('load.index')
    Route::get('/{program_id}/exercises', [LoadController::class, 'exercises'])->name('exercises');
    Route::post('/start-workout', [LoadController::class, 'startWorkout'])->name('startWorkout');
    Route::post('/remove-from-load', [LoadController::class, 'removeFromLoad'])->name('removeFromLoad');
    Route::get('/count', [LoadController::class, 'getLoadCount'])->name('count');
    Route::post('/finish-workout', [LoadController::class, 'finishWorkout'])->name('finish');

});


/*
|--------------------------------------------------------------------------
| Programs Detail (for load/start page)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('programs')->name('programs.')->group(function () {
    Route::get('/', [WorkoutController::class, 'programsList'])->name('index');
    Route::get('/{id}', [WorkoutController::class, 'showProgram'])->name('show');
    Route::post('/{id}/complete', [WorkoutController::class, 'completeProgram'])->name('complete');
});

/*
|--------------------------------------------------------------------------
| Calendar Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/calendar', [WorkoutCalendarController::class, 'index']);
    Route::get('/workout/calendar/data', [WorkoutCalendarController::class, 'getWorkoutData']);
    Route::get('/workout/calendar/date-range', [WorkoutCalendarController::class, 'getWorkoutByDateRange']);
    Route::get('/workout/calendar/date', [WorkoutCalendarController::class, 'getWorkoutByDate']);
    Route::get('/workout/calendar/stats', [WorkoutCalendarController::class, 'getMonthlyStats']);
    Route::get('/workout/calendar/streak', [WorkoutCalendarController::class, 'getWorkoutStreak']);
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');
    
    Route::post('/settings/image', [ProfileController::class, 'uploadImage'])->name('settings.image.upload');
    Route::delete('/settings/image', [ProfileController::class, 'deleteImage'])->name('settings.image.delete');
    Route::get('/profile/image-url', [ProfileController::class, 'getImageUrl'])->name('profile.image.url');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Auth + Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', fn() => redirect()->route('settings'))->name('settings');
});

/*
|--------------------------------------------------------------------------
| Other Pages (Optional)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::view('/add-exercise', 'add-exercise')->name('add-exercise');
    Route::view('/add-exercises', 'add-exercises')->name('add-exercises');
    Route::view('/add-programs', 'add-programs')->name('add-programs');
    Route::view('/list', 'list')->name('list');
});

/*
|--------------------------------------------------------------------------
| Dev Route (Local Only)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/routes-debug', function () {
        return response()->json(collect(Route::getRoutes())->map(fn($r) => [
            'method' => implode('|', $r->methods()),
            'uri' => $r->uri(),
            'name' => $r->getName(),
            'action' => $r->getActionName()
        ]));
    });
}
