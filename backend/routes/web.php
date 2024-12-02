<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TimerController;
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
    Route::get('/car/{car}', [CarController::class, 'show'])->name('cars.show');
    Route::post('/register/car', [CarController::class, 'registerCar'])->name('cars.register');
    Route::delete('/cars/{car}/unregister', [CarController::class, 'unregisterCar'])->name('cars.unregister');
    Route::put('/update/{car}/status', [CarController::class, 'updateCarStatus'])->name('cars.update-status');
    Route::put('/update/{car}/station', [CarController::class, 'setCarStation'])->name('cars.set-station');
    Route::put('/update/{car}/notes', [CarController::class, 'setCarNotes'])->name('cars.update-notes');

    Route::get('/timers', [TimerController::class, 'index'])->name('timers.index');
    Route::post('/cars/{car}/timers', [TimerController::class, 'createTimerForCar'])->name('timers.create');
    Route::post('/cars/{car}/timers/pause', [TimerController::class, 'pauseTimer'])->name('timers.pause');
    Route::post('/cars/{car}/timers/stop', [TimerController::class, 'stopTimer'])->name('timers.stop');
});

require __DIR__ . '/auth.php';
