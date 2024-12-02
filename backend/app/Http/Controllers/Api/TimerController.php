<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\Timer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TimerController extends Controller
{
    public function list()
    {
        return response()->json(Timer::all()->toArray());
    }

    public function createTimerForCar(Request $request, string $type, Car $car)
    {
        $request->merge([
            'type' => $type,
        ]);
        $request->validate([
            'type' => 'in:lift,normal'
        ]);

        $timer = Timer::where('timer_id', $car->plate)->where('type', $type)->whereNull('end_time')->first();
        if ($timer) {
            $timer->end_time = now();
            $timer->save();
        }
        $timer = Timer::create([
            'timer_id' => $car->plate,
            'type' => $type,
            'start_time' => now(),
            'end_time' => null,
        ]);
        return response()->json($timer->toArray(), 201);
    }

    public function stopTimer(Request $request, string $type, Car $car)
    {
        $request->merge([
            'type' => $type,
        ]);
        $request->validate([
            'type' => 'in:lift,normal'
        ]);
        $timer = Timer::where('timer_id', $car->plate)->where('type', $type)->whereNull('end_time')->first();
        if (!$timer) {
            return response()->json(['message' => 'No active timer'], 400);
        }
        $timer->end_time = now();
        $timer->save();
        return response()->json($timer->toArray());
    }

    public function getTimerTotal(Request $request, string $type, Car $car)
    {
        $request->merge([
            'type' => $type,
        ]);
        $request->validate([
            'type' => 'in:lift,normal'
        ]);

        $timers = Timer::where('timer_id', $car->plate)->where('type', $type)->get();


        $totalDiffInSeconds = 0;
        $timers->each(function (Timer $timer) use (&$totalDiffInSeconds) {
            $startTime = Carbon::parse($timer->start_time);
            $endTime = Carbon::parse($timer->end_time);
            $diff = $startTime->diff($endTime);
            $totalDiffInSeconds += $diff->totalSeconds;
        });

        return response()->json(['total' => $totalDiffInSeconds]);
    }

    // public function toggleLiftTimer(Request $request, int $station) {
    //     $car = Car::where('station', $station);


    //     return response()->json(['total' => $totalDiffInSeconds]);
    // }
}
