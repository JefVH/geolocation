<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    protected $fillable = [
    	'lat',
    	'lon',
    	'time',
    	'stop_id',
    	'stop_distance'
    ];

    public function track()
    {
    	return $this->belongsTo('geolocation\Track');
    }

    public function stop()
    {
    	return $this->belongsTo('geolocation\Stop');
    }
}
