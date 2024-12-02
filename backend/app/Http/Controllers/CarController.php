<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Timer;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        return view('cars.index', [
            'cars' => Car::with('timers')->get(),
        ]);
    }

    public function show(Car $car)
    {
        $normalTimers = Timer::where('timer_id', $car->plate)->where('type', 'normal')->get();
        $liftTimers = Timer::where('timer_id', $car->plate)->where('type', 'lift')->get();


        $normalTotal = CarbonInterval::create();
        $normalTimers->each(function (Timer $timer) use (&$normalTotal) {
            $startTime = Carbon::parse($timer->start_time);
            $endTime = Carbon::parse($timer->end_time);
            $diff = $startTime->diff($endTime);
            $normalTotal->add($diff);
        });

        $liftTotal = CarbonInterval::create();
        $liftTimers->each(function (Timer $timer) use (&$liftTotal) {
            $startTime = Carbon::parse($timer->start_time);
            $endTime = Carbon::parse($timer->end_time);
            $diff = $startTime->diff($endTime);
            $liftTotal->add($diff);
        });

        $activeNormalTimer = $car->timers()->where('type', 'normal')->whereNull('end_time')->get();
        $activeLiftTimer = $car->timers()->where('type', 'lift')->whereNull('end_time')->get();

        return view('cars.details', [
            'car' => $car,
            'timers' => $car->timers()->get(),
            'hasActiveNormalTimer' => $activeNormalTimer->count() > 0,
            'hasActiveLiftTimer' => $activeLiftTimer->count() > 0,
            'normalTotal' => $normalTotal,
            'liftTotal' => $liftTotal,
        ]);
    }

    public function registerCar(Request $request)
    {
        $request->validate([
            'plate' => 'required|string'
        ]);

        $car = Car::create([
            'plate' => strtoupper($request->plate),
        ]);

        return redirect()->route('cars.index');
    }

    public function unregisterCar(Car $car)
    {
        $car->delete();

        return redirect()->route('cars.index');
    }

    public function updateCarStatus(Request $request, Car $car)
    {
        $request->validate([
            'status' => 'required|in:Not-started,In-progress,Done'
        ]);

        $car->status = $request->status;
        $car->save();

        return redirect()->route('cars.show', [
            'car' => $car,
        ]);
    }

    public function setCarStation(Request $request, Car $car)
    {
        $request->validate([
            'station' => 'required|max:255'
        ]);

        $car->station = $request->station;
        $car->save();

        return redirect()->route('cars.show', [
            'car' => $car,
        ]);
    }

    public function setCarNotes(Request $request, Car $car)
    {
        $request->validate([
            'notes' => 'required'
        ]);

        $car->notes = $request->notes;
        $car->save();

        return redirect()->route('cars.show', [
            'car' => $car,
        ]);
    }
}
