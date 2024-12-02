<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeartbeatController extends Controller
{
    public function heartbeat(Request $request, string $station)
    {
        $car = Car::where('station', $station)->first();
        $heartbeat = DB::table('heartbeat')->where('car_id', $car->id)->first();
        DB::table('heartbeat')->where('car_id', $car->id)->delete();

        if ($heartbeat) {

            return response()->json([
                'commands' => $heartbeat->commands
            ]);
        }
        return response()->json(['commands' => '[]']);
    }
}
