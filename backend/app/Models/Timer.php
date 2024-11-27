<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    protected $fillable = ['timer_id', 'type', 'start_time', 'end_time'];

    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];
}
