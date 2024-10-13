<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    protected $fillable = ['timer_id', 'type', 'start_time', 'end_time'];
}
