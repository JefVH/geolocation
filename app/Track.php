<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
    	'name',
        'processed',
        'trip_id',
        'start_stop_id',
        'end_stop_id',
    	'created_at',
    	'updated_at'
    ];

    public function scopeNotProcessed($query)
    {
        return $query->where('processed', 0);
    }

    public function coordinates()
    {
    	return $this->hasMany('geolocation\Coordinate', 'track_id', 'id');
    }
}
