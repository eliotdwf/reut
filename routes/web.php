<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::middleware('auth')->get('/', function (Request $request) {
    Log::debug('User is authenticated, rendering home page with permissions', $request->session()->get('user_permissions', []));
    return Inertia::render("Home")->with('user_permissions', $request->session()->get('user_permissions', []));
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
Route::get('/login', function (Request $request) {
    // chek if url intended is set in the session
    if (!$request->session()->has('url.intended')) {
        $request->session()->put('url.intended', url()->previous());
    }
    return redirect(route('auth.login'));
})->name('login');
