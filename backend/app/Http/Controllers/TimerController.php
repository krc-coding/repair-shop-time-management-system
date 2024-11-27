<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timer;

class TimerController extends Controller
{
    public function index()
    {
        return view('timers.index', [
            'timers' => Timer::all(),
        ]);
    }
}
