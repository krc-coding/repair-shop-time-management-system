<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    public function list()
    {
        $cars = Car::all();
        return response()->json($cars->toArray());
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
