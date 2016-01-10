<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public function trips()
    {
    	return $this->hasMany('geolocation\Trip');
    }

    public function agency()
    {
    	return $this->belongsTo('geolocation\Agency');
    }
}
