<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timer;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

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

        $timer = Timer::where('timer_id', $car->plate)->where('type', $request->input('type'))->whereNull('end_time')->first();
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

        DB::table('heartbeat')->insert([
            'car_id' => $car->id,
            'commands' => json_encode(['timer' => 'start']),
            'station' => $car->station
        ]);

        return redirect(route('cars.show', ['car' => $car->id]));
    }

    public function pauseTimer(Request $request, Car $car)
    {
        $timer = Timer::where('timer_id', $car->plate)->where('type', 'normal')->whereNull('end_time')->first();
        if (!$timer) {
            return redirect()->back()->withErrors('No active timer');
        }


        $timer->end_time = now();
        $timer->save();

        DB::table('heartbeat')->insert([
            'car_id' => $car->id,
            'commands' => json_encode(['timer' => 'pause']),
            'station' => $car->station
        ]);

        return redirect(route('cars.show', ['car' => $car->id]));
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

        return redirect(route('cars.show', ['car' => $car->id]));
    }
}
