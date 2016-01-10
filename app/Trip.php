<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public function stop_time()
    {
    	return $this->hasMany('geolocation\StopTime');
    }
}
