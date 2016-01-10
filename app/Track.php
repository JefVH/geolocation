<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
    	'name',
    	'created_at',
    	'updated_at'
    ];

    public function coordinates()
    {
    	return $this->hasMany('geolocation\Coordinate', 'track_id', 'id');
    }
}
