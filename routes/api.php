<?php

use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/room-types', [RoomTypeController::class, 'index']);
Route::post('/room-types', [RoomTypeController::class, 'store']);
Route::get('/room-types/{roomType}', [RoomTypeController::class, 'show']);
Route::put('/room-types/{roomType}', [RoomTypeController::class, 'update']);
Route::delete('/room-types/{roomType}', [RoomTypeController::class, 'destroy']);
