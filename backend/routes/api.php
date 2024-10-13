<?php

use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register/car', [CarController::class, 'registerCar']);
    Route::put('/update/car/status', [CarController::class, 'updateCarStatus']);
});
