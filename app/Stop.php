<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    public function stop_times()
    {
    	return $this->hasMany('geolocation\StopTime');
    }

    public function coordinates()
    {
    	return $this->hasMany('geolocation\Stop');
    }
}
