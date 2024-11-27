<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timer;
use App\Models\Car;

class TimerController extends Controller
{
    public function index()
    {
        return view('timers.index', [
            'timers' => Timer::all(),
        ]);
    }

    public function createTimerForCar(Request $request, Car $car)
    {
        $request->validate([
            'type' => 'in:lift,normal'
        ]);

        $timer = Timer::where('timer_id', $car->plate)->whereNull('end_time')->first();
        if ($timer) {
            $timer->end_time = now();
            $timer->save();
        }

        $timer = Timer::create([
            'timer_id' => $car->plate,
            'type' => $request->get('type'),
            'start_time' => now(),
            'end_time' => null,
        ]);

        return redirect(route('cars.index') . '#timers' . $car->id);
    }

    public function stopTimer(Request $request, Car $car)
    {
        $request->validate([
            'type' => 'in:lift,normal'
        ]);

        $timer = Timer::where('timer_id', $car->plate)->whereNull('end_time')->first();
        if (!$timer) {
            return redirect()->back()->withErrors('No active timer');
        }
        $timer->end_time = now();
        $timer->save();

        return redirect(route('cars.index') . '#timers' . $car->id);
    }
}
