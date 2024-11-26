<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\TimerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cars', [CarController::class, 'list']);
Route::get('/timers', [TimerController::class, 'list']);
Route::post('/timer/start/{type}/{car}', [TimerController::class, 'createTimerForCar']);
Route::post('/timer/stop/{type}/{car}', [TimerController::class, 'stopTimer']);
Route::get('/timer/total/{type}/{car}', [TimerController::class, 'getTimerTotal']);
