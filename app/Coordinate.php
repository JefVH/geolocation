<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Coordinate
 * @package geolocation
 */
class Coordinate extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
    	'lat',
    	'lon',
    	'time',
    	'stop_id',
    	'stop_distance',
        'processed'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeProcessed($query)
    {
        return $query->where('processed', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotProcessed($query)
    {
        return $query->where('processed', 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function track()
    {
    	return $this->belongsTo('geolocation\Track');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stop()
    {
    	return $this->belongsTo('geolocation\Stop');
    }
}
