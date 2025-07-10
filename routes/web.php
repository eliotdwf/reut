<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->get('/', function () {
    info('User is authenticated, rendering home page');
    return Inertia::render("Home");
})->name('home');


Route::get('/calendar', function () {
    return Inertia::render('CalendarPage');
});


Route::prefix('/auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::get('/callback', [AuthController::class, 'callback'])
        ->name('callback');

    Route::middleware('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });
});

// Add a fallback route for login if needed
Route::get('/login', function () {
    return redirect('/auth/login');
})->name('login');
