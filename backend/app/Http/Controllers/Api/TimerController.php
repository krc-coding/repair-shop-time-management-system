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
            Log::debug('timer', $timer->toArray());
            $startTime = Carbon::parse($timer->start_time);
            $endTime = Carbon::parse($timer->end_time);
            Log::debug($startTime);
            Log::debug($endTime);
            $diff = $startTime->diff($endTime);

            $totalDiffInSeconds += $diff->totalSeconds;
            Log::debug('diff', [$diff]);
            Log::debug('total diff', [$totalDiffInSeconds]);
        });

        return response()->json(['total' => $totalDiffInSeconds]);
    }
}
