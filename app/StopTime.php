<?php namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class StopTime extends Model
{
    protected $table = 'stop_times';

    public function trip()
    {
    	return $this->belongsTo('geolocation\Trip');
    }

    public function stop()
    {
    	return $this->belongsTo('geolocation\Stop');
    }
}
