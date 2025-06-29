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


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static pages
Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

/*
|--------------------------------------------------------------------------
| Community Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::post('/community/post', [CommunityController::class, 'storePost'])->name('community.post');
    Route::post('/community/{postId}/comment', [CommunityController::class, 'storeComment'])->name('community.comment');
    Route::post('/community/{postId}/like', [CommunityController::class, 'toggleLike'])->name('community.like');
});
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
| Load Routes 
|--------------------------------------------------------------------------
*/

/// Taruh sebelum Route::middleware(['auth'])->group
Route::middleware(['auth'])->prefix('programs')->name('programs.')->group(function () {
    Route::get('/', [WorkoutController::class, 'programsList'])->name('index');
    Route::get('/{id}', [WorkoutController::class, 'showProgram'])->name('show'); 
    Route::post('/{id}/complete', [WorkoutController::class, 'completeProgram'])->name('complete');
});

// Main load page - definisikan di luar group
Route::middleware(['auth'])->get('/load', [LoadController::class, 'index'])->name('load');
// Halaman checklist gerakan untuk program tertentu
Route::middleware(['auth'])
     ->get('/load/{program_id}/exercises', [LoadController::class, 'exercises'])
     ->name('load.exercises');
// Load API endpoints
Route::middleware(['auth'])->prefix('load')->name('load.')->group(function () {
    Route::post('/start-workout', [LoadController::class, 'startWorkout'])->name('startWorkout');
    Route::post('/remove-from-load', [LoadController::class, 'removeFromLoad'])->name('removeFromLoad');
    Route::get('/count', [LoadController::class, 'getLoadCount'])->name('count');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Profile & Settings routes (existing)
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');
    
    // Profile Image routes (new)
    Route::post('/settings/image', [ProfileController::class, 'uploadImage'])->name('settings.image.upload');
    Route::delete('/settings/image', [ProfileController::class, 'deleteImage'])->name('settings.image.delete');
    Route::get('/profile/image-url', [ProfileController::class, 'getImageUrl'])->name('profile.image.url');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Uncomment these when controllers are ready
    // Route::resource('users', AdminUserController::class);
    // Route::resource('programs', AdminProgramController::class);
    // Route::resource('movements', AdminMovementController::class);
    // Route::resource('community', AdminCommunityController::class);
    // Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    
    Route::get('settings', function() {
        return redirect()->route('settings');
    })->name('settings');
});


/*
|--------------------------------------------------------------------------
| Other Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Exercise management
    Route::get('/add-exercise', function () {
        return view('add-exercise');
    })->name('add-exercise');
    
    Route::get('/add-exercises', function () {
        return view('add-exercises');
    })->name('add-exercises');
    
    // Program management
    Route::get('/add-programs', function () {
        return view('add-programs');
    })->name('add-programs');
    
    // Calendar
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');
    
    // List
    Route::get('/list', function () {
        return view('list');
    })->name('list');
    
    // Custom workout
    Route::get('/customworkout', function () {
        return view('customworkout');
    })->name('customworkout');
});

// /*
// |--------------------------------------------------------------------------
// | Legacy Route Redirects - FIXED
// |--------------------------------------------------------------------------
// */
// // FIX: Redirect ke route yang benar
// Route::get('/load', function() {
//     return redirect('/load/'); // Redirect ke URL langsung
// })->middleware('auth');

// Atau alternatif yang lebih aman:
// Route::get('/load', [LoadController::class, 'index'])->middleware('auth')->name('load.legacy');

/*
|--------------------------------------------------------------------------
| Development Routes
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/routes-debug', function () {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName()
            ];
        });
        
        return response()->json($routes);
    });
}