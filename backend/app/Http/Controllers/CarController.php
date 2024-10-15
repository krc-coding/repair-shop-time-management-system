<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        return view('cars.index', [
            'cars' => Car::all(),
        ]);
    }

    public function registerCar(Request $request)
    {
        $request->validate([
            'plate' => 'required|string'
        ]);

        $car = Car::create([
            'plate' => $request->plate
        ]);

        return redirect()->route('cars.index');
    }

    public function unregisterCar(Car $car)
    {
        $car->delete();

        return redirect()->route('cars.index');
    }

    public function updateCarStatus(Request $request, string $plate)
    {
        $request->merge(['plate' => $plate]);
        $request->validate([
            'plate' => 'exists:cars',
            'status' => 'required|in:Not-started,In-progress,Done'
        ]);

        $car = Car::where('plate', $plate)->first();
        $car->status = $request->status;
        $car->save();

        return response()->json($car->toArray(), 200);
    }
}
