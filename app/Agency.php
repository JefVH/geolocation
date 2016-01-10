<?php

namespace geolocation;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
	public function routes()
	{
		return $this->hasMany('geolocation\Route');
	}
}
