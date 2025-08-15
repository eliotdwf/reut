<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return redirect(\App\Filament\Pages\Calendar::getUrl(panel: 'main'));
});


// Add a fallback route for logout for filament
Route::get('/login', function (Request $request) {
    // chek if url intended is set in the session
    if (!$request->session()->has('url.intended')) {
        $request->session()->put('url.intended', url()->previous());
    }
    return redirect(route('auth.login'));
})->name('login');
