<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['plate', 'status', 'notes'];

	public function timers()
	{
		return $this->hasMany(Timer::class, 'timer_id', 'plate');
	}
}
