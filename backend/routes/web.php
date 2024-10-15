<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::post('/register/car', [CarController::class, 'registerCar'])->name('cars.register');
    Route::delete('/cars/{car}/unregister', [CarController::class, 'unregisterCar'])->name('cars.unregister');
    Route::put('/update/car/status', [CarController::class, 'updateCarStatus'])->name('cars.update-status');

    Route::get('/timers', fn () => view('welcome'))->name('timers.index');
});

require __DIR__.'/auth.php';
