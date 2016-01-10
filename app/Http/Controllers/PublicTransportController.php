<?php

namespace geolocation\Http\Controllers;

use Illuminate\Http\Request;

use geolocation\Http\Requests;
use geolocation\Http\Controllers\Controller;

use geolocation\Track;

class PublicTransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tracks = Track::all();

        if($tracks)
        {
            return view('publictransport', compact('tracks'));
        }
        else
        {
            return view('publictransport');
        }
    }
}
